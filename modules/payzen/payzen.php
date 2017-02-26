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

require_once _PS_MODULE_DIR_.'payzen/classes/PayzenApi.php';
require_once _PS_MODULE_DIR_.'payzen/classes/PayzenFileLogger.php';
require_once _PS_MODULE_DIR_.'payzen/classes/PayzenTools.php';

require_once _PS_MODULE_DIR_.'payzen/classes/payment/AbstractPayzenPayment.php';
require_once _PS_MODULE_DIR_.'payzen/classes/payment/PayzenAncvPayment.php';
require_once _PS_MODULE_DIR_.'payzen/classes/payment/PayzenMultiPayment.php';
require_once _PS_MODULE_DIR_.'payzen/classes/payment/PayzenOneyPayment.php';
require_once _PS_MODULE_DIR_.'payzen/classes/payment/PayzenPaypalPayment.php';
require_once _PS_MODULE_DIR_.'payzen/classes/payment/PayzenSepaPayment.php';
require_once _PS_MODULE_DIR_.'payzen/classes/payment/PayzenSofortPayment.php';
require_once _PS_MODULE_DIR_.'payzen/classes/payment/PayzenStandardPayment.php';

/**
 * PayZen payment module main class.
 */
class Payzen extends PaymentModule
{
    /* regular expressions */
    const DELIVERY_COMPANY_REGEX = '#^[A-Z0-9ÁÀÂÄÉÈÊËÍÌÎÏÓÒÔÖÚÙÛÜÇ /\'-]{1,127}$#ui';

    /* module logger */
    public $logger = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->name = 'payzen';
        $this->tab = 'payments_gateways';
        $this->version = '1.8.0';
        $this->author = 'Lyra Network';
        $this->controllers = array('redirect', 'submit');
        $this->module_key = 'f3e5d07f72a9d27a5a09196d54b9648e';
        $this->is_eu_compatible = 1;

        // check version compatibility
        $minor = Tools::substr(_PS_VERSION_, strrpos(_PS_VERSION_, '.') + 1);
        $replace = (int)$minor + 1;
        $version = substr_replace(_PS_VERSION_, (string)$replace, Tools::strlen(_PS_VERSION_) - Tools::strlen($minor));
        $this->ps_versions_compliancy = array('min' => '1.5.0.0', 'max' => $version);

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        parent::__construct();

        $order_id = (int)Tools::getValue('id_order', 0);
        $order = new Order($order_id);
        if (($order->module == $this->name) && ($this->context->controller instanceof OrderConfirmationController)) {
            // patch to use different display name according to the used payment sub-module
            $this->displayName = $order->payment;
        } else {
            $this->displayName = 'PayZen';
        }

        $this->description = $this->l('Accept payments by credit cards');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your module details ?');
    }

    /**
     * @see PaymentModuleCore::install()
     */
    public function install()
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            // incompatible version of PrestaShop
            return false;
        }

        if (!parent::install() || !$this->registerHook('header') || !$this->registerHook('paymentReturn')
                || !$this->registerHook('adminOrder') || !$this->registerHook('actionOrderSlipAdd')) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            if (!$this->registerHook('payment') || !$this->registerHook('displayPaymentEU')) {
                return false;
            }
        } else {
            if (!$this->registerHook('paymentOptions')) {
                return false;
            }
        }

        foreach (PayzenTools::getAdminParameters() as $param) {
            if (in_array($param['key'], PayzenTools::$multi_lang_fields)) {
                // multilingual field, use prestashop IDs as keys
                $default = array();

                foreach (Language::getLanguages(false) as $language) {
                    $default[$language['id_lang']] = key_exists($language['iso_code'], $param['default']) ?
                        $param['default'][$language['iso_code']] :
                        $param['default']['en'];
                }
            } else {
                $default = $param['default'];
            }

            if (!Configuration::updateValue($param['key'], $default, false, false, false)) {
                return false;
            }
        }

        ###BEGIN_ONEY_CODE###
        if (!Configuration::get('PAYZEN_OS_ONEY_PENDING')) {
            // create FacilyPay Oney pending confirmation order status
            $lang = array (
                    'en' => 'Funding request in progress',
                    'fr' => 'Demande de financement en cours',
                    'de' => 'Finanzierungsanfrage im Gange'
            );

            $name = array();
            foreach (Language::getLanguages(true) as $language) {
                $name[$language['id_lang']] = key_exists($language['iso_code'], $lang) ?
                    $lang[$language['iso_code']] :
                    $lang['en'];
            }

            $oney_state = new OrderState();
            $oney_state->name = $name;
            $oney_state->invoice = false;
            $oney_state->send_email = false;
            $oney_state->module_name = $this->name;
            $oney_state->color = '#FF8C00';
            $oney_state->unremovable = true;
            $oney_state->hidden = false;
            $oney_state->logable = false;
            $oney_state->delivery = false;
            $oney_state->shipped = false;
            $oney_state->paid = false;

            if (!$oney_state->save() || !Configuration::updateValue('PAYZEN_OS_ONEY_PENDING', $oney_state->id)) {
                return false;
            }

            // add small icon to status
            @copy(
                _PS_MODULE_DIR_.'payzen/views/img/os_oney.gif',
                _PS_IMG_DIR_.'os/'.Configuration::get('PAYZEN_OS_ONEY_PENDING').'.gif'
            );
        }
        ###END_ONEY_CODE###

        if (!Configuration::get('PS_OS_OUTOFSTOCK_PAID') && !Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK')) {
            // create a payment OK but order out of stock status
            $lang = array (
                    'en' => 'On backorder (payment accepted)',
                    'fr' => 'En attente de réapprovisionnement (paiement accepté)',
                    'de' => 'Artikel nicht auf Lager (Zahlung eingegangen)'
            );

            $name = array();
            foreach (Language::getLanguages(true) as $language) {
                $name[$language['id_lang']] = key_exists($language['iso_code'], $lang) ?
                    $lang[$language['iso_code']] :
                    $lang['en'];
            }

            $oos_state = new OrderState();
            $oos_state->name = $name;
            $oos_state->invoice = true;
            $oos_state->send_email = true;
            $oos_state->module_name = $this->name;
            $oos_state->color = '#FF69B4';
            $oos_state->unremovable = true;
            $oos_state->hidden = false;
            $oos_state->logable = false;
            $oos_state->delivery = false;
            $oos_state->shipped = false;
            $oos_state->paid = true;
            $oos_state->template = 'outofstock';

            if (!$oos_state->save() || !Configuration::updateValue('PAYZEN_OS_PAYMENT_OUTOFSTOCK', $oos_state->id)) {
                return false;
            }

            // add small icon to status
            @copy(
                _PS_MODULE_DIR_.'payzen/views/img/os_oos.gif',
                _PS_IMG_DIR_.'os/'.Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK').'.gif'
            );
        }

        if (!Configuration::get('PAYZEN_OS_AUTH_PENDING')) {
            // create payment pending authorization order status
            $lang = array (
                    'en' => 'Pending authorization',
                    'fr' => 'En attente d\'autorisation',
                    'de' => 'Autorisierung angefragt'
            );

            $name = array();
            foreach (Language::getLanguages(true) as $language) {
                $name[$language['id_lang']] = key_exists($language['iso_code'], $lang) ?
                    $lang[$language['iso_code']] :
                    $lang['en'];
            }

            $auth_state = new OrderState();
            $auth_state->name = $name;
            $auth_state->invoice = false;
            $auth_state->send_email = false;
            $auth_state->module_name = $this->name;
            $auth_state->color = '#FF8C00';
            $auth_state->unremovable = true;
            $auth_state->hidden = false;
            $auth_state->logable = false;
            $auth_state->delivery = false;
            $auth_state->shipped = false;
            $auth_state->paid = false;

            if (!$auth_state->save() || !Configuration::updateValue('PAYZEN_OS_AUTH_PENDING', $auth_state->id)) {
                return false;
            }

            // add small icon to status
            @copy(
                _PS_MODULE_DIR_.'payzen/views/img/os_auth.gif',
                _PS_IMG_DIR_.'os/'.Configuration::get('PAYZEN_OS_AUTH_PENDING').'.gif'
            );
        }

        ###BEGIN_SOFORT_CODE###
        if (!Configuration::get('PAYZEN_OS_TRANS_PENDING')) {
            // create  SOFORT and SEPA pending funds order status
            $lang = array (
                    'en' => 'Pending funds transfer',
                    'fr' => 'En attente du transfert de fonds',
                    'de' => 'Warten auf Geldtransfer'
            );

            $name = array();
            foreach (Language::getLanguages(true) as $language) {
                $name[$language['id_lang']] = key_exists($language['iso_code'], $lang) ?
                    $lang[$language['iso_code']] :
                    $lang['en'];
            }

            $sofort_state = new OrderState();
            $sofort_state->name = $name;
            $sofort_state->invoice = false;
            $sofort_state->send_email = false;
            $sofort_state->module_name = $this->name;
            $sofort_state->color = '#FF8C00';
            $sofort_state->unremovable = true;
            $sofort_state->hidden = false;
            $sofort_state->logable = false;
            $sofort_state->delivery = false;
            $sofort_state->shipped = false;
            $sofort_state->paid = false;

            if (!$sofort_state->save() || !Configuration::updateValue('PAYZEN_OS_TRANS_PENDING', $sofort_state->id)) {
                return false;
            }

            // add small icon to status
            @copy(
                _PS_MODULE_DIR_.'payzen/views/img/os_trans.gif',
                _PS_IMG_DIR_.'os/'.Configuration::get('PAYZEN_OS_TRANS_PENDING').'.gif'
            );
        }
        ###END_SOFORT_CODE###

        // clear module compiled templates
        $tpls = array(
                'payment_errors', 'payment_ancv', 'payment_multi', 'payment_oney',
                'payment_return', 'payment_sepa', 'payment_sofort', 'payment_std',
                'payment_std_eu', 'payment_paypal', 'redirect'
        );
        foreach ($tpls as $tpl) {
            $this->context->smarty->clearCompiledTemplate($this->getTemplatePath($tpl.'.tpl'));
        }

        return true;
    }

    /**
     * @see PaymentModuleCore::uninstall()
     */
    public function uninstall()
    {
        $result = true;
        foreach (PayzenTools::getAdminParameters() as $param) {
            $result &= Configuration::deleteByName($param['key']);
        }

        // delete all obsolete PayZen params but not custom order states
        $result &= Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_."configuration` WHERE `name` LIKE 'PAYZEN_%' AND `name` NOT LIKE 'PAYZEN_OS_%'"
        );

        return $result && parent::uninstall();
    }

    /**
     * Admin form management
     * @return string
     */
    public function getContent()
    {
        $msg = '';

        if (Tools::isSubmit('payzen_submit_admin_form')) {
            $this->postProcess();

            if (empty($this->_errors)) {
                // no error, display update ok message
                $msg .= $this->displayConfirmation($this->l('Settings updated.'));
            } else {
                // display errors
                $msg .= $this->displayError(implode('<br />', $this->_errors));
            }

            $msg .= '<br />';
        }

        return $msg.$this->renderForm();
    }

    /**
     * Validate and save module admin parameters
     */
    private function postProcess()
    {
        require_once _PS_MODULE_DIR_.'payzen/classes/PayzenRequest.php';
        $request = new PayzenRequest(); // new instance of PayzenRequest for parameters validation

        // load and validate from request
        foreach (PayzenTools::getAdminParameters() as $param) {
            $key = $param['key']; // PrestaShop parameter key
            $label = $this->l($param['label'], 'back_office'); // translated human-readable label
            $name = isset($param['name']) ? $param['name'] : null; // PayZen API parameter name

            $value = Tools::getValue($key, null);
            if ($value === '') { // consider empty strings as null
                $value = null;
            }

            if (in_array($key, PayzenTools::$multi_lang_fields)) {
                if (!is_array($value) || empty($value)) {
                    $value = array();
                }
            } elseif (in_array($key, PayzenTools::$group_amount_fields)) {
                if (!is_array($value) || empty($value)) {
                    $value = array();
                } else {
                    $error = false;
                    foreach ($value as $id => $option) {
                        if ($option['min_amount'] && !is_numeric($option['min_amount']) || $option['min_amount'] < 0) {
                            $value[$id]['min_amount'] = ''; // error, reset incorrect value
                            $error = true;
                        }

                        if ($option['max_amount'] && !is_numeric($option['max_amount']) || $option['max_amount'] < 0) {
                            $value[$id]['max_amount'] = ''; // error, reset incorrect value
                            $error = true;
                        }
                    }

                    if ($error) {
                        $this->_errors[] = $this->l('One or more values are invalid for field «Amount restrictions». Only valid lines are saved.');
                    }
                }

                $value = serialize($value);
            } elseif ($key === 'PAYZEN_MULTI_OPTIONS') {
                if (!is_array($value) || empty($value)) {
                    $value = array();
                } else {
                    $error = false;
                    foreach ($value as $id => $option) {
                        if (!is_numeric($option['count'])
                                || !is_numeric($option['period'])
                                || ($option['first'] && (!is_numeric($option['first']) || $option['first'] < 0 || $option['first'] > 100))) {
                            unset($value[$id]); // error, do not save this option
                            $error = true;
                        } else {
                            $default = is_string($option['label']) && $option['label'] ?
                                $option['label'] : $option['count'].' x';
                            $option_label = is_array($option['label']) ? $option['label'] : array();

                            foreach (Language::getLanguages(false) as $language) {
                                $lang = $language['id_lang'];
                                if (!isset($option_label[$lang]) || empty($option_label[$lang])) {
                                    $option_label[$lang] = $default;
                                }
                            }

                            $value[$id]['label'] = $option_label;
                        }
                    }

                    if ($error) {
                        $this->_errors[] = $this->l('One or more values are invalid for field «Payment options». Only valid lines are saved.');
                    }
                }

                $value = serialize($value);
            } elseif ($key === 'PAYZEN_AVAILABLE_LANGUAGES') {
                $value = (is_array($value) && !empty($value)) ? implode(';', $value) : '';
            } elseif ($key === 'PAYZEN_STD_PAYMENT_CARDS' || $key === 'PAYZEN_MULTI_PAYMENT_CARDS') {
                if (!is_array($value) || in_array('', $value)) {
                    $value = array();
                }

                $value = implode(';', $value);
                if (Tools::strlen($value) > 127) {
                    $this->_errors[] = $this->l('Too many card types are selected.');
                    continue;
                }

                $name = 'payment_cards';
            } elseif ($key === 'PAYZEN_ONEY_SHIP_OPTIONS') {
                if (!is_array($value) || empty($value)) {
                    $value = array();
                } else {
                    foreach ($value as $id => $option) {
                        $carrier = $option['label'].($option['address'] ?  ' '.$option['address'] : '');

                        if (!preg_match(self::DELIVERY_COMPANY_REGEX, $carrier)) {
                            unset($value[$id]); // error, not save this option
                            $this->_errors[] = sprintf($this->l('Invalid value «%1$s» for field «%2$s».'), $carrier, $label);
                        }
                    }
                }

                $value = serialize($value);
            } elseif ($key === 'PAYZEN_CATEGORY_MAPPING') {
                if (Tools::getValue('PAYZEN_COMMON_CATEGORY', null) != 'CUSTOM_MAPPING') {
                    continue;
                }

                if (!is_array($value) || empty($value)) {
                    $value = array();
                }

                $value = serialize($value);
            } elseif (($key === 'PAYZEN_ONEY_ENABLED') && ($value == 'True')) {
                $error = $this->validateOney();

                if (is_string($error) && !empty($error)) {
                    $this->_errors[] = $error;
                    $value = 'False'; // there is errors, not allow Oney activation
                }
            } elseif (in_array($key, PayzenTools::$amount_fields)) {
                if (!empty($value) && (!is_numeric($value) || $value < 0)) {
                    $this->_errors[] = sprintf($this->l('Invalid value «%1$s» for field «%2$s».'), $value, $label);
                    continue;
                }
            } elseif ($key === 'PAYZEN_STD_CARD_DATA_MODE' && $value == '3' && !Configuration::get('PS_SSL_ENABLED')) {
                $value = '1'; // force default mode
                $this->_errors[] = $this->l('The card data entry on merchant site cannot be used without enabling SSL.');
                continue;
            } elseif (($key === 'PAYZEN_STD_PROPOSE_ONEY') && $value) {
                $oneyEnabled = Tools::getValue('PAYZEN_ONEY_ENABLED', 'False') == 'True' ? true : false;

                if ($oneyEnabled) {
                    $value = '0';
                    $this->_errors[] = $this->l('FacilyPay Oney payment mean cannot be enabled in one-time payment and in FacilyPay Oney sub-module.');
                    $this->_errors[] = $this->l('You must disable the FacilyPay Oney sub-module to enable it in one-time payment.');
                } else {
                    $error = $this->validateOney(true);

                    if (is_string($error) && !empty($error)) {
                        $this->_errors[] = $error;
                        $value = '0'; // there is errors, not allow Oney activation in standard payment
                    }
                }
            }

            // validate with PayzenRequest
            if ($name) {
                $values = is_array($value) ? $value : array($value); // to check multilingual fields
                $error = false;

                foreach ($values as $v) {
                    if (!$request->set($name, $v)) {
                        $error = true;
                        if (empty($v)) {
                            $this->_errors[] = sprintf($this->l('The field «%s» is mandatory.'), $label);
                        } else {
                            $this->_errors[] = sprintf($this->l('Invalid value «%1$s» for field «%2$s».'), $v, $label);
                        }
                    }
                }

                if ($error) {
                    continue; // not save fields with errors
                }
            }

            // valid field : try to save into DB
            if (!Configuration::updateValue($key, $value)) {
                $this->_errors[] = sprintf($this->l('Problem occured while saving field «%s».'), $label);
            } else {
                // temporary variable set to update PrestaShop cache
                Configuration::set($key, $value);
            }
        }
    }

    private function validateOney($inside = false)
    {
        if (Configuration::get('PS_ALLOW_MULTISHIPPING')) {
            return $this->l('Multishipping is activated. FacilyPay Oney payment cannot be used.');
        }

        if (!$inside) {
            $group_amounts = Tools::getValue('PAYZEN_ONEY_AMOUNTS');

            $default_min = $group_amounts[0]['min_amount'];
            $default_max = $group_amounts[0]['max_amount'];

            if (empty($default_min) || empty($default_max)) {
                return $this->l('Please, enter minimum and maximum amounts in FacilyPay Oney payment tab as agreed with Banque Accord.');
            }

            foreach ($group_amounts as $id => $group) {
                if (empty($group) || $id === 0) { // All groups
                    continue;
                }

                $amount_min = $group['min_amount'];
                $amount_max = $group['max_amount'];
                if (($amount_min && $amount_min < $default_min) || ($amount_max && $amount_max > $default_max)) {
                    return $this->l('One or more values are invalid for field «Amount restrictions». Only valid lines are saved.');
                }
            }
        }

        return true;
    }

    private function renderForm()
    {
        $this->context->controller->addJS($this->_path . 'views/js/payzen.js');
        $this->context->controller->addJqueryUI('ui.accordion');

        $html = '';

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $html .= '<style type="text/css">
                            #content {
                                min-width: inherit !important;
                            }
                     </style>';
            $html .= "\n";
        }

        require_once _PS_MODULE_DIR_.'payzen/classes/admin/PayzenHelperForm.php';

        $this->context->smarty->assign(PayzenHelperForm::getAdminFormContext());
        $form = $this->context->smarty->fetch(_PS_MODULE_DIR_.'payzen/views/templates/admin/back_office.tpl');

        $prefered_post_vars = 0;
        $prefered_post_vars += substr_count($form, 'name="PAYZEN_');
        $prefered_post_vars += 100; // to take account of dynamically created inputs

        if ((ini_get('suhosin.post.max_vars') && ini_get('suhosin.post.max_vars') < $prefered_post_vars)
                || (ini_get('suhosin.request.max_vars') && ini_get('suhosin.request.max_vars') < $prefered_post_vars)) {
            $html .= $this->displayError(sprintf($this->l('Warning, please increase the suhosin patch for PHP post and request limits to save module configurations correctly. Recommended value is %s.'), $prefered_post_vars));
        } elseif (ini_get('max_input_vars') && ini_get('max_input_vars') < $prefered_post_vars) {
            $html .= $this->displayError(sprintf($this->l('Warning, please increase the value of the max_input_vars directive in php.ini to to save module configurations correctly. Recommended value is %s.'), $prefered_post_vars));
        }

        $html .= $form;
        return $html;
    }

    /**
     * Payment method selection page header.
     * @param array $params
     * @return string|void
     */
    public function hookHeader($params)
    {
        if ($this->context->controller instanceof OrderController
            || $this->context->controller instanceof OrderOpcController) {

            if (isset($this->context->cookie->payzenPayErrors)) { // process errors from other pages
                $this->context->controller->errors = array_merge(
                    $this->context->controller->errors,
                    explode("\n", $this->context->cookie->payzenPayErrors)
                );
                unset($this->context->cookie->payzenPayErrors);

                // unset HTTP_REFERER from global server variable to avoid back link display in error message
                $_SERVER['HTTP_REFERER'] = null;
                $this->context->smarty->assign('server', $_SERVER);
            }

            $html = '';

            $standard = new PayzenStandardPayment();
            if ($standard->isAvailable($this->context->cart) && $standard->getEntryMode() == '3') {
                if (method_exists($this->context->controller, 'registerJavascript')) {
                    $this->context->controller->registerJavascript(
                        'modules-payzen',
                        'modules/'.$this->name.'/views/js/card.js',
                        ['position' => 'bottom', 'priority' => 150]
                    );
                } else {
                    $this->context->controller->addJS($this->_path . 'views/js/card.js');
                }

                $html .= '<style type="text/css">'."\n";
                $html .= '  form#payzen_standard .data {
                                padding: 6px 12px;
                                font-size: 13px;
                                line-height: 1.42857;
                                vertical-align: middle;
                                background-color: #FFF;
                                border: 1px solid #D6D4D4;
                                border-radius: 0px;
                                box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;
                                margin-bottom: 12px;
                            }

                            form#payzen_standard .data.invalid {
                                background: none repeat scroll 0 0 #FAEBE7 !important;
                                border: 1px dashed #EB340A !important;
                            }'."\n";
                $html .= '</style>'."\n";
            }

            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                $suffix = version_compare(_PS_VERSION_, '1.6', '<') ? '_1.5' : '';
                $this->context->controller->addCSS($this->_path."views/css/payzen{$suffix}.css", 'all');

                // load payment module style to apply it to ours
                if ($this->useMobileTheme()) {
                    $css_file = _PS_THEME_MOBILE_DIR_.'css/global.css';
                } else {
                    $css_file = _PS_THEME_DIR_.'css/global.css';
                }

                $css = Tools::file_get_contents(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $css_file));

                $matches = array();
                $res = preg_match_all('#(p\.payment_module(?:| a| a\:hover) ?\{[^\}]+\})#i', $css, $matches);
                if ($res && !empty($matches) && isset($matches[1]) && is_array($matches[1]) && !empty($matches[1])) {
                    $html .= '<style type="text/css">'."\n";
                    $html .= str_ireplace('p.payment_module', 'div.payment_module', implode("\n", $matches[1]))."\n";
                    $html .= '</style>'."\n";
                }
            }

            return $html;
        }
    }

    protected function useMobileTheme()
    {
        if (method_exists(get_parent_class($this), 'useMobileTheme')) {
            return parent::useMobileTheme();
        } elseif (method_exists($this->context, 'getMobileDevice')) {
            return ($this->context->getMobileDevice() && file_exists(_PS_THEME_MOBILE_DIR_.'layout.tpl'));
        } else {
            return false;
        }
    }

    /**
     * Payment function, payment button render if Advanced EU Compliance module is used.
     *
     * @param array $params
     * @return void|array
     */
    public function hookDisplayPaymentEU($params)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->checkCurrency()) {
            return;
        }

        $cart = $this->context->cart;

        $standard = new PayzenStandardPayment();
        if ($standard->isAvailable($cart)) {
            $payment_options = array(
                    'cta_text' => $standard->getTitle((int)$cart->id_lang),
                    'logo' => $this->_path.'views/img/'.$standard->getLogo(),
                    'form' => $this->display(__FILE__, 'payment_std_eu.tpl')
            );

            return $payment_options;
        }
    }

    /**
     * Payment function, display payment buttons/forms for all sub-modules.
     *
     * @param array $params
     * @return void|string
     */
    public function hookPayment($params)
    {
        if (!$this->active) {
            return;
        }

        // currency support
        if (!$this->checkCurrency()) {
            return;
        }

        $cart = $this->context->cart;

        $html = '';

        $standard = new PayzenStandardPayment();
        if ($standard->isAvailable($cart)) {
            $this->context->smarty->assign($standard->getTplVars($cart));
            $html .= $this->display(__FILE__, $standard->getTplName());
        }

        $multi = new PayzenMultiPayment();
        if ($multi->isAvailable($cart)) {
            $this->context->smarty->assign($multi->getTplVars($cart));
            $html .= $this->display(__FILE__, $multi->getTplName());
        }

        $oney = new PayzenOneyPayment();
        if ($oney->isAvailable($cart)) {
            $this->context->smarty->assign($oney->getTplVars($cart));
            $html .= $this->display(__FILE__, $oney->getTplName());
        }

        $ancv = new PayzenAncvPayment();
        if ($ancv->isAvailable($cart)) {
            $this->context->smarty->assign($ancv->getTplVars($cart));
            $html .= $this->display(__FILE__, $ancv->getTplName());
        }

        $sepa = new PayzenSepaPayment();
        if ($sepa->isAvailable($cart)) {
            $this->context->smarty->assign($sepa->getTplVars($cart));
            $html .= $this->display(__FILE__, $sepa->getTplName());
        }

        $sofort = new PayzenSofortPayment();
        if ($sofort->isAvailable($cart)) {
            $this->context->smarty->assign($sofort->getTplVars($cart));
            $html .= $this->display(__FILE__, $sofort->getTplName());
        }

        $paypal = new PayzenPaypalPayment();
        if ($paypal->isAvailable($cart)) {
            $this->context->smarty->assign($paypal->getTplVars($cart));
            $html .= $this->display(__FILE__, $paypal->getTplName());
        }

        return $html;
    }

    /**
     * Payment function, display payment buttons/forms for all sub-modules in PrestaShop 1.7+.
     *
     * @param array $params
     * @return void|array[PaymentOption]
     */
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return array();
        }

        if (!$this->checkCurrency()) {
            return array();
        }

        $cart = $this->context->cart;
        $options = array();

        $standard = new PayzenStandardPayment();
        if ($standard->isAvailable($cart)) {
            $option = $standard->getPaymentOption($cart);

            if ($standard->hasForm()) {
                $this->context->smarty->assign($standard->getTplVars($cart));
                $form = $this->fetch('module:payzen/views/templates/front/'.$standard->getTplName());
                $option->setForm($form);
            }

            $options[] = $option;
        }

        $multi = new PayzenMultiPayment();
        if ($multi->isAvailable($cart)) {
            $option = $multi->getPaymentOption($cart);

            if ($multi->hasForm()) {
                $this->context->smarty->assign($multi->getTplVars($cart));
                $form = $this->fetch('module:payzen/views/templates/front/'.$multi->getTplName());
                $option->setForm($form);
            }

            $options[] = $option;
        }

        $oney = new PayzenOneyPayment();
        if ($oney->isAvailable($cart)) {
            $options[] = $oney->getPaymentOption($cart);
        }

        $ancv = new PayzenAncvPayment();
        if ($ancv->isAvailable($cart)) {
            $options[] = $ancv->getPaymentOption($cart);
        }

        $sepa = new PayzenSepaPayment();
        if ($sepa->isAvailable($cart)) {
            $options[] = $sepa->getPaymentOption($cart);
        }

        $sofort = new PayzenSofortPayment();
        if ($sofort->isAvailable($cart)) {
            $options[] = $sofort->getPaymentOption($cart);
        }

        $paypal = new PayzenPaypalPayment();
        if ($paypal->isAvailable($cart)) {
            $options[] = $paypal->getPaymentOption($cart);
        }

        return $options;
    }

    private function checkCurrency()
    {
        $cart = $this->context->cart;

        $cart_currency = new Currency((int)$cart->id_currency);
        $currencies = $this->getCurrency((int)$cart->id_currency);

        if (!is_array($currencies) || empty($currencies)) {
            return false;
        }

        foreach ($currencies as $currency) {
            if ($cart_currency->id == $currency['id_currency']) {
                // cart currency is allowed for this module
                return PayzenApi::findCurrencyByAlphaCode($cart_currency->iso_code) != null;
            }
        }

        return false;
    }

    /**
     * Manage payement gateway response.
     *
     * @param array $params
     */
    public function hookPaymentReturn($params)
    {
        $order = isset($params['order']) ? $params['order'] : $params['objOrder'];

        if (!$this->active || $order->module != $this->name) {
            return;
        }

        $error_msg = (Tools::getValue('error') == 'yes');

        $array = array(
                'check_url_warn' => (Tools::getValue('check_url_warn') == 'yes'),
                'maintenance_mode' => !Configuration::get('PS_SHOP_ENABLE'),
                'prod_info' => (Tools::getValue('prod_info') == 'yes'),
                'error_msg' => $error_msg
        );

        if ($error_msg === false) {
            $array['total_to_pay'] = Tools::displayPrice(
                $order->getOrdersTotalPaid(),
                new Currency($order->id_currency),
                false
            );
            $array['id_order'] = $order->id;
            $array['status'] = 'ok';
            $array['shop_name'] = $this->context->shop->name;

            if (isset($order->reference) && !empty($order->reference)) {
                $array['reference'] = $order->reference;
            }
        }

        $this->context->smarty->assign($array);

        return $this->display(__FILE__, 'payment_return.tpl');
    }

    /**
     * Before order details display in backend.
     * @param array $params
     */
    public function hookAdminOrder($params)
    {
        if (isset($this->context->cookie->payzenPartialRefundWarn)) {
            $this->context->controller->warnings[] = $this->context->cookie->payzenPartialRefundWarn;
            unset($this->context->cookie->payzenPartialRefundWarn);
        }
    }
    /**
     * After order slip add in backend.
     * @param array $params
     */
    public function hookActionOrderSlipAdd($params)
    {
        if (Tools::isSubmit('partialRefund') && $params['order']->module == $this->name) {
            $msg = $this->l('Refunding is not possible for this payment module. Please modify payment from your store Back Office.');
            $this->context->cookie->payzenPartialRefundWarn = $msg;
        }
    }

    /**
    * Save order and transaction info.
    */
    public function saveOrder($cart, $order_status, $payzen_response)
    {
        PayzenTools::getLogger()->logInfo("Create order for cart #{$cart->id}.");

        // retrieve customer from cart
        $customer = new Customer((int)$cart->id_customer);

        $currency = PayzenApi::findCurrency($payzen_response->get('currency'));

        // PrestaShop id_currency from currency iso num code
        $currency_id = Currency::getIdByIsoCode($currency->getAlpha3());

        // real paid total on platform
        $paid_total = $currency->convertAmountToFloat($payzen_response->get('amount'));
        if (number_format($cart->getOrderTotal(), $currency->getDecimals()) ==
            number_format($paid_total, $currency->getDecimals())) {

            // to avoid rounding issues and bypass PaymentModule::validateOrder() check
            $paid_total = $cart->getOrderTotal();
        }

        // call payment module validateOrder
        $this->validateOrder(
            $cart->id,
            $order_status,
            $paid_total,
            $payzen_response->get('order_info'), // title defined in admin panel and sent to platform as order_info
            null, // $message
            array(), // $extraVars
            $currency_id, // $currency_special
            true, // $dont_touch_amount
            $customer->secure_key
        );

        // reload order
        $order = new Order((int)Order::getOrderByCartId($cart->id));
        PayzenTools::getLogger()->logInfo("Order #{$order->id} created successfully for cart #{$cart->id}.");

        $this->createMessage($order->id, $payzen_response);

        $this->savePayment($order, $payzen_response);

        return $order;
    }

    /**
     * Update current order state.
     */
    public function setOrderState($order, $order_state, $payzen_response)
    {
        PayzenTools::getLogger()->logInfo(
            "Payment status for cart #{$order->id_cart} has changed. New order status is $order_state."
        );

        $order->setCurrentState($order_state);
        PayzenTools::getLogger()->logInfo("Order status successfully changed, cart #{$order->id_cart}.");

        $this->createMessage($order->id, $payzen_response);

        $this->savePayment($order, $payzen_response);
    }

    private function createMessage($order_id, $payzen_response)
    {
        // 3-DS extra message
        $msg3ds = "\n".$this->l('3DS authentication : ');
        if ($payzen_response->get('threeds_status') == 'Y') {
            $msg3ds .= $this->l('YES');
            $msg3ds .= "\n".$this->l('3DS certificate : ').$payzen_response->get('threeds_cavv');
        } else {
            $msg3ds .= $this->l('NO');
        }

        $msg = new Message();
        $msg->message = $payzen_response->getCompleteMessage().$msg3ds;
        $msg->id_order = (int)$order_id;
        $msg->private = 1;
        $msg->add();

        // mark message as read to archive it
        Message::markAsReaded($msg->id, 0);
    }
    /**
    * Save payment information.
    */
    public function savePayment($order, $payzen_response)
    {
        $payments = $order->getOrderPayments();

        // delete payments created by default
        if (is_array($payments) && !empty($payments)) {
            foreach ($payments as $payment) {
                if (!$payment->transaction_id) {
                    $order->total_paid_real -= $payment->amount;
                    $payment->delete();
                }
            }
        }

        if (!$this->isSuccessState($order) && !$payzen_response->isAcceptedPayment()) {
            // no payment creation
            return;
        }

        // save transaction info
        PayzenTools::getLogger()->logInfo("Save payment information for cart #{$order->id_cart}.");

        $invoices = $order->getInvoicesCollection();
        $invoice = $invoices && $invoices->getFirst() ? $invoices->getFirst() : null;

        $currency = PayzenApi::findCurrency($payzen_response->get('currency'));

        $payment_ids = array();
        if ($payzen_response->get('card_brand') == 'MULTI') {
            $sequences = Tools::jsonDecode($payzen_response->get('payment_seq'));
            $transactions = array_filter($sequences->transactions, 'Payzen::filterTransactions');

            $last_trs = end($transactions); // last transaction
            foreach ($transactions as $trs) {
                // real paid total on platform
                $amount = $currency->convertAmountToFloat($trs->{'amount'});

                if ($trs === $last_trs) {
                    $remaining = $order->total_paid - $order->total_paid_real;
                    if (number_format($remaining, $currency->getDecimals())
                        == number_format($amount, $currency->getDecimals())) {

                        // to avoid rounding problems and pass PaymentModule::validateOrder() check
                        $amount = $remaining;
                    }
                }

                $trans_id = $trs->{'sequence_number'}.'-'.$trs->{'trans_id'};
                $timestamp = isset($trs->{'presentation_date'}) ? strtotime($trs->{'presentation_date'}) : time();

                $data = array(
                        'card_number' => $trs->{'card_number'},
                        'card_brand' => $trs->{'card_brand'},
                        'expiry_month' => isset($trs->{'expiry_month'}) ? $trs->{'expiry_month'} : null,
                        'expiry_year' => isset($trs->{'expiry_year'}) ? $trs->{'expiry_year'} : null
                );

                if (!($pccId = $this->addOrderPayment($order, $invoice, $trans_id, $amount, $timestamp, $data))) {
                    return;
                }

                $payment_ids[] = $pccId;
            }
        } elseif (($info2 = $payzen_response->get('order_info2')) && $payzen_response->get('sequence_number') == 1) {
            // ID of selected payment option
            $option_id = Tools::substr($info2, Tools::strlen('option_id='));

            $multi_options = PayzenMultiPayment::getAvailableOptions();
            $option = $multi_options[$option_id];

            $count = (int) $option['count'];

            $total_amount = $payzen_response->get('amount');

            if (isset($option['first']) && $option['first']) {
                $first_amount = round($total_amount * $option['first'] / 100);
            } else {
                $first_amount = round($total_amount / $count);
            }

            $installment_amount = (int) (string) (($total_amount - $first_amount) / ($count - 1));

            $first_timestamp = strtotime($payzen_response->get('presentation_date'));

            $data = array(
                    'card_number' => $payzen_response->get('card_number'),
                    'card_brand' => $payzen_response->get('card_brand'),
                    'expiry_month' => $payzen_response->get('expiry_month'),
                    'expiry_year' => $payzen_response->get('expiry_year')
            );

            $total_paid_real = 0;
            for ($i = 1; $i <= $option['count']; $i++) {
                $trans_id = $i.'-'.$payzen_response->get('trans_id');

                $delay = (int) $option['period'] * ($i - 1);
                $timestamp = strtotime("+$delay days", $first_timestamp);

                switch (true) {
                    case ($i == 1): // first transaction
                        $amount = $currency->convertAmountToFloat($first_amount);
                        break;
                    case ($i == $option['count']): // last transaction
                        $amount = $currency->convertAmountToFloat($total_amount) - $total_paid_real;

                        $remaining = $order->total_paid - $order->total_paid_real;
                        if (number_format($remaining, $currency->getDecimals())
                            == number_format($amount, $currency->getDecimals())) {

                            // to avoid rounding problems and pass PaymentModule::validateOrder() check
                            $amount = $remaining;
                        }
                        break;
                    default: // others
                        $amount = $currency->convertAmountToFloat($installment_amount);
                        break;
                }
                $total_paid_real += $amount;

                if (!($pccId = $this->addOrderPayment($order, $invoice, $trans_id, $amount, $timestamp, $data))) {
                    return;
                }

                $payment_ids[] = $pccId;
            }
        } else {
            // real paid total on platform
            $amount = $currency->convertAmountToFloat($payzen_response->get('amount'));
            if (number_format($order->total_paid, $currency->getDecimals())
                == number_format($amount, $currency->getDecimals())) {

                // to avoid rounding problems and pass PaymentModule::validateOrder() check
                $amount = $order->total_paid;
            }

            $timestamp = strtotime($payzen_response->get('presentation_date'));
            $trans_id = $payzen_response->get('sequence_number').'-'.$payzen_response->get('trans_id');

            $data = array(
                    'card_number' => $payzen_response->get('card_number'),
                    'card_brand' => $payzen_response->get('card_brand'),
                    'expiry_month' => $payzen_response->get('expiry_month'),
                    'expiry_year' => $payzen_response->get('expiry_year')
            );

            if (!($pccId = $this->addOrderPayment($order, $invoice, $trans_id, $amount, $timestamp, $data))) {
                return;
            }

            $payment_ids[] = $pccId;
        }

        $payment_ids = implode(', ', $payment_ids);
        PayzenTools::getLogger()->logInfo(
            "Payment information with ID(s) {$payment_ids} saved successfully for cart #{$order->id_cart}."
        );
    }

    private function isSuccessState($order)
    {
        $os = new OrderState($order->getCurrentState());
        if (!$os->id) {
            return false;
        }

        // if state is one of supported states or custom state with paid flag
        return $os->id === (int)Configuration::get('PS_OS_PAYMENT')
                || $os->id === (int)Configuration::get('PS_OS_OUTOFSTOCK_UNPAID') // override pending statuses since prestashop 1.6.1
                || $os->id === (int)Configuration::get('PS_OS_OUTOFSTOCK_PAID') // override paid status since prestashop 1.6.1
                || $os->id === (int)Configuration::get('PS_OS_OUTOFSTOCK')
                || $os->id === (int)Configuration::get('PAYZEN_OS_ONEY_PENDING')
                || $os->id === (int)Configuration::get('PAYZEN_OS_TRANS_PENDING')
                || $os->id === (int)Configuration::get('PAYZEN_OS_AUTH_PENDING')
                || $os->id === (int)Configuration::get('PAYZEN_OS_PAYMENT_OUTOFSTOCK')
                || (bool)$os->paid;
    }

    private function findOrderPayment($order_ref, $trans_id)
    {
        $payment_id = Db::getInstance()->getValue(
            'SELECT `id_order_payment` FROM `'._DB_PREFIX_.'order_payment`
            WHERE `order_reference` = \''.pSQL($order_ref).'\' AND transaction_id = \''.pSQL($trans_id).'\''
        );

        if (!$payment_id) {
            return false;
        }

        return new OrderPayment((int)$payment_id);
    }

    private function addOrderPayment($order, $invoice, $trans_id, $amount, $timestamp, $data)
    {
        if (!($pcc = $this->findOrderPayment($order->reference, $trans_id))) {
            // order payment not created yet, let's create it

            $method = sprintf($this->l('%s payment'), $data['card_brand']);
            if (!$order->addOrderPayment($amount, $method, $trans_id, null, date('Y-m-d H:i:s', $timestamp), $invoice)
                    || !($pcc = $this->findOrderPayment($order->reference, $trans_id))) {

                PayzenTools::getLogger()->logWarning(
                    "Problem : payment information for cart #{$order->id_cart} cannot be saved.
                     Error may be caused by another module hooked on order update event."
                );
                return false;
            }
        } else {
            Db::getInstance()->execute(
                'REPLACE INTO `'._DB_PREFIX_.'order_invoice_payment`
                 VALUES('.(int)$invoice->id.', '.(int)$pcc->id.', '.(int)$order->id.')'
            );
        }

        // set card info
        $pcc->card_number = $data['card_number'];
        $pcc->card_brand = $data['card_brand'];
        if ($data['expiry_month'] && $data['expiry_year']) {
            $pcc->card_expiration = str_pad($data['expiry_month'], 2, '0', STR_PAD_LEFT).'/'.$data['expiry_year'];
        }
        $pcc->card_holder = null;

        if ($pcc->update()) {
            return $pcc->id;
        } else {
            PayzenTools::getLogger()->logWarning(
                "Problem : payment mean information for cart #{$order->id_cart} cannot be saved."
            );
            return false;
        }
    }

    public function isOney($payzen_response)
    {
        return $payzen_response->get('card_brand') == 'ONEY' || $payzen_response->get('card_brand') == 'ONEY_SANDBOX';
    }

    public function isOneyPendingPayment($payzen_response)
    {
        return $this->isOney($payzen_response) && $payzen_response->isPendingPayment();
    }

    public function isSofort($payzen_response)
    {
        return $payzen_response->get('card_brand') == 'SOFORT_BANKING';
    }

    public function isSepa($payzen_response)
    {
        return $payzen_response->get('card_brand') == 'SDD';
    }

    public static function filterTransactions($trs)
    {
        $successful_states = array(
                'INITIAL', 'WAITING_AUTHORISATION', 'WAITING_AUTHORISATION_TO_VALIDATE',
                'UNDER_VERIFICATION', 'AUTHORISED', 'AUTHORISED_TO_VALIDATE', 'CAPTURED',
                'CAPTURE_FAILED' /* capture will be redone */
        );

        return $trs->{'operation_type'} == 'DEBIT' && in_array($trs->{'trans_status'}, $successful_states);
    }
}
