<?php

class SubscriptionsManagerCustomer_AccountModuleFrontController extends ModuleFrontController {

    
    public function initContent() {
		
        parent :: initContent();

        $this->setTemplate('customer_account.tpl');
    }
}