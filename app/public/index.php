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
		$model = $this->make("\HMPP\Models\JourneyModel");
		
		if(isset($_POST) && !empty($_POST)){
			if(isset($_POST['topup_amount'])){
				try{
					$amount = floatval($_POST['topup_amount']);
					
					if($amount <= 0){
						throw new \Exception("Please enter an amount to top up by");
					}
					
					$model->topupCard($amount);
					return 	Header("Location: /?message=You have successfully topped up £".$amount);
				}catch (\Exception $e){
					$error = $e->getMessage();
				}
			}else{
				try{
					$journeyData = $model->handleJourney($_POST);
					return 	Header("Location: /?message=Welcome to ".$journeyData['to']."&".http_build_query($_POST));
				}catch (\Exception $e){
					$error = $e->getMessage();
				}
			}
			
		}
		
		$engine = $this->make("\HMPP\Core\DisplayEngine");
		
		$destinationArray = $this->make("\HMPP\Core\Config")->get("destinations");
		$fromCompiled = "";
		$toCompiled = "";
		
		$todaysDestinations = $model->getAllBy("date",date("Y-m-d"));
		$lastDestination = null;
		if(!empty($todaysDestinations)){
			$lastDestination = array_pop($todaysDestinations);
		}
		
		$selectedString = "selected=\"selected\"";
		foreach($destinationArray as $destination => $routes){
			
			$fromSelected = "";
			$toSelected = "";
			
			if(!empty($error)){
				if(isset($_REQUEST['destination_from'])){
					if($destination == $_REQUEST['destination_from']){
						$fromSelected = $selectedString;
					}
				}
				
				if(isset($_REQUEST['destination_to'])){
					if($destination == $_REQUEST['destination_to']){
						$toSelected = $selectedString;
					}
				}
			}else{
				if(!empty($lastDestination)){
					if($lastDestination['to'] == $destination){
						$fromSelected = $selectedString;
					}
					else if($lastDestination['from'] == $destination){
						$toSelected = $selectedString;
					}
				}
			}
			
			
			
			$fromCompiled .= $engine->build("items/destination.tpl",[
					"id" => $destination,
					"name" => $destination,
					"routes" => json_encode($routes),
					"selected" => $fromSelected
			]);
			
			$toCompiled .= $engine->build("items/destination.tpl",[
					"id" => $destination,
					"name" => $destination,
					"selected" => $toSelected,
					"routes" => json_encode($routes)
			]);
		}
		
		$messageString = "";
		
		if(isset($_REQUEST['message'])){
			// Using htmlentities to ensure it can't be used for injection
			$message = htmlentities(urldecode($_REQUEST['message']));
			$messageString .= $engine->build("items/message.tpl",["message" => $message]);
		}
		if(isset($error)){
			$messageString .= $engine->build("items/error.tpl",["error" => $error]);
		}
		
		$engine->output(
				$engine->build("index.tpl",
					[
							"from_list" => $fromCompiled,
							"to_list" => $toCompiled,
							"messages" => $messageString,
							"account_balance" => number_format($model->getCardAmount(),2),
							"bus_destination" => $_POST['bus_destination'] ?? "",
							"transport_mode_0_selected" => !isset($_POST['transport_mode']) || $_POST['transport_mode'] == 0 ? "checked='checked'" : "",
							"transport_mode_1_selected" => isset($_POST['transport_mode']) && $_POST['transport_mode'] == 1 ? "checked='checked'" : ""
					]
				),
				"Let's go somewhere fun!"
		);
	}
}
// Launch index
(new Index($bootstrap))->main();



