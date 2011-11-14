<?php

class Eepohs_EEPay_Block_Abstract extends Mage_Payment_Block_Form {
    
    public function getGatewayUrl() {
        return Mage::getStoreConfig('payment/' . $this->_code . '/gateway_url');
    }
    
}