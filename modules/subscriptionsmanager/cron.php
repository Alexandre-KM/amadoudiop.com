<?php
require(dirname(__FILE__).'/../../config/config.inc.php');
include_once(_PS_ROOT_DIR_.'/init.php');
include(dirname(__FILE__).'/subscriptionsmanager.php');
$subscriptionsmanager = new SubscriptionsManager();
$subscriptionsmanager->runCron();