<?php

/**
 * @author Eepohs
 */
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../../../phpunit/PHPUnit' . PATH_SEPARATOR . dirname(__FILE__) . '/../../../../../usr/lib/php/PEAR');

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__).'/../../../magento-1.7.0.0/');
//define your custom Magento location here if needed (for tests)
require_once '/srv/vhosts/magento-1.7.0.0/app' . DIRECTORY_SEPARATOR . 'Mage.php';