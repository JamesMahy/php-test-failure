<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 15:39
 */

namespace HMPP\Core;

class Config
{
	private $configFile = __DIR__."/../config.json";
	private $configFileRaw = "";
	private $config = null;
	
	/***
	 * Config constructor, added to allow for proper unit testing.
	 * @param String $rawJsonData only passed by unit test
	 */
	public function __construct(String $rawJsonData=""){
		
		if(empty($rawJsonData)){
			$this->configFileRaw = file_get_contents($this->configFile);
		}else{
			$this->configFileRaw = $rawJsonData;
		}
	}
	
	/***
	 * Initializes the config instance, parses the JSON in ../config.json
	 * and throws and exception if there is something wrong
	 * @throws \Exception
	 */
	public function init(){
		$jsonParsed = json_decode($this->configFileRaw, true);
		
		if(empty($jsonParsed) || !is_array($jsonParsed)){
			throw new \Exception("config.json empty or corrupted");
		}
		$this->config = $jsonParsed;
	}
	
	/***
	 * Get's the value of the provided namespace and config name
	 * throws an exception if it can't find the requested value.
	 *
	 * @param String $namespace eg "stations"
	 * @param String $name eg "Holborn"
	 * @return mixed
	 * @throws \Exception
	 */
	public function get(String $name=""){
		$exceptionText = $name." could not be found in config.json";
		
		if(isset($this->config[$name])){
			return $this->config[$name];
		}
		throw new \Exception($exceptionText);
	}
	
}