<?php
/**
 * Abstract.php
 *
 * PHP version 5
 *
 * @category   Magento
 * @package    Eepohs
 * @subpackage Estpay
 * @author     Eepohs OÜ <info@eepohs.com>
 * @license    http://opensource.org/licenses/bsd-license.php BSDL
 * @link       http://eepohs.com/
 */

/**
 * Abstract model for Estpay payment methods
 *
 * PLEASE READ THIS SOFTWARE LICENSE AGREEMENT ("LICENSE") CAREFULLY
 * BEFORE USING THE SOFTWARE. BY USING THE SOFTWARE, YOU ARE AGREEING
 * TO BE BOUND BY THE TERMS OF THIS LICENSE.
 * IF YOU DO NOT AGREE TO THE TERMS OF THIS LICENSE, DO NOT USE THE SOFTWARE.
 *
 * Copyright (c) $year$, Eepohs OÜ
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Community
 * @package    Eepohs
 * @subpackage Estpay
 * @author     Eepohs OÜ <info@eepohs.com>
 * @copyright  $year$ Eepohs OÜ
 * @license    http://opensource.org/licenses/bsd-license.php BSDL
 * @version    Release: $version$
 * @link       http://eepohs.com/
 */
abstract class Eepohs_Estpay_Model_Abstract
    extends Mage_Payment_Model_Method_Abstract
{

    protected $_canAuthorize = true;
    protected $_isGateway = true;
    protected $_canUseCheckout = true;
    protected $logFile = 'estpay.log';

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
        $order = Mage::getModel('sales/order')
            ->loadByIncrementId($this->getOrderId());

        if ( $order->canInvoice() ) {
            $invoice = $order->prepareInvoice();
            $invoice->pay()->register();
            $invoice->save();

            /* Send invoice */
            if (
                Mage::getStoreConfig(
                    'payment/' . $this->_code . '/invoice_confirmation'
                ) == '1'
            ) {
                $invoice->sendEmail(true, '');
            }

            Mage::register('current_invoice', $invoice);
        }

        $order->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
        $order->save();
    }

    /**
     * Abstract method to be overloaded by implementing classes.
     * This is used to verify response from bank
     *
     * @return boolean
     */
    public abstract function verify(array $params = array());
}
