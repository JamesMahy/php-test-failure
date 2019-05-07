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
	private $configFile = __DIR__."../config.json";
	private $configFileRaw = "";
	private $config = null;
	
	public function __construct(String $rawJsonData=""){
		
		if(!empty($rawJsonData)){
			$this->configFileRaw = file_get_contents($this->configFile);
		}else{
			$this->configFileRaw = $rawJsonData;
		}
	}
	
	public function init(){
		$jsonParsed = json_decode();
		
		if(empty($jsonParsed) || !is_array($jsonParsed)){
			throw new \Exception("config.json empty or corrupted",1);
		}
		$this->config = $jsonParsed;
	}
	
	public function get(String $namespace="", String $name=""){
		$exceptionText = $name." could not be found in config.json";
		if(empty($namespace)){
			if(isset($this->config[$name])){
				return $this->config[$name];
			}
			throw new \Exception($exceptionText);
		}
		throw new \Exception($namespace."/".$exceptionText);
	}
	
}