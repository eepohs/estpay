<?php

class Eepohs_EEPay_Block_Adminhtml_Initjs extends Mage_Adminhtml_Block_Template
{

    /**
     * Include JS in the head if section is Eepohs/EEPay
     */
    protected function _prepareLayout()
    {
        $section = $this->getAction()->getRequest()->getParam('section', false);
        if ($section == 'payment') {
            $this->getLayout()
                    ->getBlock('head')
                    ->addJs('eepohs/eepay.js');
        }
        parent::_prepareLayout();
    }

    protected function _toHtml()
    {
        $section = $this->getAction()->getRequest()->getParam('section', false);
        if ($section == 'payment') {
            return parent::_toHtml();
        } else {
            return '';
        }
    }

    public function getModuleVersion(){
        return (string) Mage::getConfig()->getNode()->modules->Eepohs_EEPay->version;
    }

}
