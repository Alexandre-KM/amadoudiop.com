<?php

/**
 * Encapsule un schéma d'abonnement en base de données
 */
class SMSchema extends ObjectModelCore {
	
	/**
	 * ID du module de payement défini pour ce schéma
	 */
	public $id_payment_module;
	
	/**
	 * Id de l'attribut du produit inclus dans l'abonnement
	 */
	public $id_product_attribute;
	
	/**
	 * Indique si l'abonnement est en paiement en 1 fois
	 */
	public $one_shot;
	
	/**
	 * 
	 */
	public $can_stop;
	
	/**
	 * @see SMSubscription
	 */
	public $time_choice;
	
	/**
	 * @see SMSubscription
	 */
	public $duration;
	
	/**
	 * Indique si l'abonnement est renouvelable
	 */
	public $is_renewable;
	
	/**
	 * Fréquence de passage du CRON
	 */
	public $frequency;
	
	/**
	 * Indique si la notification de message est active pour ce schéma
	 */
	public $notification_active;
	
	/**
	 * Nombre de jour avant la fin de l'abonnement
	 */
	public $notification_time;
	
	/**
	 * Message de notification de fin d'abonnement
	 */
	public $notification_message;
	
	/**
	 * ID du groupe associé durant la durée de l'abonnement
	 */
	public $id_group_linked;
	
	/**
	 * ID du groupe associé durant la durée de l'abonnement
	 */
	public $id_group_back;
	
	/**
	 * @see SMSubscription
	 */
	public $discount_mode;
	
	/**
	 * @see SMSubscription
	 */
	public $discount_value;
	
	/**
	 * @see SMSubscription
	 */
	public $discount_type;
	
	/**
	 * @see SMSubscriptio
	 */
	public $discount_nb_months;
	
	/**
	 * @see SMSubscription
	 */
	public $stock_decrementation;
	
	/**
	 * @see SMSubscription
	 */
	public $advance_notice_duration;
	
	/**
	 * @see SMSubscription
	 */
	public $engagement_duration;
	
	/**
	 * Vérouillage du schéma
	 */
	public $locked;
	
	/**
	 * ID du schéma de migration
	 */
	public $migrate_to;
	
	/**
	 * Type de démarrage de l'abonnement post migration
	 * 0 = démarrage manuel
	 * 1 = démarrage automatique
	 */
	public $migrate_type;
	
	/**
	 * Id du produit de l'abonnement
	 */
	public $id_product = NULL;
	
	/**
	 * Réduction en mode numéraire
	 */
	public static $DISCOUNT_OFFER = 'reduction_offer';
	
	/**
	 * Réduction en mode mois gratuit
	 */
	public static $DISCOUNT_MONTHS_OFFER = 'month_offer';
	public static $definition = array ('table' => 'SM_schema', 'primary' => 'id_schema', 'multilang' => false, 'fields' => array ('id_payment_module' => array ('type' => self::TYPE_INT, 'required' => true ), 'id_product_attribute' => array ('type' => self::TYPE_INT, 'required' => true ), 'one_shot' => array ('type' => self::TYPE_BOOL ), 'duration' => array ('type' => self::TYPE_INT ), 'is_renewable' => array ('type' => self::TYPE_BOOL ), 'frequency' => array ('type' => self::TYPE_INT ), 'notification_active' => array ('type' => self::TYPE_BOOL ), 'notification_time' => array ('type' => self::TYPE_INT ), 'notification_message' => array ('type' => self::TYPE_STRING ), 'id_group_linked' => array ('type' => self::TYPE_INT ), 'id_group_back' => array ('type' => self::TYPE_INT ), 'discount_mode' => array ('type' => self::TYPE_STRING ), 'discount_value' => array ('type' => self::TYPE_FLOAT ), 'discount_type' => array ('type' => self::TYPE_INT ), 'discount_nb_months' => array ('type' => self::TYPE_INT ), 'stock_decrementation' => array ('type' => self::TYPE_BOOL ), 'advance_notice_duration' => array ('type' => self::TYPE_INT ), 'engagement_duration' => array ('type' => self::TYPE_INT ), 'locked' => array ('type' => self::TYPE_BOOL ), 'migrate_to' => array ('type' => self::TYPE_INT ), 'migrate_type' => array ('type' => self::TYPE_BOOL ) ) );
	
	public function __construct($id_schema = null) {
		parent::__construct ( $id_schema );
		
		// Récupère l'ID du produit concerné
		$row = Db::getInstance ()->getRow ( "SELECT p.id_product FROM `" . _DB_PREFIX_ . "product_attribute` p WHERE p.id_product_attribute = '" . ( int ) $this->id_product_attribute . "';" );
		$this->id_product = ( int ) $row ['id_product'];
	}
	
	/**
	 * Renvoie la liste de tous les schémas
	 */
	public static function getList($params = array()) {
		$defaultLanguage = ( int ) (Configuration::get ( 'PS_LANG_DEFAULT' ));
		
		$sql = new DbQuery ();
		$sql->select ( '*, m.name as payment_module_name' );
		$sql->from ( 'SM_schema', 'sch' );
		$sql->leftJoin ( 'module', 'm', 'm.id_module = sch.id_payment_module' );
		$sql->leftJoin ( 'product_attribute', 'pa', 'pa.id_product_attribute = sch.id_product_attribute' );
		$sql->leftJoin ( 'product_attribute_combination', 'pac', 'pac.id_product_attribute = sch.id_product_attribute' );
		$sql->leftJoin ( 'attribute_lang', 'al', 'al.id_attribute = pac.id_attribute' );
		$sql->where ( 'al.id_lang = ' . ( int ) $defaultLanguage );
		
		// Recherche par id_product_attribute
		if (isset ( $params ['id_product_attribute'] ) && $params ['id_product_attribute'] > 0) {
			$sql->where ( 'sch.id_product_attribute = ' . ( int ) $params ['id_product_attribute'] );
			return Db::getInstance ()->getRow ( $sql );
		}
		elseif(isset ( $params ['id_product_attribute'] ) && $params ['id_product_attribute'] == 0) {
		    return array();
		}
		
		if (isset ( $params ['locked'] )) {
			$sql->where ( 'sch.locked = ' . ( int ) $params ['locked'] );
		}
		
		$sql->groupBy ( 'sch.id_schema' );
		return Db::getInstance ()->executeS ( $sql );
	}
	
	/**
	 * Retourne le prix normal sans réduction d'un schéma d'abonnement
	 */
	public function getNormalPrice($with_vat = TRUE) {
		// Prix de l'abonnement sans la r�duction
		return Product::getPriceStatic ( $this->id_product, $with_vat, $this->id_product_attribute );
	}
	
	/**
	 * Récupération des écheances en fonction d'une date de départ
	 */
	public function getEcheances($date_start) {
		$echances = array ();
		if ($this->discount_mode == self::$DISCOUNT_MONTHS_OFFER || $this->discount_mode == self::$DISCOUNT_OFFER) {
			
		    if($this->duration == 0)
			$duration = 12;
		    else
			$duration = $this->duration;
			// Cr�e un �ch�ancier ordonn� par date
			for($month = 0; $month <= $duration; $month += $this->frequency) {
				// Date de l'échéance future
				$when = date ( 'Y-m-d', strtotime ( '+1 day', strtotime ( $date_start ) ) );
				
				$is_discount = FALSE;
				if ($month < $this->discount_nb_months) {
					$is_discount = TRUE;
				}
				
				// Remplissage du tableau
				$echances [$when] = array ('discount_mode' => $this->discount_mode, 'discount_value' => $this->discount_value, 'price' => $is_discount == TRUE ? $this->getReccuringPrice () : $this->getNormalPrice (), 'is_discount' => $is_discount );
			}
		}
		return $echances;
	}
	
	/**
	 * Renvoie T.T.C le prix d'un schéma en tenant d'une eventuelle réduction      	
	 */
	public function getReccuringPrice() {
		
		// Prix de l'abonnement sans la réduction
		$price = Product::getPriceStatic ( $this->id_product, TRUE, $this->id_product_attribute );
		
		// ID du produit lié cet attribut
		
		// Applique un eventuelle r�duction
		switch ($this->discount_mode) {
			case self::$DISCOUNT_MONTHS_OFFER :
				$price = 0;
				break;
			
			case self::$DISCOUNT_OFFER :
				
				switch ($this->discount_type) {
					case SMSubscription::$DISCOUNT_TYPE_WITHOUT_VAT : // HT
						$price = Product::getPriceStatic ( $this->id_product, FALSE, $this->id_product_attribute ) - $this->discount_value;
						break;
					
					case SMSubscription::$DISCOUNT_TYPE_WITH_VAT : // TTC
						$price -= $this->discount_value;
						break;
					
					case SMSubscription::$DISCOUNT_TYPE_PERCENT : // %
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
	 * Renvoi les fréquences possibles d'un schéma
	 * selon la durée d'un abonnement donné
	 */
	static public function getAllowedFrequencies($duration = 12) {
		$frequencies = array ();
		
		if ($duration == 'unlimited') {
			for($i = 1; $i <= 12; $i ++) {
				$frequencies [$i] = $i;
			}
			
			return $frequencies;
		}
		
		// Sinon, cas normal
		foreach ( range ( 1, $duration ) as $f ) {
			if ($duration % $f == 0) {
				$frequencies [$f] = $f;
			}
		}
		
		return $frequencies;
	}
	
	/**
	 * Récupération des informations d'un produit ou d'un ensemble de produits
	 */
	static public function getProductWithAttributes($id_product_attribute = NULL) {
		
		$defaultLanguage = ( int ) (Configuration::get ( 'PS_LANG_DEFAULT' )); // Init de la langue par défaut
		

		// Si on a un id_product_attribute
		if ($id_product_attribute != NULL) {
			
			$id_product = SMTools::getProductID ( $id_product_attribute ); // Récupération de l'id du produit
			$prod = new Product ( $id_product ); // Instance du produit
			

			// Pour chaque attribut
			foreach ( Product::getProductAttributesIds ( $id_product ) as $id_attr ) {
				
				$name = $prod->name [$defaultLanguage]; // On insère le nom du produit
				

				// Pour chaque attribut de la déclinaison
				foreach ( Product::getAttributesParams ( $id_product, $id_product_attribute ) as $attr ) {
					$name .= " - " . $attr ['name']; // On insère le nom de l'attribut
				}
			}
			
			return $name;
		}
		
		// Si on a pas d'id_product_attribute
		
		$products = array();
		// Pour chaque produit trié par date d'ajout décroissante
		foreach ( Product::getProducts ( $defaultLanguage, 0, 0, 'date_add', 'DESC', false, true ) as $product ) {
			// Pour chaque déclinaison de produit
			foreach ( Product::getProductAttributesIds ( $product ['id_product'] ) as $id_attr ) {
				
				$name = $product ['name']; // On insère le nom du produit
				

				// Pour chaque attribut de la déclinaisons
				foreach ( Product::getAttributesParams ( $product ['id_product'], $id_attr ['id_product_attribute'] ) as $attr ) {
					$name .= " - " . $attr ['name']; // On insère le nom de l'attribut
				}
				
				// On retourne un tableau contenant les informations nom - prix ttc - id de la déclinaison - si le produit est déjà associé à un schéma
				$products [] = array ('name' => $name, 'price_with_vat' => Product::getPriceStatic ( $product ['id_product'], TRUE, $id_attr ['id_product_attribute'] ), 'id_product_attribute' => $id_attr ['id_product_attribute'], 'id_product' => $product ['id_product'], 'already_associated' => (self::isAlreadyAssociated ( $id_attr ['id_product_attribute'] ) == true ? 1 : 0) );
			}
		}
		return $products;
	}
	
	/**
	 * Vérifie si l'id_product_attribute est déjà associé à un schéma
	 */
	static public function isAlreadyAssociated($id_product_attribute) {
		
		// Récupère le nombre de schémas associés à l'id_product_attribute passé en paramètre
		$row = Db::getInstance ()->getRow ( "SELECT count(*) as nb FROM `" . _DB_PREFIX_ . "SM_schema` WHERE id_product_attribute = '" . ( int ) $id_product_attribute . "'" );
		
		if (( int ) $row ['nb'] > 0) // Si ce nombre est supérieur à 0
			return true; // On retourne Vrai
		else // Sinon
			return false; // On retourne Faux
	}
	
	/**
	 * Récupération de la liste des schémas pour la migration
	 */
	static public function getListForMigration($id_schema = NULL) {
		
		$schemas = array();
		
		foreach ( self::getList () as $schema ) { // Pour chaque schéma
			if ($schema ['id_schema'] != $id_schema && $schema ['locked'] != 1 && $id_schema != NULL) // Si schéma non verouillé et id_schema différent du paramètre
				$schemas [$schema ['id_schema']] = self::getProductWithAttributes ( $schema ['id_product_attribute'] ); // On ajoute la ligne dans le tableau
			elseif ($id_schema == NULL) // Sinon si l'id_schema est null
				$schemas [$schema ['id_schema']] = self::getProductWithAttributes ( $schema ['id_product_attribute'] ); // On ajoute la ligne dans le tableau
		}
		return $schemas;
	}
	
	/**
	 * Récuperation des id des schemas vérouillés
	 */
	static public function getLockedSchemas() {
		
		$lockedSchemas = array (); // Init
		

		foreach ( self::getList ( array ('locked' => 1 ) ) as $schema ) { // Pour chaque schéma vérouillé
			$lockedSchemas [] = $schema ['id_product_attribute']; // On l'insère dans le tableau
		}
		
		return $lockedSchemas;
	}
	
	/**
	 * Récupération du nombre d'abonnement en cours pour chaque déclinaisons ainsi que la date de modification possible de ces dernières
	 */
	static public function getNbSubscriptionsBySchema() {
		
		$NbSubscriptionsBySchema = array ();
		
		foreach ( self::getList () as $schema ) { // Pour chaque schéma
			$NbSubscriptionsBySchema [$schema ['id_product_attribute']] ['nb'] = SMSubscription::getNbSubscriptionsBySchema ( $schema ['id_schema'] ); // Nombre d'abonnement liés en cours
			

			if ($NbSubscriptionsBySchema [$schema ['id_product_attribute']] ['nb'] > 0) // Si le nombre est > à 0
				$NbSubscriptionsBySchema [$schema ['id_product_attribute']] ['date'] = SMSubscription::getUnlockingDate ( $schema ['id_schema'] ); // Récupération de la date de déverouillage de la déclinaison
		}
		
		return $NbSubscriptionsBySchema;
	}

}
