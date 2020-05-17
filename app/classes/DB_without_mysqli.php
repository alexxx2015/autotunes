<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This is class provide access to the database
 *********************************************************************************/
include_once('default/views/filters/EscapeSQLQuery4DB.php');

class DB{
	
	private static $dbInstance;
	
	/**
	 * This 
	 * Enter description here ...
	 * @var unknown_type
	 */
	const DB_NAME = 'caradsv3';	
	const DB_USER = 'root';
	const DB_PW = 'root';
	const DB_HOST = 'localhost';
	
	private $connection;
	 
	private function __construct(){
	}
	
	/**
	 * This is the function which return the instance of a db object
	 */
	public static function getInstance(){
		if (self::$dbInstance == null){
			self::$dbInstance = new DB();
		}
		return self::$dbInstance;
	}
	
	/**
	 * This function connect to the database
	 */
	public function connect(){
		if($this -> connection == null){
			
			$this -> connection = mysql_connect(
				self::DB_HOST, 
				self::DB_USER, 
				self::DB_PW
			);
			mysql_select_db(self::DB_NAME);
		}
	}
	
	/**
	 * This function return the connection id
	 */
	public function getConnection(){
		return $this -> connection;
	}
	
	/**
	 * This function disconnect from the database
	 */
	public function disconnect(){
		mysql_close($this -> connection);
		$this->connection = null;
	}
	
	/**
	 * This function execute a SQL query.
	 * If it is a 'SELECT' query this function return an array with the result.
	 * The array has two dimension. The first dimension mark the number of the dataset.
	 * The second dimension contain the colum names
	 *
	 * In cases other than 'SELECT' this funcion return only the success of the SQL request
	 */
	function execQuery($p){	
		$return = false;		
		if(isset($p['q'])){
			if (isset($p['connect']) && ($p['connect'] == true)){
				
			}else{
				$this -> connect();
			}
			
			$query = $p['q'];
		
			//mysql_query('SET NAMES "utf8"', $connection);
			mysql_query('set character set utf8;', $this -> connection);
		
			/*
			 $result = mysql_query('SHOW VARIABLES LIKE "character_set%"');
			 $attr = mysql_num_fields($result);
			 for($i = 0; $i < mysql_num_rows($result); $i++){
			 for($j = 0; $j < $attr; $j++){
			 echo mysql_result($result, $i, mysql_field_name($result, $j)).'<br>';
			 }
			 }
			 */
			
			$result = mysql_query($query, $this -> connection);
			if($result != false){
				if(strtoupper(substr(ltrim($query), 0, 6)) == 'SELECT'){
					
					$return = array();
					$datasets = mysql_num_rows($result);
					$numberOfAttributes = mysql_num_fields($result);
						
					for($i = 0; $i < $datasets; $i++){
						$dataset = array();
		
						for($j = 0; $j < $numberOfAttributes; $j++){
							$nameOfAttribute = mysql_field_name($result, $j);
							$dataset[$nameOfAttribute] = mysql_result($result, $i, $nameOfAttribute);
						}
		
						array_push($return, $dataset);
					}
				}
				else if(strtoupper(substr(ltrim($query), 0, 6)) == 'INSERT'){
					$return = mysql_insert_id($this -> connection);
					if ($return == 0){
						$return = mysql_affected_rows($this -> connection);
					}
				}
				else{
					$return = true;
				}
			}
		
			if (isset($p['connect']) && ($p['connect'] == true)){
				
			}else{
				$this -> disconnect();
			}
		}
			
		if(isset($p['print'])){
			echo $p['q'];
		}
	
		return $return;
	}	
}
?>