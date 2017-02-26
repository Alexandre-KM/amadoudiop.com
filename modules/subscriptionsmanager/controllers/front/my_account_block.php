<?php

class SubscriptionsManagerMy_Account_BlockModuleFrontController extends ModuleFrontController {

    
    public function initContent() {
		
        parent :: initContent();
        
        $this->setTemplate('my_account_block.tpl');
    }
}