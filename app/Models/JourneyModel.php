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
	protected $columns = array("id","from","to","mode","cost");

}