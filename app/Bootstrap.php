<?php
namespace HMPP;
use HMPP\Core\Traits\Singleton;

require_once 'Core/Traits/Singleton.php';
require_once 'Core/Framework.php';

class Bootstrap {
	/* Singleton to ensure other processes can't create a memory leak by declaring multiple instances */
	use Singleton;
	
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
	public function make(String $name="", Array $args=null, Bool $isUnique = false){
		// Prevent any directory injection incase someone hasn't santised user inputs.
		$name = preg_replace("/[^A-Z0-9\/\\\\]+/i","",$name);
		$filePath = __DIR__ . "/" . str_ireplace(array("\HMPP\\","\\"),array("","/"),$name)  . ".php";
		
		if(!empty($name)){
			if(is_file($filePath)){
				if(isset($this->instances[$name])){
					$obj = $this->instances[$name];
				}
				
				if($isUnique || !isset($this->instances[$name])){
					// require the requested file
					require_once $filePath;
					$obj = new $name();
					
					if(!$isUnique){
						/* if we're not creating a new object, store the instance for future retrival
						and to prevent singletons everywhere.*/
						$this->instances[$name] = $obj;
					}
				}
				return $obj;
			}
			throw new \Exception("Could not find file ".$filePath." for class ".$name."");
		}
		throw new \Exception("Class ".$name." does not exist");
	}
}

