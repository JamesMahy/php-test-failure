<?php

namespace HMPP;

class Bootstrap {
	use HMPP\Core\Singleton;
	
	private $instances = array();
	
	public function getInstance(String $name="", Array $args=null, Bool $isUnique = false){
		$name = str_ireplace("HMPP/","",preg_replace("/[^A-Za-z0-9\/]+",""));
		if(!empty($name) && is_file($name)){
			if($isUnique || !isset($this->instances[$name])){
				require_once($name);
				$obj = new $name();
				
				if(!$isUnique){
					$this->instances[$name] = $obj;
				}
			}
			return $obj;
		}
		throw new \Exception("Class ".$name." does not exist");
	}
}

