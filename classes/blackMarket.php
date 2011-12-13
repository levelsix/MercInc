<?php
include_once 'ConnectionFactory.php';
class BlackMarket {
	
	private $id;
	private $requester_id;
	private $target_id;
	private $payment;
	private $is_complete;
		
	function __construct() {
	}
	
	public static function getBlackmarketItems( $userID )
	{
		$query = "SELECT ui.*,b.* FROM user_blackmarket_items ui LEFT JOIN 	blackmarket b ON (b.bm_id = ui.bm_id) WHERE ui.user_id = ?";
		$objAllPotOpps = ConnectionFactory::SelectRowsAsClasses($query, array($userID), __CLASS__);

		return $objAllPotOpps;
	}
	
	
}