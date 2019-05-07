<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 16:38
 */

/* Bootstrap framework */
require_once '../Bootstrap.php';

$bootstrap = \HMPP\Bootstrap::getInstance();
$bootstrap->make('\HMPP\Core\Config')->init();

class Index extends \HMPP\Core\Framework {
	public function index(){
		return "hello world!";
	}
}
// Launch index
echo (new Index($bootstrap))->index();



