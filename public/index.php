<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 16:38
 */

require_once '../Bootstrap.php';

$bootstrap = new \HMPP\Bootstrap();
$bootstrap->getInstance("\HMPP\Core\Config")->init();

class Index extends \HMPP\Core\Framework {
	
}

new Index($bootstrap);



