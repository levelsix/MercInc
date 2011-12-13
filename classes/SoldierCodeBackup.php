<?php
include_once 'ConnectionFactory.php';

Class SoldierCodeBackup {
	
	private $id;
	private $code;
	
	function __construct() {
		
		}
		
	public function insertCode($soldierCode) {
		$params['code'] = $soldierCode;
		$query = ConnectionFactory::InsertIntoTableBasic("soldier_code_backup",$params);		
		return $query;		
	}
	public function getSoldierCode () {
		$soldierCode = ConnectionFactory::SelectRowsAsClasses("SELECT * FROM soldier_code_backup LIMIT 0,1",
											array(), __CLASS__);
		
		return $soldierCode;							
	}
	public function deleteCode($code) {
		$status = ConnectionFactory::DeleteRowFromTable("soldier_code_backup", array(code=>$code));
		return $status;
	}
	public function backupCodeExist($code) {
		$statement = ConnectionFactory::SelectAsStatementHandler(
		"SELECT * FROM soldier_code_backup WHERE code = ?", 
		array($code));
		$count = $statement->rowCount();	
		if($count == 0){
			return false;
		}
		else {
			return true;
		}
		
	}
};
?>