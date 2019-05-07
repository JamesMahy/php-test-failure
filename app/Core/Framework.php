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
	
	/***
	 * Mirror of HMPP\Bootstrap method, this is to maintain code readability
	 * @param String $name
	 * @param array|null $args
	 * @param bool $isUnique
	 * @return mixed
	 * @throws \Exception
	 */
	public function getInstance(String $name="", Array $args=null, Bool $isUnique = false){
		return $this->bootstrap->getInstance($name,$args,$isUnique);
	}
}