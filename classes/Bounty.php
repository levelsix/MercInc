<?php
include_once 'ConnectionFactory.php';
class Bounty {
	
	private $id;
	private $requester_id;
	private $target_id;
	private $payment;
	private $is_complete;
		
	function __construct() {
	}
	
	public static function getBounty($bountyID)
	{
		$objBounty = ConnectionFactory::SelectRowAsClass("SELECT * FROM bounties where id = :bountyID", 
											array("bountyID" => $bountyID), __CLASS__);
		return $objBounty;
	}
	
	public static function getBountiesForUser($userID) {
		/*$query = "SELECT * FROM bounties JOIN users ON (bounties.target_id = users.id) WHERE bounties.is_complete = 0 AND bounties.target_id != ? ORDER BY rand() LIMIT 15";*/
		
			$query =  "SELECT SUM( payment ) AS bounty, bounties.target_id, bounties.is_complete, bounties.payment, bounties.target_id, bounties.requester_id,bounties.id, users.id ,users.name,users.level,users.type
						FROM  `bounties` 
						JOIN users ON ( bounties.target_id = users.id ) 
						WHERE bounties.is_complete =0
						AND bounties.target_id !=?
						AND target_id NOT IN (SELECT bounties.target_id FROM bounties WHERE bounties.requester_id = $userID)
						GROUP BY  `target_id` 
						LIMIT 15";
		
		$objBounties = ConnectionFactory::SelectRowsAsClasses($query, array($userID), __CLASS__);
		return $objBounties;
	}
	
	public static function getBountiesCountForUser($userID) {
		/*$query = "SELECT * FROM bounties JOIN users ON (bounties.target_id = users.id) WHERE bounties.is_complete = 0 AND bounties.target_id != ? ORDER BY rand() LIMIT 15";*/
		
			$query =  "SELECT SUM( payment ) AS bounty
						FROM  `bounties` 
						JOIN users ON ( bounties.target_id = users.id ) 
						WHERE bounties.is_complete =0
						AND bounties.target_id !=?
						AND target_id NOT IN (SELECT bounties.target_id FROM bounties WHERE bounties.requester_id = $userID)
						GROUP BY  `target_id` 
						LIMIT 15";
		
		$objBounties = ConnectionFactory::SelectRowsAsClasses($query, array($userID), __CLASS__);
		return $objBounties;
	}
	
	
	
	
	public static function disableBountyAfterUser($targetID)
	{
		//$query =  "UPDATE `bounties` set bounty.is_complete = 1 where target_id = ?";
		
		$params = array();
		$params['is_complete'] = 1;
	
		$conditions = array();
		$conditions['target_id'] = $targetID;
		
		$success = ConnectionFactory::updateTableRowRelativeBasic("bounties", $params, $conditions);
		if ($success) {
			return $success; 
		}
		return false;  
	}

	public static function createBounty($requester_id, $target_id, $payment) {
		$bountyparams = array();
		$bountyparams['requester_id'] = $requester_id;
		$bountyparams['target_id'] = $target_id;
		$bountyparams['payment'] = $payment;
		$justInsertID = ConnectionFactory::InsertIntoTableBasicReturnInsertID("bounties", $bountyparams);
		if ($justInsertID) {
			return self::getBounty($justInsertID);
		}
		return NULL;
	}
		
	public function getTargetID() {
		return $this->target_id;
	}
	
	public function getPayment() {
		return $this->payment;
	}
	
}