<?php

/**
 * @package    Eepohs
 * @subpackage EEPay
 */

/**
 * EEPay Model for Nordea Bank
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
 * @subpackage EEPay
 * @category   Payment methods
 */
class Eepohs_EEPay_Model_Nordea extends Eepohs_EEPay_Model_Abstract
{

    protected $_code = 'eepohs_nordea';
    protected $_formBlockType = 'eepay/nordea';
    protected $_gateway = 'nordea';

    public function verify($params)
    {

        // No Express payment return data
        if (!$params['SOLOPMT_RETURN_PAID'])
            return FALSE;

        $data =
                $params['SOLOPMT_RETURN_VERSION'] . '&' .
                $params['SOLOPMT_RETURN_STAMP'] . '&' .
                $params['SOLOPMT_RETURN_REF'] . '&' .
                $params['SOLOPMT_RETURN_PAID'] . '&' .
                Mage::getStoreConfig('payment/' . $this->_code . '/mac_key') . '&';

        // Invalid MAC code
        if ($params['SOLOPMT_RETURN_MAC'] != strtoupper(md5($data))) {
            Mage::log("* (Nordea) Invalid MAC code: $data");
            return FALSE;
        }

        $session = Mage::getSingleton('checkout/session');
        // Reference number doesn't match.
        if ($session->getLastRealOrderId() != substr($params['SOLOPMT_RETURN_REF'], 0, -1)) {
            Mage::log("* (Nordea): Reference number doesn't match (potential tampering attempt): $data");
            return FALSE;
        }

        return TRUE;
    }

}
