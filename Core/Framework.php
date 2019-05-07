<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 16:42
 */

namespace HMPP\Core;


use HMPP\Bootstrap;

class Framework
{
	private $bootstrap = null;
	public function __construct(Bootstrap $bootstrap){
		$this->bootstrap = $bootstrap;
	}
	
	public function getInstance(String $name="", Array $args=null, Bool $isUnique = false){
		return $this->bootstrap->getInstance($name,$args,$isUnique);
	}
}