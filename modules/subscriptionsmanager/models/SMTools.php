<?php

/**
 * Fonctions outils pour le module Gestion d'abonnements
 */
abstract class SMTools extends ObjectModelCore {

    public function __construct() {
	parent::__construct();
    }

    /**
     * Calcul de la prochaine date
     * $date_start = Date de départ (important car détermine si c'est une fin de mois)
     * $mydate = Date à modifier
     * $duration = Durée à ajouter ou supprimer
     * $less = si on doit avancer ou reculer (false = on avance dans le temps, true = on recule dans le temps)
     */
    static public function getNextDate($date_start, $mydate, $duration = 1, $less = false) {

	if ($less) // Si on doit décrémenter
	    $sql = "SELECT DATE_ADD('" . $mydate . "', INTERVAL - " . $duration . " DAY) AS next_date";
	else // Si on incrémente
	    $sql = "SELECT DATE_ADD('" . $mydate . "', INTERVAL " . $duration . " DAY) AS next_date";

	$result = Db::getInstance()->getRow($sql);

	$mydate = $result ['next_date'];

	if (date("Y-m-t", strtotime($date_start)) == $date_start) // Si la date de départ est une fin de mois
	    return date("Y-m-t", strtotime($mydate));
	elseif (date("d", strtotime($date_start)) > 28 && date("Y-m-t", strtotime($mydate)) != $mydate) { //Si date_start est supérieur à 28 et mydate !fin de mois
	    $d = date("d", strtotime($date_start)); // Récupération du jour de la date de départ
	    $m = date("m", strtotime($mydate)); // Récupération du mois de la nouvelle date
	    $Y = date("Y", strtotime($mydate)); // récupération de l'année de la nouvelle date


	    $date = new DateTime (); // Instance d'un DateTime


	    $date->setDate($Y, $m, $d); // Création de la nouvelle date
	    return $date->format('Y-m-d'); // On retourne la date au format souhaité
	}
	else // Sinon
	    return $mydate;
    }

    /**
     * Calcul de la précédente date par rapport à une date d'entrée et une fréquence
     */
    static public function getPreviousDate($date_start, $mydate, $duration = 1) {
	return self::getNextDate($date_start, $mydate, $duration, true);
    }

    /**
     * Calcul la date de fin de l'engagement à partir de la date passée en paramètre
     */
    static public function getVirtualEngagementEndDate($date, $engagement_duration) {
	$date = SMTools::getNextDate($date, $date, $engagement_duration);

	return $date;
    }

    /**
     * Suppression d'une réduction panier
     */
    static public function DeleteCartRule($id_cart) {

	$context = Context::getContext();

	$CartRule_tmp = CartRule::getCartsRuleByCode('DISCOUNT-SUBSCRIPTION-' . $id_cart, $context->language->id); // Récupération du cartRule


	if (!empty($CartRule_tmp)) { // S'il existe
	    $CartRule = new CartRule($CartRule_tmp [0] ['id_cart_rule']); // On l'instancie
	    $CartRule->delete(); // On le supprime
	}
    }

    /**
     * Récupération du nom du module en fonction de l'ID passé en paramètre
     */
    static public function getPaymentModuleName($id_module, $basic_name = false) {

	foreach (ModuleCore::getModulesOnDisk() as $module) { // Pour chaque module
	    if ($module->tab == 'payments_gateways' && $module->active == TRUE && $module->id == $id_module) { // Si c'est un module de paiement, s'il est actif et si les ids correspondent
		if ($basic_name)
		    $module_name = $module->name; // Nom basique
		else
		    $module_name = $module->displayName; // Nom d'affichage
	    }
	}
	return $module_name;
    }

    /**
     * Récupération des modules de paiements disponibles
     */
    static public function getPaymentModules() {

	$paymentModules = array(); // Init


	foreach (ModuleCore::getModulesOnDisk() as $module) { // Pour chaque module
	    if ($module->tab == 'payments_gateways' && $module->active == TRUE) { // Si le module est un module de paiement et s'il est actif
		$mymodule = new stdClass (); // Nouvelle instance
		$mymodule->id = $module->id; // Récupération de l'id
		$mymodule->name = $module->name; // Récupération du nom
		$mymodule->displayName = $module->displayName; // Récupération du nom d'affichage
		$paymentModules [] = $mymodule; // Insertion des informations dans le tableau
	    }
	}
	$_SESSION ['paymentsModules'] = $paymentModules;

	return $paymentModules;
    }

    /**
     * Vérifie si le CartRule obligatoire est présent, s'il ne l'est pas, on le crée
     */
    static public function checkAndCreateMandatoryCartRule() {

	$context = Context::getContext(); // Récupération du Context


	$check_MCR = CartRule::getCartsRuleByCode('SMSUBSCRIPTION', $context->language->id); // Récupération du CartRule obligatoire


	if (!empty($check_MCR)) // S'il existe
	    return TRUE; // On retourne Vrai


	$sub = new SubscriptionsManager (); // Nouvelle instance

	$mandatoryCartRule = new CartRule (); // Nouvelle instance d'un CartRule
	$mandatoryCartRule->quantity = 1; // Quantité à 1
	$mandatoryCartRule->date_from = date("Y-m-d H:i:s", strtotime("- 2 day")); // Date de départ vaut aujourd'hui - 2 jours
	$mandatoryCartRule->date_to = date("Y-m-d H:i:s", strtotime("- 1 day")); // Date de fin vaut aujourd'hui - 1 jours (inutilisable)
	$mandatoryCartRule->name [$context->language->id] = $sub->l('DO NOT DELETE OR EDIT'); // Nom = "DO NOT DELETE OR EDIT"
	$mandatoryCartRule->reduction_currency = $context->currency->id; // La devise vaut la devise par défaut
	$mandatoryCartRule->code = 'SMSUBSCRIPTION'; // Code par défaut
	$mandatoryCartRule->reduction_percent = 1; // réduction de 1%


	if (!$mandatoryCartRule->add()) // Si problème pendant la création
	    return FALSE; // On retourne Faux


	return TRUE; // On retourne Vrai
    }

    /**
     * Création d'un etat de paiement "En attente de démarrage de l'abonnement"
     */
    static public function CreateOrderState() {

	if (!Configuration::get('SM_OS_PENDING')) { // Si aucune variable "SM_OS_PENDING"
	    $orderState = new OrderState (); // Instance d'un OrderState
	    $orderState->name = array(); // Initialisation du nom sous forme d'un tableau

	    foreach (Language::getLanguages() as $language) { // Pour chaque langue
		if (Tools::strtolower($language ['iso_code']) == 'fr') // Si la langue est fr
		    $orderState->name [$language ['id_lang']] = 'En attente de démarrage de l\'abonnement';
		else // Sinon
		    $orderState->name [$language ['id_lang']] = 'Pending for the start of the subscription';
	    }

	    $orderState->send_email = false; // Pas d'envoi d'email
	    $orderState->color = '#4169E1'; // Couleur de l'état
	    $orderState->hidden = false; // Pas caché
	    $orderState->delivery = false; // Pas livré
	    $orderState->logable = true;
	    $orderState->invoice = false; // Pas de facture


	    if ($orderState->add()) { // Si l'ajout est fait avec succès
		$source = _PS_MODULE_DIR_ . '/subscriptionsmanager/assets/images/order_state.gif';
		$destination = _PS_IMG_DIR_ . 'os/' . (int) $orderState->id . '.gif';

		// Si le fichier existe
		if (file_exists($source))
		    copy($source, $destination); // Déplacement de l'image associée dans le dossier par défaut
	    }
	    else // Sinon
		return false; // On retourne Faux


	    Configuration::updateValue('SM_OS_PENDING', (int) $orderState->id); // Mise à jout de la valeur
	}

	return true; // Retourne Vrai
    }

    /**
     * Supprime un Etat de commande
     * @return boolean
     */
    static public function DeleteOrderState() {

	$id_order_state = Configuration::get('SM_OS_PENDING');
	
	// tente de charge l'order state depuis lid de configuration
	$orderState = new OrderState($id_order_state);
	$orderState->delete();

	$sql = "DELETE FROM " . _DB_PREFIX_ . 'order_state_lang WHERE id_order_state = ' . (int) $id_order_state;
	
	Db::getInstance()->query($sql);
	$destination = _PS_IMG_DIR_ . 'os/' . (int) $id_order_state . '.gif';

	// Suppression du fichier de l'état
	if (file_exists($destination)) {
	    unlink($destination);
	}

	return true;
    }

    /**
     * Ajout d'une réduction panier en fonction du schéma du produit présent dans le panier
     */
    static public function addCartRule($cart, SMSubscription $subscription = NULL, SMSchema $schema = NULL) {

	$context = Context::getContext();

	$products = $cart->getProducts();

	$cartRule = new CartRule ();
	$cartRule->id_customer = $cart->id_customer;
	$cartRule->quantity = 1;
	$cartRule->date_from = date('Y-m-d H:i:s');
	$cartRule->date_to = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 month'));
	$cartRule->gift_product_attribute = $products [0] ['id_product_attribute'];
	$cartRule->name [$context->language->id] = 'DISCOUNT-SUBSCRIPTION-' . $cart->id;
	$cartRule->reduction_currency = $context->currency->id;
	$cartRule->code = 'DISCOUNT-SUBSCRIPTION-' . $cart->id;

	if ($schema != NULL) {
	    if ($schema->discount_mode == SMSchema::$DISCOUNT_OFFER) { // Si montant spécifique
		if ($schema->discount_type == SMSubscription::$DISCOUNT_TYPE_WITHOUT_VAT) { // Si HT
		    if ($schema->one_shot == 1) // Si one shot
			$cartRule->reduction_amount = $schema->discount_value * $schema->discount_nb_months;
		    else
			$cartRule->reduction_amount = $schema->discount_value;
		} elseif ($schema->discount_type == SMSubscription::$DISCOUNT_TYPE_WITH_VAT) { // Si TTC
		    if ($schema->one_shot == 1) // Si one shot
			$cartRule->reduction_amount = $schema->discount_value * $schema->discount_nb_months;
		    else
			$cartRule->reduction_amount = $schema->discount_value;

		    $cartRule->reduction_tax = 1;
		} elseif ($schema->discount_type == SMSubscription::$DISCOUNT_TYPE_PERCENT) { // Si %
		    if ($schema->one_shot == 1) { // Si one shot
			$cartRule->reduction_amount = ((Product::getPriceStatic($products [0] ['id_product'], TRUE, $products [0] ['id_product_attribute']) / (int) $schema->duration) * ((int) $schema->discount_value / 100)) * (int) $schema->discount_nb_months;

			$cartRule->reduction_tax = 1;
		    }
		    else
			$cartRule->reduction_percent = $schema->discount_value;
		}
	    } elseif ($schema->discount_mode == SMSchema::$DISCOUNT_MONTHS_OFFER) { // Si mois offer
		if ($schema->one_shot == 1) // Si one shot
		    $cartRule->reduction_amount = (Product::getPriceStatic($products [0] ['id_product'], FALSE, $products [0] ['id_product_attribute']) / (int) $schema->duration) * (int) $schema->discount_nb_months;
		else
		    $cartRule->reduction_amount = Product::getPriceStatic($products [0] ['id_product'], FALSE, $products [0] ['id_product_attribute']);
	    }
	} elseif($subscription != NULL){
	    if ($subscription->discount_mode == SMSchema::$DISCOUNT_OFFER) { // Si montant spécifique
		if ($subscription->discount_type == SMSubscription::$DISCOUNT_TYPE_WITHOUT_VAT) { // Si HT
		    if ($subscription->duration == 0 && $subscription->isDiscountForUnlimited()) // Si illimité
			$cartRule->reduction_amount = $subscription->discount_value;
		    elseif ($subscription->one_shot == 1) // Si one shot
			$cartRule->reduction_amount = $subscription->discount_value * $subscription->discount_nb_months;
		    else
			$cartRule->reduction_amount = $subscription->discount_value;
		} elseif ($subscription->discount_type == SMSubscription::$DISCOUNT_TYPE_WITH_VAT) { // Si TTC
		    if ($subscription->duration == 0 && $subscription->isDiscountForUnlimited()) // Si illimité
			$cartRule->reduction_amount = $subscription->discount_value;
		    elseif ($subscription->one_shot == 1) // Si one shot
			$cartRule->reduction_amount = $subscription->discount_value * $subscription->discount_nb_months;
		    else
			$cartRule->reduction_amount = $subscription->discount_value;

		    $cartRule->reduction_tax = 1;
		} elseif ($subscription->discount_type == SMSubscription::$DISCOUNT_TYPE_PERCENT) { // Si %
		    if ($subscription->duration == 0 && $subscription->isDiscountForUnlimited()) // Si illimité
			$cartRule->reduction_percent = $subscription->discount_value;
		    elseif ($subscription->one_shot == 1) { // Si one shot
			$cartRule->reduction_amount = ((Product::getPriceStatic($products [0] ['id_product'], TRUE, $products [0] ['id_product_attribute']) / (int) $subscription->duration) * ((int) $subscription->discount_value / 100)) * (int) $subscription->discount_nb_months;

			$cartRule->reduction_tax = 1;
		    }
		    else
			$cartRule->reduction_percent = $subscription->discount_value;
		}
	    } elseif ($subscription->discount_mode == SMSchema::$DISCOUNT_MONTHS_OFFER) { // Si mois offer
		if ($subscription->duration == 0 && $subscription->isDiscountForUnlimited()) // Si illimité
		    $cartRule->reduction_amount = Product::getPriceStatic($products [0] ['id_product'], FALSE, $products [0] ['id_product_attribute']);
		elseif ($subscription->one_shot == 1) // Si one shot
		    $cartRule->reduction_amount = (Product::getPriceStatic($products [0] ['id_product'], FALSE, $products [0] ['id_product_attribute']) / (int) $subscription->duration) * (int) $subscription->discount_nb_months;
		else
		    $cartRule->reduction_amount = Product::getPriceStatic($products [0] ['id_product'], FALSE, $products [0] ['id_product_attribute']);
	    }
	}

	$cartRule->add();
	$cart->addCartRule($cartRule->id);
	
    }

    /**
     * Récupération de l'ID produit en fonction de l'id de l'attribut produit passé en paramètre
     */
    static public function getProductID($id_product_attribute) {

	$row = Db::getInstance()->getRow("SELECT id_product FROM `" . _DB_PREFIX_ . "product_attribute` WHERE id_product_attribute = '" . (int) $id_product_attribute . "'");

	return $row ['id_product'];
    }
    
    
    static public function clearCartRules(){
	
	$context = context::getContext();
	$CartRule_tmp = CartRule::getCartsRuleByCode('DISCOUNT-SUBSCRIPTION', $context->language->id, true);

	foreach($CartRule_tmp as $cr){
	    $cartRule = new CartRule($cr['id_cart_rule']);    
	    $cartRule->delete();
	}

    }

    static public function generateSubscriptionPaginator() {

	// PAGINATION
	$filters = $_GET;
	
	$conf_page = Configuration::get('SM_PAGE');
	//$filters = array_unique($filters);
	// Numero de page (1 par défaut)
	if (Tools::getIsset('page') && is_numeric($_GET ['page']))
	    $page = Tools::getValue('page');
	elseif (Tools::getisset($conf_page) && is_numeric($conf_page))
	    $page = $conf_page;
	else
	    $page = 1;

	// Nombre d'info par page
	$pagination = 20; // Dans conf  v2
	// Numéro du 1er enregistrement à lire
	$limit_start = ($page - 1) * $pagination;

	$filters ['per_page'] = $pagination;
	$filters ['page'] = $limit_start;

	$filters ['ajax'] = 1;
	$filters ['operation'] = 'filter_subscriptions';


	$nbTotal = SMSubscription::countList($filters);
	$nb_pages = ceil($nbTotal / $pagination);
	if ($page > $nb_pages)
	    $page = 1;

	// Numéro du 1er enregistrement à lire
	$limit_start = ($page - 1) * $pagination;

	$filters ['per_page'] = $pagination;
	$filters ['page'] = $limit_start;

	$filters ['ajax'] = 1;
	$filters ['operation'] = 'filter_subscriptions';

	unset($filters ['controller']);


	// Nb d'enregistrement total selon le filtre utilisé
	$nbTotal = SMSubscription::countList($filters);

	// Nombre  de page associé au résultat
	$nb_pages = ceil($nbTotal / $pagination);

	if ($page > $nb_pages)
	    $page = 1;


	// Liste des abonnements filtrés
	$filters['order_by_date'] = TRUE;
	$subscriptionsList = SMSubscription::getList($filters);

	$context = context::getContext();
	$defaultLanguage = $context->language->id;

	// Ajout et traitement de champs d'abonnement
	foreach ($subscriptionsList as &$subscription) {
	    $sub = new SMSubscription($subscription ['id_subscription']);
	    $subscription ['name'] = SMSchema::getProductWithAttributes($subscription ['id_product_attribute']);
	    $subscription ['can_stop'] = $sub->customerCanStop();
	    if ($subscription ['can_stop'])
		$subscription ['date_stop'] = $sub->getDateStop();
	    $subscription ['not_renew'] = $sub->customerCanNotRenew();

	    $group_a = new Group($subscription ['id_group_linked']);
	    $subscription ['group_linked_name'] = $group_a->name [$defaultLanguage];
	    $group_b = new Group($subscription ['id_group_back']);
	    $subscription ['group_back_name'] = $group_b->name [$defaultLanguage];

	    $schemaOfSub = new SMSchema($subscription['id_schema']);
	    $subscription ['payment_name'] = SMTools::getPaymentModuleName($schemaOfSub->id_payment_module);

	    $subscription ['amount_of_levies'] = $sub->getProductPrice();
	    $subscription ['currency'] = $context->currency->sign;

	    $subscription ['already_paid'] = $sub->getPaidAmount();
	    $subscription ['nb_levies'] = $sub->getNbLevies();
	    $subscription ['total_levies'] = $sub->getTotalLevies();
	}




	$paginator = "<p> Page :";
	unset($filters ['page']);
	for ($i = 1; $i <= $nb_pages; $i++) {

	    if ($i == $page) {
		$paginator .= "<a class=\"current_page\">$i</a>";
	    } else {
		$paginator .= " <a class=\"pagination_link\" href=\"/modules/subscriptionsmanager/ajax.php?page=$i&" . http_build_query($filters, '', "&") . "\">$i</a> ";
	    }
	}
	$paginator .= " </p>";

	// FIN PAGINATION
	return array($paginator, $subscriptionsList, $nbTotal);
    }
    
    
    // Récupération du schéma par rapport au produit dans le panier
    static public function getSchemaFromIdCart($id_cart) {
	
	$cart = new Cart((int) $id_cart); // Nouvelle instance du panier

	$products = $cart->getProducts(); // récupération des produits dans le panier
	
	// On retourne le schéma
	return SMSchema::getList(array('id_product_attribute' => $products [0] ['id_product_attribute']));
    }
    
    
    static public function isCartFree($cart, SMSchema $schema){
	
	$total_with_taxes = (float) $cart->getOrderTotal(true, Cart::BOTH);
	$total_without_taxes = (float) $cart->getOrderTotal(false, Cart::BOTH);
	
	if($schema->one_shot == 1)
	    return false;
	
	if($schema->discount_mode == SMSchema::$DISCOUNT_MONTHS_OFFER)
	    return true;
	elseif($schema->discount_mode == SMSchema::$DISCOUNT_OFFER){
	    if($schema->discount_type == SMSubscription::$DISCOUNT_TYPE_PERCENT && $schema->discount_value == 100)
		return true;
	    elseif($schema->discount_type == SMSubscription::$DISCOUNT_TYPE_WITH_VAT && $schema->discount_value == $total_with_taxes)
		return true;
	    elseif($schema->discount_type == SMSubscription::$DISCOUNT_TYPE_WITHOUT_VAT && $schema->discount_value == $total_without_taxes)
		return true;
	}
	
	return false;
    }
    

}

