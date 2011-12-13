<?php
include_once 'ConnectionFactory.php';
include_once 'SoldierCode.php';
include_once 'Utils.php';
class User {
	
	private $id;
        private $udid;
   	private $soldier_code;
	private $name;
	private $level;
	private $type;
	private $attack;
	private $defense;
	private $bank_balance;
	private $cash;
	private $experience;
	private $stamina;
	private $energy;
	private $skill_points;
	private $health;
	private $missions_completed;
	private $fights_won;
	private $fights_lost;
	private $kills;
	private $deaths;
	private $income;
	private $upkeep;
	private $health_max;
	private $energy_max;
	private $stamina_max;
	private $agency_code;
	private $agency_size;
	private $last_login;
	private $num_consecutive_days_played;
	private $diamonds;	
	private $num_attacks;
	private $sound_settings;	
	private $vibration_settings;	
	private $comment_settings;
        private $splash_settings;
	private $notification_settings;
        private $device_os;
        private $next_level_experince_points;
        private $c2dm_token;

	function __construct() {
	}
	
	public static function getMissionExists($userID)
	{
		$objData = ConnectionFactory::SelectRowAsClass("SELECT * FROM users where id = ?",
		array($userID), __CLASS__);
		return $objData;
	}
        /*
	 * Returns a user
	 */
	public static function getUser($userID)
	{
		$objUser = ConnectionFactory::SelectRowAsClass("SELECT * FROM users where id = :userID", 
											array("userID" => $userID), __CLASS__);
		return $objUser;
	}
	
	
	public static function getUserName($userID)
	{
		$conditions = array();
		$conditions['id'] = $userID;
		$objUser = ConnectionFactory::SelectValue("name", "users", $conditions);
		return $objUser;
	}
	
	
    /*
	 * Returns user by UUID
	*/
	public static function getUdidUser($uuID)
	{
		$objUser = ConnectionFactory::SelectRowAsClass("SELECT * FROM users where udid = :uuID",
											array("uuID" => $uuID), __CLASS__);
		return $objUser;
	}


	/*
	 * Returns an array of users in agency. currently loops through statementhandler. better way?
	 */
	public static function getUsersInAgency($userID) {
		$agencySth = ConnectionFactory::SelectAsStatementHandler(
		"SELECT * FROM agencies WHERE (user_one_id = ? OR user_two_id = ?) AND accepted = 1", 
		array($userID, $userID));
		
		$agencySize = $agencySth->rowCount();
		$userIDs = array();
		while ($row = $agencySth->fetch(PDO::FETCH_ASSOC)) {
			$agentID = $row["user_one_id"];
			if ($agentID == $userID) 
				$agentID = $row["user_two_id"];
			array_push($userIDs, $agentID);
		}
		
		return self::getUsers($userIDs);
	}
	/*
	 * verify user belongs to same agaency in which we add bounty
	 */
	public static function checkSameUsersInAgency($userID ,  $targetID) {
		//echo $userID ;
		//echo '<br />';
		//echo  $targetID;
		//return;
                 $query = "SELECT *  FROM agencies  WHERE  (user_one_id =$userID OR user_two_id =$userID )  AND ( ( user_one_id = $targetID OR user_two_id = $targetID ) ) AND accepted =1";
		
		$agencySth = ConnectionFactory::SelectAsStatementHandler($query, array($userID , $targetID));
		
		//echo "<pre>";
		//var_dump($agencySth);
		$agencySth->rowCount();
		
		if($agencySth->rowCount() < 1)
		{
			//echo "i am empty";
			return false;
		} 
		else {
			//echo "i am not empty";
			return true;

		}
		
		/*if(count($agencySth) < 1)
		{
			return false;
		}
		return true;*/
	}
	
	/**
	 * Delete user from agency
	 * @param the user you want to delete $otherUserId
	 * @param your id $myID
	 * @return success/failure;
	 */
	public static function deleteFromAgency($otherUserId,$myID) {
		$query = "DELETE from agencies Where user_one_id = $otherUserId AND user_two_id = $myID OR user_one_id = $myID AND user_two_id = $otherUserId ";
		$result = ConnectionFactory::DeleteRowFromTableComplex($query);
		return $result;
	}
	/**
	 * Decrement the user agency size when user kicks some 1 from his agency	
	 * @param $userID
	 * @return boolean
	 */
	public static function decrementUserAgencySize($userID) {
		
		$user = User::getUser($userID);
		//die($user->getAgencySize());
		$new_agency_size = ($user->getAgencySize() - 1);
		$agency_params = array();
		$agency_params['agency_size'] = $new_agency_size;
		$conditions = array();
		$conditions['id'] = $user->getID();
		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $agency_params, $conditions);
		return $success;
	}

	public static function updateFirstMissionData($userID, $cash, $points, $energy){
			$user = User::getUser($userID);
		//die($user->getAgencySize());
		$agency_params = array();
		$agency_params['energy'] = $user->getEnergy() - $energy;
		$agency_params['experience'] = $user->getExperience() + $points;
		$agency_params['cash'] = $user->getCash() + $cash;
		$conditions = array();
		$conditions['id'] = $user->getID();
		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $agency_params, $conditions);
		return $success;
	}

	public static function updateFirstBattleData($userID, $cash, $winner_demage, $points,$fistBattleVal=0,$next_level_experince_points=0){
			$user = User::getUser($userID);
		//die($user->getAgencySize());
		$agency_params = array();
		
		//echo "OLD CASH = ".$user->getCash();
		$user->setUserCash($cash);
		//echo "NEW CASH = ".$user->getCash();
		
		$user->setUserExp($points);
		
                            $agency_params['cash'] = $user->getCash();
                            $agency_params['health'] = $user->getHealth() + $winner_demage;
                            $agency_params['experience'] = $user->getExperience();
                             if($fistBattleVal == 1){
                                    $agency_params['is_first_battle'] = 1;
									checkLevelUp($user);
									
									/*if($user->getLevel()<4){
										$agency_params['experience'] = $user->getExperience() + $points;
										$agency_params['level'] =  $user->getLevel() + 1;
										$agency_params['next_level_experince_points'] =  $next_level_experince_points;
									} else {
										
									}*/
                             }
		$conditions = array();
		$conditions['id'] = $user->getID();
		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $agency_params, $conditions);
		return $points;
	}
	
	
	
	public function addDiamonds($diamonds,$userID){
		$agency_params['diamonds'] = $diamonds;
		$conditions['id'] = $userID;
		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $agency_params, $conditions);
	}

	/*
	 * returns an associative array where the keys are city IDs and the values
	 * are the mission rank that is available to the user (i.e. rank 1, 2, 3)
	 */
	public static function getAvailableCityIDsToRankAvail($userID) {
        $query = "SELECT * from users_cities WHERE rank_avail > 0 AND user_id =".$userID;
		$citySth = ConnectionFactory::SelectAsStatementHandler($query, array($userID));
		$cityIDsToRankAvail = array();
		while ($row = $citySth->fetch(PDO::FETCH_ASSOC)) {
			$cityID = $row['city_id'];
			$cityIDsToRankAvail[$cityID] = $row['rank_avail'];
		}
		return $cityIDsToRankAvail;
	}
	/*
	 * returns an array of users given an array of user IDs
	 */
	public static function getUsers($userIDs) {
		if (count($userIDs) <= 0) {
			return array();
		}
		
		$condclauses = array();
		$values = array();
		foreach($userIDs as $key=>$value) {
			array_push($condclauses, "id=?");
			array_push($values, $value); 		
		}
		$query = "SELECT * from users where ";
		$query .= getArrayInString($condclauses, ' OR ');
		
		$objUsers = ConnectionFactory::SelectRowsAsClasses($query, $values, __CLASS__);
		return $objUsers;		
	}

        public static function getUsersWithNoSoldierCode(){
            $query = "Select id, soldier_code FROM users Where soldier_code = :SCode";
            return ConnectionFactory::SelectRowsAsClasses($query, array("SCode" => ''), __CLASS__);
            
        }
	/*
	 * updates user settings created by @waseem safder[19/10/2011]
	 */
	public static function updateUserSettings($soundSettings, $vibrationSettings, $commentSettings, $notificationSettings, $splashSettings, $userID ){
		$result = ConnectionFactory::updateTableRowAbsoluteBasic("users",array('sound_settings' => $soundSettings,'vibration_settings'=>$vibrationSettings,'comment_settings'=>$commentSettings , 'notification_settings'=>$notificationSettings, 'splash_settings'=>$splashSettings),array('id' => $userID));
		if($result)
		{
			return true;
		}
		return false;
	}

        public static function updateUserStamina($userID, $userStamina){
		$result = ConnectionFactory::updateTableRowAbsoluteBasic("users",array('stamina' => $userStamina),array('id' => $userID));
		if($result)
		{
			return true;
		}
		return false;
	}

        public static function updateUserHealth($userID, $userHealth){
		$result = ConnectionFactory::updateTableRowAbsoluteBasic("users",array('health' => $userHealth),array('id' => $userID));
		if($result)
		{
			return true;
		}
		return false;
	}

        public static function updateUserEnergy($userID, $userEnergy){
		$result = ConnectionFactory::updateTableRowAbsoluteBasic("users",array('energy' => $userEnergy),array('id' => $userID));
		if($result)
		{
			return true;
		}
		return false;
	}
        
        public static function updateUserIncome($userID, $updatedCash){
		$result = ConnectionFactory::updateTableRowAbsoluteBasic("users",array('cash' => $updatedCash),array('id' => $userID));
		if($result)
		{
			return true;
		}
		return false;
	}
	/*
	 * create battle history created by @waseem safder[21/10/2011]
	 */
	
	public static function battleHistory($userID , $targetId, $healthLoss, $won ,$cashLost=0 ) {
		$userparams = array();
		$userparams['user_id'] = $userID;
		$userparams['opponent_id'] = $targetId;
		$userparams['damage_taken'] = $healthLoss;
		$userparams['won'] = $won;
		$userparams['cash_lost'] = $cashLost;
		$justAddedID = ConnectionFactory::InsertIntoTableBasicReturnInsertID("battle_history", $userparams);
		if ($justAddedID) {
			return true; 
		}
		return false;
	}
	
	/*
	 * create get battle history created by @waseem safder[21/10/2011]
	 */
	public static function getBattleHistory($userID) {
		
//		$query = "SELECT battle_history.id as battle_id,user_id, opponent_id,damage_taken ,won ,cash_lost, users.name as rival_name FROM battle_history,users where battle_history.opponent_id = $userID GROUP BY opponent_id ORDER BY date DESC  LIMIT 10";

		$query = "SELECT  id as battle_id,user_id,won,bounty,damage_taken,cash_lost,exp_gained,date,opponent_id FROM battle_history where opponent_id = $userID AND opponent_id != user_id  ORDER BY date DESC  LIMIT 10";
		
		$objItems = ConnectionFactory::SelectRowsAsClasses($query, array($userID),__CLASS__);
		return $objItems;
	}
	
	/*
	 * creates a user with the given username
	 */
	public static function createUser($name) {
		$userparams = array();
		$userparams['name'] = $name;
		$justAddedID = ConnectionFactory::InsertIntoTableBasicReturnInsertID("users", $userparams);
		if ($justAddedID) {
			$usercitiesparams = array();
                        $success = '';

                        /*
                         * Inserting User cities with rank
                         * Currently we are dealing with 4 cities so loop iterates 4 times
                         */
                        for($i = 1; $i <= 4; $i++){
			$usercitiesparams['user_id'] = $justAddedID;
                            $usercitiesparams['city_id'] = $i;
                            $usercitiesparams['rank_avail'] = 1;
                            $success = ConnectionFactory::InsertIntoTableBasic("users_cities", $usercitiesparams);
                        }
                        $usertimerparams['user_id'] = $justAddedID;
                        $timer_success = ConnectionFactory::InsertIntoTableBasic("user_timers", $usertimerparams);

                        $achievement['user_id'] = $justAddedID;
                        $success = ConnectionFactory::InsertIntoTableBasic("users_achievements", $achievement);

                        if ($success) {
				return self::getUser($justAddedID);
			}
		}
		return NULL;
	}
    /*
	 * creates a user with the given UUID
	 */
	public static function createUserUdid($uuid, $os, $mac) {
		$soldierCode = new SoldierCode();
		$code = SoldierCode::getSoldierCode();
                if($code[0]->getCode() == ""){
                    $code = SoldierCode::getSoldierCode();
                    if($code[0]->getCode() == ""){
                        $code = SoldierCode::getSoldierCode();
                    }
                }
		$userparams = array();
		$userparams['udid'] = $uuid;
                $userparams['soldier_code'] = $code[0]->getCode();
                $userparams['device_os'] = $os;
                $userparams['device_mac'] = $mac;
                $userparams['diamonds'] = 10;
                // setting max experience points for first level
                $userparams['next_level_experince_points'] = 15;
		$justAddedID = ConnectionFactory::InsertIntoTableBasicReturnInsertID("users", $userparams);
		if ($justAddedID) {
			$usercitiesparams = array();
                        $usertimerparams = array();
                        $success = '';
                        $timer_success = '';

                        /*
                         * Inserting User cities with rank
                         * Currently we are dealing with 4 cities so loop iterates 4 times
                         */
                        for($i = 1; $i <= 4; $i++){
                            $usercitiesparams['user_id'] = $justAddedID;
                                $usercitiesparams['city_id'] = $i;
                            $usercitiesparams['rank_avail'] = 1;
                            $success = ConnectionFactory::InsertIntoTableBasic("users_cities", $usercitiesparams);
                        }

                        $usertimerparams['user_id'] = $justAddedID;
                        $timer_success = ConnectionFactory::InsertIntoTableBasic("user_timers", $usertimerparams);

                        $achievement['user_id'] = $justAddedID;
                        $success = ConnectionFactory::InsertIntoTableBasic("users_achievements", $achievement);

                        if ($success && $timer_success) {
				if($soldierCode->deleteCode($code[0]->getCode())){
					return self::getUser($justAddedID);		
				}				
			}
		}
		return NULL;
	}
	/*
	 * returns an associative array where the key is an item ID and
	 * the value is the quantity of that item that the user owns
	 */
	public static function getUsersItemsIDsToQuantity($userID) {
		$query = "SELECT users_items.quantity, items.id FROM users_items JOIN items ON " .
				"(users_items.item_id = items.id) WHERE users_items.user_id = ?";
		$itemSth = ConnectionFactory::SelectAsStatementHandler($query, array($userID));
		
		$itemIDsToQuantity = array();
		while ($row = $itemSth->fetch(PDO::FETCH_ASSOC)) {
			$itemID = $row["id"];
			$itemIDsToQuantity[$itemID] = $row["quantity"];
		}
	
		return $itemIDsToQuantity;
	}
	public static function getUsersItems($userID) {
		
		$query = "SELECT users_items.quantity,items.image_url,items.type as item_type,items.atk_boost,items.def_boost,items.upkeep as item_upkeep,items.name as item_name,items.id as item_id,items.price as item_price FROM users_items JOIN items ON " .
				"(users_items.item_id = items.id) WHERE users_items.user_id = ?";
		
		$objItems = ConnectionFactory::SelectRowsAsClasses($query, array($userID),__CLASS__);
		return $objItems;
	
		return $objItems;
	}
//	public static function getUserLootedItems($userID) {
//		$query = "SELECT user_looted_items.quantity,items.image_url,items.name as item_name, items.price as item_price, items.atk_boost as attack_boost, items.def_boost as defence_boost, items.id as item_id FROM user_looted_items JOIN items ON " .
//				"(user_looted_items.item_id = items.id) WHERE user_looted_items.user_id = ? ";
//		$objItems = ConnectionFactory::SelectRowsAsClasses($query, array($userID),__CLASS__);
//		return $objItems;
//	}
	public static function getUsersRealestates($userID) {
		$query = "SELECT users_realestates.quantity as estate_quantity,realestate.image_url as estate_image,realestate.name as estate_name,realestate.income as estate_income,realestate.price as estate_price,realestate.id as estate_id FROM users_realestates JOIN realestate ON " .
				"(users_realestates.realestate_id = realestate.id) WHERE users_realestates.user_id = ?";
		$objItems = ConnectionFactory::SelectRowsAsClasses($query, array($userID),__CLASS__);
		return $objItems;
	
	}
	/*
	 * returns an associative array of the top n items by item type sorted by stat (attack or defense)
	 * the keys are item IDs and the values are quantities
	 * $n should be the agency size of the user
	 */
	public static function getUsersTopItemsByStatIDsToQuantity($userID, $n, $stat) {
		if ($stat == "attack") $orderBy = "atk_boost";
		else if ($stat == "defense") $orderBy = "def_boost";
		
		$result = array();
		
		for ($i = 1; $i <= 3; $i++) { // for each item type
			$query = "SELECT users_items.quantity AS quantity, items.id AS id" . 
					" FROM users_items JOIN items ON (users_items.item_id = items.id)" .
					" WHERE users_items.user_id = ? AND items.type = ?" .
					" ORDER BY " . $orderBy . " DESC LIMIT " . $n;
			$values = array($userID, $i);
			
			$itemSth = ConnectionFactory::SelectAsStatementHandler($query, $values);
			
			$totalQuantity = 0;
			while ($row = $itemSth->fetch(PDO::FETCH_ASSOC)) {
				$itemID = $row['id'];
				
				$quantity = $row['quantity'];
				// check if we've stored enough items to exceed the agency size yet
				if ($totalQuantity + $quantity > $n) {
					$quantity = $n - $totalQuantity;
				}
				$totalQuantity += $quantity;
				
				$result[$itemID] = $quantity;
				
				// once we have enough items in the array, break from the loop
				if ($totalQuantity >= $n)
					break;
			}
		}
		
		return $result;
	}
	
	/*
	 * returns an associative array where the key is an real estate ID and
	 * the value is the quantity of that item that the user owns
	 */
	public static function getUsersRealEstateIDsToQuantity($userID) {
		$query = "SELECT users_realestates.quantity, realestate.id FROM users_realestates JOIN realestate ON " .
						"(users_realestates.realestate_id = realestate.id) WHERE users_realestates.user_id = ?";
		$reSth = ConnectionFactory::SelectAsStatementHandler($query, array($userID));
		
		$reIDsToQuantity = array();
		while ($row = $reSth->fetch(PDO::FETCH_ASSOC)) {
			$reID = $row["id"];
			$reIDsToQuantity[$reID] = $row["quantity"];
		}
		
		return $reIDsToQuantity;
	}
	
	/*
	 * returns an array of users that have invited the current user
	 * user_one_id is the inviter, user_two_id is the invitee
	 */
	public function getPendingAgencyInviteUsers() {
		$query = "SELECT * FROM agencies JOIN users ON (agencies.user_one_id = users.id) ";
		$query .= "WHERE agencies.user_two_id = ? AND agencies.accepted = 0";
		
		$objUsers = ConnectionFactory::SelectRowsAsClasses($query, array($this->id), __CLASS__);
		return $objUsers;
	}
	
	/*
	 * updates the user's param like health stamina energy etc
	 */
	public function updateUserParams($parm) {
		$params = array();
		$parm = strtolower($parm);
		$attribute = $parm.'_max';
		$params[$parm] = $this-> $attribute - $this-> $parm ;
	    $conditions = array();
		$conditions['id'] = $this->id;
		$success = ConnectionFactory::updateTableRowsRelativeOnIDs("users", $params, $conditions);
		if ($success) {
			$this->$parm = $params;
		}
		return $success;
	}
		/*
	 * updates the user perchased diamonds
	 */
	public static function addUserPurchasedDiamonds($udid, $diamondsPerchased) {
		$diamondsparams = array();
		$diamondsparams['diamonds'] =  $diamondsPerchased;
		$conditions = array();
		$conditions['udid'] = $udid;                   
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $diamondsparams, $conditions);		
		return $success;
	}
	/*
	 * check user exist or not
	 */
	public static function userExists($udid) {
		$conditions = array();
		$conditions['udid'] = $udid;
		$success = ConnectionFactory::SelectValue("udid", "users", $conditions);
		return $success;
	}
        /*
	 * check user exist or not
	 */
	public static function getUserLevel($udid) {
		$conditions = array();
		$conditions['udid'] = $udid;
		$success = ConnectionFactory::SelectValue("level", "users", $conditions);
		return $success;
	}
	/*
	 * updates the user's diamonds
	 */
	public function addUserDiamonds($diamondsChange) {
		$diamondsparams = array();
		$diamondsparams['diamonds'] =  $diamondsChange;
	
		$conditions = array();
		$conditions['id'] = $this->id;

		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $diamondsparams, $conditions);
		if ($success) {
			$this->diamonds +=  $diamondsChange;
		}
		return $success;
	}
        
	/*
	 * updates the user's diamonds
	 */
	public function updateUserDiamonds($diamondsChange) {
		$diamondsparams = array();
		$diamondsparams['diamonds'] = - $diamondsChange;
	
		$conditions = array();
		$conditions['id'] = $this->id;

		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $diamondsparams, $conditions);
		if ($success) {
			$this->diamonds =  - $diamondsChange;
		}
		return $success;
	}
	
	
	
	/*
	 * updates the user's agency Count
	 */
	public function updateUserAgencyCount() {
		$dummyUserCount = array();
		$dummyUserCount['dummy_user_count'] = 1;
                $dummyUserCount['agency_size'] = 1;
	
		$conditions = array();
		$conditions['id'] = $this->id;

		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $dummyUserCount, $conditions);
		if ($success) {
			$this-> dummy_user_count = $this->dummy_user_count + 1;
                        $this-> agency_size = $this-> agency_size + 1;
		}
		return $success;
	}
	
	
	
	
	/*
	 * updates the user's cash
	 */
	public function updateUserCash($cashChange) {
		$cashparams = array();
		$cashparams['cash'] = $cashChange;
	
		$conditions = array();
		$conditions['id'] = $this->id;

		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $cashparams, $conditions);
		if ($success) {
			$this->cash = $this->cash + $cashChange;
		}
		return $success;
	}

        public function updateUserSoldierCode($soldierCode) {
		$soldierCodeData = array();
		$soldierCodeData['soldier_code'] = $soldierCode;

		$conditions = array();
		$conditions['id'] = $this->id;

		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $soldierCodeData, $conditions);
		if ($success) {
			$this->soldier_code = $soldierCode;
		}
		return $success;
	}

        public static function updateSoldierCode($userId, $soldierCode) {
		$soldierCodeData = array();
		$soldierCodeData['soldier_code'] = $soldierCode;

		$conditions = array();
		$conditions['id'] = $userId;

		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $soldierCodeData, $conditions);
		return $success;
	}
	
	/*
	 * updates the user's cash and income
	 */
	public function updateUserCashAndIncome($cashChange, $incomeChange) {
		$cashparams = array();
                $cashparams['cash'] = $cashChange;
		$cashparams['income'] = $incomeChange;
	
		$conditions = array();
		$conditions['id'] = $this->id;
		
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $cashparams, $conditions);
		if ($success) {
			$this->cash += $cashChange;
			$this->income += $incomeChange;
		}
		return $success;
	}
	
	/*
	 * updates the user's bank balance and cash after a deposit
	 */
	public function depositBankDeductCash($cashLost, $bankGain) {		
		$bankparams = array();
		$bankparams['bank_balance'] = $bankGain;
		$bankparams['cash'] = $cashLost*-1;
		
		$conditions = array();
		$conditions['id'] = $this->id;
	
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $bankparams, $conditions);
		
		if ($success) {
			$this->cash -= $cashLost;
			$this->bank_balance += $bankGain;
		}
		return $success;
	}
	
	/*
	 * updates the user's bank balance and cash after a withdrawal
	 */
	public function withdrawBankGainCash($cashGain) {
		$bankparams = array();
		$bankparams['bank_balance'] = $cashGain*-1;
		$bankparams['cash'] = $cashGain;
		
		$conditions = array();
		$conditions['id'] = $this->id;
		
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $bankparams, $conditions);
		
		if ($success) {
			$this->cash += $cashGain;
			$this->bank_balance -= $cashGain;
		}
		return $success;
	}
	
	/*
	 * removes $quantity number of item from the user
	 */
	public function decrementUserItem($itemID, $quantity) {
		$itemParams = array();
		$itemParams['quantity'] = $quantity*-1;
		
		$conditions = array();
		$conditions['user_id'] = $this->id;
		$conditions['item_id'] = $itemID;
		$success = ConnectionFactory::updateTableRowRelativeBasic("users_items", $itemParams, $conditions);
		if ($success) {
			$success = ConnectionFactory::DeleteZeroAndBelowQuantity("users_items");				
		}
		return $success;
	}
//public function decrementUserLootedItem($itemID, $quantity) {
//		$itemParams = array();
//		$itemParams['quantity'] = $quantity*-1;
//		
//		$conditions = array();
//		$conditions['user_id'] = $this->id;
//		$conditions['item_id'] = $itemID;
//		$success = ConnectionFactory::updateTableRowRelativeBasic("user_looted_items", $itemParams, $conditions);
//		if ($success) {
//			$success = ConnectionFactory::DeleteZeroAndBelowQuantity("user_looted_items");				
//		}
//		return $success;
//}
	
	/*
	 * gives the user $quantity number of an item
	 */
	public function incrementUserItem($itemID, $quantity) {
		$itemParams = array();
		$itemParams['user_id'] = $this->id;
		$itemParams['item_id'] = $itemID;
		$itemParams['quantity'] = $quantity;
                //print_r($itemParams);
	
		//for this to work, need to modify appropriate tables to have unique constraint over two columns
		//http://www.w3schools.com/sql/sql_unique.asp		
		//although i think the two primary keys are doing it
		return ConnectionFactory::InsertOnDuplicateKeyUpdate("users_items", $itemParams, "quantity", $quantity);
	}
        /*
	 * gives the user $quantity number of an item
	 */
//	public function incrementUserLootedItem($itemID, $quantity) {
//		$itemParams = array();
//		$itemParams['user_id'] = $this->id;
//		$itemParams['item_id'] = $itemID;
//		$itemParams['quantity'] = $quantity;
//      //print_r($itemParams);
//
//		//for this to work, need to modify appropriate tables to have unique constraint over two columns
//		//http://www.w3schools.com/sql/sql_unique.asp
//		//although i think the two primary keys are doing it
//		return ConnectionFactory::InsertOnDuplicateKeyUpdate("user_looted_items", $itemParams, "quantity", $quantity);
//	}
	
	/*
	 * removes $quantity number of real estate from the user
	 */
	public function decrementUserRealEstate($realEstateID, $quantity) {
		$realEstateParams = array();
		$realEstateParams['quantity'] = $quantity*-1;
		
		$conditions = array();
		$conditions['user_id'] = $this->id;
		$conditions['realestate_id'] = $realEstateID;
		$success = ConnectionFactory::updateTableRowRelativeBasic("users_realestates", $realEstateParams, $conditions);
		if ($success) {
			$success = ConnectionFactory::DeleteZeroAndBelowQuantity("users_realestates");
		}
		return $success;
	}
	
	/*
	 * gives the user $quantity number of real estate
	 */
	public function incrementUserRealEstate($realEstateID, $quantity) {
		$realEstateParams = array();
		$realEstateParams['user_id'] = $this->id;
		$realEstateParams['realestate_id'] = $realEstateID;
		$realEstateParams['quantity'] = $quantity;
					
		//for this to work, need to modify appropriate tables to have unique constraint over two columns
		//http://www.w3schools.com/sql/sql_unique.asp
		//although i think the two primary keys are doing it
		return ConnectionFactory::InsertOnDuplicateKeyUpdate("users_realestates", $realEstateParams, "quantity", $quantity);
	}
	
	/*
	 * updates the user's energy, cash, experience, and completed missions in one
	 * database hit
	 * used after mission completion
	 */
	public function updateUserEnergyCashExpCompletedmissions($energyCost, $totalCashGained, $totalExpGained) {
		$missionCompleteParams = array();
		$missionCompleteParams['energy'] = $energyCost*-1;
		$missionCompleteParams['missions_completed'] = 1;
		$missionCompleteParams['cash'] = $totalCashGained;
		$missionCompleteParams['experience'] = $totalExpGained;
		
		$conditions = array();
		$conditions['id'] = $this->id;
				
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $missionCompleteParams, $conditions);		
		if ($success) {
			//$this->cash += $cashLost;
                        $this->cash += $totalCashGained;
			$this->missions_completed += 1;
			$this->energy -= $energyCost;
			$this->experience += $totalExpGained;	
		}
		return $success;
	}	


     public function friendsList()
	 {
		 $userID = $this->id;
		 $query = "SELECT * FROM agencies WHERE ( agencies.user_one_id =? OR agencies.user_two_id = ? ) AND agencies.accepted=1 ";		 
		 $objAllFriendsList = ConnectionFactory::SelectAsStatementHandler($query, array($userID));
		 return $objAllFriendsList;
	 }

	/*
	 * returns an array of users for the attack list
	 */
	/*TODO: change this to use LIMIT in the query and use mysql to randomize instead*/
	public function getPotentialOpponents() {
		
		$userID = $this->id;
		$level = $this->level;
		$agencySize = $this->agency_size;
		$minHealth = 25;
				
		// get up to 10 people to list on the attack list
		$attackListSize = 15;
		
		$maxAgencySize = $agencySize + 5;
		$minAgencySize = max(array(1, $agencySize - 5));
		
		// temporary solution for level range
		$minLevel = $level - 5 .'<br />';
		$maxLevel = $level + 5 .'<br />';
		
/*		$query = "SELECT * FROM users,agencies WHERE (users.level <= ? AND users.level >= ? OR users.agency_size <= ? AND users.agency_size >= ?)  AND (users.health > $minHealth AND users.level>3) AND (users.id != agencies.user_one_id AND users.id != agencies.user_two_id AND agencies.accepted =1) AND users.id != ? GROUP BY users.id ORDER BY RAND() LIMIT $attackListSize";*/

		/*$query = "SELECT * FROM users,agencies WHERE (users.level <= ? AND users.level >= ? OR users.agency_size <= ? AND users.agency_size >= ?)  AND (users.health > $minHealth AND users.level>3) AND (users.id NOT IN (SELECT agencies.user_one_id FROM agencies WHERE agencies.user_two_id = $userID AND agencies.accepted =1)) AND (users.id NOT IN (SELECT agencies.user_two_id FROM agencies WHERE agencies.user_one_id = $userID AND agencies.accepted =1)) AND users.id != $userID GROUP BY users.id ORDER BY users.id LIMIT  $attackListSize";*/
		
		
		 $query = "SELECT * FROM users WHERE (users.level <= ? AND users.level >= ?) AND users.id != $userID GROUP BY users.id ORDER BY users.id LIMIT $attackListSize";
		
		$objAllPotOpps = ConnectionFactory::SelectRowsAsClasses($query, array($maxLevel, $minLevel), __CLASS__);
				
		if (!$objAllPotOpps || count($objAllPotOpps) < $attackListSize) {
			// TODO: execute further queries with higher level or agency size ranges if too few users
			//the next lines is temp solution if there is 1<x<attacklistsize opponents
			if ($objAllPotOpps) return $objAllPotOpps;
			else return array();
		}

		// get random indices
		$randomIntegers = getRandomIntegers($attackListSize, count($objAllPotOpps));
		
		$opponents = array();
		foreach ($randomIntegers as $key=>$value) {
			array_push($opponents, $objAllPotOpps[$key]);
		}
		return $opponents;
	}
	
	/*
	 * gives a player an agency recruit invite based on their agency code
	 */
	/*string based success codes*/
	public function invitePlayerUsingAgencycode($inviteeAgencyCode) {
		$userIDQuery = "SELECT id FROM users WHERE soldier_code = ?";
		$userIDSth = ConnectionFactory::SelectAsStatementHandler($userIDQuery, array($inviteeAgencyCode));
		$userIDRow = $userIDSth->fetch(PDO::FETCH_ASSOC);
		if ($userIDRow) {
			$inviteeID = $userIDRow['id'];
			
			$already_existing_check = "Select count(0) as numrows FROM agencies WHERE user_one_id = $this->id AND user_two_id = $inviteeID OR user_one_id = $inviteeID AND user_two_id = $this->id";
			$counter = ConnectionFactory::executeQuerySimple($already_existing_check);
			if($counter > 0) {
				return "alreadyExisting";
			}
			$success = ConnectionFactory::InsertIgnoreIntoTableBasic("agencies", array('user_one_id'=>$this->id, 
				'user_two_id'=>$inviteeID, 'accepted'=>0));
			if (!$success) {
				return "fail";
			} else {
				return "success";
			}
		} else {
			return "noUserWithAgencyCode";
		}
	}
	public function invitePlayerUsingLvl6id($lvl6id) {
		
		$userIDQuery = "SELECT id FROM users WHERE id = ?";
		$userIDSth = ConnectionFactory::SelectAsStatementHandler($userIDQuery, array($lvl6id));
		$userIDRow = $userIDSth->fetch(PDO::FETCH_ASSOC);
		if ($userIDRow) {
			$inviteeID = $userIDRow['id'];
			$already_existing_check = "Select count(0) as numrows FROM agencies WHERE user_one_id = $this->id AND user_two_id = $inviteeID OR user_one_id = $inviteeID AND user_two_id = $this->id";
			$counter = ConnectionFactory::executeQuerySimple($already_existing_check);
			if($counter > 0) {
				return "alreadyExisting";
			}
			$success = ConnectionFactory::InsertIgnoreIntoTableBasic("agencies", array('user_one_id'=>$this->id, 
				'user_two_id'=>$inviteeID, 'accepted'=>0));
			if (!$success) {
				return "fail";
			} else {
				return "success";
			}
		} else {
			return "noUserWithLvl6id";
		}
	}
	/*
	 * applies skill points towards an attribute
	 */
	public function useSkillPoint($attribute) {
		
		$skillParams = array();
		if ($attribute == 'attack') {
			$skillParams['skill_points'] = -1;
			$skillParams['attack'] = 1;
		}
		if ($attribute == 'defense') {
			$skillParams['skill_points'] = -1;
			$skillParams['defense'] = 1;
		}
		if ($attribute == 'energymax') {
			$skillParams['skill_points'] = -1;
			$skillParams['energy_max'] = 1;
			$skillParams['energy'] = 1;
		}
		if ($attribute == 'healthmax') {
			$skillParams['skill_points'] = -1;
			$skillParams['health_max'] = 10;
			$skillParams['health'] = 10;
		}
		if ($attribute == 'staminamax') {
			$skillParams['skill_points'] = -2;
			$skillParams['stamina_max'] = 1;
			$skillParams['stamina'] = 1;
		}
		$conditions = array();
		$conditions['id'] = $this->id;
		
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $skillParams, $conditions);
		if ($success) {
			$this->skill_points -= $skillParams['skill_points'];
		}
		return $success;
	}
	
	/*
	 * updates the user's last_login to the current time
	 */
	public function updateLogin() {
		$params = array();
		$params['last_login'] = date('Y-m-d H:i:s');
		
		$conditions = array();
		$conditions['id'] = $this->id;
		
		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $params, $conditions);
		if ($success) {
			$this->last_login = $params['last_login'];
		}
		return $success;
	}
	
	/*
	 * updates the user's last_login, cash, and num_consec_days_played in one db hit
	 * used for when the user has daily bonuses to apply
	 */
	public function updateCashNumConsecLogin($cash, $numConsecDays) {
		$absParams = array();
		$absParams['num_consecutive_days_played'] = $numConsecDays;
		$absParams['last_login'] = date('Y-m-d H:i:s');
		
		$relParams = array();
		$relParams['cash'] = $cash;
		
		$conditions = array();
		$conditions['id'] = $this->id;
		
		if ($cash != 0) {
			$success = ConnectionFactory::updateTableRowGenericBasic("users", $absParams, $relParams, $conditions);
		} else {
			$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $absParams, $conditions);
		}
		if ($success) {
			$this->num_consecutive_days_played = $numConsecDays;
			$this->last_login = $absParams['last_login'];
			$this->cash += $cash;
		}
		return $success;
	}
	
	/*
	 * returns an associative array where the keys are columns in the
	 * users_dailybonuses table and values are the values of those columns for the user
	 * the array tells how much the user gained for their daily bonus for each day
	 */
	public function getDailyBonuses() {
		$query = "SELECT * FROM users_dailybonuses WHERE user_id = ?";
		$values = array($this->id);
		
		$result = ConnectionFactory::SelectRowAsAssociativeArray($query, $values);
		
		return $result;
	}
	
	/*
	 * updates the user's daily bonus amount in the users_dailybonuses table
	 * if the user has gotten a weekly bonus or does not log in for consecutive days,
	 * the values are reset to 0 for that user and it starts again from day1
	 */
	public function updateDailyBonus($amount, $numConsecDays) {
		if ($numConsecDays < 6) {
			$params = array();
			$params['user_id'] = $this->id;
		
			$dayString = 'day' . ($numConsecDays + 1);
			$params[$dayString] = $amount;

			return ConnectionFactory::InsertOnDuplicateKeyUpdate("users_dailybonuses", $params, $dayString, $amount);
		} else {
			$params = array();
			for ($i = 1; $i <= 6; $i++)
				$params['day' . $i] = 0;
			
			$conditions = array();
			$conditions['user_id'] = $this->id;
			
			return ConnectionFactory::updateTableRowAbsoluteBasic("users_dailybonuses", $params, $conditions);
		}
	}
		
	/*
	 * accepts an agency invite from the given inviter 
	 */
	public function acceptInvite($inviterID) {
		$conditions = array();
		$conditions['user_one_id'] = $inviterID;
		$conditions['user_two_id'] = $this->id;

		$success = ConnectionFactory::updateTableRowAbsoluteBasic("agencies", array('accepted'=>1), $conditions);

		if ($success) {
			return ConnectionFactory::updateTableRowsRelativeOnIDs("users", array('agency_size'=>1),
				array($this->id, $inviterID));
		}
	}
	
	/*
	 * rejects an agency invite from the given inviter
	 */
	public function rejectInvite($inviterID) {
		return ConnectionFactory::DeleteRowFromTable("agencies", array('user_one_id'=>$inviterID, 
				'user_two_id'=>$this->id));
	}
	
	/*
	 * increments/decrements the user's health
	 */
	// $healthAmt is a relative amount to increment/decrement current health by
	public function updateHealth($healthAmt) {
		$params = array();
		$params['health'] = $healthAmt;
		
		$conditions = array();
		$conditions['id'] = $this->id;
		
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $params, $conditions);
		
		if ($success) {
			$this->health += $healthAmt;
		}
		return $success;
	}
	
	/*
	 * updates the user's health, stamina, fights_won/fights_lost, and experience in one db hit
	 * used after battles
	 */
	public function updateHealthStaminaFightsExperience($healthAmt, $staminaAmt, $fightsWon, $fightsLost, $expAmt) {
		$params = array();
		$params['health'] = $healthAmt;
		$params['stamina'] = $staminaAmt;
		$params['fights_won'] = $fightsWon;
		$params['fights_lost'] = $fightsLost;
		$params['experience'] = $expAmt;
		
		$conditions = array();
		$conditions['id'] = $this->id;
				
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $params, $conditions);
				
		if ($success) {
			$this->health += $healthAmt;
			$this->stamina += $staminaAmt;
			$this->fights_won += $fightsWon;
			$this->fights_lost += $fightsLost;
			$this->experience += $expAmt;
		}
		return $success;
	}
        public function refill($health, $energy, $stamina){
            
            $params = array();
            $params['health'] = $health;
            $params['energy'] = $energy;
            $params['stamina'] = $stamina;
            
            $conditions = array();
            $conditions['id'] = $this->id;

            $success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $params, $conditions);

            if ($success) {
                return false;
            }

            return $success;
        }
	
	/*
	 * updates the user's level and skill points (skill points is a relative gain)
	 */
	public function updateLevel($level, $skillPointsGained) {
		$absParams = array();
		$absParams['level'] = $level;
		
		$relParams = array();
		$relParams['skill_points'] = $skillPointsGained;
		
		$conditions = array();
		$conditions['id'] = $this->id;

                $objItem = ConnectionFactory::SelectRowAsClass("SELECT required_experience_points FROM level_experience_points where level_id = :levelID",
                                                                        array("levelID" => ($level+1)), __CLASS__);

                $nextRequiredExp = $objItem->required_experience_points;

                $absParams['next_level_experince_points'] = $nextRequiredExp;

                $success = ConnectionFactory::updateTableRowGenericBasic("users", $absParams, $relParams, $conditions);
		
		if ($success) {
			$this->level = $level;
			$this->skill_points += $skillPointsGained;
		}
		
		return $success;
	}
	/**
	 * Enter description here ...
	 * @param $status wether user won or lost the fight
	 * @param $isBounty wether it was battle or fight
	 * @param $id id of the user
	 */
	public static function updateKillsDeaths($status,$id) {
		$params = array();
		$params = null;
		if($status == "win" ) {
			$params['kills'] = 1;
		}
		else if ($status == "lose" ){
			$params['deaths'] = 1;
		}
		$conditions = array();
		$conditions = null;
		$conditions['id'] = $id;
		ConnectionFactory::updateTableRowRelativeBasic("users", $params, $conditions);
	}
	public function incrementKills() {
		$this->kills++;
	}
	public function incrementDeaths () {
		$this->deaths++;
	}
	/*
	 * removes some money from userbank and restores userhealth to healthmax
	 */
	public function healAtHospital($healCost) {
            if( $this->getBankBalance() >$healCost )
            {
		$success = ConnectionFactory::updateTableRowGenericBasic("users", 
			array('health'=>$this->health_max), 
			array('bank_balance'=>-1*$healCost),
			array('id'=>$this->id));
		if ($success) {
			$this->bank_balance -= $healCost;
			$this->health = $this->health_max;
		}
		return $success;
	}
             else
             {
                 return false;
             }
        }
	/*
	 * setter for the type attribute
	 */
	public function setType($playerType) {
		ConnectionFactory::updateTableRowAbsoluteBasic("users", array('type' => $playerType),
		array('id'=>$this->id));
		$this->type = $playerType;
	}

        /*
	 * setter for the type attribute
	 */
	public function setName($playerName) {
		ConnectionFactory::updateTableRowAbsoluteBasic("users", array('name' => $playerName),
		array('id'=>$this->id));
		$this->name = $playerName;
	}
	
	/**
	 * Increment user upkeep if user buys a item
	 * @param $itemId
	 */
	public function incrementUserUpkeep($upkeepAmount) {
		$this->upkeep += $upkeepAmount;
		ConnectionFactory::updateTableRowAbsoluteBasic("users",array('upkeep' => $this->upkeep),array('id' => $this->id));
	}
	/**
	 * Decrement user upkeep if user buys an item
	 * @param $itemId
	 */
	public function decrementUserUpkeep($upkeepAmount) {
		$this->upkeep -= $upkeepAmount;
		ConnectionFactory::updateTableRowAbsoluteBasic("users",array('upkeep' => $this->upkeep),array('id' => $this->id));
	}
	public function postBroadcastMessage($content) {
		$params = array();
		$params['sender_id'] = $this->id;
		$params['content'] = $content;
		//$params['time_posted'] = strftime('%c');
		$success = ConnectionFactory::InsertIntoTableBasic("broadcast", $params);
		return $success;
	}

	public function getBroadcastMessages() {
		$myAgency = $this->getUsersInAgency($this->id);
		$agency = "";
		foreach ($myAgency as $users) {
			$agency .= $users->getID().",";
		}
		$agency .= $this->id;
		
		$query = "Select * FROM broadcast WHERE sender_id IN (".$agency.") ORDER BY id DESC LIMIT 0,10";
		$message = ConnectionFactory::getAssociativeArray($query);
		return $message;
	}
	public function deleteBroadcastMessage($id) {
		return ConnectionFactory::DeleteRowFromTable("broadcast", array('id'=>$id ));
	}
	public function incrementUserSkillpoints ($skillpoints) {
		$params = array ();
		$params['skill_points'] = $skillpoints;
		$conditions = array ();
		$conditions['id'] = $this->id; 
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $params, $conditions);
		if ($success) {
			$this->skill_points += $skillpoints;
		}
	}
	public function incrementNumAttacks () {
		$params = array();
		$conditions = array();
		
		$params['num_attacks'] = 1;
		$conditions['id'] = $this->id;
		$success = ConnectionFactory::updateTableRowRelativeBasic("users", $params, $conditions);
		if($success) {
			$this->num_attacks++;
		}
	}
	public static function updateAchievementRank($achievementName,$id) {
		$params = array();
		$conditions = array();
		$params[$achievementName] = 1;
		$conditions['user_id'] = $id;
		$success = ConnectionFactory::updateTableRowRelativeBasic("users_achievements", $params, $conditions);
	}
	public static function getUserAchievementRanks ($id) {
		$query = "Select * from users_achievements WHERE user_id = $id";
		$achievements = ConnectionFactory::getAssociativeArray($query);
		return $achievements;
	}
    public static function updateUserC2DMRegistrationToken($udid,$registrationToken) {
		$params = array();
		$conditions = array();
		$params['c2dm_token'] = $registrationToken;
		$conditions['udid'] = $udid;
		$success = ConnectionFactory::updateTableRowAbsoluteBasic("users", $params, $conditions);
                 return $success;
	}
	public static function addOfflineAchievement($userID,$achievementName,$achievementLevel) {
		$params = array();
		$params['achievement_name'] = $achievementName;
		$params['achievement_level'] = $achievementLevel;
		$params['user_id'] = $userID;
		$success = ConnectionFactory::InsertOnDuplicateKeyUpdateAbsolute("offline_achievements", $params, "achievement_level",$achievementLevel);
	}
	public function getOfflineAchievements () {
		$query = "Select * FROM offline_achievements WHERE user_id = $this->id ";
		$achievements = ConnectionFactory::getAssociativeArray($query);
		return $achievements;
	}
	public function removeOfflineAchievements () {
		$conditions = array();
		$conditions['user_id'] = $this->id;
		$success = ConnectionFactory::DeleteRowFromTable('offline_achievements',$conditions);
		return $success;
	}
        public static function getFullHealhUsers() {            
             $query = "SELECT users.udid FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, health_timer, NOW())/4)> (users.health_max-users.health) AND (users.health_max-users.health)>0";
             $fullHealth = ConnectionFactory::getAssociativeArray($query);            
             return $fullHealth;
	}
         public static function getFullEnergy() {            
            //SELECT users.udid, users.name,user_timers.id, (users.energy_max-users.energy) as diff, FLOOR(TIMESTAMPDIFF(MINUTE, energy_timer, NOW())/5) as tintv FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, energy_timer, NOW())/5)> (users.energy_max-users.energy) AND (users.energy_max-users.energy)>0             
             $query = "SELECT users.udid FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, energy_timer, NOW())/5)> (users.energy_max-users.energy) AND (users.energy_max-users.energy)>0";
             $fullEnergy = ConnectionFactory::getAssociativeArray($query);            
             return $fullEnergy;
	}
         public static function getFullStamina() {            
             $query = "SELECT users.udid FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, stamina_max, NOW())/4)> (users.stamina_max-users.stamina) AND (users.stamina_max-users.stamina)>0";
             $fullHealth = ConnectionFactory::getAssociativeArray($query);            
             return $fullHealth;
	}
        public static function lastTwoDayInactive() {            
             $query = "SELECT users.udid from users WHERE TIMESTAMPDIFF(DAY, last_login, NOW())>2";
             $lastTwoDayUsers = ConnectionFactory::getAssociativeArray($query);            
             return $lastTwoDayUsers;
            }
	public function getPlayerPreviousLevel () {
		$query = "SELECT required_experience_points FROM level_experience_points WHERE required_experience_points < $this->next_level_experince_points ORDER BY required_experience_points DESC LIMIT 1";
		$xp = ConnectionFactory::getAssociativeArray($query);
                $experiencePoints = null;
		foreach ($xp as $experience) {
			$experiencePoints =  $experience['required_experience_points'];
		}
		return $experiencePoints;
	}

        public static function updateStaminaOfflineTimer($interval){
            $query = "SELECT users.id, users.udid, users.c2dm_token, users.device_os FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, stamina_timer, NOW())/".$interval.")> (users.stamina_max-users.stamina) AND (users.stamina_max-users.stamina)>0";
             $fullStamina = ConnectionFactory::SelectRowsAsClasses($query, array(), __CLASS__);

            $query = "update users, user_timers set user_timers.stamina_timer = NOW(), users.stamina =
                    IF (users.stamina + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.stamina_timer, NOW())/".$interval.") < users.stamina_max,
                         users.stamina + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.stamina_timer, NOW())/".$interval."),
                        users.stamina_max)
                    WHERE users.id = user_timers.user_id AND TIMESTAMPDIFF(HOUR, user_timers.stamina_timer, NOW()) > 1";
            if(ConnectionFactory::getAssociativeArray($query)) {
                return $fullStamina;
            }
            
        }
        

        public static function updateEnergyOfflineTimer($interval){
            $query = "SELECT users.id, users.udid, users.c2dm_token, users.device_os FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, energy_timer, NOW())/".$interval.")> (users.energy_max-users.energy) AND (users.energy_max-users.energy)>0";
             $fullEnergy = ConnectionFactory::SelectRowsAsClasses($query, array(), __CLASS__);

            $query = "update users, user_timers set user_timers.energy_timer = NOW(), users.energy =
                    IF (users.energy + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.energy_timer, NOW())/".$interval.") < users.energy_max,
                         users.energy + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.energy_timer, NOW())/".$interval."),
                        users.energy_max)
                    WHERE users.id = user_timers.user_id AND TIMESTAMPDIFF(HOUR, user_timers.energy_timer, NOW()) > 1 AND users.type != 1";
            if(ConnectionFactory::getAssociativeArray($query)) {
                return $fullEnergy;
            }

        }

        public static function updateEnergyOfflineTimerSpecialUsers($interval){
            $query = "SELECT users.id, users.udid, users.c2dm_token, users.device_os FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, energy_timer, NOW())/".$interval.")> (users.energy_max-users.energy) AND (users.energy_max-users.energy)>0";
             $fullEnergy = ConnectionFactory::SelectRowsAsClasses($query, array(), __CLASS__);

             $query = "update users, user_timers set user_timers.energy_timer = NOW(), users.energy =
                    IF (users.energy + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.energy_timer, NOW())/".$interval.") < users.energy_max,
                         users.energy + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.energy_timer, NOW())/".$interval."),
                        users.energy_max)
                    WHERE users.id = user_timers.user_id AND TIMESTAMPDIFF(HOUR, user_timers.energy_timer, NOW()) > 1 AND users.type = 1";
            if(ConnectionFactory::getAssociativeArray($query)) {
                return $fullEnergy;
            }

        }
        
        public static function updateHealthOfflineTimer($interval){

            $query = "SELECT users.id, users.udid, users.c2dm_token, users.device_os FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, health_timer, NOW())/".$interval.")> (users.health_max-users.health) AND (users.health_max-users.health)>0";
             $fullHealth = ConnectionFactory::SelectRowsAsClasses($query, array(), __CLASS__);
            
            $query = "update users, user_timers set user_timers.health_timer = NOW(), users.health =
                    IF (users.health + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.health_timer, NOW())/".$interval.") < users.health_max,
                         users.health + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.health_timer, NOW())/".$interval."),
                        users.health_max)
                    WHERE users.id = user_timers.user_id AND TIMESTAMPDIFF(HOUR, user_timers.health_timer, NOW()) > 1 AND users.type != 3";
            
            if(ConnectionFactory::getAssociativeArray($query)) {
                return $fullHealth;
            }

        }
         public static function updateHealthOfflineTimerSpecialUsers($interval){
             $query = "SELECT users.id, users.udid, users.c2dm_token, users.device_os FROM users JOIN user_timers ON (users.id = user_timers.user_id) WHERE FLOOR(TIMESTAMPDIFF(MINUTE, health_timer, NOW())/".$interval.")> (users.health_max-users.health) AND (users.health_max-users.health)>0";
             $fullHealth = ConnectionFactory::SelectRowsAsClasses($query, array(), __CLASS__);
            
            $query = "update users, user_timers set user_timers.health_timer = NOW(), users.health =
                    IF (users.health + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.health_timer, NOW())/".$interval.") < users.health_max,
                         users.health + FLOOR(TIMESTAMPDIFF(MINUTE, user_timers.health_timer, NOW())/".$interval."),
                        users.health_max)
                    WHERE users.id = user_timers.user_id AND TIMESTAMPDIFF(HOUR, user_timers.health_timer, NOW()) > 1 AND users.type = 3";

            if(ConnectionFactory::getAssociativeArray($query)) {
                return $fullHealth;
            }

        }
        public function updateIncomeOfflineTimer(){
            $query = "Select users.cash + ((users.income - users.upkeep ) * TIMESTAMPDIFF(HOUR, user_timers.health_timer, NOW())) as CASH
                        from users, user_timers
                        WHERE users.id = user_timers.user_id AND TIMESTAMPDIFF(HOUR, user_timers.income_timer, NOW()) > 1";
            //echo $query;
            if(ConnectionFactory::getAssociativeArray($query)) {
                return true;
            }

        }
        
	/*
	 * Getters
	 */
	 
	public function getCash() {
		return $this->cash;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getName() {
            
		return $this->name;
	}
	
	public function getBankBalance() {
		return $this->bank_balance;
	}
	
	public function getLevel() {
		return $this->level;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getNumMissionsCompleted() {
		return $this->missions_completed;
	}
	
	public function getFightsWon() {
		return $this->fights_won;
	}
	
	public function getFightsLost() {
		return $this->fights_lost;
	}
	
	public function getUserKills() {
		return $this->kills;
	}
	public function getUserDeaths() {
		return $this->deaths;
	}
	
	public function getIncome() {
            
		return $this->income;
	}
	
	public function getUpkeep() {
		return $this->upkeep;
	}
	
	public function getNetIncome() {
		return $this->income - $this->upkeep;
	}
	
	public function getAgencySize() {
		return $this->agency_size;
	}
	
	public function getEnergy() {
		return $this->energy;
	}
	
	public function getHealth() {
		return $this->health;
	}
	
	public function getStamina() {
		return $this->stamina;
	}
	
	public function getSkillPoints() {
		return $this->skill_points;
	}
	
	public function getAttack() {
		return $this->attack;
	}
	
	public function getDefense() {
		return $this->defense;
	}
	
	public function getExperience() {
		return $this->experience;
	}
	
	public function getStaminaMax() {
		return $this->stamina_max;
	}
	
	public function getHealthMax() {
		return $this->health_max;
	}
	public function getEnergyMax() {
		return $this->energy_max;
	}
	
	public function getAgencyCode() {
		return $this->soldier_code;
	}
	
	public function getLastLogin() {
		return $this->last_login;
	}
	
	public function getNumConsecDaysPlayed() {
		return $this->num_consecutive_days_played;
	}
	
	public function getDiamonds() {
		return $this->diamonds;
	}

        public function getUDID() {
		return $this->udid;
	}

        public function getLvl6ID() {
                    return $this->lvl6_id;
	}

	public function getSoundSettings() {
		return $this->sound_settings;
	}
	
	public function getVibrationSettings() {
		return $this->vibration_settings;
	}
	
	public function getCommentSettings() {
		return $this->comment_settings;
	}

        public function getSplashSettings(){
            return $this->splash_settings;
        }
	
	public function getNotificationSettings() {
		return $this->notification_settings;
	}
	public function getNumAttacks() {
		return $this->num_attacks;
	}

	public function getNextLevelExperiencePoints(){
		 return $this->next_level_experince_points;
	}

	public function getDeviceOS(){
		return $this->device_os;
	}
	public function getC2DMToken(){
		return $this->c2dm_token;
	}
	
	public function setUserCash($cash){
		$this->cash = $this->cash + $cash;
	}
	public function setUserExp($experience){
		$this->experience = $this->experience + $experience;
	}
}