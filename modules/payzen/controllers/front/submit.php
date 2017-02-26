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
 * This controller manages return from PayZen payment gateway.
 */
class PayzenSubmitModuleFrontController extends ModuleFrontController
{
    private $current_cart;

    public function postProcess()
    {
        $cart_id = Tools::getValue('vads_order_id');
        $this->current_cart = new Cart((int)$cart_id);

        PayzenTools::getLogger()->logInfo("User return to shop process starts for cart #$cart_id.");

        // check cart errors
        if (!Validate::isLoadedObject($this->current_cart) || $this->current_cart->nbProducts() <= 0) {
            PayzenTools::getLogger()->logWarning("Cart is empty, redirect to cart page. Cart ID: $cart_id.");

            $page = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order';
            Tools::redirect('index.php?controller='.$page);
        }

        if ($this->current_cart->id_customer == 0 || $this->current_cart->id_address_delivery == 0
                || $this->current_cart->id_address_invoice == 0 || !$this->module->active) {
            PayzenTools::getLogger()->logWarning("No address selected for customer or module disabled, redirect to first checkout step. Cart ID: $cart_id.");

            $page = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order&step=1';
            Tools::redirect('index.php?controller='.$page);
        }

        $this->processPaymentReturn();
    }

    private function processPaymentReturn()
    {
        require_once _PS_MODULE_DIR_.'payzen/classes/PayzenResponse.php';

        /** @var PayzenResponse $payzen_response */
        $payzen_response = new PayzenResponse(
            $_REQUEST,
            Configuration::get('PAYZEN_MODE'),
            Configuration::get('PAYZEN_KEY_TEST'),
            Configuration::get('PAYZEN_KEY_PROD')
        );

        $cart_id = $this->current_cart->id;

        // check the authenticity of the request
        if (!$payzen_response->isAuthentified()) {
            PayzenTools::getLogger()->logError("Cart #$cart_id : authentication error ! Redirect to home page.");
            Tools::redirectLink('index.php');
        }

        // search order in db
        $order_id = Order::getOrderByCartId($cart_id);

        if ($order_id == false) {
            // order has not been processed yet

            if ($payzen_response->isAcceptedPayment()) {
                PayzenTools::getLogger()->logWarning("Payment for cart #$cart_id has been processed by client return ! This means the IPN URL did not work.");

                switch (true) {
                    case $this->module->isOneyPendingPayment($payzen_response):
                        $new_state = Configuration::get('PAYZEN_OS_ONEY_PENDING');
                        break;
                    case $this->module->isSofort($payzen_response):
                    case $this->module->isSepa($payzen_response):
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
                $order = $this->module->saveOrder($this->current_cart, $new_state, $payzen_response);

                // redirect to success page
                $this->redirectSuccess($order, $this->module->id, true);
            } else {
                // payment KO

                if (Configuration::get('PAYZEN_FAILURE_MANAGEMENT') == PayzenTools::ON_FAILURE_SAVE || $this->module->isOney($payzen_response)) {
                    // save on failure option is selected or oney payment : save order and go to history page
                    $new_state = $payzen_response->isCancelledPayment() ? Configuration::get('PS_OS_CANCELED') : Configuration::get('PS_OS_ERROR');

                    PayzenTools::getLogger()->logWarning("Payment for order #$cart_id has been processed by client return ! This means the IPN URL did not work.");

                    $msg = $this->module->isOney($payzen_response) ? 'FacilyPay Oney payment' : 'Save on failure option is selected';
                    PayzenTools::getLogger()->logInfo("$msg : save failed order for cart #$cart_id. New order status is $new_state.");

                    $order = $this->module->saveOrder($this->current_cart, $new_state, $payzen_response);

                    PayzenTools::getLogger()->logInfo("Redirect to history page, cart ID : #$cart_id.");
                    Tools::redirect('index.php?controller=history');
                } else {
                    $this->context->cookie->id_cart = $cart_id;

                    // option 2 choosen : get back to checkout process and show message
                    PayzenTools::getLogger()->logInfo("Payment failed, redirect to order checkout page, cart ID : #$cart_id.");

                    $this->context->cookie->payzenPayErrors = $this->module->l('Your payment was not accepted. Please, try to re-order.', 'submit');

                    $controller = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order'
                            .(version_compare(_PS_VERSION_, '1.5.1', '>=') && version_compare(_PS_VERSION_, '1.7', '<') ? '&step=3' : '');
                    Tools::redirect('index.php?controller='.$controller);
                }
            }
        } else {
            /* order already registered */
            PayzenTools::getLogger()->logInfo("Order already registered for cart #$cart_id.");

            $order = new Order((int)$order_id);
            $old_state = $order->getCurrentState();

            if ((!Configuration::get('PS_OS_OUTOFSTOCK_UNPAID') && ($old_state == Configuration::get('PS_OS_OUTOFSTOCK'))) ||
                    (Configuration::get('PS_OS_OUTOFSTOCK_UNPAID') && ($old_state == Configuration::get('PS_OS_OUTOFSTOCK_UNPAID')))) {
                // if out of stock, retrieve the actual order state set by our module

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

                    $msg = $this->module->isOney($payzen_response) ? 'FacilyPay Oney payment. ' : '';
                    PayzenTools::getLogger()->logInfo($msg."Order for cart #$cart_id is in a failed status.");

                    if ($payzen_response->isAcceptedPayment()) {
                        // order saved with failed status while payment is successful

                        if (number_format($order->total_paid, 2) != number_format($order->total_paid_real, 2)) {
                            /* amount paid not equals initial amount. */
                            PayzenTools::getLogger()->logWarning("Error: amount paid not equals initial amount. Order is in a failed status, cart #$cart_id.");
                        } else {
                            PayzenTools::getLogger()->logWarning("Error: payment success received from platform while order is in a failed status, cart #$cart_id.");
                        }

                        Tools::redirect(
                            'index.php?controller=order-confirmation&id_cart='.$cart_id
                            .'&id_module='.$this->module->id.'&id_order='.$order->id
                            .'&key='.$order->secure_key.'&error=yes'
                        );
                    } else {
                        // just redirect to order history page
                        PayzenTools::getLogger()->logInfo("Payment failure confirmed for cart #$cart_id.");
                        Tools::redirect('index.php?controller=history');
                    }
                    break;

                case (Configuration::get('PAYZEN_OS_ONEY_PENDING') && ($old_state == Configuration::get('PAYZEN_OS_ONEY_PENDING'))):
                case (Configuration::get('PAYZEN_OS_TRANS_PENDING') && ($old_state == Configuration::get('PAYZEN_OS_TRANS_PENDING'))):
                case (Configuration::get('PAYZEN_OS_AUTH_PENDING') && ($old_state == Configuration::get('PAYZEN_OS_AUTH_PENDING'))):

                    PayzenTools::getLogger()->logInfo("Order for cart #$cart_id is saved in pending state. Update order status according to payment result.");

                    if ($payzen_response->isPendingPayment() || ($payzen_response->isAcceptedPayment() && ($this->module->isSofort($payzen_response) || $this->module->isSepa($payzen_response)))) {
                        // redirect to success page
                        PayzenTools::getLogger()->logInfo("No changes for cart #$cart_id status, payment remains pending confirmation.");
                        $this->redirectSuccess($order, $this->module->id);
                    } else {
                        // order is in a pending state, payment is not pending : error case
                        PayzenTools::getLogger()->logWarning("Error: order saved with a pending status while payment is not pending, cart ID : #$cart_id.");
                        Tools::redirect(
                            'index.php?controller=order-confirmation&id_cart='.$cart_id
                            .'&id_module='.$this->module->id.'&id_order='.$order->id
                            .'&key='.$order->secure_key.'&error=yes'
                        );
                    }
                    break;

                case ($old_state == Configuration::get('PS_OS_PAYMENT')):
                case (Configuration::get('PS_OS_OUTOFSTOCK_PAID') && ($old_state == Configuration::get('PS_OS_OUTOFSTOCK_PAID'))):
                case (Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK') && ($old_state == Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK'))):

                    if ($payzen_response->isAcceptedPayment()) {
                        // just display a confirmation message
                        PayzenTools::getLogger()->logInfo("Payment success confirmed for cart #$cart_id.");
                        $this->redirectSuccess($order, $this->module->id);
                    } else {
                        // order saved with success status while payment failed
                        PayzenTools::getLogger()->logWarning("Error: payment failure received from platform while order is in a success status, cart #$cart_id.");
                        Tools::redirect(
                            'index.php?controller=order-confirmation&id_cart='.$cart_id
                            .'&id_module='.$this->module->id.'&id_order='.$order->id
                            .'&key='.$order->secure_key.'&error=yes'
                        );
                    }
                    break;

                default:

                    PayzenTools::getLogger()->logWarning("Unknown order status for cart #$cart_id. Managed by merchant.");

                    if ($payzen_response->isAcceptedPayment()) {
                        // redirect to success page
                        PayzenTools::getLogger()->logInfo("Payment success for cart #$cart_id. Redirect to success page.");
                        $this->redirectSuccess($order, $this->module->id);
                    } else {
                        $this->context->cookie->id_cart = $cart_id;
                        PayzenTools::getLogger()->logInfo("Payment failure for cart #$cart_id. Redirect to history page.");
                        Tools::redirect('index.php?controller=history');
                    }
                    break;
            }
        }
    }

    private function redirectSuccess($order, $id_module, $check = false)
    {
        // display a confirmation message

        $link = 'index.php?controller=order-confirmation&id_cart='.$order->id_cart
                .'&id_module='.$id_module.'&id_order='.$order->id
                .'&key='.$order->secure_key;

        // amount paid not equals initial amount. Error !
        if (number_format($order->total_paid, 2) != number_format($order->total_paid_real, 2)) {
            $link .= '&error=yes';
        }

        if (Configuration::get('PAYZEN_MODE') == 'TEST') {
            if ($check) {
                // TEST mode (user is the webmaster) : order has not been paid, but we receive a successful
                // payment code => IPN didn't work, so we display a warning
                $link .= '&check_url_warn=yes';
            }

            $link .= '&prod_info=yes';
        }

        Tools::redirect($link);
    }
}
