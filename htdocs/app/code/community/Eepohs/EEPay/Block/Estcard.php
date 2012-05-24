<?php

/**
 * @package    Eepohs
 * @subpackage EEPay
 */

/**
 * EEPay block for Estcard
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
class Eepohs_EEPay_Block_Estcard extends Eepohs_EEPay_Block_Abstract
{

    protected $_code = 'eepohs_estcard';
    protected $_gateway = 'estcard';

    public function getFields()
    {

        $fields = array();
        $helper = Mage::helper('eepay');
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);

        $fields['action'] = 'gaf';
        $fields['ver'] = '002';
        $fields['id'] = Mage::getStoreConfig('payment/' . $this->_code . '/merchant_id');
        $fields['ecuno'] = sprintf('%012s', $order->getIncrementId());
        $fields['eamount'] = sprintf("%012s", (round($order->getBaseGrandTotal(), 2) * 100));
        $fields['cur'] = $order->getOrderCurrencyCode();
        $fields['datetime'] = date("YmdHis");

        switch (Mage::app()->getLocale()->getLocaleCode()) {
            case 'et_EE':
                $language = 'et';
                break;
            case 'fi_FI':
                $language = 'fi';
                break;
            case 'de_DE':
                $language = 'de';
                break;
            default:
                $language = 'en';
                break;
        }
        $fields['lang'] = $language;

        $data =
                $fields['ver']
                . sprintf("%-10s", $fields['id'])
                . $fields['ecuno']
                . $fields['eamount']
                . $fields['cur']
                . $fields['datetime'];

        $mac = sha1($data);
        $key = openssl_pkey_get_private(Mage::getStoreConfig('payment/' . $this->_code . '/private_key'));
        openssl_sign($data, $mac, $key);
        $fields['mac'] = bin2hex($mac);
        openssl_free_key($key);

        return $fields;
    }

}
