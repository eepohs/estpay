<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Estpay Model for Nordea Bank
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
class Eepohs_Estpay_Model_Nordea extends Eepohs_Estpay_Model_Abstract
{

    protected $_code = 'eepohs_nordea';
    protected $_formBlockType = 'estpay/nordea';
    protected $_gateway = 'nordea';

    /**
     * Verifies response from Nordea
     *
     * @param array $params
     * 
     * @return boolean
     */
    public function verify(array $params = array())
    {

        // No Express payment return data
        if ( !isset($params['SOLOPMT_RETURN_PAID']) )
            return false;

        $data =
            $params['SOLOPMT_RETURN_VERSION'] . '&' .
            $params['SOLOPMT_RETURN_STAMP'] . '&' .
            $params['SOLOPMT_RETURN_REF'] . '&' .
            $params['SOLOPMT_RETURN_PAID'] . '&' .
            Mage::getStoreConfig('payment/' . $this->_code . '/mac_key') . '&';

        // Invalid MAC code
        if ( $params['SOLOPMT_RETURN_MAC'] != strtoupper(md5($data)) ) {
            Mage::log(sprintf("%s (%s): (Nordea) Invalid MAC code", __METHOD__, __LINE__));
            return false;
        }

        $session = Mage::getSingleton('checkout/session');
        // Reference number doesn't match.
        if ( $session->getLastRealOrderId() != substr($params['SOLOPMT_RETURN_REF'], 0, -1) ) {
            Mage::log(sprintf("%s (%s): (Nordea): Reference number doesn't match (potential tampering attempt). IP logged: %s", __METHOD__, __LINE__, $_SERVER['REMOTE_ADDR']));
            return false;
        }

        return true;
    }

}
