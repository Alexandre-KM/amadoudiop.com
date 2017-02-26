<?php
/**
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class FrontController extends FrontControllerCore
{
    /**
     * Initializes front controller: sets smarty variables,
     * class properties, redirects depending on context, etc.
     *
     * @global bool     $useSSL           SSL connection flag
     * @global Cookie   $cookie           Visitor's cookie
     * @global Smarty   $smarty
     * @global Cart     $cart             Visitor's cart
     * @global string   $iso              Language ISO
     * @global Country  $defaultCountry   Visitor's country object
     * @global string   $protocol_link
     * @global string   $protocol_content
     * @global Link     $link
     * @global array    $css_files
     * @global array    $js_files
     * @global Currency $currency         Visitor's selected currency
     *
     * @throws PrestaShopException
     */
    public function init()
    {
        parent::init();
        // return contrat infos
        $this->context->smarty->assign(array(
            'ip_address' => Tools::getRemoteAddr(),
            'entreprise_nom' => Configuration::get('PS_SHOP_NAME'),
            'entreprise_add1' => Configuration::get('PS_SHOP_ADDR1'),
            'entreprise_add2' => Configuration::get('PS_SHOP_ADDR2'),
            'entreprise_ville' => Configuration::get('PS_SHOP_CITY'),
            'entreprise_codepost' => Configuration::get('PS_SHOP_CODE'),
            'entreprise_pays' => Configuration::get('PS_SHOP_COUNTRY'),
            'entreprise_siret' => Configuration::get('PS_SHOP_DETAILS'),
        ));
        include_once(_PS_GEOIP_DIR_.'geoipcity.inc');
        $gi = geoip_open(realpath(_PS_GEOIP_DIR_._PS_GEOIP_CITY_FILE_), GEOIP_STANDARD);
        $record = geoip_record_by_addr($gi, Tools::getRemoteAddr());
        $this->context->smarty->assign(array(
            'geolocation_city'     => $record->city,
            'geolocation_country'     => $record->country_name
        ));
        $context = Context::getContext();
        if ($context->cookie->isLogged()){
            $customer = $context->customer;
            $id_customer = $customer->id;
            $id_group = $customer->id_default_group;
            $website = $customer->website;
            $company = $customer->company;
            $siret = $customer->siret;
            $id_lang = $context->language->id;
            $customer_address = Address::getCustomerAddress($id_customer);
            $customer_address_country = Country::getNameById($id_lang, 8);
            $context->smarty->assign(array(
                'customer_id'     => $id_customer,
                'customer_group'     => $id_group,
                'customer_company'     => $company,
                'customer_siret'     => $siret,
                'customer_website'     => $website,
                'customer_address'     => $customer_address,
                'customer_country'     => $customer_address_country,
            ));
        }
    }
}
