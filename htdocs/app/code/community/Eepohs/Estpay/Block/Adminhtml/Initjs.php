<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * AdminHtml InitJS block for Estpay that loads custom JS
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
 * @subpackage Estpay
 * @category   Payment methods
 */
class Eepohs_Estpay_Block_Adminhtml_Initjs extends Mage_Adminhtml_Block_Template
{

    /**
     * Include JS in the head if section is Eepohs/Estpay
     */
    protected function _prepareLayout()
    {
        $section = $this->getAction()->getRequest()->getParam('section', false);
        if ($section == 'payment') {
            $this->getLayout()
                    ->getBlock('head')
                    ->addJs('eepohs/estpay.js');
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

    public function getModuleVersion()
    {
        return (string) Mage::getConfig()->getNode()->modules->Eepohs_Estpay->version;
    }

}
