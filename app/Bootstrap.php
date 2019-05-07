<?php

namespace HMPP;

class Bootstrap {
	/* Singleton to ensure other processes can't create a memory leak by declaring multiple instances */
	use HMPP\Core\Singleton;
	
	private $instances = array();
	
	/***
	 * Retrieves an instance of the provided fully qualified class name,
	 * if $isUnique is true, it will always create a new instance.
	 *
	 * @param String $name
	 * @param array|null $args
	 * @param bool $isUnique
	 * @return mixed
	 * @throws \Exception
	 */
	public function getInstance(String $name="", Array $args=null, Bool $isUnique = false){
		// Prevent any directory injection incase someone hasn't santised user inputs.
		$name = str_ireplace("HMPP/","",preg_replace("/[^A-Za-z0-9\/]+",""));
		if(!empty($name) && is_file($name)){
			if($isUnique || !isset($this->instances[$name])){
				// require the requested file
				require_once $name;
				$obj = new $name();
				
				if(!$isUnique){
					/* if we're not creating a new object, store the instance for future retrival
					and to prevent singletons everywhere.*/
					$this->instances[$name] = $obj;
				}
			}
			return $obj;
		}
		throw new \Exception("Class ".$name." does not exist");
	}
}

