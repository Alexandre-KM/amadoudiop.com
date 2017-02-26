<?php

class CartController extends CartControllerCore {

    public $php_self = 'cart';
    protected $id_product;
    protected $id_product_attribute;
    protected $id_address_delivery;
    protected $customization_id;
    protected $qty;
    protected $ajax_refresh = false;

    public function postProcess() {

        $actual_cart = $this->context->cart->getProducts(true); // Récuperation des produits du panier actuel
				


        // Update the cart ONLY if $this->cookies are available, in order to avoid ghost carts created by bots
        if ($this->context->cookie->exists() && !$this->errors && !($this->context->customer->isLogged() && !$this->isTokenValid())) {


            if (Tools::getIsset('add') || Tools::getIsset('update')) {

		$NEW_id_product_attribute = $this->id_product_attribute; // nouveau produit

		$is_schema_locked = Db::getInstance()->getValue("SELECT count(*) FROM `" . _DB_PREFIX_ . "SM_schema` WHERE locked = 1 AND id_product_attribute = '" . $NEW_id_product_attribute . "'");

		if($is_schema_locked)
                        $this->errors[] = Tools::displayError('This product is locked, you can\'t add it to your cart');

                if (count($actual_cart)) { // le panier est déjà peuplé
                    if (empty($this->id_product_attribute)) {
                        $is_NEW_in_sm_schema = 0;
                    } else {

                        $is_NEW_in_sm_schema = Db::getInstance()->getValue("SELECT count(*) FROM `" . _DB_PREFIX_ . "SM_schema` WHERE id_product_attribute = '" . $NEW_id_product_attribute . "'");


                    }


                    $EXISTING_id_product_attribute = $actual_cart[0]['id_product_attribute']; // nouveau produit
                    $is_EXISTING_in_sm_schema = Db::getInstance()->getValue("SELECT count(*) FROM `" . _DB_PREFIX_ . "SM_schema` WHERE id_product_attribute = '" . $EXISTING_id_product_attribute . "'");


                    if ($is_NEW_in_sm_schema)
                        $this->errors[] = Tools::displayError('You already have a product in your cart, you can\'t add a subscription');
                    elseif ($is_EXISTING_in_sm_schema)
                        $this->errors[] = Tools::displayError('You already have a subscription in you cart, you can\'t add new product');
                }

                if (!$this->errors) {
                    $this->processChangeProductInCart();
                }
            } else if (Tools::getIsset('delete'))
                $this->processDeleteProductInCart();
            else if (Tools::getIsset('changeAddressDelivery'))
                $this->processChangeProductAddressDelivery();
            else if (Tools::getIsset('allowSeperatedPackage'))
                $this->processAllowSeperatedPackage();
            else if (Tools::getIsset('duplicate'))
                $this->processDuplicateProduct();

            // Make redirection
            if (!$this->errors && !$this->ajax) {
                $queryString = Tools::safeOutput(Tools::getValue('query', null));
                if ($queryString && !Configuration::get('PS_CART_REDIRECT'))
                    Tools::redirect('index.php?controller=search&search=' . $queryString);

                // Redirect to previous page
                if (isset($_SERVER['HTTP_REFERER'])) {
                    preg_match('!http(s?)://(.*)/(.*)!', $_SERVER['HTTP_REFERER'], $regs);
                    if (isset($regs[3]) && !Configuration::get('PS_CART_REDIRECT'))
                        Tools::redirect($_SERVER['HTTP_REFERER']);
                }

                Tools::redirect('index.php?controller=order&' . (isset($this->id_product) ? 'ipa=' . $this->id_product : ''));
            }
        }
        elseif (!$this->isTokenValid())
            Tools::redirect('index.php');
    }

}
