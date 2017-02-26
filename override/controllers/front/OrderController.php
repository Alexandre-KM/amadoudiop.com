<?php

class OrderController extends OrderControllerCore {

  public function initContent() {
    parent::initContent();

      if (Tools::isSubmit('ajax') && Tools::getValue('method') == 'updateExtraCarrier') {
          // Change virtualy the currents delivery options
          $delivery_option = $this->context->cart->getDeliveryOption();
          $delivery_option[(int)Tools::getValue('id_address')] = Tools::getValue('id_delivery_option');
          $this->context->cart->setDeliveryOption($delivery_option);
          $this->context->cart->save();
          $return = array(
              'content' => Hook::exec(
                  'displayCarrierList',
                  array(
                      'address' => new Address((int)Tools::getValue('id_address'))
                  )
              )
          );
          $this->ajaxDie(Tools::jsonEncode($return));
      }

      if ($this->nbProducts) {
          $this->context->smarty->assign('virtual_cart', $this->context->cart->isVirtualCart());
      }

      if (!Tools::getValue('multi-shipping')) {
          $this->context->cart->setNoMultishipping();
      }

      // Check for alternative payment api
      $is_advanced_payment_api = (bool)Configuration::get('PS_ADVANCED_PAYMENT_API');

      // 4 steps to the order
      switch ((int)$this->step) {

          case OrderController::STEP_SUMMARY_EMPTY_CART:
              $this->context->smarty->assign('empty', 1);
              $this->setTemplate(_PS_THEME_DIR_.'shopping-cart.tpl');
          break;

          case OrderController::STEP_ADDRESSES:
              $this->_assignAddress();
              $this->processAddressFormat();
              //$this->setTemplate(_PS_THEME_DIR_.'order-address.tpl');
              Tools::redirect('index.php?controller=order&step=2');

          break;

          case OrderController::STEP_DELIVERY:
              if (Tools::isSubmit('processAddress')) {
                  $this->processAddress();
              }
              $this->autoStep();
              $this->_assignCarrier();
              $this->setTemplate(_PS_THEME_DIR_.'order-carrier.tpl');
          break;

          case OrderController::STEP_PAYMENT:
              // Check that the conditions (so active) were accepted by the customer
              $cgv = Tools::getValue('cgv') || $this->context->cookie->check_cgv;

              if ($is_advanced_payment_api === false && Configuration::get('PS_CONDITIONS')
                  && (!Validate::isBool($cgv) || $cgv == false)) {
                  Tools::redirect('index.php?controller=order&step=2');
              }

              if ($is_advanced_payment_api === false) {
                  Context::getContext()->cookie->check_cgv = true;
              }

              // Check the delivery option is set
              if ($this->context->cart->isVirtualCart() === false) {
                  if (!Tools::getValue('delivery_option') && !Tools::getValue('id_carrier') && !$this->context->cart->delivery_option && !$this->context->cart->id_carrier) {
                      // Tools::redirect('index.php?controller=order&step=2');
                  } elseif (!Tools::getValue('id_carrier') && !$this->context->cart->id_carrier) {
                      $deliveries_options = Tools::getValue('delivery_option');
                      if (!$deliveries_options) {
                          $deliveries_options = $this->context->cart->delivery_option;
                      }

                      foreach ($deliveries_options as $delivery_option) {
                          if (empty($delivery_option)) {
                              //Tools::redirect('index.php?controller=order&step=2');
                          }
                      }
                  }
              }

              $this->autoStep();

              // Bypass payment step if total is 0
              if (($id_order = $this->_checkFreeOrder()) && $id_order) {
                  if ($this->context->customer->is_guest) {
                      $order = new Order((int)$id_order);
                      $email = $this->context->customer->email;
                      $this->context->customer->mylogout(); // If guest we clear the cookie for security reason
                      Tools::redirect('index.php?controller=guest-tracking&id_order='.urlencode($order->reference).'&email='.urlencode($email));
                  } else {
                      Tools::redirect('index.php?controller=history');
                  }
              }
              $this->_assignPayment();

              if ($is_advanced_payment_api === true) {
                  $this->_assignAddress();
              }

              // assign some informations to display cart
              $this->_assignSummaryInformations();
              $this->setTemplate(_PS_THEME_DIR_.'order-payment.tpl');
          break;

          default:
              $this->_assignSummaryInformations();
              $this->setTemplate(_PS_THEME_DIR_.'shopping-cart.tpl');
          break;
      }
  }

  public function postProcess(){
      parent::postProcess();
      if (isset($_POST['ajax'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $id_cart = Tools::getValue('id_cart');
        $signature = Tools::getValue('signature');
        if ($signature == 'image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iMCIgaGVpZ2h0PSIwIj48L3N2Zz4='){
        $data = '';
      } else {   $data = $signature;}
        Cart::addSignature($id_cart,$signature,$ip_address);
        $this->processCarrier();

        $this->ajaxDie(Tools::jsonEncode($data));


      }
  }

}
