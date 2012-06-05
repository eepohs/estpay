<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Abstract controller for Estpay payment methods
 *
 * PLEASE READ THIS SOFTWARE LICENSE AGREEMENT ("LICENSE") CAREFULLY
 * BEFORE USING THE SOFTWARE. BY USING THE SOFTWARE, YOU ARE AGREEING
 * TO BE BOUND BY THE TERMS OF THIS LICENSE.
 * IF YOU DO NOT AGREE TO THE TERMS OF THIS LICENSE, DO NOT USE THE SOFTWARE.
 *
 * Full text of this license is available @license
 *
 * @license http://www.eepohs.com/eepohs-commercial-software-license/
 * @licensee $ReleasedTo$
 * @version $version$
 * @author Eepohs OÜ
 * @copyright $year$ Eepohs OÜ http://www.eepohs.com/
 *
 * @package    Eepohs
 * @subpackage Estpay
 * @category   Payment methods
 */
class Eepohs_Estpay_Controller_Abstract extends Mage_Core_Controller_Front_Action
{

    public function redirectAction()
    {

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

    /**
     * This is return action handler for Estpay
     * payment method
     * It verifies signature and creates invoice.
     * In case of verification failure it cancels the order
     */
    public function returnAction()
    {

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