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
	
	/***
	 * Adds the $amount to the balance
	 * @param Float $amount
	 * @return Float
	 */
	public function topupCard(Float $amount = 0.00){
		$_SESSION['credit'] += $amount;
		return $this->getCardAmount();
	}
	
	/***
	 * Deducts the $amount from the balance
	 * @param Float $amount
	 * @return Float
	 * @throws \Exception
	 */
	public function deductFromCard(Float $amount = 0.00){
		if($_SESSION['credit'] - $amount >= 0){
			$_SESSION['credit'] -= $amount;
			return $this->getCardAmount();
		}
		
		$difference = ($amount - $_SESSION['credit']);
		
		throw new \Exception("You don't have enough credit to complete this transaction, please top-up by at-least &pound;".number_format($difference,2));
	}
	
	/***
	 * Returns the balance
	 * @return Float
	 */
	public function getCardAmount(){
		return floatval($_SESSION['credit']);
	}
}