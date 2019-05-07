<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 16:40
 */

namespace HMPP\Core;

use HMPP\Bootstrap;

class DisplayEngine extends Framework
{
	private $templatePath = "";
	public function __construct()
	{
		// to reduce code use
		$this->templatePath = $this->make("\HMPP\Core\Config")->get("app_path") . "assets/templates/";
	}
	
	/***
	 * Build a string from a supplied template and arguments passed.
	 * @param String $name
	 * @param array $args
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function build(String $name, Array $args=array()){
		$filePath = preg_replace("/[^A-Z0-9\/\\\\][tpl]+/i","",$this->templatePath . $name).".tpl";
		
		if(is_file($filePath)){
			$templateContents = file_get_contents($filePath);
			$replaceFrom = array();
			$replaceTo = array();
			
			if(preg_match_all("/{{([A-Z0-9_-]+)}}/im",$templateContents,$matches)){
				
				foreach($matches[1] as $key => $match){
					if(isset($args[$match])){
						$replaceFrom[] = $matches[0][$key];
						$replaceTo[] = $args[$match];
					}
				}
				if(!empty($replaceFrom)){
					$templateContents = str_ireplace($replaceFrom,$replaceTo,$templateContents);
				}
			}
			return $templateContents;
			
		}
		throw new \Exception("Template file ".$filePath . " not found");
	}
	
	public function output(String $output, String $title = "", Bool $bootstrap=true){
		$output = ($bootstrap ? $this->build("bootstrap.tpl", array("title" => $title, "body" => $output)) : $output);
		echo $output;
		
		return $output;
	}
}