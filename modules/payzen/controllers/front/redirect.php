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
 * This controller prepares form and redirects to PayZen payment gateway.
 */
class PayzenRedirectModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    private $accepted_payment_types = array(
        'standard',
        'multi',
        'oney',
        'ancv',
        'sepa',
        'sofort',
        'paypal'
    );

    public function __construct()
    {
        $this->display_column_left = false;
        $this->display_column_right = version_compare(_PS_VERSION_, '1.6', '<');

        parent::__construct();
    }

    /**
     * Initializes page header variables
     */
    public function initHeader()
    {
        parent::initHeader();

        // to avoid document expired warning
        session_cache_limiter('private_no_expire');
    }

    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $redirect = false;

        // page to redirect to if errors
        $page = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order';

        $cart = $this->context->cart;

        // check cart errors
        if (!Validate::isLoadedObject($cart) || $cart->nbProducts() <= 0) {
            $redirect = $this->context->link->getPageLink($page, $this->ssl, (int)$cart->id_lang);
        } elseif ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0
            || !$this->module->active) {

            $params = array();
            if (!Configuration::get('PS_ORDER_PROCESS_TYPE') && version_compare(_PS_VERSION_, '1.7', '<')) {
                $params['step'] = 1; // not one page checkout, goto first checkout step
            }

            $redirect = $this->context->link->getPageLink($page, $this->ssl, (int)$cart->id_lang, $params);
        }

        if ($this->ajax && Tools::getIsset('checkCart')) {
            $result = array();

            if ($redirect === false) { // no errors
                $result['success'] = true;
                unset($this->context->cookie->id_cart); // to avoid double call to this page
            } else {
                $result['success'] = false;
                $result['redirect'] = $redirect;
            }

            die(Tools::jsonEncode($result));
        } elseif ($redirect) {
            Tools::redirect($redirect);
        }

        $data = array();

        $type = Tools::getValue('payzen_payment_type'); /* the selected PayZen payment sub-module */
        if (!in_array($type, $this->accepted_payment_types)) {
            PayzenTools::getLogger()->logInfo('Error: payment type "' . $type . '" is not supported. Load standard payment by default.');

            // do not log sensitive data
            $sensitive_data = array('payzen_card_number', 'payzen_cvv', 'payzen_expiry_month', 'payzen_expiry_year');
            $dataToLog = array();
            foreach ($_REQUEST as $key => $value) {
                if (in_array($key, $sensitive_data)) {
                    $dataToLog[$key] = str_repeat('*', Tools::strlen($value));
                } else {
                    $dataToLog[$key] = $value;
                }
            }
            PayzenTools::getLogger()->logInfo('Request data : ' . print_r($dataToLog, true));

            $type = 'standard'; // by default, payment is standard
        }

        $payment = null;

        switch ($type) {
            case 'standard':
                $payment = new PayzenStandardPayment();

                if ($payment->getEntryMode() == 2 || $payment->getEntryMode() == 3) {
                    $data['card_type'] = Tools::getValue('payzen_card_type');

                    if ($payment->getEntryMode() == 3) {
                        $data['card_number'] = Tools::getValue('payzen_card_number');
                        $data['cvv'] = Tools::getValue('payzen_cvv');
                        $data['expiry_month'] = Tools::getValue('payzen_expiry_month');
                        $data['expiry_year'] = Tools::getValue('payzen_expiry_year');
                    }
                }

                break;

            case 'multi':
                $data['opt'] = Tools::getValue('payzen_opt');
                $data['card_type'] = Tools::getValue('payzen_card_type', '');

                $payment = new PayzenMultiPayment();
                break;

            case 'oney':
                $payment = new PayzenOneyPayment();
                break;

            case 'ancv':
                $payment = new PayzenAncvPayment();
                break;

            case 'sepa':
                $payment = new PayzenSepaPayment();
                break;

            case 'sofort':
                $payment = new PayzenSofortPayment();
                break;

            case 'paypal':
                $payment = new PayzenPaypalPayment();
                break;
        }

        // validate payment data
        $errors = $payment->validate($cart, $data);
        if (!empty($errors)) {
            $this->context->cookie->payzenPayErrors = implode("\n", $errors);
            $controller = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order'
                    .(version_compare(_PS_VERSION_, '1.5.1', '>=') && version_compare(_PS_VERSION_, '1.7', '<') ? '&step=3' : '');
                    Tools::redirect('index.php?controller='.$controller);
        }

        // prepare data for PayZen payment form
        $request = $payment->prepareRequest($cart, $data);
        $fields = $request->getRequestFieldsArray(false, false /* data escape will be done in redirect template */);

        PayzenTools::getLogger()->logInfo('Data to be sent to payment platform : ' . print_r($request->getRequestFieldsArray(true /* to hide sensitive data */), true));

        $this->context->smarty->assign('payzen_params', $fields);
        $this->context->smarty->assign('payzen_url', $request->get('platform_url'));
        $this->context->smarty->assign('payzen_logo', _MODULE_DIR_.'payzen/views/img/'.$payment->getLogo());
        $this->context->smarty->assign('payzen_title', $request->get('order_info'));

        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->setTemplate('module:payzen/views/templates/front/redirect.tpl');
        } else {
            $this->setTemplate('redirect_bc.tpl');
        }
    }
}
