<?php

/**
 * Encapsule un abonnement en base de données
 */
class SMSubscription extends ObjectModel {
	
	/**
	 * ID du client
	 * @var integer
	 */
	public $id_customer;
	
	/**
	 * ID de la commande initiale
	 * @var integer 
	 */
	public $id_order;
	
	/**
	 * ID du mode de paiement
	 * @var integer 
	 */
	public $id_payment_module;
	
	/**
	 * Id du schéma d'abonnement associé à l'abonnement courant
	 * @var integer 
	 */
	public $id_schema;
	
	/**
	 * Date de fin de l'abonnement
	 * @var date 
	 */
	public $date_end = NULL;
	
	/**
	 * Date de prochain passage du CRON
	 * @var type 
	 */
	public $date_check;
	
	/**
	 * Date de démmarrage de l'abonnement
	 * @var date 
	 */
	public $date_start;
	
	/**
	 * Durée de l'abonnement
	 * @var integer
	 */
	public $duration = NULL;
	
	/**
	 * Fréquence de passage du CRON
	 * @var type 
	 */
	public $frequency = 1;
	
	/**
	 * Indique si l'abonnement est renouvelable
	 * @var bool 
	 */
	public $is_renewable = FALSE;
	
	/**
	 * Indique si l'abonnement est en paiement en 1 fois
	 * @var type 
	 */
	public $one_shot = FALSE;
	
	/**
	 * Indique si l'abonné a initié une résiliation
	 * @var type 
	 */
	public $has_stop = 0;
	
	/**
	 * Groupe par défaut de l'abonné durant l'abonnement
	 * @var integer
	 */
	public $id_group_linked = NULL;
	
	/**
	 * Groupe par défaut du client une fois l'abonnement termniné
	 * @var int 
	 */
	public $id_group_back = 3;
	
	/**
	 * Indique le mode de réduction appliqué à l'abonnement      
	 * 'reduction_offer' ou 'months_offer'
	 * @var string 
	 */
	public $discount_mode = NULL;
	
	/**
	 * Montant de la réduction appliquée si $discount_mode = 'reduction_offer'
	 * @var float 
	 */
	public $discount_value = 0;
	
	/**
	 * Indique si la réduction s'applique en H.T, T.T.C ou %
	 * 0 => H.T, 1 => T.T.C , 2 => %
	 * @var integer
	 */
	public $discount_type = 0;
	
	/**
	 * Nombre de mois remisés
	 * @var integer
	 */
	public $discount_nb_months = 0;
	
	/**
	 * Indique si le stock doit être décrémenté
	 * S'applique uniquement si l'abonnement est en one_shot
	 * @var type 
	 */
	public $stock_decrementation = FALSE;
	
	/**
	 * Durée du préavis ne permettant à l'abonné de résilier et/ou ne pas
	 * renouveller son abonnement en cours
	 * @var integer
	 */
	public $advance_notice_duration = 0;
	
	/**
	 * Durée d'engagement de l'abonnement ne permettant à l'abonné de résilier et/ou ne pas
	 * renouveller son abonnement en cours
	 * @var type 
	 */
	public $engagement_duration = 0;
	
	/**
	 * Indique si le mail de notification de fin d'abonnement
	 * a été envoyé à l'abonné
	 * @var type 
	 */
	public $notification_sent = FALSE;
	
	/**
	 * Indique le status de l'abonnement
	 * 0 => désactivé
	 * 1 => activé
	 * 2 => désactivé
	 * @var type 
	 */
	public $status = 0;
	
	/**
	 * Date de création
	 */
	public $date_creation;
	
	/**
	 * Id du produit
	 */
	private $id_product;
	
	/**
	 * ID de la déclinaison associée
	 */
	private $id_product_attribute;
	
	/**
	 * Schéma associé
	 * @var SMSchema
	 */
	private $schema = NULL;
	
	/**
	 * Statut résilié
	 * l'abonnement est résilié. Il n'est plus facturable par la CRON TAB 
	 * @var unknown_type
	 */
	static $STATUS_CANCELLED = - 2;
	
	/**
	 * Statut en pause
	 * L'abonnment est en pause pour raison propre au commercant.
	 * @var integer
	 */
	static $STATUS_PAUSED = - 1;
	
	/**
	 * Statut inactif
	 * L'abonnement est en attente de validatation de paiement 
	 * ou d'une action ddu commercant (passage de la commande à payée)
	 * @var integer
	 */
	static $STATUS_DESACTIVE = 0;
	
	/**
	 * Statut actif
	 * L'abonnement est en cours de fonctionnement
	 * @var integer
	 */
	static $STATUS_ACTIVE = 1;
	
	/**
	 * Statut terminé
	 * L'abonnement est termniné. Il n'est plus facturable par la CRON TAB
	 * @var integer
	 */
	static $STATUS_TERMINATED = 2;
	static $DISCOUNT_TYPE_WITHOUT_VAT = 0;
	static $DISCOUNT_TYPE_WITH_VAT = 1;
	static $DISCOUNT_TYPE_PERCENT = 2;
	public static $definition = array ('table' => 'SM_subscription', 'primary' => 'id_subscription', 'multilang' => false, 'fields' => array ('id_customer' => array ('type' => self::TYPE_INT, 'required' => true ), 'id_order' => array ('type' => self::TYPE_INT, 'required' => true ), 'id_payment_module' => array ('type' => self::TYPE_INT, 'required' => true ), 'id_schema' => array ('type' => self::TYPE_INT, 'required' => true ), 'date_start' => array ('type' => self::TYPE_DATE, 'required' => true ), 'date_check' => array ('type' => self::TYPE_DATE, 'required' => true ), 'date_end' => array ('type' => self::TYPE_DATE ), 'duration' => array ('type' => self::TYPE_INT ), 'frequency' => array ('type' => self::TYPE_INT ), 'is_renewable' => array ('type' => self::TYPE_BOOL ), 'one_shot' => array ('type' => self::TYPE_BOOL ), 'has_stop' => array ('type' => self::TYPE_BOOL ), 'id_group_linked' => array ('type' => self::TYPE_INT ), 'id_group_back' => array ('type' => self::TYPE_INT ), 'discount_mode' => array ('type' => self::TYPE_STRING ), 'discount_value' => array ('type' => self::TYPE_FLOAT ), 'discount_type' => array ('type' => self::TYPE_INT ), 'discount_nb_months' => array ('type' => self::TYPE_INT ), 'stock_decrementation' => array ('type' => self::TYPE_BOOL ), 'advance_notice_duration' => array ('type' => self::TYPE_INT ), 'engagement_duration' => array ('type' => self::TYPE_INT ), 'notification_sent' => array ('type' => self::TYPE_BOOL ), 'status' => array ('type' => self::TYPE_INT ), 'date_creation' => array ('type' => self::TYPE_DATE ) ) );
	
	/**
	 * Constructeur     	
	 */
	public function __construct($id_subscription = null) {
		parent::__construct ( $id_subscription );
		
		require_once (dirname ( __FILE__ ) . '/SMSchema.php'); // On insère le fichier des schémas
		

		$this->schema = new SMSchema ( $this->id_schema ); // Nouvelle instance du schéma
		

		$this->id_group_back = ( int ) Configuration::get ( 'PS_CUSTOMER_GROUP' ); // Par défaut, le groupe de retour vaut le groupe par défaut de la boutique
		

		$order = new Order ( $this->id_order ); // Instance de la commande associée
		$cart = new Cart ( ( int ) $order->id_cart ); // Instance du panier associée
		$products = $cart->getProducts (); // Récupération des produits du panier
		
		if(!empty($products)){
		    $this->id_product = $products [0] ['id_product']; // Stockage de l'id du produit
		    $this->id_product_attribute = $products [0] ['id_product_attribute']; // Stockage de l'id de la déclinaison
		}
	}
	
	/**
	 * Renvoie le schéma d'un abonnement
	 */
	public function getSchema() {
		return $this->schema;
	}
	
	/**
	 * Récupération de l'objet client associé à l'abonnement
	 */
	public function getCustomer() {
		return new Customer ( $this->id_customer );
	}
	
	/**
	 * Vérification du lien entre le client et l'abonnement
	 */
	public static function isOwnedByCustomer($id_customer, $id_subscription) {
		
		$sql = new DbQuery ();
		$sql->select ( 'id_subscription, id_customer' );
		$sql->from ( 'SM_subscription', 'sub' );
		$sql->where ( 'sub.id_subscription = ' . ( int ) $id_subscription );
		$sql->where ( 'sub.id_customer = ' . ( int ) $id_customer );
		Db::getInstance ()->getRow ( $sql );
		return Db::getInstance ()->numRows () == 1;
	}
	
	/**
	 * Initialise les champs du select
	 */
	private static function initSelects() {
		$sql = new DbQuery ();
		$sql->select ( 'sch.*, sub.*, c.*, sch.id_product_attribute' );
		$sql->from ( 'SM_subscription', 'sub' );
		$sql->leftJoin ( 'SM_schema', 'sch', 'sub.id_schema = sch.id_schema' );
		$sql->leftJoin ( 'orders', 'o', 'o.id_order = sub.id_order' );
		$sql->leftJoin ( 'customer', 'c', 'c.id_customer = o.id_customer' );
		$sql->leftJoin ( 'product_attribute', 'pa', 'pa.id_product_attribute = sch.id_product_attribute' );
		$sql->leftJoin ( 'product_attribute_combination', 'pac', 'pac.id_product_attribute = sch.id_product_attribute' );
		$sql->leftJoin ( 'attribute_lang', 'al', 'al.id_attribute = pac.id_attribute' );
		$sql->leftJoin ( 'product_lang', 'pl', 'pl.id_product = pa.id_product' );
		return $sql;
	}
	
	/**
	 * Renvoie la liste de tous les schémas
	 */
	public static function getList($params = array()) {
		
		$sql = self::initSelects ();
		
		if (! isset ( $params ['ajax'] )) {
			
			if (isset ( $params ['customer_active'] )) {
				$sql->where ( 'c.active = ' . ( int ) $params ['customer_active'] );
			}
			
			if (isset ( $params ['id_subscription'] )) {
				$sql->where ( 'sub.id_subscription = ' . ( int ) $params ['id_subscription'] );
			}
			
			if (isset ( $params ['date_check'] )) {
				$sql->where ( 'sub.date_check <= "' . $params ['date_check'] . '"' );
			}
			
			if (isset ( $params ['status'] )) {
				$sql->where ( 'sub.status = ' . ( int ) $params ['status'] );
			}
			
			if (isset ( $params ['id_schema'] )) {
				$sql->where ( 'sub.id_schema = ' . ( int ) $params ['id_schema'] );
			}
			
			if (isset ( $params ['id_order'] )) {
				$sql->where ( 'sub.id_order = ' . ( int ) $params ['id_order'] );
				return ( object ) Db::getInstance ()->getRow ( $sql );
			}
			
			if (isset ( $params ['id_customer'] )) {
				$sql->where ( 'c.id_customer = ' . ( int ) $params ['id_customer'] );
				$sql = $sql->build ();
				$sql .= ' GROUP BY sub.id_subscription ';
				
				if (isset ( $params ['return_array'] ))
				    return Db::getInstance ()->ExecuteS ( $sql );
				else
				    return ( object ) Db::getInstance ()->ExecuteS ( $sql );
			}
		}
		
		/* Recherche AJAX */
		if (isset ( $params ['ajax'] ) && $params ['ajax'] == 1) {
			$sql = $sql->build ();
			if (isset ( $params ['customer_name'] )) {
				$criteria = explode ( " ", $params ['customer_name'] );
				$sql .= ' WHERE 1=1 AND (';
				foreach ( $criteria as $index => $criterium ) {
					
					$sql .= "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $criterium . "%'";
					
					if ($index < count ( $criteria ) - 1)
						$sql .= ' OR ';
				}
				$sql .= ' )';
			}
			
			if (isset ( $params ['start_date'] ) && $params ['start_date'] != '') {
				$sql .= " AND sub.date_start >= '" . $params ['start_date'] . "'";
			}
			
			if (isset ( $params ['end_date'] ) && $params ['end_date'] != '') {
				$sql .= " AND sub.date_end <= '" . $params ['end_date'] . "'";
			}
			
			if (isset ( $params ['duration'] ) && $params ['duration'] != '') {
				$sql .= " AND sub.duration =  " . $params ['duration'];
			}
			
		
			
			$sql .= ' GROUP BY sub.id_subscription ';
			
			
		if (isset ( $params ['order_by_date'] ) && $params ['order_by_date'] == TRUE) {
				$sql .= ' ORDER BY date_start DESC ';
			}
			
			if (isset ( $params ['per_page'] ) && isset ( $params ['page'] )) {
				$sql .= ' LIMIT ' . $params ['page'] . ' , ' . $params ['per_page'];
			}
			
			
			
			return Db::getInstance ()->ExecuteS ( $sql );
		}
		
		if (isset ( $params ['order_by_date'] ) && $params ['order_by_date'] == TRUE) {
			$sql .= ' ORDER BY date_start DESC';
		}
		
		$sql .= ' GROUP BY sub.id_subscription ';
		
		if (isset ( $params ['per_page'] ) && isset ( $params ['page'] )) {
			$sql .= ' LIMIT ' . $params ['page'] . ' , ' . $params ['per_page'];
		}
		
		/* Fin recherche AJAX */
		return Db::getInstance ()->ExecuteS ( $sql );
	}
	
	public static function countList($params) {
		$sql = self::initSelects ();
		$sql = new DbQuery ();
		$sql->select ( 'COUNT(*) as nb_subscription' );
		$sql->from ( 'SM_subscription', 'sub' );
		$sql->leftJoin ( 'orders', 'o', 'o.id_order = sub.id_order' );
		$sql->leftJoin ( 'customer', 'c', 'c.id_customer = o.id_customer' );
		
		if (isset ( $params ['customer_name'] )) {
			$criteria = explode ( " ", $params ['customer_name'] );
			$sql .= 'WHERE 1=1 AND (';
			foreach ( $criteria as $index => $criterium ) {
				$sql .= " CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $criterium . "%'";
				if ($index < count ( $criteria ) - 1)
					$sql .= ' OR ';
			}
			$sql .= ' )';
		}
		
		if (isset ( $params ['start_date'] ) && $params ['start_date'] != '') {
			$sql .= " AND sub.date_start >= '" . $params ['start_date'] . "'";
		}
		
		if (isset ( $params ['end_date'] ) && $params ['end_date'] != '') {
			$sql .= " AND sub.date_end >= '" . $params ['end_date'] . "'";
		}
		
		if (isset ( $params ['duration'] ) && $params ['duration'] != '') {
			$sql .= " AND sub.duration =  " . $params ['duration'];
		}
		
		$row = Db::getInstance ()->getRow ( $sql );
		return $row ['nb_subscription'];
	}
	
	/**
	 * Renvoi une date augmentée d'un nombre de mois donné
	 */
	public function getNextDateCheck() {
		return SMTools::getNextDate ( $this->date_start, $this->date_check, $this->frequency );
	}
	
	/**
	 * Retourne le prix normal sans réduction d'un schéma d'abonnement
	 */
	public function getNormalPrice($with_vat = TRUE) {
		return Product::getPriceStatic ( $this->id_product, $with_vat, $this->id_product_attribute );
	}
	
	/**
	 * Récupération des écheances d'un abonnement
	 */
	public function getEcheances() {
		
		$echeances = array (); // Init
			
			$start = $this->date_start; // Init à la date de départ
			

			$i = 1;
			// Tant que la date start est inférieure ou égale à la date de fin
			while ( $start < $this->date_end ) {
				
				$is_discount = FALSE; // Init
				

				if ($i <= $this->discount_nb_months && $this->discount_nb_months != 0) { // Si $i est compris entre 0 et discount_nb_months
					$is_discount = TRUE; // On passe la variable à Vrai
				}
				
				// Remplissage du tableau
				$echeances [$start] = array ('price' => $is_discount == TRUE ? $this->getReccuringPrice () : $this->getNormalPrice (), 'is_discount' => $is_discount );
				
				$start = SMTools::getNextDate ( $this->date_start, $start, $this->frequency ); // On avance à la date_check suivante
				$i ++; // Incrémentation
			}
		
		return $echeances;
	}
	
	/**
	 * Renvoie T.T.C le prix d'un schéma en tenant d'une eventuelle réduction
	 */
	public function getReccuringPrice() {
		
		// Prix de l'abonnement sans la r�duction
		//$price = CombinationCore::getPrice ( $this->id_product_attribute );
		$price = Product::getPriceStatic ( $this->id_product, TRUE, $this->id_product_attribute );
		
		$product = new Product($this->id_product);
		$taxe = $product->getTaxesRate();
		// Applique un eventuelle r�duction
		switch ($this->discount_mode) {
			case SMSchema::$DISCOUNT_MONTHS_OFFER :
				if($this->one_shot == 1){
				    $price = $price - (($price * $this->discount_nb_months) / $this->duration);
				}else{
				    $price = 0;				    
				}
				
				break;
			
			case SMSchema::$DISCOUNT_OFFER :
				
				switch ($this->discount_type) {
					case SMSubscription::$DISCOUNT_TYPE_WITHOUT_VAT :
					    
					    if($this->one_shot == 1){	
						$price = Product::getPriceStatic ( $this->id_product, FALSE, $this->id_product_attribute ) - ($this->discount_value * ( int ) $this->discount_nb_months);
						$price = $price * (1 + ($taxe / 100));
					    }
					    else{
						$price = Product::getPriceStatic ( $this->id_product, FALSE, $this->id_product_attribute ) - $this->discount_value;
						$price = $price * (1 + ($taxe / 100));
					    }
					    
						break;
					
					case SMSubscription::$DISCOUNT_TYPE_WITH_VAT :
						
						if($this->one_shot == 1)
						    $price -= ( int ) $this->discount_value * ( int ) $this->discount_nb_months;					
						else
						    $price -= $this->discount_value;
						break;
					
					case SMSubscription::$DISCOUNT_TYPE_PERCENT :
						if($this->one_shot == 1)
						    $price -= (($price / ( int ) $this->duration) * (( int ) $this->discount_value / 100)) * ( int ) $this->discount_nb_months;						
						else
						    $price *= 1 - $this->discount_value / 100;
						break;
				}
				
				break;
			
			default :
				break;
		}
		
		return $price;
	}
	
	/**
	 * Retourne les informations de réduction pour la date_check
	 */
	public function getEcheanceDetail() {
		$echeances = $this->getEcheances ();
		return isset ( $echeances [$this->date_check] ) ? $echeances [$this->date_check] : FALSE;
	}
	
	/**
	 * Vérifie si l'abonnement est toujours en réduction par rapport à sa date_check
	 */
	public function isStillDiscount() {
		
		if ($this->duration == 0 && $this->isDiscountForUnlimited ()) // Si l'abonnement est illimité et qu'il est toujours en période de réduction
			return true; // On retourne Vrai
		

		$echeance = $this->getEcheanceDetail (); // Récupération du détail de l'échéance du date_check
		

		if (! empty ( $echeance ) && $echeance ['is_discount'] == 1) // Si la varaible n'est pas vide et que le champ is_discount vaut 1
			return true; // On retourne Vrai
		

		return false; // On retourne Faux
	}
	
	/**
	 * Vérifie si le client peut resilier son abonnement en fonction de la date actuelle
	 */
	public function customerCanStop() {
		
		$nb = 0;
		$today = date ( 'Y-m-d' ); // Init à aujourd'hui
		// Si l'abonnement est en paiement direct, ou si l'abonnement va etre résilier ou si l'abonnement n'est pas actif
		if ($this->one_shot == 1 || $this->has_stop == 1 || $this->status != SMSubscription::$STATUS_ACTIVE)
			return false; // On retourne Faux
		

		// Tant que la variable valant aujourd'hui est supérieure ou égale à la date de départ de l'abonnement
		while ( $today >= $this->date_start ) {
			$today = SMTools::getPreviousDate ( $this->date_start, $today, 1 ); // On recul d'une fréquence en arrière
			$nb ++; // On incrémente
		}
		
		if ($nb > $this->engagement_duration) { // Si le nombre est supérieur à la durée d'engagement
			if ($this->advance_notice_duration == 0) // S'il n'y a pas de préavis
				return true; // On retourne Vrai
			

			$nb = 0;
			$today = date ( 'Y-m-d' ); // Init à aujourd'hui
			// Tant que la variable valant aujourd'hui est inférieure ou égale à la date de fin de l'abonnement
			while ( $today <= $this->date_end ) {
				$today = SMTools::getNextDate ( date ( 'Y-m-d' ), $today, 1 ); // On avance d'une fréquence en avant
				$nb ++; // On incrémente
			}
			
			if ($nb > $this->advance_notice_duration) // Si le nombre est supérieur à la durée du préavis
				return true; // On retourne Vrai
		}
		
		return false; // on retourne Faux
	}
	
	/**
	 * Vérifie si le client peut resilier son abonnement en fonction de la date actuelle
	 */
	public function customerCanNotRenew() {
		
		$today = date ( 'Y-m-d' ); // Init à aujourd'hui
		// Si l'abonnement est illimité, est en paiement direct, va etre résilier, n'est pas renouvelable ou si l'abonnement n'est pas actif
		if ($this->duration == 0 || $this->one_shot == 1 || $this->has_stop == 1 || $this->is_renewable == 0 || $this->status != SMSubscription::$STATUS_ACTIVE)
			return false; // On retourne Faux
		

		$nb = 0;
		
		// Tant que la variable valant aujourd'hui est supérieure ou égale à la date de départ de l'abonnement
		while ( $today >= $this->date_start ) {
			$today = SMTools::getPreviousDate ( $this->date_start, $today, 1 ); // On recul d'une fréquence en arrière
			$nb ++; // On incrémente
		}
		
		if ($nb > $this->engagement_duration) { // Si le nombre est supérieur à la durée d'engagement
			if ($this->advance_notice_duration == 0) // S'il n'y a pas de préavis
				return true; // On retourne Vrai
			

			$nb = 0;
			$today = date ( 'Y-m-d' ); // Init à aujourd'hui
			// Tant que la variable valant aujourd'hui est inférieure ou égale à la date de fin de l'abonnement
			while ( $today <= $this->date_end ) {
				$today = SMTools::getNextDate ( date ( 'Y-m-d' ), $today, 1 ); // On avance d'une fréquence en avant
				$nb ++; // On incrémente
			}
			
			if ($nb > $this->advance_notice_duration) // Si le nombre est supérieur à la durée du préavis
				return true; // On retourne Vrai
		}
		
		return false; // on retourne Faux
	}
	
	/**
	 * Calcul de la date de fin de l'abonnement
	 */
	public function getDateEnd() {
		
		return SMTools::getNextDate ( $this->date_start, $this->date_start, $this->duration );
	}
	
	/**
	 * Calcul de la date d'arret
	 */
	public function getDateStop() {
		
		$NB_DAYS_STOP = configuration::get ( 'NB_DAYS_STOP' ); // récupération de l'écart maximum pour l'arret d'un abonnement (en nombre de jours)
		

		$sql = "SELECT DATE_ADD('" . date ( 'Y-m-d' ) . "', INTERVAL + " . $NB_DAYS_STOP . " DAY) AS next_date"; // Ajout de ce nombre de jours à aujourd'hui
		

		$result = Db::getInstance ()->getRow ( $sql );
		
		if ($result ['next_date'] <= $this->date_check) // Si la nouvelle date est inférieure ou égale à la prochaine date_check
			return $this->date_check; // On retourne la date du prochain prélèvement
		else // Sinon
			return $this->getNextDateCheck (); // On retourne la date check suivante
	}
	
	/**
	 * Vérification de l'éxistance d'un abonnement
	 */
	public static function subscriptionExists($id_subscription) {
		
		$sub = SMSubscription::getList ( array ('id_subscription' => $id_subscription ) ); // récupération de la liste des abonnements dont l'id vaut le paramètre d'entré
		

		if (empty ( $sub )) // Si le tableau est vide
			return false; // On retourne Faux
		

		return true; // Sinon on retourne Vrai
	}
	
	/**
	 * Calcul de la nouvelle durée de l'abonnement après une résiliation
	 */
	public function calculNewDuration() {
		
		$i = 0; // Init
		$date_end = $this->date_end; // Récupération de la date de fin
		

		while ( $date_end > $this->date_start ) { // Tant que la date de fin est supérieure à la date de départ
			$date_end = SMTools::getPreviousDate ( $this->date_start, $date_end, $this->frequency ); // On recule d'une fréquence
			$i = $i + $this->frequency; // On incrémente
		}
		return $i; // On retourne la nouvelle durée
	}
	
	/**
	 * Fonction d'arret d'un abonnement
	 */
	public function stopSubscription() {
		
		$this->date_end = $this->getDateStop (); // Récupération de la date de fin possible
		$this->advance_notice_duration = FALSE; // Arret du préavis
		$this->has_stop = TRUE; // Indication de la résiliation à Vrai
		$this->is_renewable = FALSE; // Blockage du renouvellement
		$this->duration = $this->calculNewDuration (); // Calcul de la nouvelle durée d'abonnement
		$this->update (); // Mise à jour
	}
	
	/**
	 * Fonction d'arret d'un abonnement via le module Paypal Abonnement
	 */
	public function paypalStopSubscription() {
		
		$this->date_end = date('Y-m-d'); // Récupération de la date de fin possible
		$this->advance_notice_duration = FALSE; // Arret du préavis
		$this->has_stop = TRUE; // Indication de la résiliation à Vrai
		$this->is_renewable = FALSE; // Blockage du renouvellement
		$this->status = SMSubscription::$STATUS_CANCELLED;
		$this->update (); // Mise à jour
	}
	
	/**
	 * Récuperation le nombre d'abonnement actifs en fonction de l'id du schema
	 */
	static public function getNbSubscriptionsBySchema($id_schema) {
		
		$row = Db::getInstance ()->getRow ( "SELECT count(*) as nb FROM `" . _DB_PREFIX_ . "SM_subscription` WHERE status = " . SMSubscription::$STATUS_ACTIVE . " AND id_schema = '" . ( int ) $id_schema . "'" );
		
		return $row ['nb'];
	}
	
	/**
	 * Récupération de la date de la fin la plus reculée dans le temps en fonction de l'id du schema
	 */
	static public function getUnlockingDate($id_schema) {
		
		$date = NULL; // Init 
		

		foreach ( self::getList ( array ('id_schema' => $id_schema ) ) as $subscription ) { // Pour chaque abonnement dont l'id du schéma vaut le paramètre d'entré
			if ($date == NULL || $subscription ['date_end'] > $date) // Si la date vaut NULL ou si la date est inférieure à celle du schéma
				$date = $subscription ['date_end']; // On stock la nouvelle date de fin
		}
		
		return $date;
	}
	
	/**
	 * Si des abonnement sont en cours avec le produit passé en paramètre, on retourne Vrai
	 */
	static public function hasActiveSubscriptions($id_product) {
		
		foreach ( self::getList ( array ('status' => self::$STATUS_ACTIVE ) ) as $subscription ) {
			if ($id_product == SMTools::getProductID ( $subscription ['id_product_attribute'] ))
				return true;
		}
		
		return false;
	}
	
	/*
     * Fonction de vérification du CartRule si abonnement illimité
     */
	
	public function isDiscountForUnlimited() {
		
		if ($this->discount_mode == NULL) // S'il n'y a pas de réduction
			return false; // On retourne Faux
		

		$nb = 0;
		
		$date = $this->date_check; // On récupère la date du prochain prélèvement
		

		while ( $date > $this->date_start ) { // Tant que la date est supérieure à la date de départ
			$date = SMTools::getPreviousDate ( $this->date_start, $date, $this->frequency ); // On recul d'une date de prélèvement
			$nb ++; // On incrémente
		}
		
		if ($nb < $this->discount_nb_months) // Si le nombre est inférieur au nombre de mois de réduction
			return true; // On retourne Vrai
		

		return false; // On retourne Faux
	}
	
	/**
	 * Verifie si la date correspond a un envoi de notification
	 */
	public function checkNotification() {
		
		// Recupere le schema en cours
		$schema = new SMSchema ( $this->id_schema ); // Récupération du schéma
		

		if ($schema->notification_active == true && $this->notification_sent == false) { // Si la notification est active et qu'elle n'a pas encore été envoyée
			$date_mail_notification = date ( "Y-m-d", strtotime ( "-" . $schema->notification_time . " days", strtotime ( $this->date_end ) ) ); // On récupère la date d'envoi de la notification
			

			if ($date_mail_notification <= date ( 'Y-m-d' )) { // Si on a passé cette date
				if ($this->sendNotification () === TRUE) { // Envoi de la notification -> Si tout s'est bien passé
					$this->notification_sent = TRUE; // On indique que la notification a été envoyée
					$this->update (); // Mise à jour
				}
			}
		}
	}
	
	/**
	 * Envoi un email d'alerte à un client avant la fin de son abonnement
	 */
	public function sendNotification() {
		
		$context = Context::getContext (); // Récupération du Context
		$customer = $this->getCustomer (); // Récupération du client
		$schema = $this->getSchema (); // Récupération du schéma de l'abonnement
		

		$productAttributeName = SMSchema::getProductWithAttributes ( $schema->id_product_attribute ); // Récupération du nom du produit
		//Paramètrage du Mail + Envoi
		$templateVars = array ('{lastname}' => $customer->lastname, '{firstname}' => $customer->firstname, '{email}' => $customer->email, '{product_name}' => $productAttributeName, '{end_date}' => $this->date_end, '{message_important}' => $schema->notification_message );
		Mail::Send ( ( int ) $context->language->id, 'alert_email', Mail::l ( 'Fin abonnement proche' ), $templateVars, $customer->email, 'Abonnement', NULL, NULL, NULL, NULL, _PS_ROOT_DIR_ . _MODULE_DIR_ . 'subscriptionsmanager/mails/' );
		
		return TRUE;
	}
	
	/**
	 * Fonction de changement de groupe utilisateur à la fin d'un abonnement
	 */
	public function changeCustomerGroup() {
		
		$noChange = false;
		
		//Si le client n'a pas eu de groupe sp�cial pendant son abonnement, on quitte la fonction
		if ($this->id_group_linked == 0)
			return;
		
		//Pour chaque abonnement
		foreach ( $this->getList ( array ('status' => SMSubscription::$STATUS_ACTIVE, 'id_customer' => $this->id_customer ) ) as $subscription ) {
			
			//Si les id des groupes sont identiques
			if ($subscription ['id_group_linked'] == $this->id_group_linked)
				$noChange = true; //On passe la variable a false			   
		}
		
		if (! $noChange) {
			
			$customer = new Customer ( $this->id_customer );
			$customer->id_default_group = $this->id_group_back;
			$customer->addGroups ( array ($this->id_group_back ) );
			$customer->update ();
			
			Db::getInstance ()->Execute ( "DELETE FROM " . _DB_PREFIX_ . "customer_group WHERE id_customer='" . $this->id_customer . "' AND id_group='" . $this->id_group_linked . "';" );
		}
	}
	
	/**
	 * Fonction de duplication de l'abonnement courant
	 */
	public function duplicateSubscription() {
		
		$duplicateSub = $this->duplicateObject (); // Duplication de l'abonnement
		$duplicateSub->date_start = $this->date_end; // Nouvelle date de départ = Ancienne date de fin
		$duplicateSub->date_check = $this->date_end; // Nouvelle date de prélèvement = Ancienne date de fin
		$duplicateSub->date_end = $duplicateSub->getDateEnd (); // Calcul de la nouvelle date de fin
		$duplicateSub->date_creation = date ( 'Y-m-d' ); // Nouvelle date de création vaut aujourd'hui
		$duplicateSub->status = SMSubscription::$STATUS_ACTIVE; // Status en cours pour le nouvel abonnement
		$duplicateSub->discount_mode = NULL; // Annulation de la réduction à appliquer au début de l'abonnement (car renouvellement)
		$duplicateSub->discount_value = 0; // Idem
		$duplicateSub->discount_type = 0; // Idem
		$duplicateSub->discount_nb_months = 0; // Idem
		$duplicateSub->has_stop = 0; // réinitialisation de l'indication de résiliation
		$duplicateSub->notification_sent = 0; // Réinitialisation de l'indication d'envoi de notification
		$duplicateSub->update (); // Mise à jour
		

		return $duplicateSub;
	}
	
	/**
	 * Récupération du montant déjà payé pour l'abonnement
	 */
	public function getPaidAmount(){
	    
	    $paid = 0;	    
	   
	    foreach(SMSAutomaticBilling::getList(array('id_subscription' => $this->id)) as $automaticBilling){
		$paid += (int)$automaticBilling['amount'];
	    }
	    
	    return $paid;
	}
	
	/**
	 * Récupère le nombre de prélèvements déjà effectués 
	 */
	public function getNbLevies(){
	    
	    $nb = 0;	    
	   
	    foreach(SMSAutomaticBilling::getList(array('id_subscription' => $this->id)) as $automaticBilling){
		if(!empty($automaticBilling))
		    $nb ++;
	    }
	    
	    return $nb;
	}
	
	/**
	 * Récupère le nombre total de prélèvements à faire
	 */
	public function getTotalLevies(){
	    
	    if($this->one_shot == 1)
		return 1;
	    elseif($this->duration == 0)
		return 0;
	    else
		return ($this->duration / $this->frequency);
	}
	
	/**
	 * Récupère le prix du produit lié à l'abonnement
	 */
	public function getProductPrice($without_tax = false){
	    
	    $order = new OrderCore($this->id_order);
	    $products = $order->getProducts ();
	    $subscriptionProduct = array_shift ( $products );
	    
	    if($without_tax)
		return $subscriptionProduct['total_price_tax_excl'];
	    else
		return $subscriptionProduct['total_price_tax_incl'];
	}
	
	/**
	 * Récupère le prix total de l'abonnement
	 */
	public function getTotalAmount(){
	    
	    $price = $this->getProductPrice();
	    $total = 0;
	    $i = 0;
	    
	    while($i < $this->duration){
		
		if($i < ($this->discount_nb_months * $this->frequency) && $this->discount_mode == 'reduction_offer'){
		    
		    if($this->discount_type == self::$DISCOUNT_TYPE_WITHOUT_VAT){
			$product = new Product(SMTools::getProductID($this->id_product_attribute));
			$taxe = $product->getTaxesRate();
			$total += round(($price - ($this->discount_value  * (($taxe / 100) + 1))), 2);
			
		    }elseif($this->discount_type == self::$DISCOUNT_TYPE_WITH_VAT){
			$total += $price - $this->discount_value;
		    }elseif($this->discount_type == self::$DISCOUNT_TYPE_PERCENT){
			$total += $price * (1 + ($this->discount_value / 100));
		    }
		    
		}elseif($i >= $this->discount_nb_months)
		    $total += $price;
		
		$i += $this->frequency;
	    }
	    
	    return $total;
	    
	}
	
	
	/**
	 * Récupération des cycles de l'abonnement (Nombre de cycles passés, nombre de cycles à faire)
	 */
	public function getCycles(){
	    
	    $start = $this->date_start;
	    
	    if($this->duration == 0)
		$end = $this->date_check;
	    else
		$end = $this->date_end;
	    
	    $cyclesOk = 0;
	    $cyclesTodo = 0;
	    
	    while($start < $end){
		if($start <= $this->date_check)
		    $cyclesOk ++;
		else
		    $cyclesTodo ++;
		
		$start = SMTools::getNextDate($this->date_start, $start, $this->frequency);
	    }
	    
	    return array('cyclesOk' => $cyclesOk, 'cyclesTodo' => $cyclesTodo);
	}

}
