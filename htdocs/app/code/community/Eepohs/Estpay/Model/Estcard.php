<?php

/**
 * Estcard.php
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
 * Estpay Estcard payment method model
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
class Eepohs_Estpay_Model_Estcard extends Eepohs_Estpay_Model_Abstract
{
    protected $_code = 'eepohs_estcard';
    protected $_formBlockType = 'estpay/estcard';
    protected $_gateway = 'estcard';

    /**
     * Verifies response sent by the bank
     *
     * @param array $params Parameters by bank
     *
     * @return int
     */
    public function verify(array $params = array())
    {

        $merchantId = Mage::getStoreConfig('payment/' . $this->_code . '/merchant_id');

        if (!isset($params['id']) || $params['id'] != $merchantId) {
            Mage::log(sprintf(
                            '%s (%s)@%s: Wrong merchant ID used for return: %s vs %s', __METHOD__, __LINE__,
                            $_SERVER['REMOTE_ADDR'], $params['id'], $merchantId
                    ), null, $this->logFile
            );
            return Eepohs_Estpay_Helper_Data::_VERIFY_CORRUPT;
        }

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

        switch ($result) {
            case 1: // ssl verify successful
                if ($params['respcode'] == '000')
                    return Eepohs_Estpay_Helper_Data::_VERIFY_SUCCESS;
                else
                    return Eepohs_Estpay_Helper_Data::_VERIFY_CANCEL;

            case 0: // ssl verify failed
                Mage::log(sprintf(
                                '%s (%s)@%s: Verification of signature failed for estcard', __METHOD__, __LINE__,
                                $_SERVER['REMOTE_ADDR'], $params['VK_SND_ID']
                        ), null, $this->logFile);

                return Eepohs_Estpay_Helper_Data::_VERIFY_CORRUPT;

            case -1: // ssl verify error
            default:
                $error = '';
                while ($msg = openssl_error_string())
                    $error .= $msg . "\n";
                Mage::log(sprintf(
                                '%s (%s)@%s: Verification of signature error for %s : %s', __METHOD__, __LINE__,
                                $_SERVER['REMOTE_ADDR'], $params['VK_SND_ID'], $error
                        ), null, $this->logFile);

                return Eepohs_Estpay_Helper_Data::_VERIFY_CORRUPT;
        }
    }

}
