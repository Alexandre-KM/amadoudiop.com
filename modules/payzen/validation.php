<?php
/**
 * PayZen V2-Payment Module version 1.8.0 for PrestaShop 1.5-1.7. Support contact : support@payzen.eu.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category  payment
 * @package   payzen
 * @author    Lyra Network (http://www.lyra-network.com/)
 * @copyright 2014-2016 Lyra Network and contributors
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Instant payment notification file. Wait for PayZen payment confirmation, then validate order.
 */

require_once dirname(dirname(dirname(__FILE__))).'/config/config.inc.php';

if (($cart_id = (int)Tools::getValue('vads_order_id')) && Tools::getValue('vads_hash')) {
    /* module main class */
    require_once(dirname(__FILE__).'/payzen.php');
    $payzen = new Payzen();

    PayzenTools::getLogger()->logInfo("Server call process starts for cart #$cart_id.");

    $cart = new Cart($cart_id);

    /* cart errors */
    $trans_id = htmlspecialchars(Tools::getValue('vads_trans_id'), ENT_COMPAT, 'UTF-8');
    if (!Validate::isLoadedObject($cart)) {
        PayzenTools::getLogger()->logError("Cart #$cart_id not found in database.");
        die('<span style="display:none">KO-'.$trans_id."=Impossible de retrouver la commande\n</span>");
    } elseif ($cart->nbProducts() <= 0) {
        PayzenTools::getLogger()->logError("Cart #$cart_id was emptied before redirection.");
        die('<span style="display:none">KO-'.$trans_id."=Le panier a été vidé avant la redirection\n</span>");
    }

    /* reload context */
    if (isset($cart->id_shop)) {
        $_GET['id_shop'] = $cart->id_shop;
        Context::getContext()->shop = Shop::initialize();
    }

    Context::getContext()->customer = new Customer((int)$cart->id_customer);
    Context::getContext()->cart = $cart = new Cart((int)$cart_id); // reload cart to take into account customer group

    $address = new Address((int)$cart->id_address_invoice);
    Context::getContext()->country = new Country((int)$address->id_country);
    Context::getContext()->language = new Language((int)$cart->id_lang);
    Context::getContext()->currency = new Currency((int)$cart->id_currency);

    require_once _PS_MODULE_DIR_.'payzen/classes/PayzenResponse.php';

    /** @var PayzenResponse $payzen_response */
    $payzen_response = new PayzenResponse(
        $_POST,
        Configuration::get('PAYZEN_MODE'),
        Configuration::get('PAYZEN_KEY_TEST'),
        Configuration::get('PAYZEN_KEY_PROD')
    );

    /* check the authenticity of the request */
    if (!$payzen_response->isAuthentified()) {
        PayzenTools::getLogger()->logError("Cart #$cart_id : authentication error !");
        die($payzen_response->getOutputForPlatform('auth_fail'));
    }

    /* search order in db */
    $order_id = Order::getOrderByCartId($cart_id);

    if ($order_id == false) {
        /* order has not been processed yet */

        if ($payzen_response->isAcceptedPayment()) {
            switch (true) {
                case $payzen->isOneyPendingPayment($payzen_response):
                    $new_state = Configuration::get('PAYZEN_OS_ONEY_PENDING');
                    break;
                case $payzen->isSofort($payzen_response):
                case $payzen->isSepa($payzen_response):
                    $new_state = Configuration::get('PAYZEN_OS_TRANS_PENDING');
                    break;
                case $payzen_response->isPendingPayment():
                    $new_state = Configuration::get('PAYZEN_OS_AUTH_PENDING');
                    break;
                default:
                    $new_state = Configuration::get('PS_OS_PAYMENT');
                    break;
            }

            PayzenTools::getLogger()->logInfo("Payment accepted for cart #$cart_id. New order status is $new_state.");

            $order = $payzen->saveOrder($cart, $new_state, $payzen_response);

            if (number_format($order->total_paid, 2) != number_format($order->total_paid_real, 2)) {
                /* amount paid not equals initial amount. */
                PayzenTools::getLogger()->logWarning("Error: amount paid not equals initial amount. Order is in a failed status, cart #$cart_id.");
                die($payzen_response->getOutputForPlatform('ko', 'Le montant payé est différent du montant intial'));
            } else {
                /* response to server */
                die($payzen_response->getOutputForPlatform('payment_ok'));
            }
        } else {
            /* payment KO */
            PayzenTools::getLogger()->logInfo("Payment failed for cart #$cart_id.");

            if (Configuration::get('PAYZEN_FAILURE_MANAGEMENT') == PayzenTools::ON_FAILURE_SAVE || $payzen->isOney($payzen_response)) {
                /* save on failure option is selected or oney payment */
                $new_state = $payzen_response->isCancelledPayment() ? Configuration::get('PS_OS_CANCELED') : Configuration::get('PS_OS_ERROR');

                $msg = $payzen->isOney($payzen_response) ? 'FacilyPay Oney payment' : 'Save on failure option is selected';
                PayzenTools::getLogger()->logInfo("$msg : save failed order for cart #$cart_id. New order status is $new_state.");
                $order = $payzen->saveOrder($cart, $new_state, $payzen_response);
            }
            die($payzen_response->getOutputForPlatform('payment_ko'));
        }
    } else {
        /* order already registered */
        PayzenTools::getLogger()->logInfo("Order already registered for cart #$cart_id.");

        $order = new Order((int)$order_id);
        $old_state = $order->getCurrentState();

        $outofstock = false;
        if ((!Configuration::get('PS_OS_OUTOFSTOCK_UNPAID') && ($old_state == Configuration::get('PS_OS_OUTOFSTOCK'))) ||
                (Configuration::get('PS_OS_OUTOFSTOCK_UNPAID') && ($old_state == Configuration::get('PS_OS_OUTOFSTOCK_UNPAID')))) {
            /* if out of stock, retrieve the actual order state set by our module */
            $outofstock = true;

            $old_state = Db::getInstance()->getValue(
                'SELECT `id_order_state` FROM `'._DB_PREFIX_.'order_history`
                WHERE `id_order` = '.(int)$order_id.' AND `id_order_state` <> '.(int)$old_state.'
                ORDER BY `date_add` DESC'
            );
        }
        PayzenTools::getLogger()->logInfo("The current state for order #$order_id is $old_state.");

        switch (true) {
            case ($old_state == Configuration::get('PS_OS_ERROR')):
            case ($old_state == Configuration::get('PS_OS_CANCELED')):

                $msg = $payzen->isOney($payzen_response) ? 'FacilyPay Oney payment. ' : '';
                PayzenTools::getLogger()->logInfo($msg."Order for cart #$cart_id is in a failed status.");

                if ($payzen_response->isAcceptedPayment()) {
                    /* order saved with failed status while payment is successful */

                    if (number_format($order->total_paid, 2) != number_format($order->total_paid_real, 2)) {
                        /* amount paid not equals initial amount. */
                        PayzenTools::getLogger()->logWarning("Error: amount paid not equals initial amount. Order is in a failed status, cart #$cart_id.");
                        die($payzen_response->getOutputForPlatform('ko', 'Le montant payé est différent du montant intial'));
                    } else {
                        PayzenTools::getLogger()->logWarning("Error: payment success received from platform while order is in a failed status, cart #$cart_id.");
                    }

                    $msg = 'payment_ko_on_order_ok';
                } else {
                    /* just display a failure confirmation message */
                    PayzenTools::getLogger()->logInfo("Payment failure confirmed for cart #$cart_id.");
                    $msg = 'payment_ko_already_done';
                }

                die($payzen_response->getOutputForPlatform($msg));

            case (Configuration::get('PAYZEN_OS_ONEY_PENDING') && ($old_state == Configuration::get('PAYZEN_OS_ONEY_PENDING'))):
            case (Configuration::get('PAYZEN_OS_TRANS_PENDING') && ($old_state == Configuration::get('PAYZEN_OS_TRANS_PENDING'))):
            case (Configuration::get('PAYZEN_OS_AUTH_PENDING') && ($old_state == Configuration::get('PAYZEN_OS_AUTH_PENDING'))):

                PayzenTools::getLogger()->logInfo("Order for cart #$cart_id is saved in pending state. Update order status according to payment result.");

                if ($payzen_response->isPendingPayment() || ($payzen_response->isAcceptedPayment() && ($payzen->isSofort($payzen_response) || $payzen->isSepa($payzen_response)))) {
                    PayzenTools::getLogger()->logInfo("No changes for cart #$cart_id status, payment remains pending confirmation.");
                    $msg = 'payment_ok_already_done';
                } elseif ($payzen_response->isAcceptedPayment()) {
                    /* order is pending, payment success : update order status */
                    if ($outofstock) {
                        if (Configuration::get('PS_OS_OUTOFSTOCK_PAID')) {
                            $new_state = Configuration::get('PS_OS_OUTOFSTOCK_PAID');
                        } else {
                            $new_state = Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK');
                        }
                    } else {
                        $new_state = Configuration::get('PS_OS_PAYMENT');
                    }

                    PayzenTools::getLogger()->logInfo("Cart #$cart_id, payment is now accepted. New order status is $new_state.");
                    $payzen->setOrderState($order, $new_state, $payzen_response);
                    $msg = 'payment_ok';
                } else {
                    /* order is pending, payment failed : update order status */
                    $new_state = $payzen_response->isCancelledPayment() ? Configuration::get('PS_OS_CANCELED') : Configuration::get('PS_OS_ERROR');
                    PayzenTools::getLogger()->logInfo("Cart #$cart_id, payment is now failed. New order status is $new_state.");

                    $payzen->setOrderState($order, $new_state, $payzen_response);
                    $msg = 'payment_ko';
                }

                die($payzen_response->getOutputForPlatform($msg));

            case ($old_state == Configuration::get('PS_OS_PAYMENT')):
            case (Configuration::get('PS_OS_OUTOFSTOCK_PAID') && ($old_state == Configuration::get('PS_OS_OUTOFSTOCK_PAID'))):
            case (Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK') && ($old_state == Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK'))):

                if ($payzen_response->isAcceptedPayment()) {
                    /* just display a confirmation message */
                    PayzenTools::getLogger()->logInfo("Payment success confirmed for cart #$cart_id.");
                    $msg = 'payment_ok_already_done';
                } else {
                    /* order saved with success status while payment failed */
                    PayzenTools::getLogger()->logWarning("Error: payment failure received from platform while order is in a success status, cart #$cart_id.");
                    $msg = 'payment_ko_on_order_ok';
                }

                die($payzen_response->getOutputForPlatform($msg));

            default:

                PayzenTools::getLogger()->logWarning("Unknown order status for cart #$cart_id. Managed by merchant.");
                die($payzen_response->getOutputForPlatform('ok', 'Statut de commande inconnu'));
        }
    }
}
