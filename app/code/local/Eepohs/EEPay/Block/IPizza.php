<?php

class Eepohs_EEPay_Block_IPizza extends Eepohs_EEPay_Block_Abstract {

    public function getFields() {
	
	$fields = array();
        
        $fields['VK_SERVICE'] = '1002';
        $fields['VK_VERSION'] = '008';
        $fields['VK_SND_ID'] = Mage::getStoreConfig('payment/' . $this->_code . '/vk_snd_id');
        $fields['VK_REF'] = '';
        $fields['VK_RETURN'] = Mage::getUrl('eepay/' . $this->_gateway . '/return');
        
	switch(Mage::app()->getLocale()->getLocaleCode()) {
	    case 'et_EE':
		$language = 'EST';
		break;
	    case 'ru_RU':
		$language = 'RUS';
		break;
	    default:
		$language = 'ENG';
		break;
	}
	
	$fields['VK_LANG'] = $language;
        $fields['VK_STAMP'] = time();
        
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);
        
        $fields['VK_AMOUNT'] = number_format($order->getBaseGrandTotal(), 2, '.', '');
        $fields['VK_CURR'] = 'EUR';
        $fields['VK_MSG'] = __('Invoice number ') . $order->getIncrementId();
        
        $data = sprintf('%03d%s', strlen($fields['VK_SERVICE']), $fields['VK_SERVICE'])
            . sprintf('%03d%s', strlen($fields['VK_VERSION']), $fields['VK_VERSION'])
            . sprintf('%03d%s', strlen($fields['VK_SND_ID']), $fields['VK_SND_ID'])
            . sprintf('%03d%s', strlen($fields['VK_STAMP']), $fields['VK_STAMP'])
            . sprintf('%03d%s', strlen($fields['VK_AMOUNT']), $fields['VK_AMOUNT'])
            . sprintf('%03d%s', strlen($fields['VK_CURR']), $fields['VK_CURR'])
            . sprintf('%03d%s', strlen($fields['VK_REF']), $fields['VK_REF'])
            . sprintf('%03d%s', strlen($fields['VK_MSG']), $fields['VK_MSG']);

        $key = openssl_pkey_get_private(Mage::getStoreConfig('payment/' . $this->_code . '/private_key'), '');
        openssl_sign($data, $signature, $key);
        $fields['VK_MAC'] = base64_encode($signature);
	openssl_free_key($key);

        return $fields;
    }
    
}
