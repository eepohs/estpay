<?php

class Eepohs_EEPay_Block_Abstract extends Mage_Payment_Block_Form
{

    public function getGatewayUrl()
    {
        return Mage::getStoreConfig('payment/' . $this->_code . '/gateway_url');
    }

    /**
     * Adds payment mehtod logotypes after method name
     * @return string
     */
    public function getMethodLabelAfterHtml()
    {
        //()//if blaa blaa in conf show logo then show logo

        $blockHtml = sprintf('<img src="%1$s" title="%2$s" alt="%2$s" class="payment-method-logo"/>', $this->getMethodLogoUrl(), $this->_gateway);
        return $blockHtml;
    }

    /**
     * Returns payment method logo URL
     *
     * @return string
     */
    public function getMethodLogoUrl()
    {
        return $imageUrl = $this->getSkinUrl(sprintf('images/eepohs/eepay/%s_logo_88x31.gif', strtolower($this->_gateway)));
    }

}