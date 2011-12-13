<?php
include_once 'ConnectionFactory.php';

Class SoldierCode {
	
	private $id;
	private $code;
	
	function __construct() {
		
		}
		
	public function insertCode($soldierCode) {
		$params['code'] = $soldierCode;
		$query = ConnectionFactory::InsertIntoTableBasic("soldier_code",$params);		
		if($query == 1)
		{
			$backupSoldierCode = new SoldierCodeBackup();
			if(!$backupSoldierCode->backupCodeExist($soldierCode))
			{
				if($backupSoldierCode->insertCode($soldierCode))
				{
					return true;
				}
				else
				{
					deleteCode($soldierCode);
					return false;
				}
				
			}
			else 
			{
				deleteCode($soldierCode);
				return false;
			}			
		}
		else{
			return false;
		}
		
	}
	public static function getSoldierCode () {
		$soldierCode = ConnectionFactory::SelectRowsAsClasses("SELECT code FROM soldier_code LIMIT 0,1",
											array(), __CLASS__);
		
		return $soldierCode;							
	}
	public function deleteCode($code) {
		$status = ConnectionFactory::DeleteRowFromTable("soldier_code", array('code'=>$code));
		return $status;
	}
	public function codeExist($code) {
		$statement = ConnectionFactory::SelectAsStatementHandler(
		"SELECT * FROM soldier_code WHERE code = ?", 
		array($code));
		$count = $statement->rowCount();	
		if($count == 0){
			return false;
		}
		else {
			return true;
		}
		
	}
	public function getCode(){
		return $this->code;
	}
};
?>