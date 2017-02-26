<?php

/**
 * Encapsule un �tat de facturation automatique en base de donn�es
 * */
class SMSAutomaticBilling extends ObjectModelCore {
	
	/**
	 * ID de l'abonnement
	 */
	public $id_subscription;
	
	/**
	 * ID de la commande assignée à l'abonnement
	 */
	public $id_order;
	
	/**
	 * Date de la facturation
	 */
	public $billing_date;
	
	/**
	 * Etat de la facturation
	 * -1 => non payé, 0 => mois offert, 1 => payé
	 */
	public $state = 1;
	
	/**
	 * Montant de la facturation
	 */
	public $amount = 0;
	
	/**
	 * Message de retour lié au paiement
	 */
	public $payment_message = NULL;
	
	/**
	 * Statut non payé
	 */
	public static $STATE_NOT_PAID = - 1;
	
	/**
	 * Statut MOIS offert
	 */
	public static $STATE_OFFER = 0;
	
	/**
	 * Statut payé
	 */
	public static $STATE_PAID = 1;
	
	public static $definition = array ('table' => 'SM_automatic_billing', 'primary' => 'id_automatic_billing', 'multilang' => false, 'fields' => array ('id_subscription' => array ('type' => self::TYPE_INT, 'required' => true ), 'id_order' => array ('type' => self::TYPE_INT, 'required' => true ), 'billing_date' => array ('type' => self::TYPE_DATE, 'required' => true ), 'state' => array ('type' => self::TYPE_INT ), 'amount' => array ('type' => self::TYPE_FLOAT ), 'payment_message' => array ('type' => self::TYPE_STRING ) ) );
	
	public function __construct($id_automatic_billing = null) {
		parent::__construct ( $id_automatic_billing );
	}
	
	/**
	 * Renvoie la liste de tous les facturation automatique
	 */
	public static function getList($params = array()) {
		
		$sql = new DbQuery ();
		$sql->select ( '*' );
		$sql->from ( self::$definition ['table'], 'sab' );
		
		if (isset ( $params ['id_subscription'] )) {
			$sql->where ( 'sab.id_subscription = ' . ( int ) $params ['id_subscription'] );
			return Db::getInstance ()->ExecuteS ( $sql );
		}
		
		if (isset ( $params ['id_order'] )) {
			$sql->where ( 'sab.id_order = ' . ( int ) $params ['id_order'] );
			return Db::getInstance ()->ExecuteS ( $sql );
		}
		return Db::getInstance ()->executeS ( $sql );
	}
	
	/**
	 * Ajout d'un log lors d'un nouvel abonnement ou d'un prélèvement
	 */
	static public function addAutomaticBilling(SMSubscription $sub, $id_order) {
		
		$order = new Order ( $id_order );
		
		$automaticBilling = new self ();
		
		$automaticBilling->billing_date = $sub->date_check;
		
		$automaticBilling->id_subscription = $sub->id;
		$automaticBilling->id_order = $order->id;
		$automaticBilling->state = ($sub->status == 1 ? 1 : 0);
		$automaticBilling->amount = $order->total_paid_tax_incl;
		$automaticBilling->payment_message = NULL;
		$automaticBilling->save ();
	}
	
	/**
	 * Changement du status d'un Automatic Billing
	 */
	static public function changeAutomaticBillingState(SMSubscription $sub, $id_order) {
		
		$sab = self::getList(array('id_subscription' => $sub->id, 'id_order' => $id_order));
			
		$automaticBilling = new self ($sab[0]['id_automatic_billing']);
		
		$automaticBilling->state = ($sub->status == 1 ? 1 : 0);
		$automaticBilling->save ();
	}
	

}