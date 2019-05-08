<?php
namespace HMPP;
use HMPP\Core\Traits\Singleton;

session_start();

require_once 'Core/Traits/Singleton.php';
require_once 'Core/Framework.php';

class Bootstrap {
	/* Singleton to ensure other processes can't create a memory leak by declaring multiple instances */
	use Singleton;
	
	private $instances = array();
	
	public function __construct(){
		$this->createSPLRegister();
	}
	
	/***
	 * Cleans the class name to protect against user input injection
	 * @param String $name
	 * @return null|string|string[]
	 */
	private function cleanClassName(String $name=""){
		return preg_replace("/[^A-Z0-9\/\\\\]+/i","",$name);
	}
	
	/***
	 * Gets the file path based on the provided class name
	 * @param String $name
	 * @return string
	 */
	private function getFilePathFromCleanClassName(String $name=""){
		return __DIR__ . "/" . str_ireplace(array("\HMPP\\","HMPP\\","\\"),array("","","/"),$name)  . ".php";
	}
	
	/***
	 * Basic dependency injection
	 */
	private function createSPLRegister(){
		spl_autoload_register(function($name){
			$filePath = $this->getFilePathFromCleanClassName(
					$this->cleanClassName($name)
			);
			if(is_file($filePath)){
				require_once $filePath;
			}
		});
	}
	
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
		$name = $this->cleanClassName($name);
		$filePath = $this->getFilePathFromCleanClassName($name);
		
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

