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

    /**
     *
     * @var Specifies model to be used to verify response from bank
     */
    protected $_model;

    /**
     * This action redirects user to bank for payment
     */
    public function redirectAction()
    {

        /* Send order confirmation */
        if ( Mage::getStoreConfig('payment/' . $this->_code . '/order_confirmation') == '1' ) {
            try {
                $order = Mage::getModel('sales/order');
                $order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
                $order->sendNewOrderEmail();
                $order->save();
            } catch ( Exception $e ) {
                Mage::log(sprintf('%s(%s): %s', __METHOD__, __LINE__, print_r($e->getMessage(), true)));
            }
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

        $session = Mage::getSingleton('checkout/session');
        $orderId = $session->getLastRealOrderId();
        if ( !$orderId ) {
            $orderId = $this->getRequest()->getParam('VK_STAMP');
        }
        if ( !$orderId ) {
            $this->_redirect('checkout/onepage/failure');
            return;
        }
        $model = Mage::getModel($this->_model);
        $model->setOrderId($orderId);
        $verify = $model->verify($this->getRequest()->getParams());
        if ( $verify === true ) {
            $model->createInvoice();
            $this->_redirect('checkout/onepage/success');
        } else {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $order->cancel()->save();
            $this->_redirect('checkout/onepage/failure');
        }
    }

}
