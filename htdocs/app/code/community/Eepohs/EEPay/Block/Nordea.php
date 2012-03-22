<?php

class Eepohs_EEPay_Block_Nordea extends Eepohs_EEPay_Block_Abstract
{

    protected $_code = 'eepohs_nordea';
    protected $_gateway = 'nordea';

    public function getFields()
    {

        $fields = array();
        $helper = Mage::helper('eepay');
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);

        $fields['SOLOPMT_VERSION'] = '0003';
        $fields['SOLOPMT_STAMP'] = time();
        $fields['SOLOPMT_RCV_ID'] = Mage::getStoreConfig('payment/' . $this->_code . '/service_provider');

        /* Choose language (3 = english, 4 = estonian, 6 = latvian, 7 = lithuanian */
        switch (Mage::app()->getLocale()->getLocaleCode()) {
            case 'et_EE':
                $language = '4';
                break;
            default:
                $language = '3';
                break;
        }
        $fields['SOLOPMT_LANGUAGE'] = $language;

        $fields['SOLOPMT_AMOUNT'] = number_format($order->getBaseGrandTotal(), 2, '.', '');
        $fields['SOLOPMT_REF'] = $helper->calcRef($order->getIncrementId());
        $fields['SOLOPMT_DATE'] = 'EXPRESS';
        $fields['SOLOPMT_MSG'] = __('Invoice number') . ' ' . $order->getIncrementId();
        $fields['SOLOPMT_RETURN'] = Mage::getUrl('eepay/' . $this->_gateway . '/return') . '?';
        $fields['SOLOPMT_CANCEL'] = Mage::getUrl('eepay/' . $this->_gateway . '/return') . '?';
        $fields['SOLOPMT_REJECT'] = Mage::getUrl('eepay/' . $this->_gateway . '/return') . '?';
        $fields['SOLOPMT_CONFIRM'] = 'YES';
        $fields['SOLOPMT_KEYVERS'] = '0001';
        $fields['SOLOPMT_CUR'] = 'EUR';

        $data =
                $fields['SOLOPMT_VERSION'] . '&' .
                $fields['SOLOPMT_STAMP'] . '&' .
                $fields['SOLOPMT_RCV_ID'] . '&' .
                $fields['SOLOPMT_AMOUNT'] . '&' .
                $fields['SOLOPMT_REF'] . '&' .
                $fields['SOLOPMT_DATE'] . '&' .
                $fields['SOLOPMT_CUR'] . '&' .
                Mage::getStoreConfig('payment/' . $this->_code . '/mac_key') . '&';

        $fields['STRING'] = $data;
        $fields['SOLOPMT_MAC'] = strtoupper(md5($data));

        return $fields;
    }

}