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
		
		// The post handler
		if(isset($_POST) && !empty($_POST)){
			if(isset($_POST['topup_amount'])){
				// Handles topups
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
				// Handles the Journey
				try{
					$journeyData = $this->handleJourney($_POST);
					return 	Header("Location: /?message=Welcome to ".$journeyData['to']."&".http_build_query($_POST));
				}catch (\Exception $e){
					$error = $e->getMessage();
				}
			}
		}
		
		$engine = $this->make("\HMPP\Core\DisplayEngine");
		
		// Gets available destinations;
		$destinationArray = $this->make("\HMPP\Core\Config")->get("destinations");
		$fromCompiled = "";
		$toCompiled = "";
		
		// If we've visited anywhere, we want to keep track for UX
		$todaysDestinations = $model->getAllBy("date",date("Y-m-d"));
		$lastDestination = null;
		if(!empty($todaysDestinations)){
			$lastDestination = array_pop($todaysDestinations);
		}
		
		$selectedString = "selected=\"selected\"";
		// Creates the dropdown lists and selects the correct item if there is an error
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
				// If we last travelled somewhere, may as well start there!
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
		
		// Have we been passed any messages to parse and display?
		$messageString = "";
		if(isset($_REQUEST['message'])){
			// Using htmlentities to ensure it can't be used for injection
			$message = htmlentities(urldecode($_REQUEST['message']));
			$messageString .= $engine->build("items/message.tpl",["message" => $message]);
		}
		if(isset($error)){
			$messageString .= $engine->build("items/error.tpl",["error" => $error]);
		}
		
		// Output the display
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
	
	/***
	 * Handles the journey post and saves the information to the database.
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function handleJourney(Array $data=[]){
		
		$mode = $data['transport_mode'] ?? 0;
		$fare = 0.00;
		
		$config = $this->make("\HMPP\Core\Config");
		
		$journeyData = array(
				"mode" => $mode,
				"date" => date("Y-m-d"),
				"time" => date("H:i")
		);
		
		if($mode == 1){
			$fare = $config->get("zone_fares\\bus");
			
			if(!isset($data['bus_destination']) || empty($data['bus_destination'])){
				throw new \Exception("You didn't tell us where you'd like to take the bus!");
			}
			
			$journeyData['to'] = $data['bus_destination'];
		}
		else{
			
			$start = $data['destination_from'] ?? 0;
			$finish = $data['destination_to'] ?? 0;
			
			if(empty($start) || empty($finish)){
				throw new \Exception("Please provide a destination from and to");
			}
			else if($start == $finish){
				throw new \Exception("You can't travel to the same location silly");
			}else {
				$journeyData['from'] = $data['destination_from'];
				$journeyData['to'] = $data['destination_to'];
				
				$from = $config->get("destinations\\" . $start);
				$to = $config->get("destinations\\" . $finish);
				
				$zones = array_merge($from, $to);
				sort($zones);
				
				
				$minFrom = min($from);
				$minTo = min($to);
				
				$maxFrom = max($from);
				$maxTo = max($to);
				
				$numberOfZones = 0;
				
				if ($minFrom != 1 || $minTo != 1){
					if ($maxFrom > $maxTo) {
						$numberOfZones = $maxFrom - $maxTo;
					} elseif ($maxFrom < $maxTo) {
						$numberOfZones = $maxTo - $maxFrom;
					}
				}
				
				$numberOfZones++;
				
				if($numberOfZones){
					if($numberOfZones == 1){
						if($minFrom == 1 && $minTo == 1){
							$fare = $config->get("zone_fares\\zone_1");
						}else{
							$fare = $config->get("zone_fares\\outside_zone_1");
						}
					}
					else if($numberOfZones == 2){
						if($minFrom == 1 || $minTo == 1){
							$fare = $config->get("zone_fares\\two_zones_inc_zone_1");
						}else{
							$fare = $config->get("zone_fares\\two_zones_exc_zone_1");
						}
					}else{
						$fare = $config->get("max_fare");
					}
				}
			}
			
		}
		
		$model = $this->make("\HMPP\Models\JourneyModel");
		$model->deductFromCard($fare);
		
		$journeyData['fare'] = $fare;
		$model->set($journeyData);
		
		return $journeyData;
		
	}
}
// Launch index
(new Index($bootstrap))->main();



