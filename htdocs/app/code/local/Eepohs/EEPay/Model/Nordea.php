<?php

class Eepohs_EEPay_Model_Nordea extends Eepohs_EEPay_Model_Abstract {

    protected $_code = 'eepohs_nordea';
    protected $_formBlockType = 'eepay/nordea';
    protected $_gateway = 'nordea';
    
    public function verify($params) {
	
	// No Express payment return data
	if (!$params['SOLOPMT_RETURN_PAID'])
	    return FALSE;
	
	$data =
	    $params['SOLOPMT_RETURN_VERSION'] . '&' .
	    $params['SOLOPMT_RETURN_STAMP'] . '&' .
	    $params['SOLOPMT_RETURN_REF'] . '&' .
	    $params['SOLOPMT_RETURN_PAID'] . '&' .
	    Mage::getStoreConfig('payment/' . $this->_code . '/mac_key') . '&';
	
	// Invalid MAC code
	if ($params['SOLOPMT_RETURN_MAC'] != strtoupper(md5($data))) {
	    Mage::log("* (Nordea) Invalid MAC code: $data");
	    return FALSE;
	}
	
	$session = Mage::getSingleton('checkout/session');
        // Reference number doesn't match.
	if ($session->getLastRealOrderId() != substr($params['SOLOPMT_RETURN_REF'], 0, -1)) {
	    Mage::log("* (Nordea): Reference number doesn't match (potential tampering attempt): $data");
	    return FALSE;
	}	
	
	return TRUE;
	
    }
   
}
