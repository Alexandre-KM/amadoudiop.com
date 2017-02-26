<?php

require (dirname ( __FILE__ ) . '/../../config/config.inc.php');
include_once (_PS_ROOT_DIR_ . '/init.php');

require_once (dirname ( __FILE__ ) . '/models/SMTools.php');
require_once (dirname ( __FILE__ ) . '/models/SMSchema.php');
require_once (dirname ( __FILE__ ) . '/models/SMSubscription.php');
require_once (dirname ( __FILE__ ) . '/subscriptionsmanager.php');

$context = Context::getContext ();

$operation = Tools::getValue ( 'operation' );

switch ($operation) {
	case 'check_frequencies' :
		$duration = Tools::getValue ( 'duration' );
		$frequencies = SMSchema::getAllowedFrequencies ( $duration );
		
		if ($duration == '')
			$frequencies = SMSchema::getAllowedFrequencies ( 'unlimited' );
		
		$html = '';
		$sm = new SubscriptionsManager();
		$context = context::getContext();
		
		
		foreach ( $frequencies as $f ) {
			$html .= '<option value="' . $f . '">' . $f . ' ' . ($context->language->iso_code == 'fr' ? 'mois' : 'months') .'</option>';
		}
		echo $html;
		break;
	
	case 'list_echeances' :
		require_once (dirname ( __FILE__ ) . '/models/SMSAutomaticBilling.php');
		$echeances = SMSAutomaticBilling::getList ( $_GET );
		
		// ABonnement courant
		$subscription = new SMSubscription ( ( int ) Tools::getValue('id_subscription'));
		
		if ($subscription->stock_decrementation == 1 || $subscription->duration == 0) {
			
			$echp = array ();
			foreach ( $echeances as $echeance ) {
				
				if ($subscription->getProductPrice() != ( int ) $echeance ['amount'])
					$echp [$echeance ['billing_date']] ['is_discount'] = 1;
				
				$echp [$echeance ['billing_date']] ['state'] = $echeance ['state'];
				$echp [$echeance ['billing_date']] ['id_subscription'] = $echeance ['id_subscription'];
				$echp [$echeance ['billing_date']] ['id_order'] = $echeance ['id_order'];
				$echp [$echeance ['billing_date']] ['price'] = $echeance ['amount'];
				$echp [$echeance ['billing_date']] ['payment_message'] = $echeance ['payment_message'];
			
			}
			
			$context->smarty->assign ( 'echeances_programmed', $echp );
		} else {
			// Echeances programmées de l'abonnement
			$echeances_programmed = $subscription->getEcheances ();
			
			foreach ( $echeances_programmed as $billing_date => &$echp ) {
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
			
			$context->smarty->assign ( 'echeances_programmed', $echeances_programmed );
		}
		
		// Construit un tableau des échéances
		$context->smarty->assign ( 'echeances', $echeances );
		$html = $context->smarty->fetch ( dirname ( __FILE__ ) . '/views/templates/admin/loop_echeances.tpl' );
		echo $html;
		
		break;
	
	case 'filter_subscriptions' :
		
		Configuration::updateValue('SM_PAGE', Tools::getValue('page'));
		
		list ( $paginator, $subscriptionsList ) = SMTools::generateSubscriptionPaginator ();
		
		foreach ( $subscriptionsList as &$subscription ) {
			$sub = new SMSubscription ( $subscription ['id_subscription'] );
			$subscription ['name'] = SMSchema::getProductWithAttributes ( $subscription ['id_product_attribute'] );
			$subscription ['can_stop'] = $sub->customerCanStop ();
			if ($subscription ['can_stop'])
				$subscription ['date_stop'] = $sub->getDateStop ();
			$subscription ['not_renew'] = $sub->customerCanNotRenew ();
		}
		
		$request_uri = $context->link->getAdminLink ( 'AdminModules' ) . '&configure=subscriptionsmanager&module_name=subscriptionsmanager';
		
		$context->smarty->assign ( 'subscriptionsListCount', count ( $subscriptionsList ) );
		$context->smarty->assign ( 'subscriptionsList', $subscriptionsList );
		$context->smarty->assign ( 'module_dir', _MODULE_DIR_ . 'subscriptionsmanager/' );
		$context->smarty->assign ( 'request_uri', $request_uri );
		$html = $context->smarty->fetch ( dirname ( __FILE__ ) . '/views/templates/admin/loop_subscriptions.tpl' );
		
		echo Tools::jsonEncode( array ('html' => $html, 'paginator' => $paginator ) );
		break;
	
	default :
		;
		break;
}

