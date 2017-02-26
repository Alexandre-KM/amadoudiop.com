<?php

/**
 *
 * Module de gestion d'abonnements avec gestion des réductions
 * @author vectow
 * @copyright  2012 Vectow EURL vectow@gmail.com http://www.vectow.eu
 * @version 4.0
 *
 */
if (!defined('_PS_VERSION_'))
    exit();

define('DISCOUNT_TYPE_WITHOUT_VAT', 0);
define('DISCOUNT_TYPE_WITH_VAT', 1);
define('DISCOUNT_TYPE_PERCENT', 2);

require_once (dirname(__FILE__) . '/models/SMSchema.php');
require_once (dirname(__FILE__) . '/models/SMSubscription.php');
require_once (dirname(__FILE__) . '/models/SMSAutomaticBilling.php');
require_once (dirname(__FILE__) . '/models/SMLog.php');
require_once (dirname(__FILE__) . '/models/SMTools.php');

class SubscriptionsManager extends Module {

    private $_periods = array();
    protected $context = NULL;
    public $_postErrors = array();

    /**
     * Constructeur du module
     */
    public function __construct() {
	$this->name = 'subscriptionsmanager';
	$this->tab = 'front_office_features';
	$this->version = '4.0.4';
	$this->module_key = "9183a774c35e5e627fa8d237e1676172";
	$this->author = 'Aduler.com';
	$this->need_instance = 0;

	parent::__construct();

	$this->bootstrap = true;
	$this->display = 'view';

	$this->displayName = $this->l('Subscription Manager');
	$this->description = $this->l('Module managing Subscriptions of orders');

	$this->confirmUninstall = $this->l('Do you really want to uninstall ?') . $this->name;
	$this->secure_key = Tools::encrypt($this->name);

	// Periods values
	$this->_periods = array(1 => $this->l('1 month'), 2 => $this->l('2 months'), 3 => $this->l('3 months'), 4 => $this->l('4 months'), 5 => $this->l('5 months'), 6 => $this->l('6 months'), 7 => $this->l('7 months'), 8 => $this->l('8 months'), 9 => $this->l('9 months'), 10 => $this->l('10 months'), 11 => $this->l('11 months'), 12 => $this->l('12 months'));
	$this->language = (int) (Configuration::get('PS_LANG_DEFAULT'));
	$this->context = Context::getContext();
    }

    private function _initSQL() {
	Db::getInstance()->Execute('
		  CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'SM_schema` (
		  `id_schema` int(10) unsigned NOT NULL auto_increment,
		  `id_payment_module` int(10) unsigned NOT NULL,
		  `id_product_attribute` int(10) unsigned NOT NULL,
		  `one_shot` TINYINT(1) NOT NULL DEFAULT 0,		 	
		  `duration` int(10) unsigned NULL DEFAULT NULL,
		  `is_renewable` TINYINT(1) NOT NULL DEFAULT 0,
		  `frequency` int(10) DEFAULT "1",
		  `notification_active` int(10) DEFAULT 0,
		  `notification_time` int(10) DEFAULT "1",
		  `notification_message` TEXT,
		  `id_group_linked` int(10) unsigned NULL DEFAULT NULL,
		  `id_group_back` int(10) unsigned NOT NULL DEFAULT "3",
		  `discount_mode` VARCHAR(20) DEFAULT NULL,
		  `discount_value` DECIMAL(10,2) unsigned NOT NULL DEFAULT 0,
		  `discount_type` TINYINT(3) NOT NULL DEFAULT "0",
		  `discount_nb_months` int(10) DEFAULT 0,
		  `stock_decrementation` TINYINT(1) NOT NULL DEFAULT "0",		  
		  `advance_notice_duration` int(10) DEFAULT 0,
		  `engagement_duration` int(10) DEFAULT 0,
		  `locked` TINYINT(1) NOT NULL DEFAULT 0,
		  `migrate_to` int(10) unsigned NOT NULL DEFAULT "0",
		  `migrate_type` TINYINT(1) NOT NULL DEFAULT 0,
		  PRIMARY KEY (`id_schema`),
		  KEY `id_product_attribute` (`id_product_attribute`),
		  KEY `id_payment_module` (`id_payment_module`)
		) ENGINE=' . _MYSQL_ENGINE_ . '  DEFAULT CHARSET=utf8;');

	Db::getInstance()->Execute('
		  CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'SM_subscription` (
		  `id_subscription` int(10) unsigned NOT NULL auto_increment,
		  `id_customer` INT(10) unsigned NOT NULL,
		  `id_order` int(10) unsigned NOT NULL,
		  `id_payment_module` int(10) unsigned NOT NULL,
		  `id_schema` int(10) unsigned NOT NULL,
		  `date_start` date NOT NULL,
		  `date_check` date NOT NULL,
		  `date_end` date NULL DEFAULT NULL,
		  `duration` int(10) unsigned NULL DEFAULT NULL,
		  `frequency` int(10) DEFAULT "1",
		  `is_renewable` TINYINT(1) NOT NULL DEFAULT 0,
		  `one_shot` TINYINT(1) NOT NULL DEFAULT 0,	  		
		  `has_stop` TINYINT(1) NOT NULL DEFAULT 0,		  
		  `id_group_linked` int(10) unsigned NULL DEFAULT NULL,
		  `id_group_back` int(10) unsigned NOT NULL DEFAULT "3",
		  `discount_mode` VARCHAR(20) DEFAULT NULL,
		  `discount_value` DECIMAL(10,2) unsigned NOT NULL DEFAULT 0,
		  `discount_type` TINYINT(3) NOT NULL DEFAULT "0",
		  `discount_nb_months` int(10) DEFAULT 0,
		  `stock_decrementation` TINYINT(1) NOT NULL DEFAULT "0",		  
		  `advance_notice_duration` int(10) DEFAULT 0,
		  `engagement_duration` int(10) DEFAULT 0,		  
		  `notification_sent` TINYINT(1) NOT NULL DEFAULT "0",
		  `status` int(10) DEFAULT 0,
		  `date_creation` date NOT NULL,
		  PRIMARY KEY (`id_subscription`),
		  KEY `id_order` (`id_order`),
		  KEY `id_schema` (`id_schema`)
		) ENGINE=' . _MYSQL_ENGINE_ . '  DEFAULT CHARSET=utf8;');

	Db::getInstance()->Execute('
		  CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'SM_automatic_billing` (
		  `id_automatic_billing` int(10) unsigned NOT NULL auto_increment,
		  `id_subscription` int(10) unsigned NOT NULL,		  
		  `id_order` int(10) unsigned NOT NULL,
		  `billing_date` date NOT NULL,
		  `state` int(10) default "1",
		  `amount` DECIMAL(10,2) unsigned NOT NULL DEFAULT 0,
		  `payment_message` VARCHAR( 255 ) NULL DEFAULT NULL,
		  PRIMARY KEY (`id_automatic_billing`),
		  KEY `id_subscription` (`id_subscription`)
		) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;');

	Db::getInstance()->Execute('
		  CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'SM_log` (
		  `id_log` int(10) unsigned NOT NULL auto_increment,
		  `log_id_employee` int(10) NULL DEFAULT NULL,		  
		  `log_id_customer` int(10) NULL DEFAULT NULL,
		  `log_key` VARCHAR( 255 ) NULL DEFAULT NULL,
		  `log_value` LONGTEXT NULL DEFAULT NULL,
		  `log_date` DATETIME NOT NULL,
		  `log_id_entity` int(10) NULL DEFAULT NULL,
		  `log_type_entity` VARCHAR( 255 ) NULL DEFAULT NULL,	 
		  PRIMARY KEY (`id_log`)
		) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;');

	return true;
    }

    private function _razSql() {
	Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'SM_schema`');
	Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'SM_subscription`');
	Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'SM_automatic_billing`');
	Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'SM_log`');

	return true;
    }

    public function install() {

	// Enregistrement des hooks et création des tables du module 
	if (!parent::install() || !$this->_initSQL() || !$this->registerHook('actionOrderStatusUpdate') || !$this->registerHook('displayBackOfficeHeader') || !$this->registerHook('productActions') || !$this->registerHook('newOrder') || !$this->registerHook('displayAdminOrder') || !$this->registerHook('actionCartSave') || !$this->registerHook('paymentTop') || !$this->registerHook('header') || !$this->registerHook('CustomerAccount') || !$this->registerHook('displayMyAccountBlock'))
	    return false;

	// Création de la règle panier obligatoire
	if (!SMTools::checkAndCreateMandatoryCartRule())
	    return false;

	// Création et initialisation des variables de configuration
	if (!Configuration::updateValue('CRON_OK', '0') || !Configuration::updateValue('NB_DAYS_STOP', '0') || !Configuration::updateValue('NOTIFY_ENGAGEMENT', '0') || !Configuration::updateValue('VSM_HOUR_CRON', NULL))
	    return false;

	if (!Configuration::updateValue('SUBSCRIPTION_GLOBAL', NULL) || !Configuration::updateValue('MIGRATE', NULL) || !Configuration::updateValue('SM_PAGE', 1))
	    return false;

	// Création d'un etat de commande
	if (!SMTools::CreateOrderState())
	    return false;

	SMLog::addLog("install_Module", NULL, $this->context->employee->id); // Log de l'installation


	return true;
    }

    public function uninstall() {

	// Supression des tables du module
	if (!parent::uninstall() || !$this->_razSql())
	    return false;

	// Suppression de l'état de la commande
	if (!SMTools::DeleteOrderState())
	    return false;

	// Supression des hooks
	if (!$this->unregisterHook('actionOrderStatusUpdate') || !$this->unregisterHook('displayBackOfficeHeader') || !$this->unregisterHook('productActions') || !$this->unregisterHook('newOrder') || !$this->unregisterHook('displayAdminOrder') || !$this->unregisterHook('actionCartSave') || !$this->unregisterHook('paymentTop') || !$this->unregisterHook('header') || !$this->unregisterHook('CustomerAccount') || !$this->unregisterHook('displayMyAccountBlock'))
	    return false;

	// Supression des variables de configuration
	if (!Configuration::deleteByName('CRON_OK') || !Configuration::deleteByName('NB_DAYS_STOP') || !Configuration::deleteByName('NOTIFY_ENGAGEMENT') || !Configuration::deleteByName('SM_OS_PENDING') || !Configuration::deleteByName('VSM_HOUR_CRON'))
	    return false;

	if (!Configuration::deleteByName('SUBSCRIPTION_GLOBAL') || !Configuration::deleteByName('MIGRATE') || !Configuration::deleteByName('SM_PAGE'))
	    return false;

	return true;
    }

    //////////////////////////////////
    //             HOOK             //
    //////////////////////////////////

    /**
     * A chaque appel en BO, on vérifie si le MandatoryCartRule existe, sinon on le crée
     */
    public function hookDisplayBackOfficeHeader() {

	SMTools::checkAndCreateMandatoryCartRule(); // On vérifie si le CartRule obligatoire est présent, sinon on le crée

	$controller = Tools::getValue('controller');
	$id_product = Tools::getValue('id_product');

	if ($controller == 'AdminProducts' && $id_product > 0 && SMSubscription::hasActiveSubscriptions($id_product)) {
	    if (SMSubscription::hasActiveSubscriptions($id_product)) { // Si on est sur une page d'édition de produit ET que le produit contient des déclinaisons utiliées
		$this->context->smarty->assign('NbSubscriptionsBySchema', SMSchema::getNbSubscriptionsBySchema()); // Envoi des infos sur les déclinaisons utilisées
		return $this->display(__FILE__, '/views/templates/admin/hook_admin_product.tpl'); // Appel du fichier de restriction sur les déclinaisons
	    }
	}
    }

    /**
     * Changement du status d'une commande
     */
    public function hookActionOrderStatusUpdate($params) {

	if ($params['newOrderStatus']->paid == 1) {


	    $order = new Order($params['id_order']);

	    $getSubscription = SMSubscription::getList(array('id_order' => $params['id_order']));

	    if (isset($getSubscription->id_subscription)) {
		$subscription = new SMSubscription($getSubscription->id_subscription);

		$subscription->status = SMSubscription::$STATUS_ACTIVE;
		$subscription->save();

		SMSAutomaticBilling::changeAutomaticBillingState($subscription, $order->id); // Stockage des informations de l'abonnement en base
		SMLog::addLog('start_SMSubscription', $subscription, $this->context->employee->id);
	    }
	}
    }

    /**
     * Bloque l'ajout de produits vérouillés dans le panier
     */
    public function hookProductActions() {

	$lockedSchemas = SMSchema::getLockedSchemas(); // Récuperation des id_product_attribute vérouillés


	if (!empty($lockedSchemas)) // Si aucun abonnement vérouillé
	    $this->context->smarty->assign('lockedSchemas', Tools::jsonEncode($lockedSchemas)); // Envoi des infos sur les produits vérouillés


	return $this->display(__FILE__, '/views/templates/admin/hook_product_actions.tpl'); // Appel du fichier de restriction sur les déclinaisons vérouillées
    }

    /**
     * Création de la commande
     */
    public function hookNewOrder($params) {

	$from = array();

	SMTools::checkAndCreateMandatoryCartRule(); // On vérifie si le CartRule obligatoire est présent, sinon on le crée


	$subscription_global = Configuration::get('SUBSCRIPTION_GLOBAL'); // Récupération de la variable globale permettant de ne pas créer de nouvel abonnement si c'est un prélèvement
	$migrate = Configuration::get('MIGRATE'); // Récupération de la variable globale permettant de déterminer si l'abonnement à créer est classique ou suite à une migration


	if ($subscription_global == NULL) { // Si la variable est nulle, on crée un nouvel abonnement
	    $id_order = (int) $params ['order']->id; // Récupération de l'id de la commande en cours


	    $cart = new Cart((int) $params ['cart']->id); // Récupération du panier ayant servi à la commande

	    if (isset($this->context->employee->id))
		$from['employee'] = $this->context->employee->id;
	    elseif (isset($cart->id_customer))
		$from['customer'] = $cart->id_customer;


	    $products = $cart->getProducts(); // Récupération des produits du panier
	    // Pour chaque produit présent dans le panier
	    foreach ($products as $product) {

		$id_attribute = $product ['id_product_attribute']; // Récupération de l'id_product_attribute


		$schema = SMSchema::getList(array('id_product_attribute' => $id_attribute)); // Récupération du schéma associé à cette déclinaison


		if (isset($schema ['id_schema']) && $schema ['id_schema'] > 0) { // Si un schéma existe
		    $schema = new SMSchema($schema ['id_schema']); // Nouvelle instance du schéma


		    $subscription = new SMSubscription (); // Nouvelle instance d'un abonnement
		    // Paramètrage de l'abonnement en fonction de son schéma de base
		    $subscription->id_customer = $cart->id_customer;
		    $subscription->id_order = $id_order;
		    $subscription->id_payment_module = $schema->id_payment_module;
		    $subscription->id_schema = $schema->id;
		    $subscription->duration = $schema->duration;
		    $subscription->frequency = $schema->frequency;
		    $subscription->date_start = date('Y-m-d');
		    $subscription->date_check = date('Y-m-d');
		    $subscription->date_end = $subscription->getDateEnd();
		    $subscription->is_renewable = $schema->is_renewable;
		    $subscription->one_shot = $schema->one_shot;
		    $subscription->has_stop = 0;
		    $subscription->id_group_linked = $schema->id_group_linked;
		    $subscription->id_group_back = $schema->id_group_back;
		    $subscription->discount_mode = $schema->discount_mode;
		    $subscription->discount_value = $schema->discount_value;
		    $subscription->discount_type = $schema->discount_type;
		    $subscription->discount_nb_months = $schema->discount_nb_months;
		    $subscription->stock_decrementation = $schema->stock_decrementation;
		    $subscription->advance_notice_duration = $schema->advance_notice_duration;
		    $subscription->engagement_duration = $schema->engagement_duration;
		    $subscription->notification_sent = 0;
		    $subscription->has_stop = 0;
		    $subscription->status = $params ['orderStatus']->paid == '1' ? SMSubscription::$STATUS_ACTIVE : SMSubscription::$STATUS_DESACTIVE;
		    $subscription->date_creation = date('Y-m-d');

		    if (isset($migrate)) { // Si l'abonnement est une migration
			// Pas de réduction
			$subscription->discount_mode = NULL;
			$subscription->discount_value = 0;
			$subscription->discount_type = 0;
			$subscription->discount_nb_months = 0;

			// On adapte les dates de l'abonnement à la fin de son prédécesseur
			$subscription->date_start = $migrate;
			$subscription->date_check = $migrate;
			$subscription->date_end = $subscription->getDateEnd();
		    }

		    $subscription->save(); // Sauvegarde de l'abonnement


		    SMSAutomaticBilling::addAutomaticBilling($subscription, $id_order); // Stockage des informations de l'abonnement en base


		    $subscription->date_check = $subscription->getNextDateCheck(); // On passe à la date de prélèvement suivante


		    $subscription->update(); // Sauvegarde de l'abonnement


		    if ($params ['orderStatus']->paid == '1') { // Si la commande est payée
			SMLog::addLog('createAndStart_SMSubscription', $subscription, (isset($from['employee']) ? $from['employee'] : NULL), (isset($from['customer']) ? $from['customer'] : NULL)); // Log de la création et du démarrage de l'abonnement
		    } elseif (isset($migrate)) { // Si migration
			SMLog::addLog('renewAndMigrate_SMSubscription', $subscription, 0); // Log de la migration de l'abonnement
		    }
		    else
			SMLog::addLog('create_SMSubscription', $subscription, (isset($from['employee']) ? $from['employee'] : NULL), (isset($from['customer']) ? $from['customer'] : NULL)); // Log de la création de l'abonnement


		    if (!empty($subscription->id_group_linked)) { //Si l'abonnement modifie le groupe de l'abonné
			$customer = new CustomerCore($subscription->id_customer); // Récuperation du client


			$groups = $customer->getGroups(); // Récupération des groupes du client
			// Si le client n'est pas déjà dans ce groupe
			if (!in_array($subscription->id_group_linked, $groups))
			    $customer->addGroups(array($subscription->id_group_linked)); // On l'ajoute dans ce dernier


			$default_group = $customer->id_default_group; // Récupération du groupe par défaut du client
			// Si le client n'a pas le groupe de l'abonnement en groupe par défaut
			if ($default_group != $subscription->id_group_linked)
			    $customer->id_default_group = $subscription->id_group_linked; // On l'ajoute


			$customer->save(); // Sauvegarde des modifications apportées au client
		    }


		    // SMTools::DeleteCartRule($params ['cart']->id); // Supression du CartRule après création de la commande
		}
	    }
	}

	Configuration::updateValue('SUBSCRIPTION_GLOBAL', NULL); // Reset après utilisation
	Configuration::updateValue('MIGRATE', NULL); // Reset après utilisation


	return TRUE;
    }

    /**
     * Fonction d'ajout d'un CartRule dans le panier si le client prend un abonnement avec une réduction
     */
    public function hookActionCartSave($params) {

	if (Tools::getValue('add') == 1) { // Si ajout dans le panier
	    $cart = new CartCore((int) $params ['cart']->id); // Nouvelle instance du panier

	    $products = $cart->getProducts(); // récupération des produits dans le panier
	    // Récupérer le produit pour vérifier s'il le produit fait partie d'un schéma
	    $schema = SMSchema::getList(array('id_product_attribute' => $products [0] ['id_product_attribute']));

	    if (!empty($schema)) { // S'il y a bien un schéma
		$sch = new SMSchema($schema ['id_schema']); // Nouvelle instance du schéma associé

		$paymentModule = ModuleCore::getInstanceById($sch->id_payment_module);

		if ($paymentModule->name != 'paypalsubscriptions' || ($paymentModule->name == 'paypalsubscriptions' && !SMTools::isCartFree($cart, $sch))) {

		    SMTools::DeleteCartRule($cart->id); // Supression des réductions panier

		    if ($sch->discount_mode != NULL) // Si le schéma comprend une réduction
			SMTools::addCartRule($cart, NULL, $sch); // Ajout de la réduction
		}
	    }
	} elseif (Tools::getValue('delete') == 1) { // Si on supprime un produit du panier
	    SMTools::DeleteCartRule($cart->id); // On supprime les réductions
	}
    }

    /**
     * Sert a indiquer dans une commande qu'elle est un abonnement
     */
    public function hookdisplayAdminOrder($params) {
	// Charge l'abonnement
	$auto = array();
	$auto = SMSAutomaticBilling::getList(array('id_order' => $params ['id_order']));

	if (!empty($auto))
	    return $this->display(__FILE__, '/views/templates/admin/hook_admin_order.tpl');
    }

    /**
     * Restriction des modes de paiement + Affichage des informations d'engagement
     */
    public function hookPaymentTop() {

	$row = Db::getInstance()->getRow("SELECT id_product_attribute FROM `" . _DB_PREFIX_ . "cart_product` WHERE id_cart = '" . (int) $this->context->cookie->id_cart . "'"); // Récupère l'ID attribut produit lié au panier

	$getSchema = array();

	if ($row['id_product_attribute'] != 0)
	    $getSchema = SMSchema::getList($row); // Trouve le schéma associé à cet element

	if (!empty($getSchema)) { // Si le produit est bien lié à un schéma
	    $schema = new SMSchema($getSchema ['id_schema']); // Nouvelle instance du schéma


	    foreach (SMTools::getPaymentModules() as $module) { // Pour chaque mode de paiement du site
		$moduleInstance = ModuleCore::getInstanceByName($module->name); // On récupère l'instance du mode de paiement


		if (isset($moduleInstance)) // Si l'instance existe
		    if ($module->id != $schema->id_payment_module) // Si le module n'est pas celui du schéma
			$moduleInstance->active = false; // On le désactive





		    
	    }

	    if (Configuration::get('NOTIFY_ENGAGEMENT') == 1 && $schema->one_shot == 0 && $schema->engagement_duration > 0) { // Si on doit notifier le client de la durée de son engagement
		$this->context->smarty->assign('date_end_engagement', SMTools::getVirtualEngagementEndDate(date('Y-m-d'), $schema->engagement_duration)); // Envoi des infos d'engagement
		return $this->display(__FILE__, '/views/templates/front/hook_payment_top.tpl'); // Appel de la page affichant la date d'engagement minimum de l'abonnement
	    }
	}
    }

    /**
     * Affichage du lien "mes abonnements" dans la page Mon compte
     */
    public function hookCustomerAccount() {

	$subscriptions = SMSubscription::getList(array('id_customer' => $this->context->customer->id, 'return_array' => 1));

	if (empty($subscriptions))
	    return;

	$this->smarty->assign(array('this_path' => $this->_path, 'this_path_ssl' => Configuration::get('PS_FO_PROTOCOL') . $_SERVER ['HTTP_HOST'] . __PS_BASE_URI__ . "modules/{$this->name}/"));

	return $this->display(__FILE__, '/views/templates/front/customer_account.tpl');
    }

    /**
     * Affichage du lien "mes abonnements" dans le bloc Mon compte
     */
    public function hookdisplayMyAccountBlock() {

	$subscriptions = SMSubscription::getList(array('id_customer' => $this->context->customer->id, 'return_array' => 1));

	if (empty($subscriptions))
	    return;

	return $this->display(__FILE__, '/views/templates/front/my_account_block.tpl');
    }

    /**
     * Intégration du fichier CSS du module
     */
    public function hookHeader() {
	$this->context->controller->addCSS(($this->_path) . 'subscriptionsmanager.css', 'all');
    }

    /**
     * Récupére le panier d'un invité qui a ajouté un produit d'abonnement
     * Si le produit bénéficie d'une réduction numéraire, on transfère cette*
     * réduction dans le nouveau panier
     * @param unknown_type $params
     */
    public function hookActionAuthentication($params) {

	// Récuppère le panier en cours
	$cart = new Cart($params ['cart']->id);

	// Récupére les régles de panier ayant une réduction
	$cartRules = $cart->getCartRules(CartRule::FILTER_ACTION_REDUCTION);

	// Sauvegarde les réductions de l'invité vers l'utilisateur loggué
	foreach ($cartRules as $cartRuleDetail) {
	    $cartRule = new CartRule($cartRuleDetail ['id_cart_rule']);
	    $cartRule->id_customer = $cart->id_customer;
	    $cartRule->update();
	}
    }

    //////////////////////////////////
    //           END HOOK           //
    //////////////////////////////////

    /**
     * Configuration principale du module
     */
    public function getContent() {
	$this->context = Context::getContext();

	$errors = '';

	$defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
	$iso = Language::getIsoById((int) $this->context->language->id);

	// Inclusion du CSS        
	$this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/assets/js/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.min.css', 'all');
	$this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/assets/css/subscriptionmanager.css', 'all');

	// Inclusion du JS
	$this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js');
	$this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/assets/js/jquery-ui/js/i18n/jquery.ui.datepicker-' . $iso . '.js');


	// Récupère l'opération demandée via L'URL pour traitement
	$op = Tools::getValue('op');

	// URL du module
	$request_uri = $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' . $this->name;
	$form_action = $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' . $this->name . '&op=' . $op;

	// URL ADMIN ORDER
	$tokenAdminOrder = Tools::getAdminToken('AdminOrders' . (int) (Tab::getIdFromClassName('AdminOrders')) . (int) ($this->context->employee->id));
	$this->smarty->assign('admin_order_uri', 'index.php?controller=AdminOrders&addorder&token=' . $tokenAdminOrder);
	$this->smarty->assign('module_dir_for_ajax', _MODULE_DIR_ . $this->name);
	$this->smarty->assign('request_uri', $request_uri);

	// Traitement de la requete selon l'opération demandée
	switch ($op) {
	    case 'createSchema' :
	    case 'saveSchema' :
	    case 'editSchema' :

		if (Tools::isSubmit('back'))
		    Tools::redirectAdmin($request_uri . '&tabs-2');


		// Si on a soumis le formulaire
		if (Tools::isSubmit('saveSchema')) {
		    // Création d'un objet de base de données


		    $smSchema = new SMSchema ();

		    // Indique si on est mode creation ou update
		    $smSchema->hydrate($_POST);


		    // Si pas de mode de paiement
		    if (empty($smSchema->id_payment_module))
			$this->_postErrors[] = $this->l('No payment module');



		    // Si pas de produit associé
		    if (empty($smSchema->id_product_attribute))
			$this->_postErrors[] = $this->l('No product linked');


		    $paymentModule = ModuleCore::getInstanceById($smSchema->id_payment_module);

		    if ($paymentModule->name == 'paypalsubscriptions') {
			$smSchema->is_renewable = 0;
			$smSchema->migrate_to = NULL;
		    }


		    // Si paiement en 1 fois
		    if ($smSchema->one_shot == 1) {

			// Si aucune durée
			if (empty($smSchema->duration))
			    $smSchema->duration = 12; // On force à 12 mois


			if (empty($smSchema->stock_decrementation)) { // Si pas de décrémentation
			    $smSchema->frequency = $smSchema->duration; // La Fréquence vaut la durée de l'abonnement (pour ne pas passer le CRON)
			    $smSchema->stock_decrementation = FALSE;
			} else {
			    if (empty($smSchema->frequency))
				$smSchema->frequency = 1;
			}

			// Init
			if (empty($smSchema->is_renewable))
			    $smSchema->is_renewable = FALSE;

			$smSchema->advance_notice_duration = 0; // Préavis impossible
			$smSchema->engagement_duration = 0; // Engagement impossible
			// Init
			if (empty($smSchema->notification_active))
			    $smSchema->notification_active = FALSE;

			// Init
			if (empty($smSchema->notification_time))
			    $smSchema->notification_time = 0;

			// Init
			if (empty($smSchema->notification_message))
			    $smSchema->notification_message = NULL;

			//Sinon si abonnement illimité
		    } elseif (empty($smSchema->duration)) {

			// Init
			$smSchema->duration = NULL;

			// Init
			if (empty($smSchema->engagement_duration))
			    $smSchema->engagement_duration = 0;

			$smSchema->is_renewable = FALSE; // Renouvellement auto inutile
			$smSchema->one_shot = FALSE; // Paiement en une fois impossible
			$smSchema->stock_decrementation = FALSE; // Décrémentation inutile
			$smSchema->advance_notice_duration = 0; // Préavis impossible
			$smSchema->notification_active = FALSE; // Pas de date de fin donc impossible d'avoir une date de notification
			// Init
			if (empty($smSchema->notification_time))
			    $smSchema->notification_time = 0;

			// Init
			if (empty($smSchema->notification_message))
			    $smSchema->notification_message = NULL;

			//Sinon (abonnement classique)
		    } else {

			$smSchema->one_shot = FALSE;
			$smSchema->stock_decrementation = FALSE;

			// Init
			if (empty($smSchema->is_renewable))
			    $smSchema->is_renewable = FALSE;

			// Init
			if (empty($smSchema->engagement_duration))
			    $smSchema->engagement_duration = 0;

			// Init
			if (empty($smSchema->advance_notice_duration))
			    $smSchema->advance_notice_duration = 0;

			// Init
			if (empty($smSchema->notification_active))
			    $smSchema->notification_active = FALSE;

			// Init
			if (empty($smSchema->notification_time))
			    $smSchema->notification_time = 0;

			// Init
			if (empty($smSchema->notification_message))
			    $smSchema->notification_message = NULL;
		    }

		    // TRAITEMENT GLOBAL
		    // Si pas de mode de réduction
		    if (empty($smSchema->discount_mode)) {
			$smSchema->discount_mode = NULL;
			$smSchema->discount_nb_months = 0;
			$smSchema->discount_value = 0;
			$smSchema->discount_type = DISCOUNT_TYPE_WITHOUT_VAT;
		    } elseif ($smSchema->discount_mode == "month_offer") { // Si mois offert
			$smSchema->discount_value = 0;
			$smSchema->discount_type = DISCOUNT_TYPE_WITHOUT_VAT;
		    } else { // Si réduction numéraire
			if (empty($smSchema->discount_value)) // Si aucune valeur
			    $this->_postErrors[] = $this->l('No value entered for reduction amount');
			elseif (!is_numeric($smSchema->discount_value)) // Si valeur non numérique
			    $this->_postErrors[] = $this->l('Value for reduction amount is not numeric');
			elseif ($smSchema->discount_value < 0) // Si valeur inférieur à 0
			    $this->_postErrors[] = $this->l('Value for reduction amount is less than zero');

			if (!in_array($smSchema->discount_mode, array(DISCOUNT_TYPE_WITHOUT_VAT, DISCOUNT_TYPE_WITH_VAT, DISCOUNT_TYPE_PERCENT)))
			    $this->_postErrors[] = $this->l('Discount mode does not exist');
		    }

		    //Si pas de groupe abonné
		    if (empty($smSchema->id_group_linked)) {
			$smSchema->id_group_linked = NULL; // Init
			$smSchema->id_group_back = (int) Configuration::get('PS_CUSTOMER_GROUP'); // On prend le groupe par défaut du site;
		    } else {
			if (empty($smSchema->id_group_back))
			    $smSchema->id_group_back = (int) Configuration::get('PS_CUSTOMER_GROUP'); // On prend le groupe par défaut du site
		    }

		    if (empty($smSchema->locked))
			$smSchema->locked = 0; // Init


		    if (empty($smSchema->migrate_to) || $smSchema->is_renewable == 0 || $smSchema->migrate_to == 0) {
			$smSchema->migrate_to = NULL; // Init
		    }

		    if (empty($smSchema->migrate_type) || $smSchema->is_renewable == 0)
			$smSchema->migrate_type = 0; // Init





		    if (!count($this->_postErrors)) { // Si aucune erreur
			try {
			    // Création ou mise à jour
			    $smSchema->save(TRUE);

			    $id_schema = Tools::getValue('id_schema');

			    if (empty($id_schema))
				SMLog::addLog('create_SMSchema', $smSchema, $this->context->employee->id);
			    else
				SMLog::addLog('update_SMSchema', $smSchema, $this->context->employee->id);

			    Tools::redirectAdmin($request_uri . '&conf=4' . '&tabs-2');
			} catch (Exception $ex) {
			    $this->smarty->assign('errors', $ex->getMessage());
			}
		    } else {

			foreach ($this->_postErrors as $err) {
			    $errors .= $this->displayError($err);
			}

			$this->smarty->assign('errors', $errors);

			// Repeuplement du formulaire
			foreach ($smSchema as $attr => $val) {
			    $_REQUEST [$attr] = $val;
			}

			// Liste des paiements disponibles
			$paymentModules = SMTools::getPaymentModules();

			// Liste des groupes de clients disponibles
			$clientGroups = Group::getGroups($defaultLanguage, true);

			// Liste des produits avec déclinaisons                
			$products = SMSchema::getProductWithAttributes();

			$schemasList = SMSchema::getListForMigration();

			foreach ($products as &$p) {
			    // Calcul du prix TTC
			    $p ['price_with_vat'] = Product::getPriceStatic($p ['id_product'], TRUE, $p ['id_product_attribute']);
			}

			$this->smarty->assign('periods', $this->_periods);
			$this->smarty->assign('products', $products);
			$this->smarty->assign('clientGroups', $clientGroups);
			$this->smarty->assign('paymentModules', $paymentModules);
			$this->smarty->assign('form_action', $form_action);
			$this->smarty->assign('schemas', $schemasList);
			$this->smarty->assign('op', $op);

			$paypalSubscriptions = ModuleCore::getInstanceByName('paypalsubscriptions');
			if (!empty($paypalSubscriptions))
			    $this->smarty->assign('id_paypal', $paypalSubscriptions->id);
			else
			    $this->smarty->assign('id_paypal', 0);

			return $this->display(__FILE__, '/views/templates/admin/form_schema.tpl');
		    }
		}

		if ($op == 'editSchema' && Tools::getValue('id') > 0) {
		    // Chargement d'un schéma
		    $smSchema = new SMSchema((int) Tools::getValue('id'));

		    // Repeuplement du formulaire
		    foreach ($smSchema as $attr => $val) {
			$_REQUEST [$attr] = $val;
		    }

		    $schemasList = SMSchema::getListForMigration($smSchema->id);
		}
		else
		    $schemasList = SMSchema::getListForMigration();

		// Liste des paiements disponibles
		$paymentModules = SMTools::getPaymentModules();

		// Liste des groupes de clients disponibles
		$clientGroups = Group::getGroups($defaultLanguage, true);

		// Liste des produits avec déclinaisons                
		$products = SMSchema::getProductWithAttributes();

		$this->smarty->assign('periods', $this->_periods);
		$this->smarty->assign('products', $products);
		$this->smarty->assign('clientGroups', $clientGroups);
		$this->smarty->assign('paymentModules', $paymentModules);
		$this->smarty->assign('form_action', $form_action);
		$this->smarty->assign('schemas', $schemasList);
		$this->smarty->assign('currency', $this->context->currency->sign);
		$this->smarty->assign('op', $op);

		if (!empty($paypalSubscriptions))
		    $this->smarty->assign('id_paypal', $paypalSubscriptions->id);
		else
		    $this->smarty->assign('id_paypal', 0);

		// URL ADMIN ORDER
		$tokenAdminOrder = Tools::getAdminToken('AdminAttributesGroups' . (int) (Tab::getIdFromClassName('AdminAttributesGroups')) . (int) ($this->context->employee->id));
		$this->smarty->assign('admin_attributes_uri', 'index.php?controller=AdminAttributesGroups&token=' . $tokenAdminOrder);

		return $this->display(__FILE__, '/views/templates/admin/form_schema.tpl');
		break;

	    // Renouvelever ou non un abonnement
	    case 'toggleRenew' :

		$id = Tools::getValue('id');

		// Chargement de l'abonnement
		$subscription = new SMSubscription($id);

		// Si pas d'objet d'abonnement valide, on lance une exception
		if (!is_a($subscription, 'SMSubscription'))
		    throw new Exception('Invalid subscription object', 500);

		// Mise à jour du renouvellement
		$subscription->is_renewable = !$subscription->is_renewable;
		$subscription->update(NULL);

		SMLog::addLog('actionNoRenew_SMSubscription', $subscription, $this->context->employee->id);

		// redirection vers l'administration
		Tools::redirectAdmin($request_uri . '&conf=4' . '&tabs-1');
		break;

	    case 'start' :
	    case 'stop' :

		$id = Tools::getValue('id');

		// Chargement de l'abonnement
		$subscription = new SMSubscription($id);

		if ($op == 'start') {

		    // Déclenchement d'un nouvel état de commande pour l'abonnement sélectionné
		    $order = new Order($subscription->id_order);

		    // Si la commande n'a jamais été facturée, on génére un nouvel état payé pour cette commande
		    if (!$order->hasInvoice()) {
			$orderHistory = new OrderHistory ();
			$orderHistory->id_employee = $this->context->employee->id;
			$orderHistory->id_order = $order->id;
			$use_existings_payment = true;
			$orderHistory->changeIdOrderState(Configuration::get('PS_OS_PAYMENT'), $order, $use_existings_payment); // Payé
			$orderHistory->save();


			$getSubscription = SMSubscription::getList(array('id_order' => $order->id));

			if (isset($getSubscription->id_subscription)) {
			    $subscription = new SMSubscription($getSubscription->id_subscription);

			    $subscription->status = SMSubscription::$STATUS_ACTIVE;
			    $subscription->save();

			    SMSAutomaticBilling::changeAutomaticBillingState($subscription, $order->id); // Stockage des informations de l'abonnement en base
			    SMLog::addLog('start_SMSubscription', $subscription, $this->context->employee->id);
			}
		    }
		} else {
		    $subscription->stopSubscription();

		    SMLog::addLog('actionStop_SMSubscription', $subscription, $this->context->employee->id);
		}

		$subscription->update();

		Tools::redirectAdmin($request_uri . '&conf=4' . '&tabs-1');
		break;

	    case 'deleteSchema' :
		// Faire un comptage des abonnements associés en LIMIT 1
		$subscriptions = SMSubscription::getList(array('id_schema' => Tools::getValue('id')));

		if (empty($subscriptions)) {
		    $smSchema = new SMSchema((int) Tools::getValue('id'));

		    SMLog::addLog('delete_SMSchema', $smSchema, $this->context->employee->id);

		    $smSchema->delete();
		}

		Tools::redirectAdmin($request_uri . '&conf=4' . '&tabs-2');
		break;

	    default :



		// Retirer le message d'information concernant la configuration du CRON
		$cron_ok = Tools::getValue('cron_ok');
		if (isset($cron_ok) && $cron_ok == 1) {
		    Configuration::updateValue('CRON_OK', '1');
		    Tools::redirectAdmin($request_uri . '&conf=4' . '&tabs-1');
		}

		$config = array();

		if (Tools::getValue('saveConfig')) {
		    $config ['NB_DAYS_STOP'] = Tools::getValue('NB_DAYS_STOP');
		    $config ['NOTIFY_ENGAGEMENT'] = Tools::getValue('NOTIFY_ENGAGEMENT');
		    $config ['VSM_HOUR_CRON'] = Tools::getValue('VSM_HOUR_CRON');

		    if (!is_numeric($config ['NB_DAYS_STOP']))
			$this->_postErrors[] = $this->l('Value must be numeric');
		    elseif ((int) $config ['NB_DAYS_STOP'] < 0)
			$this->_postErrors[] = $this->l('Value cannot be negative');

		    if (empty($config ['NOTIFY_ENGAGEMENT']))
			Configuration::updateValue('NOTIFY_ENGAGEMENT', 0);
		    else
			Configuration::updateValue('NOTIFY_ENGAGEMENT', 1);

		    if (!count($this->_postErrors)) {
			Configuration::updateValue('NB_DAYS_STOP', (int) $config ['NB_DAYS_STOP']);
			Configuration::updateValue('VSM_HOUR_CRON', (int) $config ['VSM_HOUR_CRON']);

			SMLog::addLog('update_module', $config, $this->context->employee->id);

			Tools::redirectAdmin($request_uri . '&conf=4' . '&tabs=3');
		    } else {
			$errors = '';
			foreach ($this->_postErrors as $err) {
			    $errors .= $this->displayError($err);
			}

			$this->smarty->assign('errors', $errors);
		    }
		}

		list ( $paginator, $subscriptionsList, $totalSubscriptions ) = SMTools::generateSubscriptionPaginator();

		// Liste des schémas d'abonnements
		$schemasList = SMSchema::getList();

		$logsList = SMLog::getListFormatted();

		foreach ($schemasList as &$schema) {
		    $group_a = new Group($schema ['id_group_linked']);
		    $schema ['id_group_linked'] = $group_a->name [$defaultLanguage];
		    $group_b = new Group($schema ['id_group_back']);
		    $schema ['id_group_back'] = $group_b->name [$defaultLanguage];
		    $schema ['currency'] = $this->context->currency->sign;
		    $schema ['payment_name'] = SMTools::getPaymentModuleName($schema ['id_module']);
		    $schema ['is_used'] = (SMSubscription::getNbSubscriptionsBySchema($schema ['id_schema']) > 0 ? 1 : 0);
		    $schema ['name'] = SMSchema::getProductWithAttributes($schema ['id_product_attribute']);
		}

		$moduleConfig = array();

		$moduleConfig ['NB_DAYS_STOP'] = Configuration::get('NB_DAYS_STOP');
		$moduleConfig ['CRON_OK'] = Configuration::get('CRON_OK');
		$moduleConfig ['NOTIFY_ENGAGEMENT'] = Configuration::get('NOTIFY_ENGAGEMENT');
		$moduleConfig ['VSM_HOUR_CRON'] = Configuration::get('VSM_HOUR_CRON');
		$cron = '*/60 * * * * wget -O - -q -t 1 "' . _PS_BASE_URL_ . __PS_BASE_URI__ . 'modules/' . $this->name . '/cron.php?key=' . $this->module_key . '" > /dev/null';

		// Affichage du numéro de version
		$this->smarty->assign('subscriptionsList', $subscriptionsList);
		$this->smarty->assign('schemasList', $schemasList);
		$this->smarty->assign('schemasListCount', count($schemasList));
		$this->smarty->assign('subscriptionsListCount', $totalSubscriptions);
		$this->smarty->assign('logsList', $logsList);
		$this->smarty->assign('moduleConfig', $moduleConfig);
		$this->smarty->assign('cron', $cron);
		$this->smarty->assign('version', $this->version);
		$this->smarty->assign('periods', $this->_periods);
		$this->smarty->assign('paginator', $paginator);
		$this->smarty->assign('form_action', $form_action);

		break;
	}

	// Affichage de l'écran principal avec les onglets
	return $this->display(__FILE__, '/views/templates/admin/interface.tpl');
    }

    ///////////////////////////////////
    //           FONCTIONS           //
    ///////////////////////////////////

    /**
     * Génère une commande et créer une facture pour un abonnement donné
     */
    static public function processOrderAndCreateInvoice(SMSubscription $subscription) {

	Configuration::updateValue('SUBSCRIPTION_GLOBAL', 1); // On change la variable


	$order = new Order($subscription->id_order); // Nouvelle instance
	$cart = new Cart($order->id_cart); // Nouvelle instance


	$price = $order->total_paid_tax_incl; // On stock le prix


	$tempo_cart = $cart->duplicate(); // Duplication du panier
	$new_cart = $tempo_cart['cart']; // Récupération de l'objet
	$new_cart->deleteAssociations(); // Suppression des produits du panier

	$p = $order->getProducts();
	$products = array_shift($p); // On récupère le premier produit de la commande


	$new_cart->updateQty(1, SMTools::getProductID($products ['product_attribute_id']), $products ['product_attribute_id']); // On l'ajoute dans le panier
	$new_cart->update(); // Sauvegarde du panier


	if ($subscription->isStillDiscount()) { // Si l'abonnement est toujours en réduction 
	    SMTools::addCartRule($new_cart, $subscription);
	    $price = $order->total_products_wt;
	}

	if ($new_cart->getOrderTotal(true, 3) <= 0) { // Si le prix du panier vaut 0
	    // Chargement du fichier qui permet de créer des commandes gratuites
	    require_once (dirname(__FILE__) . '/../../controllers/front/ParentOrderController.php');

	    $modulePayment = new FreeOrder (); // Nouvelle instance
	    $modulePayment->validateOrder($new_cart->id, Configuration::get('PS_OS_PAYMENT'), 0, $this->l('Free Order'), NULL, array(), (int) $this->context->currency->id); // Validation de la commande
	} else { // Si le prix du panier vaut plus de 0
	    if ($order->module == "free_order") // Si l'ancienne commande était gratuite
		$payment_module = SMTools::getPaymentModuleName($subscription->id_payment_module, true); // On récupère le nom du mode de paiement associé à l'abonnement
	    else
		$payment_module = $order->module; // Sinon on récupère le nom du mode de paiement de l'ancienne commande


	    require_once _PS_ROOT_DIR_ . '/modules/' . $payment_module . '/' . $payment_module . '.php'; // On charge le fichier du mode de paiement


	    $modulePayment = new $payment_module (); // Nouvelle instance

		$context = New Context();
	    $context->customer = new Customer($subscription->id_customer); // On stock le client dans le context


	    $modulePayment->validateOrder((int) $new_cart->id, Configuration::get('PS_OS_PAYMENT'), $new_cart->getOrderTotal(true, 3), $modulePayment->displayName, NULL, array(), (int) $context->currency->id, false, $context->customer->secure_key); // Validation de la commande
	}

	if ($subscription->isStillDiscount()) // S'il y avait une réduction appliquée
	    SMTools::DeleteCartRule($new_cart->id); // On supprime cette réduction


	return $modulePayment->currentOrder; // On retourne l'id de la nouvelle commande
    }

    /**
     * Fonction de creation de commande pour une migration
     */
    public function ProcessOrderForMigration(SMSchema $schema, SMSubscription $subscription, $auto_start = false) {

	Configuration::updateValue('MIGRATE', $subscription->date_end); // On passe la date de fin de l'ancien abonnement


	$order = new Order($subscription->id_order); // On charge l'ancienne commande
	// Creation du panier
	$old_cart = new Cart($order->id_cart);
	$tempo_cart = $old_cart->duplicate(); // Duplication du panier


	$cart = $tempo_cart ['cart']; // On récupère l'objet


	$cart->deleteAssociations(); // On supprime les produits du panier


	$id_product = SMTools::getProductID($schema->id_product_attribute); // On récupère l'id du produit


	$cart->updateQty(1, $id_product, $schema->id_product_attribute); // On ajoute le produit concerné dans le panier


	$cart->update(); // Mise à jour du panier
	// Paiement
	$payment_name = SMTools::getPaymentModuleName($schema->id_payment_module, true); // Récupération du nom du mode de paiement associé


	$price = (float) number_format($cart->getOrderTotal(true, 3), 2, '.', ''); // On récupère le prix du panier


	require_once _PS_ROOT_DIR_ . '/modules/' . $payment_name . '/' . $payment_name . '.php'; // On appel le fichier du mode de paiement


	$this->context->customer = new Customer($subscription->id_customer); // On ajoute le client au context


	$modulePayment = new $payment_name (); // Nouvelle instance du mode de paiement


	if ($auto_start) // Si démarrage auto
	    $state = Configuration::get('PS_OS_PAYMENT');
	else // Sinon on prend le status "En attente de démarrage"
	    $state = Configuration::get('SM_OS_PENDING');

	$modulePayment->validateOrder((int) $cart->id, $state, $price, $modulePayment->displayName, NULL, array(), (int) $this->context->currency->id, false, $this->context->customer->secure_key); // On valide la commande
    }

    /**
     * CRON du module 
     */
    public function runCron($module_key = NULL) {

	// Test de la clé
	if ($module_key != NULL && $module_key != $this->module_key)
	    return false;
	elseif (trim(Tools::getValue('key', NULL)) != NULL && trim(Tools::getValue('key', NULL)) != $this->module_key)
	    return false;

	$currentDate = date('Y-m-d'); // Recuperation de la date du jour


	SMLog::addLog('start_CRON', NULL, 0);

	// Pour chaque abonnement dont la date_check est inferieure 
	foreach (SMSubscription::getList(array('status' => SMSubscription::$STATUS_ACTIVE, 'customer_active' => TRUE, 'date_check' => $currentDate)) as $subscription) {

	    $sub = new SMSubscription($subscription ['id_subscription']);

	    // Si l'abonnement se termine
	    if ($sub->date_end == $sub->date_check) {

		// Termine l'abonnement
		if ($sub->has_stop == 1) {
		    $sub->status = SMSubscription::$STATUS_CANCELLED;
		    SMLog::addLog('stop_SMSubscription', $sub, 0);
		} else {
		    $sub->status = SMSubscription::$STATUS_TERMINATED;
		    SMLog::addLog('end_SMSubscription', $sub, 0);
		}

		$sub->update();

		// Abonnement renouvelable
		if ($sub->is_renewable == true) {

		    $schema = new SMSchema($sub->id_schema);

		    if ($schema->locked == 1) {

			if ($schema->migrate_to == 0) {
			    $sub->changeCustomerGroup(); // Modification du groupe de l'abonne
			    SMLog::addLog('NotRenewedBecauseOfLocking_SMSubscription', $sub, 0);
			} else {

			    $schema_migrate = new SMSchema($schema->migrate_to);

			    $auto_start = $schema->migrate_type == 0 ? FALSE : TRUE;

			    $this->ProcessOrderForMigration($schema_migrate, $sub, $auto_start); // Création d'une nouvelle commande
			}
		    } elseif ($schema->migrate_to != 0) {

			$schema_migrate = new SMSchema($schema->migrate_to);

			$auto_start = $schema->migrate_type == 0 ? FALSE : TRUE;

			$this->ProcessOrderForMigration($schema_migrate, $sub, $auto_start); // Création d'une nouvelle commande
		    } else {

			$new_sub = $sub->duplicateSubscription(); // Duplication de l'abonnement termine


			$id_order = $this->processOrderAndCreateInvoice($new_sub); // Création d'une nouvelle commande


			SMSAutomaticBilling::addAutomaticBilling($new_sub, $id_order); // Generation d'un etat de paiement


			$new_sub->date_check = $new_sub->getNextDateCheck();
			$new_sub->update();

			SMLog::addLog('renew_SMSubscription', $new_sub, 0);
		    }
		}
		else
		    $sub->changeCustomerGroup(); // Modification du groupe de l'abonne
	    } // Si l'abonnement demande une décrémentation du stock
	    elseif ($sub->one_shot == 1 && $sub->stock_decrementation == 1) {

		$order = new Order($sub->id_order);

		$products = array_shift($order->getProducts());
		$products ['id_product_attribute'] = $products ['product_attribute_id'];

		StockAvailable::updateQuantity($products ['id_product'], $products ['id_product_attribute'], - 1);

		$sub->date_check = $sub->getNextDateCheck();
		$sub->update();

		SMLog::addLog('stock_SMSubscription', $sub, 0);

		// Si l'abonnement n'est pas termine
	    } elseif ($sub->date_end != $sub->date_check) {

		$paymentModule = ModuleCore::getInstanceById($sub->id_payment_module);

		if ($paymentModule->name == 'paypalsubscriptions')
		    continue;

		// Duplique l'ancienne commande et en cree une nouvelle
		$id_order = $this->processOrderAndCreateInvoice($sub);

		SMSAutomaticBilling::addAutomaticBilling($sub, $id_order); // Generation d'un etat de paiement
		//
				// Met a jour la prochaine date de passage	
		$sub->date_check = $sub->getNextDateCheck();
		$sub->update();

		SMLog::addLog('check_SMSubscription', $sub, 0);
	    }

	    $sub->checkNotification(); // Notification
	}


	SMTools::clearCartRules(); // Nettoyage des Règles paniers qui ont échappées à leur triste sort.


	$not_finished = SMSubscription::getList(array('status' => SMSubscription::$STATUS_ACTIVE, 'customer_active' => TRUE, 'date_check' => $currentDate));
	// Si le traitement des abonnements n'est pas terminé
	if (!empty($not_finished))
	    $this->runCron($this->module_key);
    }

    /**
     * Traitement ajax du module
     */
    public function ajax() {
	$op = Tools::getvalue('op');
	$this->context->smarty->assign('op', $op);

	switch ($op) {
	    case 'getAllowedFrequencies' :
		$duration = (int) Tools::getValue('duration');
		$this->context->smarty->assign('frequencies', SMSchema::getAllowedFrequencies($duration));
		$this->context->smarty->assign('duration', $duration);
		return $this->display(__FILE__, '/views/templates/admin/ajax.tpl');
		break;

	    default :
		;
		break;
	}
	return TRUE;
    }

}

