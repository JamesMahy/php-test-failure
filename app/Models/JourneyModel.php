<?php
/**
* Created by PhpStorm.
* User: james
* Date: 08/05/2019
* Time: 15:38
*/

namespace HMPP\Models;

use \HMPP\Core\SessionModel;
class JourneyModel extends SessionModel {

	protected $table = "journeys";
	protected $columns = array("id","date","time", "from","to","mode","fare");
	
	public function __construct()
	{
		if(!isset($_SESSION['credit'])){$_SESSION['credit'] = 0.00;}
	}
	
	public function topupCard(Float $amount = 0.00){
		$_SESSION['credit'] += $amount;
		return $this->getCardAmount();
	}
	
	public function deductFromCard(Float $amount = 0.00){
		if($_SESSION['credit'] - $amount >= 0){
			$_SESSION['credit'] -= $amount;
			return $this->getCardAmount();
		}
		
		$difference = ($amount - $_SESSION['credit']);
		
		throw new \Exception("You don't have enough credit to complete this transaction, please top-up by at-least &pound;".number_format($difference,2));
	}
	
	public function getCardAmount(){
		return $_SESSION['credit'];
	}
	
	public function handleJourney(Array $data=[]){
		
		$mode = $data['transport_mode'] ?? 0;
		$fare = 0.00;
		
		$config = \HMPP\Bootstrap::getInstance()->make("\HMPP\Core\Config");
		
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
		
		
		$this->deductFromCard($fare);
		
		$journeyData['fare'] = $fare;
		$this->set($journeyData);
		
		return $journeyData;
		
	}
	
}