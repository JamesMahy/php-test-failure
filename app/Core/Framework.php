<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 16:42
 */

namespace HMPP\Core;

class Framework
{
	/***
	 * Mirror of HMPP\Bootstrap method, this is to maintain code readability
	 * @param String $name
	 * @param array|null $args
	 * @param bool $isUnique
	 * @return mixed
	 * @throws \Exception
	 */
	public function make(String $name="", Array $args=null, Bool $isUnique = false){
		return \HMPP\Bootstrap::getInstance()->make($name,$args,$isUnique);
	}
}