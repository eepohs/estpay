<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Estpay Model for Estcard
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
class Eepohs_Estpay_Model_Estcard extends Eepohs_Estpay_Model_Abstract
{

    protected $_code = 'eepohs_estcard';
    protected $_formBlockType = 'estpay/estcard';
    protected $_gateway = 'estcard';

    public function verify(array $params = array())
    {

        $merchantId = Mage::getStoreConfig('payment/' . $this->_code . '/merchant_id');

        if ( !isset($params['id']) || $params['id'] != $merchantId ) {
            Mage::log(sprintf('%s (%s): Wrong merchant ID used for return: %s vs %s', __METHOD__, __LINE__, $params['id'], $merchantId));
            return false;
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

        if ( $result && $params['respcode'] == '000' )
            return true;

        return false;
    }

}
