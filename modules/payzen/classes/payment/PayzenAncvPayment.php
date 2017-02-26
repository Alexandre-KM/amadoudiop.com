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

class PayzenAncvPayment extends AbstractPayzenPayment
{
    protected $prefix = 'PAYZEN_ANCV_';
    protected $tpl_name = 'payment_ancv.tpl';
    protected $logo = 'e_cv.png';
    protected $name = 'ancv';

    public function isAvailable($cart)
    {
        if (!parent::isAvailable($cart)) {
            return false;
        }

        // ANCV is available only in France
        $billing_address = new Address($cart->id_address_invoice);
        $country = new Country($billing_address->id_country);
        if ($country->iso_code != 'FR') {
            return false;
        }

        return true;
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

        // override with ANCV card
        $request->set('payment_cards', 'E_CV');

        return $request;
    }

    protected function getDefaultTitle()
    {
        return $this->l('Payment with ANCV');
    }
}
