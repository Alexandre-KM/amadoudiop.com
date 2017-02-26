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

class AuthController extends AuthControllerCore {


   public function postProcess()
   {
       if (Tools::isSubmit('SubmitCreate')) {
           $this->processSubmitCreate();
       }
       if (Tools::isSubmit('submitAccount') || Tools::isSubmit('submitGuestAccount')) {
           $this->processSubmitAccount();
       }
       if (Tools::isSubmit('SubmitLogin')) {
           $this->processSubmitLogin();
       }
        if (Tools::isSubmit('submitAccountModal')) {
           $this->processSubmitAccountModal();
       }
   }

   protected function processSubmitAccountModal()
   {
      //On recupere les inputs
       $group = Tools::getValue('group');
       $password = trim(Tools::getValue('passwd'));
       $_POST['passwd'] = null;
       $email = trim(Tools::getValue('email'));
       $postcode = trim(Tools::getValue('customer_postcode'));
       $prenom = trim(Tools::getValue('customer_firstname'));
       $nom = trim(Tools::getValue('customer_lastname'));
       $fonction = trim(Tools::getValue('customer_fonction'));
       $phone = trim(Tools::getValue('customer_phone'));
       $denomination = trim(Tools::getValue('customer_denomination'));
       $siret = trim(Tools::getValue('customer_siret'));
       $website = trim(Tools::getValue('customer_website'));
       $address1 = Tools::getValue('customer_address');
       $address2 = Tools::getValue('customer_address2');
       $ville = trim(Tools::getValue('customer_city'));
       //On vérifie les inputs
       if (empty($email)) {
           $this->errors[] = Tools::displayError('An email address required.');
       } elseif (!Validate::isEmail($email)) {
           $this->errors[] = Tools::displayError('Invalid email address.');
       } elseif (empty($password)) {
           $this->errors[] = Tools::displayError('Password is required.');
       } elseif (!Validate::isPasswd($password)) {
           $this->errors[] = Tools::displayError('Invalid password.');
       } elseif (empty($postcode)) {
           $this->errors[] = Tools::displayError('A Zip / Postal code is required.');
       } elseif ($postcode && !Validate::isPostCode($postcode)) {
           $this->errors[] = Tools::displayError('The Zip / Postal code is invalid.');
       } elseif (empty($prenom)) {
           $this->errors[] = Tools::displayError('Prénom obligatoire');
       } elseif ($prenom && !Validate::isGenericName($prenom)) {
           $this->errors[] = Tools::displayError('Prénom invalide');
       } elseif (empty($nom)) {
           $this->errors[] = Tools::displayError('Nom obligatoire');
       } elseif ($nom && !Validate::isGenericName($nom)) {
           $this->errors[] = Tools::displayError('Nom invalide');
       } elseif (empty($fonction)) {
           $this->errors[] = Tools::displayError('Fonction obligatoire');
       } elseif ($fonction && !Validate::isGenericName($fonction)) {
           $this->errors[] = Tools::displayError('Fonction invalide');
       } elseif (empty($phone)) {
           $this->errors[] = Tools::displayError('Téléphone obligatoire');
       } elseif ($phone && !Validate::isPhoneNumber($phone)) {
           $this->errors[] = Tools::displayError('Téléphone invalide');
       } elseif (empty($denomination)) {
           $this->errors[] = Tools::displayError('La dénomisation de votre entreprise est obligatoire');
       } elseif ($denomination && !Validate::isGenericName($denomination)) {
           $this->errors[] = Tools::displayError('Dénomisation invalide');
       } elseif (empty($siret)) {
           $this->errors[] = Tools::displayError('SIRET obligatoire');
       } elseif ($siret && !Validate::isSiret($siret)) {
           $this->errors[] = Tools::displayError('SIRET invalide');
       } elseif ($website && !Validate::isUrl($website)) {
           $this->errors[] = Tools::displayError('Website invalide');
       } elseif (empty($address1)) {
           $this->errors[] = Tools::displayError('Addresse obligatoire');
       } elseif ($address1 && !Validate::isAddress($address1)) {
           $this->errors[] = Tools::displayError('Addresse invalide');
       } elseif ($address2 && !Validate::isAddress($address2)) {
           $this->errors[] = Tools::displayError('Addresse complément invalide');
       } elseif (empty($ville)) {
           $this->errors[] = Tools::displayError('Ville obligatoire');
       } elseif ($ville && !Validate::isCityName($ville)) {
           $this->errors[] = Tools::displayError('Ville invalide');
       }
       else {
           Customer::registerAccountModal($group,$prenom,$nom,$fonction,$phone,$email,$password,$denomination,$siret,$website);
           Address::registerAddressModal($prenom,$nom,$phone,$denomination,$siret,$address1,$address2,$postcode,$ville);
           Tools::redirect('index.php');
      }

   }

}
