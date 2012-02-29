<?php

class Eepohs_EEPay_Controller_Abstract extends Mage_Core_Controller_Front_Action {

    public function redirectAction() {

        /* Send order confirmation */
	if (Mage::getStoreConfig('payment/' . $this->_code . '/order_confirmation') == '1') {
	    $order = Mage::getModel('sales/order');
	    $order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
	    $order->sendNewOrderEmail();
	    $order->save();
	}
	
	$this->loadLayout();
	$this->renderLayout();
	    
    }
    
    public function returnAction() {
	
	$model = Mage::getModel($this->_model);
        $verify = $model->verify($this->getRequest()->getParams());
        if ($verify === TRUE) {
            $model->createInvoice();
            $this->_redirect('checkout/onepage/success');
            // Pronto: mis siis kui kasutusel on mingi muu checkout?
        } else {
            $session = Mage::getSingleton('checkout/session');
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            $order->cancel()->save();
            $this->_redirect('checkout/onepage/failure');
        }	
    
    }
    
}
