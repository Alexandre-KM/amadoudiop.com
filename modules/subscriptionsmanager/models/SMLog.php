<?php

/**
 * Log les actions faites par le module de gestion d'abonnement
 */
class SMLog extends ObjectModelCore {

    /**
     * ID du log
     * @var integer
     */
    public $id_log;

    /**
     * Id de l'employé qui a fait l'action
     * @var integer
     */
    public $log_id_employee;

    /**
     * Id du client qui a fait l'action
     * @var bool 
     */
    public $log_id_customer;

    /**
     * Type d'action
     * @var type 
     */
    public $log_key;

    /**
     * Stockage de l'objet concerné
     * @var type 
     */
    public $log_value;

    /**
     * Date de l'action
     * @var type 
     */
    public $log_date;

    /**
     * ID de l'entité concernée
     * @var type 
     */
    public $log_id_entity;

    /**
     * Type de l'entité concernée
     * @var type 
     */
    public $log_type_entity;

    
    public static $definition = array('table' => 'SM_log', 'primary' => 'id_log', 'multilang' => false,
	'fields' => array(
	    'log_id_employee' => array('type' => self::TYPE_INT),
	    'log_id_customer' => array('type' => self::TYPE_INT),
	    'log_key' => array('type' => self::TYPE_STRING),
	    'log_value' => array('type' => self::TYPE_STRING),
	    'log_date' => array('type' => self::TYPE_DATE, 'required' => true),
	    'log_id_entity' => array('type' => self::TYPE_INT),
	    'log_type_entity' => array('type' => self::TYPE_STRING)));

    public function __construct($id_log = NULL) {
	parent::__construct($id_log);

	$this->log_date = date('Y-m-d h:i:s'); // La date du log = aujourd'hui
    }

    /**
     * Fonction d'ajout d'un log en BDD
     */
    static public function addLog($key, $object = null, $id_employee = NULL, $id_customer = NULL) {

	$log = new self(); // Nouvelle instance de l'objet SMLog

	$log->log_id_employee = NULL; // Init
	$log->log_id_customer = NULL; // Init
	
	$log->log_key = $key;

	if($object != NULL && !is_array($object)){ // Si c'est un objet
	    $log->log_value = serialize($object); // On serialise l'objet
	    $log->log_type_entity = $log->getEntity($key); // récupération du type de l'entité
	    $log->log_id_entity = $object->id; // Récupération de l'id de l'entité
	}
	elseif(is_array($object)) // Si c'est un tableau
	    $log->log_value = serialize($object); // On serialise le tableau
	
	if($id_employee != NULL) // Si on a un id employé
	    $log->log_id_employee = $id_employee;
	
	if($id_customer != NULL) // Si on a un id client
	    $log->log_id_customer = $id_customer;
		

	$log->save(); // Sauvegarde
    }

    /**
     * récupération de l'entité
     */
    public function getEntity($key) {
	list($action, $entity) = explode("_", $key);
		
	return $entity;
    }

    /**
     * Récupération de l'action effectuée
     */
    static public function getAction($key) {
	list($action, $entity) = explode("_", $key);

	return $action;
    }

    /**
     * Récupération de la liste des logs
     */
    static public function getList() {
	$sql = new DbQuery();
	$sql->select('*');
	$sql->from('SM_log');

	return array_reverse(Db::getInstance()->ExecuteS($sql));
    }
    
    /**
     * Récupération et formatage de la liste des logs
     */
    static public function getListFormatted() {
	
	$logs = SMlog::getList();

	//Pour chaque log
	foreach($logs as &$log){
	    
	    
	    if(!empty($log['log_id_employee']) && $log['log_id_employee'] != 0){ // Si c'est un employé
		$employee = new Employee($log['log_id_employee']);
		$log['author'] = $employee->firstname.' '.$employee->lastname;
	    }
	    elseif(!empty($log['log_id_customer']) && $log['log_id_customer'] != 0){ // Si c'est un client
		$employee = new Customer($log['log_id_customer']);
		$log['author'] = $employee->firstname.' '.$employee->lastname;		
	    }
	    elseif($log['log_id_employee'] == 0) // Si c'est le CRON
		$log['author'] = "CRON";
	    
	    $log['action'] = SMLog::getAction($log['log_key']); // Récupération de l'action
		
	 
	    
	}	
	
	return $logs;
    }

    
    
}




