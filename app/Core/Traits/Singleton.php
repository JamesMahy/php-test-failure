<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 15:59
 */

namespace HMPP\Core\Traits;

trait Singleton
{
	protected static $instance;
	final public static function getInstance()
	{
		return isset(static::$instance) ? static::$instance : static::$instance = new static;
	}
	
	final private function __construct() {
		static::init();
	}
	
	protected function init() {}
	final private function __wakeup() {}
	final private function __clone() {}
}