<?php

class Eepohs_EEPay_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function calcRef($number) {
	
	$n = (string)$number;
	$w = array(7,3,1);
     
	$sl = $st = strlen($n);
	$total = 0;
	while($sl > 0 and substr($n, --$sl, 1) >= '0') {
	    $total += substr($n, ($st - 1) - $sl, 1) * $w[($sl % 3)];
	}
	$c = ((ceil(($total / 10)) * 10) - $total);
	return $n.$c;
	
    }

}

