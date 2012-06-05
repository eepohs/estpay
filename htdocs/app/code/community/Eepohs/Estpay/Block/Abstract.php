<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Abstract block for Estpay payment methods (different banks)
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
class Eepohs_Estpay_Block_Abstract extends Mage_Payment_Block_Form
{

    /**
     * Returns payment gateway URL
     *
     * @return string Gateway URL
     */
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
        $blockHtml = sprintf('<img src="%1$s" title="%2$s" alt="%2$s" class="payment-method-logo"/>', $this->getMethodLogoUrl(), ucfirst($this->_gateway) );
        return $blockHtml;
    }

    /**
     * Returns payment method logo URL
     *
     * @return string
     */
    public function getMethodLogoUrl()
    {
        return $imageUrl = $this->getSkinUrl(sprintf('images/eepohs/estpay/%s_logo_88x31.gif', strtolower($this->_gateway)));
    }

    /**
     * Checks if quick redirect is enabled and
     * returns javascript block that redirects user
     * to bank without intermediate page
     *
     * @since 2.0.0
     * @return outstr javascript block
     */
    public function getQuickRedirectScript()
    {
        $outstr = '';
        if (Mage::getStoreConfig('payment/' . $this->_code . '/quick_redirect')) {
            $outstr = '<script type="text/javascript"><!--
                if($("GatewayForm")){$("GatewayForm").submit();}
                //--></script>';
        }
        return $outstr;
    }

}