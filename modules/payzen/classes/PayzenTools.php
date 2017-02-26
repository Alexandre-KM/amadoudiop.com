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

class PayzenTools
{
    const ORDER_ID_REGEX = '#^[a-zA-Z0-9]{1,9}$#';
    const CUST_ID_REGEX = '#^[a-zA-Z0-9]{1,8}$#';
    const PRODUCT_REF_REGEX = '#^[a-zA-Z0-9]{1,64}$#';

    const ON_FAILURE_RETRY = 'retry';
    const ON_FAILURE_SAVE = 'save';

    /* fields lists */
    public static $multi_lang_fields = array(
            'PAYZEN_REDIRECT_SUCCESS_M', 'PAYZEN_REDIRECT_ERROR_M',
            'PAYZEN_STD_TITLE', 'PAYZEN_MULTI_TITLE', 'PAYZEN_ONEY_TITLE', 'PAYZEN_ANCV_TITLE',
            'PAYZEN_SEPA_TITLE', 'PAYZEN_SOFORT_TITLE', 'PAYZEN_PAYPAL_TITLE'
    );
    public static $amount_fields = array('PAYZEN_3DS_MIN_AMOUNT');
    public static $group_amount_fields = array(
            'PAYZEN_STD_AMOUNTS', 'PAYZEN_MULTI_AMOUNTS', 'PAYZEN_ANCV_AMOUNTS',
            'PAYZEN_ONEY_AMOUNTS', 'PAYZEN_SEPA_AMOUNTS', 'PAYZEN_SOFORT_AMOUNTS',
            'PAYZEN_PAYPAL_AMOUNTS'
    );

    public static function checkAddress($address, $type)
    {
        /* @var Payzen */
        $payzen = Module::getInstanceByName('payzen');

        $invalid_msg = $payzen->l('The field %1$s of your %2$s is invalid.', 'payzentools');
        $empty_msg = $payzen->l('The field %1$s of your %2$s is mandatory.', 'payzentools');

        $name_regex = "#^[A-ZÁÀÂÄÉÈÊËÍÌÎÏÓÒÔÖÚÙÛÜÇ/ '-]{1,63}$#ui";
        $phone_regex = '#^[0-9]{10}$#';
        $city_regex = "#^[A-Z0-9ÁÀÂÄÉÈÊËÍÌÎÏÓÒÔÖÚÙÛÜÇ/ '-]{1,127}$#ui";
        $street_regex = "#^[A-Z0-9ÁÀÂÄÉÈÊËÍÌÎÏÓÒÔÖÚÙÛÜÇ/ '.,-]{1,127}$#ui";
        $country_regex = '#^FR$#i';
        $zip_regex = '#^[0-9]{5}$#';

        $address_type = $type == 'billing' ? $payzen->l('billing address', 'payzentools') : $payzen->l('delivery address', 'payzentools');

        $errors = array();

        if (empty($address->lastname)) {
            $errors[] = sprintf($empty_msg, $payzen->l('Last name', 'payzentools'), $address_type);
        } elseif (!preg_match($name_regex, $address->lastname))
            $errors[] = sprintf($invalid_msg, $payzen->l('Last name', 'payzentools'), $address_type);

        if (empty($address->firstname)) {
            $errors[] = sprintf($empty_msg, $payzen->l('First name', 'payzentools'), $address_type);
        } elseif (!preg_match($name_regex, $address->firstname)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('First name', 'payzentools'), $address_type);
        }

        if (!empty($address->phone) && !preg_match($phone_regex, $address->phone)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('Phone', 'payzentools'), $address_type);
        }

        if (!empty($address->phone_mobile) && !preg_match($phone_regex, $address->phone_mobile)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('Phone mobile', 'payzentools'), $address_type);
        }

        if (empty($address->address1)) {
            $errors[] = sprintf($empty_msg, $payzen->l('Address', 'payzentools'), $address_type);
        } elseif (!preg_match($street_regex, $address->address1)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('Address', 'payzentools'), $address_type);
        }

        if (!empty($address->address2) && !preg_match($street_regex, $address->address2)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('Address2', 'payzentools'), $address_type);
        }

        if (empty($address->postcode)) {
            $errors[] = sprintf($empty_msg, $payzen->l('Zip code', 'payzentools'), $address_type);
        } elseif (!preg_match($zip_regex, $address->postcode)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('Zip code', 'payzentools'), $address_type);
        }

        if (empty($address->city)) {
            $errors[] = sprintf($empty_msg, $payzen->l('City', 'payzentools'), $address_type);
        } elseif (!preg_match($city_regex, $address->city)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('City', 'payzentools'), $address_type);
        }

        $country = new Country($address->id_country);
        if (empty($country->iso_code)) {
            $errors[] = sprintf($empty_msg, $payzen->l('Country', 'payzentools'), $address_type);
        } elseif (!preg_match($country_regex, $country->iso_code)) {
            $errors[] = sprintf($invalid_msg, $payzen->l('Country', 'payzentools'), $address_type);
        }

        return $errors;
    }

    /**
     * Return the list of configuration parameters with their payzen names and default values.
     *
     * @return array[array[key, name, default]]
     */
    public static function getAdminParameters()
    {
        // NB : keys are 32 chars max
        $params = array(
                array('key' => 'PAYZEN_ENABLE_LOGS', 'default' => 'True', 'label' => 'Logs'),

                array('key' => 'PAYZEN_SITE_ID', 'name' => 'site_id', 'default' => '12345678', 'label' => 'Site ID'),
                array('key' => 'PAYZEN_KEY_TEST', 'name' => 'key_test', 'default' => '1111111111111111',
                    'label' => 'Certificate in test mode'),
                array('key' => 'PAYZEN_KEY_PROD', 'name' => 'key_prod', 'default' => '2222222222222222',
                    'label' => 'Certificate in production mode'),
                array('key' => 'PAYZEN_MODE', 'name' => 'ctx_mode', 'default' => 'TEST', 'label' => 'Mode'),
                array('key' => 'PAYZEN_PLATFORM_URL', 'name' => 'platform_url',
                    'default' => 'https://secure.payzen.eu/vads-payment/', 'label' => 'Payment page URL'),

                array('key' => 'PAYZEN_DEFAULT_LANGUAGE', 'default' => 'fr', 'label' => 'Default language'),
                array('key' => 'PAYZEN_AVAILABLE_LANGUAGES', 'name' => 'available_languages', 'default' => '',
                    'label' => 'Available languages'),
                array('key' => 'PAYZEN_DELAY', 'name' => 'capture_delay', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_VALIDATION_MODE', 'name' => 'validation_mode', 'default' => '',
                    'label' => 'Payment validation'),

                array('key' => 'PAYZEN_THEME_CONFIG', 'name' => 'theme_config', 'default' => '',
                    'label' => 'Theme configuration'),
                array('key' => 'PAYZEN_SHOP_NAME', 'name' => 'shop_name', 'default' => '', 'label' => 'Shop name'),
                array('key' => 'PAYZEN_SHOP_URL', 'name' => 'shop_url', 'default' => '', 'label' => 'Shop URL'),

                array('key' => 'PAYZEN_3DS_MIN_AMOUNT', 'default' => '', 'label' => 'Minimum amount to activate 3-DS'),

                array('key' => 'PAYZEN_REDIRECT_ENABLED', 'name' => 'redirect_enabled', 'default' => 'False',
                    'label' => 'Automatic redirection'),
                array('key' => 'PAYZEN_REDIRECT_SUCCESS_T', 'name' => 'redirect_success_timeout', 'default' => '5',
                        'label' => 'Redirection timeout on success'),
                array('key' => 'PAYZEN_REDIRECT_SUCCESS_M', 'name' => 'redirect_success_message',
                    'default' => array(
                        'en' => 'Redirection to shop in few seconds...',
                        'fr' => 'Redirection vers la boutique dans quelques instants...',
                        'de' => 'Weiterleitung zum Shop in Kürze...'
                    ),
                    'label' => 'Redirection message on success'),
                array('key' => 'PAYZEN_REDIRECT_ERROR_T', 'name' => 'redirect_error_timeout', 'default' => '5',
                    'label' => 'Redirection timeout on failure'),
                array('key' => 'PAYZEN_REDIRECT_ERROR_M', 'name' => 'redirect_error_message',
                    'default' => array(
                        'en' => 'Redirection to shop in few seconds...',
                        'fr' => 'Redirection vers la boutique dans quelques instants...',
                        'de' => 'Weiterleitung zum Shop in Kürze...'
                    ),
                    'label' => 'Redirection message on failure'),
                array('key' => 'PAYZEN_RETURN_MODE', 'name' => 'return_mode', 'default' => 'GET',
                    'label' => 'Return mode'),
                array('key' => 'PAYZEN_FAILURE_MANAGEMENT', 'default' => self::ON_FAILURE_RETRY,
                    'label' => 'Payment failed management'),

                array('key' => 'PAYZEN_COMMON_CATEGORY', 'default' => 'FOOD_AND_GROCERY',
                    'label' => 'Category mapping'),
                array('key' => 'PAYZEN_CATEGORY_MAPPING', 'default' => array(), 'label' => 'Category mapping'),
                array('key' => 'PAYZEN_SEND_SHIP_DATA', 'default' => '0',
                    'label' => 'Always send advanced shipping data'),
                array('key' => 'PAYZEN_ONEY_SHIP_OPTIONS', 'default' => array(), 'label' => 'Shipping options'),

                array('key' => 'PAYZEN_STD_TITLE',
                    'default' => array(
                        'en' => 'Payment by bank card',
                        'fr' => 'Paiement par carte bancaire',
                        'de' => 'Zahlung mit EC-/Kreditkarte'
                    ),
                    'label' => 'Method title'),
                array('key' => 'PAYZEN_STD_ENABLED', 'default' => 'True', 'label' => 'Activation'),
                array('key' => 'PAYZEN_STD_DELAY', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_STD_VALIDATION', 'default' => '-1', 'label' => 'Payment validation'),
                array('key' => 'PAYZEN_STD_PAYMENT_CARDS', 'default' => '', 'label' => 'Card Types'),
                array('key' => 'PAYZEN_STD_PROPOSE_ONEY', 'default' => '0', 'label' => 'Propose FacilyPay Oney'),
                array('key' => 'PAYZEN_STD_AMOUNTS', 'default' => array(), 'label' => 'Amount restrictions'),
                array('key' => 'PAYZEN_STD_CARD_DATA_MODE', 'default' => '1', 'label' => 'Card data entry mode'),

                array('key' => 'PAYZEN_MULTI_TITLE',
                    'default' => array(
                        'en' => 'Payment by bank card in several times',
                        'fr' => 'Paiement par carte bancaire en plusieurs fois',
                        'de' => 'Ratenzahlung mit EC-/Kreditkarte'
                    ),
                    'label' => 'Method title'),
                array('key' => 'PAYZEN_MULTI_ENABLED', 'default' => 'False', 'label' => 'Activation'),
                array('key' => 'PAYZEN_MULTI_DELAY', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_MULTI_VALIDATION', 'default' => '-1', 'label' => 'Payment validation'),
                array('key' => 'PAYZEN_MULTI_PAYMENT_CARDS', 'default' => '', 'label' => 'Card Types'),
                array('key' => 'PAYZEN_MULTI_CARD_MODE', 'default' => '1', 'label' => 'Card selection mode'),
                array('key' => 'PAYZEN_MULTI_AMOUNTS', 'default' => array(), 'label' => 'Amount restrictions'),
                array('key' => 'PAYZEN_MULTI_OPTIONS', 'default' => array(), 'label' => 'Payment options'),

                array('key' => 'PAYZEN_ONEY_TITLE',
                    'default' => array(
                        'en' => 'Payment with FacilyPay Oney',
                        'fr' => 'Paiement avec FacilyPay Oney',
                        'de' => 'Zahlung via FacilyPay Oney'
                    ),
                    'label' => 'Method title'),
                array('key' => 'PAYZEN_ONEY_ENABLED', 'default' => 'False', 'label' => 'Activation'),
                array('key' => 'PAYZEN_ONEY_DELAY', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_ONEY_VALIDATION', 'default' => '-1', 'label' => 'Payment validation'),
                array('key' => 'PAYZEN_ONEY_AMOUNTS', 'default' => array(), 'label' => 'Amount restrictions'),

                array('key' => 'PAYZEN_ANCV_TITLE',
                    'default' => array(
                        'en' => 'Payment with ANCV',
                        'fr' => 'Paiement avec ANCV',
                        'de' => 'Zahlung via ANCV'
                    ),
                    'label' => 'Method title'),
                array('key' => 'PAYZEN_ANCV_ENABLED', 'default' => 'False', 'label' => 'Activation'),
                array('key' => 'PAYZEN_ANCV_DELAY', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_ANCV_VALIDATION', 'default' => '-1', 'label' => 'Payment validation'),
                array('key' => 'PAYZEN_ANCV_AMOUNTS', 'default' => array(), 'label' => 'Amount restrictions'),

                array('key' => 'PAYZEN_SEPA_TITLE',
                    'default' => array(
                        'en' => 'Payment with SEPA',
                        'fr' => 'Paiement avec SEPA',
                        'de' => 'Zahlung via SEPA'
                    ),
                    'label' => 'Method title'),
                array('key' => 'PAYZEN_SEPA_ENABLED', 'default' => 'False', 'label' => 'Activation'),
                array('key' => 'PAYZEN_SEPA_DELAY', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_SEPA_VALIDATION', 'default' => '-1', 'label' => 'Payment validation'),
                array('key' => 'PAYZEN_SEPA_AMOUNTS', 'default' => array(), 'label' => 'Amount restrictions'),

                array('key' => 'PAYZEN_SOFORT_TITLE',
                    'default' => array(
                        'en' => 'Payment with SOFORT Banking',
                        'fr' => 'Paiement avec SOFORT Banking',
                        'de' => 'Zahlung via SOFORT Banking'
                    ),
                    'label' => 'Method title'),
                array('key' => 'PAYZEN_SOFORT_ENABLED', 'default' => 'False', 'label' => 'Activation'),
                array('key' => 'PAYZEN_SOFORT_DELAY', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_SOFORT_VALIDATION', 'default' => '-1', 'label' => 'Payment validation'),
                array('key' => 'PAYZEN_SOFORT_AMOUNTS', 'default' => array(), 'label' => 'Amount restrictions'),

                array('key' => 'PAYZEN_PAYPAL_TITLE',
                    'default' => array(
                        'en' => 'Payment with PayPal',
                        'fr' => 'Paiement avec PayPal',
                        'de' => 'Zahlung via  PayPal'
                    ),
                    'label' => 'Method title'),
                array('key' => 'PAYZEN_PAYPAL_ENABLED', 'default' => 'False', 'label' => 'Activation'),
                array('key' => 'PAYZEN_PAYPAL_DELAY', 'default' => '', 'label' => 'Capture delay'),
                array('key' => 'PAYZEN_PAYPAL_VALIDATION', 'default' => '-1', 'label' => 'Payment validation'),
                array('key' => 'PAYZEN_PAYPAL_AMOUNTS', 'default' => array(), 'label' => 'Amount restrictions'),
        );

        return $params;
    }

    public static function checkOneyRequirements($cart)
    {
        // check order_id param
        if (!preg_match(self::ORDER_ID_REGEX, $cart->id)) {
            $msg = 'Order ID «%s» does not match FacilyPay Oney specifications.';
            $msg .= ' The regular expression for this field is «%s». Module is not displayed.';
            PayzenTools::getLogger()->logWarning(sprintf($msg, $cart->id, self::ORDER_ID_REGEX));
            return false;
        }

        // check customer ID param
        if (!preg_match(self::CUST_ID_REGEX, $cart->id_customer)) {
            $msg = 'Customer ID «%s» does not match FacilyPay Oney specifications.';
            $msg .= ' The regular expression for this field is «%s». Module is not displayed.';
            PayzenTools::getLogger()->logWarning(sprintf($msg, $cart->id_customer, self::CUST_ID_REGEX));
            return false;
        }

        // check products
        foreach ($cart->getProducts(true) as $product) {
            if (!preg_match(self::PRODUCT_REF_REGEX, $product['id_product'])) {
                // product id doesn't match FacilyPay Oney rules

                $msg = 'Product reference «%s» does not match FacilyPay Oney specifications.';
                $msg .= ' The regular expression for this field is «%s». Module is not displayed.';
                PayzenTools::getLogger()->logWarning(sprintf($msg, $product['id_product'], self::PRODUCT_REF_REGEX));
                return false;
            }
        }

        return true;
    }

    public static function getSupportedCardTypes()
    {
        $cards = PayzenApi::getSupportedCardTypes();

        if (isset($cards['ONEY'])) {
            unset($cards['ONEY']);
        }
        if (isset($cards['ONEY_SANDBOX'])) {
            unset($cards['ONEY_SANDBOX']);
        }

        return $cards;
    }

    public static function getSupportedMultiCardTypes()
    {
        $multi_cards = array(
            'AMEX', 'CB', 'DINERS', 'DISCOVER', 'E-CARTEBLEUE', 'ECCARD', 'JCB ', 'MAESTRO',
            'MASTERCARD', 'PAYBOX', 'PAYDIREKT', 'PRV_BDP', 'PRV_BDT', 'PRV_OPT', 'PRV_SOC',
            'VISA', 'VISA_ELECTRON'
        );

        $cards = array();
        foreach (PayzenApi::getSupportedCardTypes() as $code => $label) {
            if (in_array($code, $multi_cards)) {
                $cards[$code] = $label;
            }
        }

        return $cards;
    }

    /**
     * SoColissimo does not set delivery address ID into cart object.
     * So we get address data from SoColissimo database table.
     *
     * @param Cart $cart
     * @return Address|null
     */
    public static function getColissimoDeliveryAddress($cart)
    {
        // SoColissimo not installed
        if (!Configuration::get('SOCOLISSIMO_CARRIER_ID')) {
            return null;
        }

        // SoColissimo is not selected as shipping method
        if ($cart->id_carrier != Configuration::get('SOCOLISSIMO_CARRIER_ID')) {
            return null;
        }

        // get address saved by SoColissimo
        $row = Db::getInstance()->getRow(
            'SELECT * FROM '._DB_PREFIX_.'socolissimo_delivery_info WHERE id_cart = \''.
            (int)$cart->id.'\' AND id_customer = \''.(int)$cart->id_customer.'\''
        );

        $not_alloewd_chars = array(' ', '.', '-', ',', ';', '+', '/', '\\', '+', '(', ')');
        $new_address = new Address();
        $ps_address = new Address((int)$cart->id_address_delivery);
        if (Tools::strtoupper($ps_address->lastname) != Tools::strtoupper($row['prname'])
                || Tools::strtoupper($ps_address->firstname) != Tools::strtoupper($row['prfirstname'])
                || Tools::strtoupper($ps_address->address1) != Tools::strtoupper($row['pradress3'])
                || Tools::strtoupper($ps_address->address2) != Tools::strtoupper($row['pradress2'])
                || Tools::strtoupper($ps_address->postcode) != Tools::strtoupper($row['przipcode'])
                || Tools::strtoupper($ps_address->city) != Tools::strtoupper($row['prtown'])
                || str_replace($not_alloewd_chars, '', $ps_address->phone_mobile) != $row['cephonenumber']) {

            // address is modified in SoColissimo page : use SoColissimo address as delivery address
            $new_address->lastname = Tools::substr($row['prname'], 0, 32);
            $new_address->firstname = Tools::substr($row['prfirstname'], 0, 32);
            $new_address->postcode = $row['przipcode'];
            $new_address->city = $row['prtown'];
            $new_address->id_country = Country::getIdByName(null, 'france');

            if (!in_array($row['delivery_mode'], array('DOM', 'RDV'))) {
                $new_address->address1 = $row['pradress1'];
                $new_address->address1 .= isset($row['pradress2']) ?  ' '.$row['pradress2'] : '';
                $new_address->address1 .= isset($row['pradress3']) ?  ' '.$row['pradress3'] : '';
                $new_address->address1 .= isset($row['pradress4']) ?  ' '.$row['pradress4'] : '';
            } else {
                $new_address->address1 = $row['pradress3'];
                $new_address->address2 = isset($row['pradress4']) ? $row['pradress4'] : '';
                $new_address->other = isset($row['pradress1']) ?  $row['pradress1'] : '';
                $new_address->other .= isset($row['pradress2']) ?  ' '.$row['pradress2'] : '';
            }

            // return the SoColissimo updated address
            return $new_address;
        } else {
            // use initial customer address
            return null;
        }
    }

    private static $logger;

    public static function getLogger()
    {
        if (!self::$logger) {
            self::$logger = new PayzenFileLogger(Configuration::get('PAYZEN_ENABLE_LOGS') != 'False');

            $logs_dir = _PS_ROOT_DIR_.'/app/logs/';
            if (!file_exists($logs_dir)) {
                $logs_dir = _PS_ROOT_DIR_.'/log/';
            }

            self::$logger->setFilename($logs_dir.date('Y_m').'_payzen.log');
        }

        return self::$logger;
    }
}
