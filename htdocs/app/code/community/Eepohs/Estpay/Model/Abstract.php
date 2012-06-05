<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Abstract Estpay Model
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
class Eepohs_Estpay_Model_Abstract extends Mage_Payment_Model_Method_Abstract
{

    protected $_canAuthorize = true;
    protected $_isGateway = true;
    protected $_canUseCheckout = true;
    /**
     * Order Id to create invoice for
     * @var string
     */
    protected $_orderId;

    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl("estpay/" . $this->_gateway . "/redirect");
    }

    /**
     * This method creates invoice for current order
     */
    public function createInvoice()
    {
        $order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());

        if ($order->canInvoice()) {
            $invoice = $order->prepareInvoice();
            $invoice->pay()->register();

            /* Send invoice */
            if (Mage::getStoreConfig('payment/' . $this->_code . '/invoice_confirmation') == '1') {
                $invoice->sendEmail(TRUE, '');
            }

            $invoice->save();
            Mage::register('current_invoice', $invoice);
        }

        $order->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
        $order->save();
    }

}
