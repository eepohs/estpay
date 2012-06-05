<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Estpay block for iPizza (Generic API)
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
class Eepohs_Estpay_Block_IPizza extends Eepohs_Estpay_Block_Abstract
{

    /**
     * Populates and returns array of fields to be submitted
     * to a bank for payment
     *
     * @return Array
     */
    public function getFields()
    {

        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);

        $fields = array();

        $fields['VK_SERVICE'] = '1002';
        $fields['VK_VERSION'] = '008';
        $fields['VK_SND_ID'] = Mage::getStoreConfig('payment/' . $this->_code . '/vk_snd_id');
        $fields['VK_REF'] = '';
        $fields['VK_RETURN'] = Mage::getUrl('estpay/' . $this->_gateway . '/return');

        switch (Mage::app()->getLocale()->getLocaleCode()) {
            case 'et_EE':
                $language = 'EST';
                break;
            case 'ru_RU':
                $language = 'RUS';
                break;
            default:
                $language = 'ENG';
                break;
        }

        $fields['VK_LANG'] = $language;
        $fields['VK_STAMP'] = $order->getIncrementId();

        $fields['VK_AMOUNT'] = number_format($order->getBaseGrandTotal(), 2, '.', '');
        $fields['VK_CURR'] = 'EUR';
        $fields['VK_MSG'] = __('Order number') . ': ' . $order->getIncrementId();

        $data = sprintf('%03d%s', strlen($fields['VK_SERVICE']), $fields['VK_SERVICE'])
                . sprintf('%03d%s', strlen($fields['VK_VERSION']), $fields['VK_VERSION'])
                . sprintf('%03d%s', strlen($fields['VK_SND_ID']), $fields['VK_SND_ID'])
                . sprintf('%03d%s', strlen($fields['VK_STAMP']), $fields['VK_STAMP'])
                . sprintf('%03d%s', strlen($fields['VK_AMOUNT']), $fields['VK_AMOUNT'])
                . sprintf('%03d%s', strlen($fields['VK_CURR']), $fields['VK_CURR'])
                . sprintf('%03d%s', strlen($fields['VK_REF']), $fields['VK_REF'])
                . sprintf('%03d%s', strlen($fields['VK_MSG']), $fields['VK_MSG']);

        $key = openssl_pkey_get_private(Mage::getStoreConfig('payment/' . $this->_code . '/private_key'), '');
        $signature = null;
        openssl_sign($data, $signature, $key);
        $fields['VK_MAC'] = base64_encode($signature);
        openssl_free_key($key);

        return $fields;
    }

}
