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

if (!defined('_PS_VERSION_')) {
    exit;
}

class PayzenOneyPayment extends AbstractPayzenPayment
{
    protected $prefix = 'PAYZEN_ONEY_';
    protected $tpl_name = 'payment_oney.tpl';
    protected $logo = 'oney.png';
    protected $name = 'oney';

    public function isAvailable($cart)
    {
        if (!parent::isAvailable($cart)) {
            return false;
        }

        if (!PayzenTools::checkOneyRequirements($cart)) {
            return false;
        }

        return true;
    }

    protected function proposeOney($data = array())
    {
        return true;
    }

    public function validate($cart, $data = array())
    {
        $errors = parent::validate($cart, $data);
        if (!empty($errors)) {
            return $errors;
        }

        $billing_address = new Address((int)$cart->id_address_invoice);

        // check address validity according to FacilyPay Oney payment specifications
        $errors = PayzenTools::checkAddress($billing_address, 'billing');

        if (empty($errors)) {
            // billing address is valid, check delivery address
            $delivery_address = new Address((int)$cart->id_address_delivery);

            $errors = PayzenTools::checkAddress($delivery_address, 'delivery');
        }

        return $errors;
    }

    /**
     * Generate form fields to post to the payment gateway.
     *
     * @param Cart $cart
     * @param array[string][string] $data
     * @return PayzenRequest
     */
    public function prepareRequest($cart, $data = array())
    {
        $request = parent::prepareRequest($cart, $data);

        // override with FacilyPay Oney payment cards
        $test_mode = $request->get('ctx_mode') == 'TEST';
        $request->set('payment_cards', $test_mode ? 'ONEY_SANDBOX' : 'ONEY');

        return $request;
    }

    protected function getDefaultTitle()
    {
        return $this->l('Payment with FacilyPay Oney');
    }
}
