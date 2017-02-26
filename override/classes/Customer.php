<?php
/*
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

class Customer extends CustomerCore {

    public static function registerAccountModal($group,$prenom,$nom,$fonction,$phone,$email,$password,$denomination,$siret,$website) {

        $passwd = md5(_COOKIE_KEY_.$password);

        $customer = new Customer();
        $customer->passwd = $passwd;
        $customer->email = $email;
        $customer->firstname = $prenom;
        $customer->lastname = $nom;
        $customer->website = $website;
        $customer->company = $denomination;
        $customer->siret = $siret;
        $customer->active = 1;
        $customer->newsletter = 0;
        $customer->id_default_group = 3; // Ca marche :)
        $customer->add();
        $customer->cleanGroups();
        $customer->addGroups(array(3,2,1));
        $context = Context::getContext();
        $context->cookie->__set('id_customer' , $customer->id);
        $context->cookie->__set('id_group_default' , $customer->id_default_group);
        $context->cookie->__set('customer_lastname' , $customer->lastname);
        $context->cookie->__set('customer_firstname' , $customer->firstname);
        $context->cookie->__set('passwd' , $customer->passwd);
        $context->cookie->__set('logged' , 1);
        $context->cookie->__set('email' , $customer->email);
        $context->cart->secure_key = $customer->secure_key;


        }
}
