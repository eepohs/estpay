<?php

/**
 * Nordea.php
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
 * Nordea Bank payment model for Estpay
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
class Eepohs_Estpay_Model_Nordea extends Eepohs_Estpay_Model_Abstract
{
    protected $_code = 'eepohs_nordea';
    protected $_formBlockType = 'estpay/nordea';
    protected $_gateway = 'nordea';

    /**
     * Verifies response from Nordea
     *
     * @param array $params Response sent by bank and to be verified
     *
     * @return int
     */
    public function verify(array $params = array())
    {
        $test_success = false;

        // Not present if cancelled or rejected
        if (isset($params['SOLOPMT_RETURN_PAID']))
            $test_success = true;

        $data =
                $params['SOLOPMT_RETURN_VERSION'] . '&' .
                $params['SOLOPMT_RETURN_STAMP'] . '&' .
                $params['SOLOPMT_RETURN_REF'] . '&' .
                ($test_success ? $params['SOLOPMT_RETURN_PAID'] . '&' : '') .
                Mage::getStoreConfig('payment/' . $this->_code . '/mac_key') . '&';

        // Invalid MAC code
        if ($params['SOLOPMT_RETURN_MAC'] != strtoupper(md5($data))) {
            Mage::log(sprintf("%s (%s)@%s: (Nordea) Invalid MAC code", __METHOD__, __LINE__, $_SERVER['REMOTE_ADDR']));
            return Eepohs_Estpay_Helper_Data::_VERIFY_CORRUPT;
        }

        $session = Mage::getSingleton('checkout/session');

        $helper = Mage::helper('estpay');
        // Reference number doesn't match.
        if ($helper->calcRef($session->getLastRealOrderId()) != $params['SOLOPMT_RETURN_REF']) {
            Mage::log(
                    sprintf("%s (%s)@%s: (Nordea): Reference number doesn't match (potential tampering attempt).",
                            __METHOD__, __LINE__, $_SERVER['REMOTE_ADDR']
                    )
            );
            return Eepohs_Estpay_Helper_Data::_VERIFY_CORRUPT;
        }

        if ($test_success)
            return Eepohs_Estpay_Helper_Data::_VERIFY_SUCCESS;
        else
            return Eepohs_Estpay_Helper_Data::_VERIFY_CANCEL;
    }

}
