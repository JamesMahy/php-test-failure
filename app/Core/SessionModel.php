<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 08/05/2019
 * Time: 15:39
 */

namespace HMPP\Core;

abstract class SessionModel implements ModelInterface {
	
	protected $table = "";
	protected $columns = array();
	
	/***
	 * SessionModel constructor.
	 * Creates the initial "table"
	 */
	public function __construct()
	{
		if(!isset($_SESSION[$this->table])){
			$this->truncate();
		}
	}
	
	/***
	 * Generates an ID to use in our session based data store
	 * @return int
	 */
	private function generateNextID(){
		// Get the keys
		$keys = array_keys($_SESSION[$this->table]);
		if(!sizeof($keys)){
			return 1;
		}
		
		// Sort them and return the last value + 1
		sort($keys);
		
		return array_pop($keys) + 1;
	}
	
	public function set(Array $data = array(), Int $id = 0){
		$id = (empty($id) ? $this->generateNextID() : $id);
		$data['id'] = $id;
		
		// Normalize our data to the data columns, only need this because we're playing with objects and sessions
		foreach($this->columns as $column){
			$data[$column] = $data[$column] ?? "";
		}
		
		$_SESSION[$this->table][$id] = $data;
	}
	
	public function get(Int $id = 0){
		if(isset($_SESSION[$this->table][$id])){
			return $_SESSION[$this->table][$id];
		}
		throw new \Exception("Record not found");
	}
	
	public function getAll(){
		return $_SESSION[$this->table];
	}
	
	public function getAllBy(String $key="", String $value=""){
		$results = array();
		foreach($_SESSION[$this->table] as $id => $item){
			if(isset($item[$key]) && $item[$key] == $value){
				$results[$key] = $item;
			}
		}
		return $results;
	}
	
	public function truncate(){
		$_SESSION[$this->table] = array();
	}
}