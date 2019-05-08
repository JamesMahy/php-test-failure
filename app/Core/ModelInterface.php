<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 08/05/2019
 * Time: 16:09
 */

namespace HMPP\Core;


interface ModelInterface
{
	/***
	 * Set's or updates the provided data in the database
	 * @param Int $id
	 * @param array $data
	 * @return mixed
	 */
	public function set(Int $id = 0, Array $data = array());
	
	/***
	 * Gets the requested data from the datastore
	 * @param Int $id
	 * @return mixed
	 * @throws \Exception
	 */
	public function get(Int $id = 0);
	
	/***
	 * Retrieves all the data from the data store
	 * @return mixed
	 */
	public function getAll();
	
	/***
	 * Retrieves all data that matches the provided key and value
	 * @param String $key
	 * @param String $value
	 * @return mixed
	 */
	public function getAllBy(String $key="", String $value="");
	
	/***
	 * Empties the data store.
	 * @return bool
	 */
	public function truncate();
}