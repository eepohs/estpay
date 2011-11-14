<?php

class Eepohs_EEPay_Model_Estcard extends Eepohs_EEPay_Model_Abstract {

    protected $_code = 'eepohs_estcard';
    protected $_formBlockType = 'eepay/estcard';
    protected $_gateway = 'estcard';
    
    public function verify($params) {
	
	$data = 
	    sprintf("%03s", $params['ver'])
	    . sprintf("%-10s", $params['id'])
	    . sprintf("%012s", $params['ecuno'])
	    . sprintf("%06s", $params['receipt_no'])
	    . sprintf("%012s", $params['eamount'])
	    . sprintf("%3s", $params['cur'])
	    . $params['respcode']
	    . $params['datetime']
	    . sprintf("%-40s", urldecode($params['msgdata']))
	    . sprintf("%-40s", urldecode($params['actiontext']));
	$mac = pack('H*', $params['mac']);

	$key = openssl_pkey_get_public(Mage::getStoreConfig('payment/' . $this->_code . '/bank_certificate'));
	$result = openssl_verify($data, $mac, $key);
	openssl_free_key($key);
	
	if ($result && $params['respcode'] == '000')
	    return TRUE;
	
	return FALSE;
	
    }
   
}
