<?php

require_once _PS_MODULE_DIR_ . 'subscriptionsmanager/models/SMSchema.php';
require_once _PS_MODULE_DIR_ . 'subscriptionsmanager/models/SMSubscription.php';
require_once _PS_MODULE_DIR_ . 'subscriptionsmanager/models/SMSAutomaticBilling.php';
require_once _PS_MODULE_DIR_ . 'subscriptionsmanager/models/SMLog.php';
require_once _PS_MODULE_DIR_ . 'subscriptionsmanager/subscriptionsmanager.php';

class SubscriptionsManagerSubscriptionsModuleFrontController extends ModuleFrontController {
	
    
	/**
	 * Afiche la liste des abonnements d'un client
	 * Traite l'arret, le renouvellement et la pause d'un abonnement
	 */
	public function initContent() {
		
		parent::initContent ();
		
		$cancel = 0;
		
		$op = Tools::getValue ( 'op' );
		$id_subscription = Tools::getValue ( 'id' );
		
		$sm = new SubscriptionsManager ();
		
		if (! empty ( $id_subscription ) && ! SMSubscription::subscriptionExists ( $id_subscription ))
			$this->show_subscriptions ( array ($sm->l ( 'This subscription is unknown.' ) ) );
		else {
			$subscription = new SMSubscription ( $id_subscription );
			
			if ($id_subscription > 0) {
				// Vérifie que l'ID d'abonnement appartient bien au client connecté
				if (! SMSubscription::isOwnedByCustomer ( $this->context->customer->id, $id_subscription )) {
					$sm->_postErrors[] = $sm->l ( 'You cant affect this subscription.' );
				}
			}
			
			switch ($op) {
				// Arret de l'abonnement
				case 'stop' :
					
					if ($subscription->customerCanStop () && !count($sm->_postErrors)) {
					    
					    $paypalSubscriptions = ModuleCore::getInstanceByName('paypalsubscriptions');
						
					    if(!empty($paypalSubscriptions) && $subscription->id_payment_module == $paypalSubscriptions->id){
						if(!PSTools::cancelPaypalSubscription($subscription)){
						    $sm->_postErrors[] = $sm->l ( 'Error while cancellation of subscription with Paypal' );
						    break;
						}
						$cancel = 1;
					    }
					    else
						$subscription->stopSubscription ();
						
						SMLog::addLog ( 'actionStop_SMSubscription', $subscription, NULL, $this->context->customer->id );
						
						// Redirection vesr la liste
						Tools::redirect ( 'index.php?fc=module&module=subscriptionsmanager&controller=subscriptions'.($cancel == 1 ? '&cancel=1' : ''));
					
					} else {
						
						$sm->_postErrors[] = $sm->l ( 'You cant stop this subscription.' );
					}
					
					break;
				case 'no_renew' :
					
					if ($subscription->customerCanNotRenew () && !count($sm->_postErrors)) {
						$subscription->is_renewable = 0;
						$subscription->update ();
						
						SMLog::addLog ( 'actionNoRenew_SMSubscription', $subscription, NULL, $this->context->customer->id );
						
						// Redirection vesr la liste
						Tools::redirect ( 'index.php?fc=module&module=subscriptionsmanager&controller=subscriptions' );
					} else {
						$sm->_postErrors[] = $sm->l ( 'You cant stop the renewal of this subscription.' );
					}
					
					break;
				default :
					
					break;
			}
						
			$this->show_subscriptions ($sm->_postErrors );
		}
	}
	
	public function show_subscriptions($errors = array()) {
	    
	    
		$sm = new SubscriptionsManager ();
		$error_list = '';
		
		// Liste des abonnements du client
		$subscriptions = SMSubscription::getList ( array ('id_customer' => ( int ) $this->context->customer->id ) );

		$echeancess = array();
		$echeances_programmed = array();
		
		foreach ( $subscriptions as $sub ) {
			
			$sub = new SMSubscription($sub['id_subscription']);
			
			$echeances = SMSAutomaticBilling::getList ( array ('id_subscription' => $sub->id ) );
			
		
			if ($sub->stock_decrementation == 1 || $sub->duration == 0) {
				
				$ech = array ();
				foreach ( $echeances as $echeance ) {
				
					if ($sub->getProductPrice () != ( int ) $echeance ['amount'])
						$ech [$echeance ['billing_date']] ['is_discount'] = 1;
					
					$ech [$echeance ['billing_date']] ['state'] = $echeance ['state'];
					$ech [$echeance ['billing_date']] ['id_subscription'] = $echeance ['id_subscription'];
					$ech [$echeance ['billing_date']] ['id_order'] = $echeance ['id_order'];
					$ech [$echeance ['billing_date']] ['price'] = $echeance ['amount'];
					$ech [$echeance ['billing_date']] ['payment_message'] = $echeance ['payment_message'];
				
				}
				
				$echeancess[$sub->id] = $ech;
				
				//$context->smarty->assign ( 'echeances_programmed', $echp );
			} else {
				// Echeances programmées de l'abonnement
				$echeances_programmed[$sub->id] = $sub->getEcheances ();
				
				foreach ( $echeances_programmed[$sub->id] as $billing_date => &$echp ) {
					foreach ( $echeances as $echeance ) {
						if ($echeance ['billing_date'] == $billing_date) {
							$echp ['state'] = $echeance ['state'];
							$echp ['id_subscription'] = $echeance ['id_subscription'];
							$echp ['id_order'] = $echeance ['id_order'];
							$echp ['amount'] = $echeance ['amount'];
							$echp ['payment_message'] = $echeance ['payment_message'];
						}
					}
				}
				
				$echeancess[$sub->id] = $echeances_programmed[$sub->id];
				
				//$context->smarty->assign ( 'echeances_programmed', $echeances_programmed );
			}
		}
		
		
		foreach ( $subscriptions as &$subscription ) {
			$sub = new SMSubscription ( $subscription ['id_subscription'] );
			$subscription ['can_stop'] = $sub->CustomerCanStop ();
			
			if ($subscription ['can_stop'] == 1)
				$subscription ['date_stop'] = $sub->getDateStop ();
			
			$subscription ['can_not_renew'] = $sub->CustomerCanNotRenew ();
			$subscription ['name'] = SMSchema::getProductWithAttributes ( $subscription ['id_product_attribute'] );
			
			
			$paypalSubscriptions = ModuleCore::getInstanceByName('paypalsubscriptions');			
						
			if(!empty($paypalSubscriptions) && $subscription['id_payment_module'] == $paypalSubscriptions->id)
			    $subscription ['isPaypalSubscription'] = 1;
			else
			    $subscription ['isPaypalSubscription'] = 0;
			
		}
		$this->context->smarty->assign ( 'subscriptions', $subscriptions );
		
		if (count($errors)){
		    
		    foreach ($errors as $err) {
			$error_list .= $sm->displayError($err);
		    }
			
		    $this->context->smarty->assign('error_list', $error_list );
		}
		
			
		$this->context->smarty->assign ( 'echeances_programmed', $echeancess );	
		$this->setTemplate ( 'subscriptions.tpl' );
	}

}