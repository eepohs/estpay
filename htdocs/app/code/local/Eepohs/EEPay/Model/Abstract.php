<?php

class Eepohs_EEPay_Model_Abstract extends Mage_Payment_Model_Method_Abstract {

    protected $_canAuthorize = TRUE;
    protected $_isGateway = TRUE;
    protected $_canUseCheckout = TRUE;
    
    public function getOrderPlaceRedirectUrl() {
	return Mage::getUrl("eepay/" . $this->_gateway . "/redirect");
    }

    public function createInvoice() {
        
        $session = Mage::getSingleton('checkout/session');
        $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
        
        if ($order->canInvoice()) {
            $invoice = $order->prepareInvoice();
            $invoice->pay()->register();
            
            /* Send invoice */
            if (Mage::getStoreConfig('payment/' . $this->_code . '/invoice_confirmation') == '1') {
                $invoice->sendEmail(TRUE, '');
            }
            
            $invoice->save();
            Mage::register('current_invoice', $invoice); // Pronto: ma ei tea, kas see on vajalik?
        }
        
        $order->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
        $order->save();
        
    }
    
}
