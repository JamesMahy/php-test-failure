<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/05/2019
 * Time: 16:38
 */

/* Bootstrap framework */
require_once '../Bootstrap.php';

$bootstrap = \HMPP\Bootstrap::getInstance();
$bootstrap->make('\HMPP\Core\Config')->init();

class Index extends \HMPP\Core\Framework {
	public function main(){
		if(isset($_POST) && !empty($_POST)){
			print_r($_POST);
			die();
			return 	Header("Location: /");
		}
		
		$engine = $this->make("\HMPP\Core\DisplayEngine");
		
		$destinationArray = $this->make("\HMPP\Core\Config")->get("destinations");
		$destinationsCompiled = "";
		
		foreach($destinationArray as $destination => $routes){
			$destinationsCompiled .= $engine->build("items/destination.tpl",["id" => $destination, "name" => $destination, "routes" => json_encode($routes)]);
		}
		
		$engine->output(
				$engine->build("index.tpl",
					[
							"destination_list" => $destinationsCompiled
					]
				),
				"Let's go somewhere fun!"
		);
	}
}
// Launch index
(new Index($bootstrap))->main();



