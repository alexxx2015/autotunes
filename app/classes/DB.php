<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This is class provide access to the database
 *********************************************************************************/
include_once('classes/System_Properties.php');
class DB{
	
	private static $dbInstance;
	
	/**
	 * This 
	 * Enter description here ...
	 * @var unknown_type
	 */
	
	const DB_HOST = 'localhost';
	/*test database access code
	const DB_NAME = 'autotunessql2';	
	const DB_USER = 'autotunessql2';
	const DB_PW = '23f23e9a';
	*/
	
	/* Database access codes*/
	const DB_NAME = 'autotunessql1';	
	const DB_USER = 'autotunessql1';
	const DB_PW = '1e449cac';//07261ff1';
	
	/*localhost	
	const DB_NAME = 'caradsv3';	
	const DB_USER = 'root';
	const DB_PW = 'root';
	*/
	//Froxlor Database access data
	const DB_FROXLOR_NAME = 'froxlor';
	const DB_FROXLOR_USER = 'froxlor';
	const DB_FROXLOR_PW = 'ysgdxEVmFyUuTxEpJW37';
	
	private $connection;
	
	private $mysqli;
	 
	private function __construct($p = null){
		/*
		$this -> mysqli = new mysqli(
			 	self::DB_HOST, 
			 	self::DB_USER, 
			 	self::DB_PW, 
			 	self::DB_NAME);
		if ($this -> connect_errno) {
			die('Connection to database failed!!!');
		}
		*/
		$this -> connect($p);
	}
	
	/**
	 * This is the function which return the instance of a db object
	 */
	public static function getInstance($p = array()){
		if (isset($p['NEW_INSTANCE']) && ($p['NEW_INSTANCE'] == true)){
			return new DB($p);
		}
		elseif (self::$dbInstance == null){
			self::$dbInstance = new DB($p);
		}
		return self::$dbInstance;
	}
	
	/**
	 * This function connect to the database
	 */
	private function connect($p = null){
		if ($this -> mysqli == null){
			
			$dbHost = self::DB_HOST;
			$dbUser = self::DB_USER;
			$dbName = self::DB_NAME;
			$dbPW = self::DB_PW;

			if (($p != null) && is_array($p) && isset($p['DB_HOST'])){
				$dbHost = $p['DB_HOST'];
			}

			if (($p != null) && is_array($p) && isset($p['DB_NAME'])){
				$dbName = $p['DB_NAME'];
			}

			if (($p != null) && is_array($p) && isset($p['DB_USER'])){
				$dbUser = $p['DB_USER'];
			}

			if (($p != null) && is_array($p) && isset($p['DB_PW'])){
				$dbPW = $p['DB_PW'];
			}
			
			$this -> mysqli = new mysqli(
					 	$dbHost
					 	, $dbUser
					 	, $dbPW
					 	, $dbName);
					 	
			if ($this -> mysqli -> connect_errno) {
				die('Connection to database failed!!!');
			}
		}
	}
	
	/**
	 * This function return the mysql instance
	 */
	public function getConnection(){
		return $this -> mysqli;
	}
	
	/**
	 * This function disconnect from the database
	 */
	private function disconnect(){
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
		if(isset($p['q']) && $this -> mysqli -> ping()){
			$query = $p['q'];
		
			//mysql_query('SET NAMES "utf8"', $connection);
			$this -> mysqli -> query('set character set utf8;');
		
			/*
			 $result = mysql_query('SHOW VARIABLES LIKE "character_set%"');
			 $attr = mysql_num_fields($result);
			 for($i = 0; $i < mysql_num_rows($result); $i++){
			 for($j = 0; $j < $attr; $j++){
			 echo mysql_result($result, $i, mysql_field_name($result, $j)).'<br>';
			 }
			 }
			 */
			
			
			$result = $this -> mysqli -> query($query);
			if($result != false){
				if(strtoupper(substr(ltrim($query), 0, 6)) == 'SELECT'){
					$return = array();
					while ($row = $result -> fetch_assoc()){
						array_push($return, $row);
					}
				}
				else if((strtoupper(substr(ltrim($query), 0, 6)) == 'INSERT')
						|| (strtoupper(substr(ltrim($query), 0, 6)) == 'UPDATE')){
					$return = $this -> mysqli -> insert_id;
					if ($return == 0){
						$return = $this -> mysqli -> affected_rows;
					}
				}
				else{
					$return = true;
				}
			}
		}
			
		if(isset($p['print'])){
			echo $p['q'];
		}
	
		return $return;
	}	
	
	public function escape($p){
		$ret = $p;
		if(is_array($ret)){
			$ret = $this -> filterArr($ret);
		}
		else{
			//remove HTML and PHP key words 
			$ret = strip_tags($ret);
			
			//protected SQL injection
			$ret = $this -> mysqli -> escape_string($ret);
		}
		return $ret;
	}
	
	public function real_escape($p){
		$ret = $p;
		if(is_array($ret)){
			$ret = $this -> filterArr($ret);
		}
		else{
			//remove HTML and PHP key words
			$ret = strip_tags($ret);
			
			//protected SQL injection
			$ret = $this -> mysqli -> real_escape_string($ret);
		}
		return $ret;
	}

	private function filterArr($p_arr){
		foreach($p_arr as $key => $val){
			if(is_array($val)){
				$this -> filterArr($val);
			}
			else{
				$p_arr[$key] = $this -> mysqli -> escape_string($val);
			}
		}
		return $p_arr;
	}
}
?>