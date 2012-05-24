<?php

/**
 * @package    Eepohs
 * @subpackage EEPay
 */

/**
 * Helper class for EEPay payment method
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
class Eepohs_EEPay_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function calcRef($number)
    {

        $n = (string) $number;
        $w = array(7, 3, 1);

        $sl = $st = strlen($n);
        $total = 0;
        while ($sl > 0 and substr($n, --$sl, 1) >= '0') {
            $total += substr($n, ($st - 1) - $sl, 1) * $w[($sl % 3)];
        }
        $c = ((ceil(($total / 10)) * 10) - $total);
        return $n . $c;
    }

}

