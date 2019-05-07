<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 16:38
 */

/* Bootstrap framework */
require_once '../Bootstrap.php';

$bootstrap = new \HMPP\Bootstrap();
$bootstrap->getInstance("\HMPP\Core\Config")->init();

class Index extends \HMPP\Core\Framework {

}
// Launch index
new Index($bootstrap);



