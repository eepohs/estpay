<?php

/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Estpay Model for SEB
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
class Eepohs_Estpay_Model_Seb extends Eepohs_Estpay_Model_IPizza
{

    protected $_code = 'eepohs_seb';
    protected $_formBlockType = 'estpay/seb';
    protected $_gateway = 'seb';

}
