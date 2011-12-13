<?php
include_once 'Utils.php';

class ConnectionFactory{

	/*
	 * http://www.karlrixon.co.uk/articles/sql/update-multiple-rows-with-different-values-and-a-single-sql-query/
	 * http://dev.mysql.com/doc/refman/5.0/en/insert-on-duplicate.html
	 * */

	private static $factory;
	private $db_name;
	private $db_user;
	private $db_pass;
	private $db_host;
	private $db;
    public static $log = array();

	function __construct() {
		
	}
	public static function getFactory() {            
            if (!self::$factory)
		self::$factory = new ConnectionFactory();
		return self::$factory;
	}
	
	//TODO: make this private after refactoring, should only be called in this class
	//should be private static?
	public function getConnection() {   
		if (!$this->db) {
			try {
				//include $_SERVER['DOCUMENT_ROOT'] . '/properties/dbproperties.php';
				include $_SERVER['DOCUMENT_ROOT'] . '/lvl6_qa/properties/dbproperties.php';
				$this->db = new PDO("mysql:host=$server;dbname=$database", $user, $password);
				//$this->db = new PDO("mysql:host=localhost;dbname=mercernaryinc", 'root', '');
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			} catch (PDOException $e) {
				// Redirect to 'server down' page
                echo $e;
                die("test");
//				include $_SERVER['DOCUMENT_ROOT'] . '/properties/serverproperties.php';
//				header("Location: " . $serverRoot . "/errorpage.html");
				exit;
			}
		}
		return $this->db;
	}
	/*
	 * returns the value of a single column for a single row
	 */
	public static function SelectValue($retrieveValue, $tablename, $conditions) {		
                $mydb = self::getFactory()->getConnection();

		$values = array();
		$condclauses = array();
		foreach($conditions as $key=>$value) {
			$condclauses[] = $key."=?";
			$values[] = $value;
		}

		$stmtString = "SELECT ". $retrieveValue . " FROM " . $tablename . " WHERE ";
		$stmtString .= getArrayInString($condclauses, 'and');

		$stmt = $mydb->prepare($stmtString);
		$start = microtime(true);  
		$stmt->execute($values);
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));     
		return $stmt->fetchColumn();
                printLog();
	}
	/**
	 * Given a simple query executes it and return the result set ...
	 * @param unknown_type $query
	 */
	public static function executeQuerySimple($query) {
		
		$mydb = self::getFactory()->getConnection();
		$result = $mydb->query($query);
                $start = microtime(true);  
		$result = $mydb->query($query);
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $query,
				                  'time' => round($time * 1000, 3));
                
		return $result->fetchColumn();
	}
	public static function getAssociativeArray($query) {
		$mydb = self::getFactory()->getConnection();
                $start = microtime(true);  
		$result = $mydb->query($query);
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $query,
				                  'time' => round($time * 1000, 3));		
		return $result;
	}
	/*
	 * returns a row as a class using PDO's FETCH_CLASS option
	 */
	public static function SelectRowAsClass($query, $values, $className) {	
                $mydb = self::getFactory()->getConnection();
		$sth = $mydb->prepare($query);
		$sth->execute($values);
                    
		$sth->setFetchMode(PDO::FETCH_CLASS, $className);
		$obj = $sth->fetch();
		return $obj;
	}
	/*
	 * returns several rows as a class using PDO's FETCH_CLASS option
	 */
	public static function SelectRowsAsClasses($query, $values, $className) {
		$mydb = self::getFactory()->getConnection();
		$sth = $mydb->prepare($query);
		$sth->execute($values);

		$sth->setFetchMode(PDO::FETCH_CLASS, $className);
		$objs = $sth->fetchAll();
		return $objs;
	}
	/*trying sth new
	 public static function SelectRowsAsClasses($tablename, $desiredFields, $conditions) {
		$values = array();
		$condclauses = array();
		foreach($conditions as $key=>$value) {
		$condclauses[] = $key."=?";
		$values[] = $value;
		}
		$query = createSelectQuery($tablename, $desiredFields, $condclauses, $values);

		echo $query;

		$mydb = self::getFactory()->getConnection();
		$sth = $mydb->prepare($query);
		$sth->execute($values);

		$sth->setFetchMode(PDO::FETCH_CLASS, $className);
		$objs = $sth->fetchAll();
		return $objs;
		}

		private function createSelectQuery($tablename, $desiredFields, $condclauses, $values) {
		$query = "SELECT ";
		if ($params) {
		$query .= getArrayInString($desiredFields, ',');
		} else {
		$query .= "* ";
		}
		$query .= "from " . $tablename . " where ";
		$query .= getArrayInString($condclauses, " OR ");
		return $query;
		}
		*/

	/*
	 * returns a PDO statement handler using the given query
	 */
	public static function SelectAsStatementHandler($query, $values) {
		$mydb = self::getFactory()->getConnection();
		$stmt = $mydb->prepare($query);
		
                $start = microtime(true);  
		$stmt->execute($values);
                $time = microtime(true) - $start;                  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		return $stmt;
	}

	/*
	 * returns the result of a query as an associative array where the keys
	 * are column names and the values are the column values
	 */
	public static function SelectRowAsAssociativeArray($query, $values) {
		$sth = ConnectionFactory::SelectAsStatementHandler($query, $values);
		return $sth->fetch();
	}

	/*
	 * updates a row with relative values, i.e. health = health + 10
	 * $params and $conditions should be associative arrays from column names to values
	 */
	public static function updateTableRowRelativeBasic($tablename, $params, $conditions) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?
		$values = array();
		$setclauses = array();
		foreach($params as $key=>$value) {
			$setclauses[] = $key . "=" . $key . "+?";
			$values[] = $value;
		}
		$condclauses = array();
		foreach($conditions as $key=>$value) {
			$condclauses[] = $key."=?";
			$values[] = $value;
		}
		$stmtString = "UPDATE ". $tablename . " SET ";
		$stmtString .= getArrayInString($setclauses, ',') . " WHERE ";
		$stmtString .= getArrayInString($condclauses, 'and');
		$stmt = $mydb->prepare($stmtString);
		
                $start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}

	/*
	 * updates several rows with id equal to any of the ids in the array $IDs
	 * the column name for the WHERE condition must be "id"
	 * updates with relative values, i.e. health = health + 10
	 * $params and $conditions should be associative arrays from column names to values
	 */
	public static function updateTableRowsRelativeOnIDs($tablename, $params, $IDs) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?

		$values = array();

		$setclauses = array();
		foreach($params as $key=>$value) {
			$setclauses[] = $key . "=" . $key . "+?";
			$values[] = $value;
		}

		$condclauses = array();
		foreach($IDs as $key=>$value) {
			$condclauses[] = "id=?";
			$values[] = $value;
		}

		$stmtString = "UPDATE ". $tablename . " SET ";
		$stmtString .= getArrayInString($setclauses, ',') . " WHERE ";
		$stmtString .= getArrayInString($condclauses, 'OR');
			
		$stmt = $mydb->prepare($stmtString);

		$start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}


	/*
	 * updates a row with absolute values, i.e. health = 10
	 * $params and $conditions should be associative arrays from column names to values
	 */
	public static function updateTableRowAbsoluteBasic($tablename, $params, $conditions) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?

		$values = array();

		$setclauses = array();
		foreach($params as $key=>$value) {
			$setclauses[] = $key . "=?";
			$values[] = $value;
		}

		$condclauses = array();
		foreach($conditions as $key=>$value) {
			$condclauses[] = $key."=?";
			$values[] = $value;
		}

		$stmtString = "UPDATE ". $tablename . " SET ";
		$stmtString .= getArrayInString($setclauses, ',') . " WHERE ";
		$stmtString .= getArrayInString($condclauses, 'and');

		$stmt = $mydb->prepare($stmtString);                  
		
                $start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}

	/*
	 * Update a row in a table with both absolute and relative values
	 * i.e. combines the functionality of updateTableRowRelative and updateTableRowAbsolute
	 * $absParams, $relParams, and $conditions should be associative arrays from column names to values
	 */
	public static function updateTableRowGenericBasic($tablename, $absParams, $relParams, $conditions) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?

		$values = array();

		$absSetClauses = array();
		foreach($absParams as $key=>$value) {
			$absSetClauses[] = $key . "=?";
			$values[] = $value;
		}

		$relSetClauses = array();
		foreach($relParams as $key=>$value) {
			$relSetClauses[] = $key . "=" . $key . "+?";
			$values[] = $value;
		}

		$condclauses = array();
		foreach($conditions as $key=>$value) {
			$condclauses[] = $key."=?";
			$values[] = $value;
		}

		$stmtString = "UPDATE ". $tablename . " SET ";
		$stmtString .= getArrayInString($absSetClauses, ',') . ", " . getArrayInString($relSetClauses, ',');
		$stmtString .= " WHERE ";
		$stmtString .= getArrayInString($condclauses, 'and');

		$stmt = $mydb->prepare($stmtString);

		$start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}
	public static function InsertIntoTableMultipleRows($tablename,$values)
	{
		$mydb = self::getFactory()->getConnection();
		$query = "INSERT INTO ".$tablename."VALUES".$values;
		return $mydb->execute();
	}
	/*
	 * $params should be an associative array from column names to values
	 * used for basic inserts
	 * returns success or failure
	 */
	public static function InsertIntoTableBasic($tablename, $params) {
		$mydb = self::getFactory()->getConnection();
		$questions = array();
		$keys = array();
		$values = array();
		foreach($params as $key=>$value) {
			$keys[] = $key;
			$values[] = $value;
			$questions[] = '?';
		}

		$stmtString = "INSERT INTO ". $tablename . "(";
		$stmtString .= getArrayInString($keys, ',') . ") VALUES (";
		$stmtString .= getArrayInString($questions, ',') . ")";
		
		$stmt = $mydb->prepare($stmtString);
		
		$start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}
	/*
	 * $params should be an associative array from column names to values
	 * used for basic insert ignore
	 * returns success or failure
	 */
	public static function InsertIgnoreIntoTableBasic($tablename, $params) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?

		$questions = array();
		$keys = array();
		$values = array();
		foreach($params as $key=>$value) {
			$keys[] = $key;
			$values[] = $value;
			$questions[] = '?';
		}

		$stmtString = "INSERT IGNORE INTO ". $tablename . "(";
		$stmtString .= getArrayInString($keys, ',') . ") VALUES (";
		$stmtString .= getArrayInString($questions, ',') . ") ";


		$stmt = $mydb->prepare($stmtString);

		$start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}
	/*
	 * executes an insert into statement and returns the last insert ID
	 * $params should be an associative array from column names to values
	 */
	public static function InsertIntoTableBasicReturnInsertID($tablename, $params) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?

		$questions = array();
		$keys = array();
		$values = array();
		foreach($params as $key=>$value) {
			$keys[] = $key;
			$values[] = $value;
			$questions[] = '?';
		}

		$stmtString = "INSERT INTO ". $tablename . "(";
		$stmtString .= getArrayInString($keys, ',') . ") VALUES (";
		$stmtString .= getArrayInString($questions, ',') . ")";


		$stmt = $mydb->prepare($stmtString);

		$success = $stmt->execute($values);
		if ($success) {
			return $mydb->lastInsertId();
		}
		return 0;
	}
	/*
	 * executes an insert into ... on duplicate key update SQL statement
	 * $params should be an associative array from column names to values
	 * $columnUpdate is the column to update if there is a duplicate key
	 * $updateQuantity is the value to update to (relative, NOT absolute)
	 */
	public static function InsertOnDuplicateKeyUpdate($tablename, $params, $columnUpdate, $updateQuantity) {
		$mydb = self::getFactory()->getConnection();

		$questions = array();
		$keys = array();
		$values = array();
		foreach($params as $key=>$value) {
			$keys[] = $key;
			$values[] = $value;
			$questions[] = '?';
		}
		$values[] = $updateQuantity;
		$stmtString = "INSERT INTO ". $tablename . "(";
		$stmtString .= getArrayInString($keys, ',') . ") VALUES (";
		$stmtString .= getArrayInString($questions, ',') . ") ";
		$stmtString .= "ON DUPLICATE KEY UPDATE ";
		$stmtString .= $columnUpdate."=".$columnUpdate."+?";

                $stmt = $mydb->prepare($stmtString);
		$start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}
	public static function InsertOnDuplicateKeyUpdateAbsolute($tablename, $params, $columnUpdate, $updateQuantity) {
		$mydb = self::getFactory()->getConnection();
		$questions = array();
		$keys = array();
		$values = array();
		foreach($params as $key=>$value) {
			$keys[] = $key;
			$values[] = $value;
			$questions[] = '?';
		}
		$values[] = $updateQuantity;
		$stmtString = "INSERT INTO ". $tablename . "(";
		$stmtString .= getArrayInString($keys, ',') . ") VALUES (";
		$stmtString .= getArrayInString($questions, ',') . ") ";
		$stmtString .= "ON DUPLICATE KEY UPDATE ";
		$stmtString .= $columnUpdate." = ?";
		
                $stmt = $mydb->prepare($stmtString);
		
                $start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}

	/*
	 * deletes all rows in a table where the quantity <= 0
	 * the column name must be "quantity"
	 */
	public static function DeleteZeroAndBelowQuantity($tablename) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?
		$stmtString = "DELETE FROM ". $tablename . " WHERE quantity<=?";
		$stmt = $mydb->prepare($stmtString);
		return $stmt->execute(array(0));
	}

	/*
	 * deletes a row from a table
	 * $conditions should be an associative array from column names to values
	 */
	public static function DeleteRowFromTable($tablename, $conditions) {
		$mydb = self::getFactory()->getConnection();
		//TODO: after refactor, just eliminate getFactory, change getConnection to static, and call that?
		$stmtString = "DELETE FROM ". $tablename . " WHERE ";
		$condclauses = array();
		foreach($conditions as $key=>$value) {
			$condclauses[] = $key."=?";
			$values[] = $value;
		}
		$stmtString .= getArrayInString($condclauses, 'and');

		$stmt = $mydb->prepare($stmtString);
		$start = microtime(true);  
                $result = $stmt->execute($values);                                
                $time = microtime(true) - $start;  
                self::$log[] = array('query' => $stmt->queryString,
				                  'time' => round($time * 1000, 3));   
                return $result;
	}
	public static function DeleteRowFromTableComplex($query) {
		$mydb = self::getFactory()->getConnection();
		$result = $mydb->exec($query);
		
	}
        public function logQuery() {
            $start = microtime(true);  
            $result = $this->statement->execute();  
            $time = microtime(true) - $start;  
        }
        public static function printLog() {
		$totalTime = 0;
		$message; 
                $message ='<table border=1><tr><th>Query</th><th>Time (ms)</th></tr>';
		foreach(self::$log as $entry) {
			$totalTime += $entry['time'];
			$message .= '<tr><td>' . $entry['query'] . '</td><td>' . $entry['time'] . '</td></tr>\n';
		}
		$message .= '<tr><th>' . count(self::$log) . ' queries</th><th>' . $totalTime . '</th></tr>\n';
		$message .= '</table>';              
                echo $message;
                self::logFile($message);
	}
    public static function logFile($message) {
        $fp = fopen('log.txt', 'a');      
        fwrite($fp,$message);       
        fclose($fp);        
    }
}
?>