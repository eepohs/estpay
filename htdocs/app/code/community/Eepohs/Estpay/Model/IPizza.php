<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Estpay Model for iPizza (Generic  API)
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
class Eepohs_Estpay_Model_IPizza extends Eepohs_Estpay_Model_Abstract
{

    public function verify(array $params = array())
    {

        if ( !isset($params['VK_SERVICE']) || $params['VK_SERVICE'] != '1101' ) {
            Mage::log(sprintf('%s (%s): IPizza return service is not 1101: %s', __METHOD__, __LINE__, $params['VK_SERVICE']));
            return false;
        }

        $data = sprintf('%03d%s', strlen($params['VK_SERVICE']), $params['VK_SERVICE'])
            . sprintf('%03d%s', strlen($params['VK_VERSION']), $params['VK_VERSION'])
            . sprintf('%03d%s', strlen($params['VK_SND_ID']), $params['VK_SND_ID'])
            . sprintf('%03d%s', strlen($params['VK_REC_ID']), $params['VK_REC_ID'])
            . sprintf('%03d%s', strlen($params['VK_STAMP']), $params['VK_STAMP'])
            . sprintf('%03d%s', strlen($params['VK_T_NO']), $params['VK_T_NO'])
            . sprintf('%03d%s', strlen($params['VK_AMOUNT']), $params['VK_AMOUNT'])
            . sprintf('%03d%s', strlen($params['VK_CURR']), $params['VK_CURR'])
            . sprintf('%03d%s', strlen($params['VK_REC_ACC']), $params['VK_REC_ACC'])
            . sprintf('%03d%s', strlen($params['VK_REC_NAME']), $params['VK_REC_NAME'])
            . sprintf('%03d%s', strlen($params['VK_SND_ACC']), $params['VK_SND_ACC'])
            . sprintf('%03d%s', strlen($params['VK_SND_NAME']), $params['VK_SND_NAME'])
            . sprintf('%03d%s', strlen($params['VK_REF']), $params['VK_REF'])
            . sprintf('%03d%s', strlen($params['VK_MSG']), $params['VK_MSG'])
            . sprintf('%03d%s', strlen($params['VK_T_DATE']), $params['VK_T_DATE']);

        $key = openssl_pkey_get_public(Mage::getStoreConfig('payment/' . $this->_code . '/bank_certificate'));
        if ( !$key ) {
            Mage::log(sprintf('%s (%s): Key not found: %s', __METHOD__, __LINE__, 'payment/' . $this->_code . '/bank_certificate'));
        }
        if ( !openssl_verify($data, base64_decode($params['VK_MAC']), $key) ) {
            return false;
        }

        return true;
    }

}
