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
	
	public function __construct()
	{
		if(!isset($_SESSION[$this->table])){
			$this->truncate();
		}
	}
	
	public function set(Int $id = 0, Array $data = array()){
		$id = (empty($id) ? uniqid($this->table,true) : $id);
		$data['id'] = $id;
		$_SESSION[$this->table][$id] = array_merge($this->columns, $data);
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
				$results[] = $item;
			}
		}
		return $results;
	}
	
	public function truncate(){
		$_SESSION[$this->table] = array();
	}
}