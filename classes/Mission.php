<?php
include_once 'ConnectionFactory.php';
include_once 'lvl6MemCache.php';

// use this to display errors
class Mission {
	
	private $id;
	private $name;	
	private $description;
	private $energy_cost;
	private $min_cash_gained;
	private $max_cash_gained;
	private $min_level;
	private $loot_item_id;
	private $chance_of_loot;
	private $exp_gained;
	private $rank_one_times;
	private $rank_two_times;
	private $rank_three_times;
	private $city_id;
	private $min_agency_size;
        private $sound;
	private $memcache;        
        

	function __construct() {
          
	}
	
	public static function getMission($missionID){
            $objMission = Lvl6MemCache::getCache('mission'.$missionID);            
            if($objMission) {              
                return $objMission;
            }            
            $objMission = ConnectionFactory::SelectRowAsClass("SELECT * FROM missions where id = :missionID", 
											array("missionID" => $missionID), __CLASS__);
            
            Lvl6MemCache::addCache('mission'.$missionID, $objMission);            
            return $objMission;
	}
		
	public static function getMissionsInCity($cityID) {
                // see if we already have this user in the cache               
                $objMissions = Lvl6MemCache::getCache('missions'.$cityID);            
                if($objMissions) {              
                    return $objMissions;
                } 
                $query = "SELECT * from missions where city_id=?";		
		$objMissions = ConnectionFactory::SelectRowsAsClasses($query, array($cityID), __CLASS__);
                Lvl6MemCache::addCache('missions'.$cityID, $objMissions);                 
		return $objMissions;
	}
	
	public static function getMissionsInCityGivenPlayerLevel($playerLevel, $cityID) {                
                $objMissions = Lvl6MemCache::getCache('missionsInCityGivenPlayerLevel'.$playerLevel.$cityID);            
                if($objMissions) {              
                    return $objMissions;
                }
		$query = "SELECT * FROM missions WHERE min_level <= ? AND city_id = ? ORDER BY min_level";
		$objMissions = ConnectionFactory::SelectRowsAsClasses($query, array($playerLevel, $cityID), __CLASS__);
                Lvl6MemCache::addCache('missionsInCityGivenPlayerLevel'.$playerLevel.$cityID,$objMissions);                
		return $objMissions;
	}
	
        public static function getUnlockMissionsInCity($playerLevel, $cityID) {              
                $objMissions = Lvl6MemCache::getCache('unlockMissionsInCity'.$playerLevel.$cityID);            
                if($objMissions) {              
                    return $objMissions;
                }
                $query = "SELECT * FROM missions WHERE missions.min_level =
                    (SELECT missions.min_level FROM missions WHERE missions.min_level > ? AND missions.city_id = ? LIMIT 0,1)";
		//$query = "SELECT * FROM missions WHERE min_level > ? AND city_id = ? ORDER BY min_level LIMIT 2";

		$objMissions = ConnectionFactory::SelectRowsAsClasses($query, array($playerLevel, $cityID), __CLASS__);
                Lvl6MemCache::addCache('unlockMissionsInCity'.$playerLevel.$cityID,$objMissions);               
		return $objMissions;
	}
	
	public static function getMissionRequiredItemsIDsToQuantity($missionID) {                      
                $itemIDsToQuantity = Lvl6MemCache::getCache('missionRequiredItemsIDsToQuantity'.$missionID);            
                if($itemIDsToQuantity) {              
                    return $itemIDsToQuantity;
                }
		$query = "SELECT missions_itemreqs.item_quantity, items.id FROM missions_itemreqs JOIN items ON " .
					"(missions_itemreqs.item_id = items.id) WHERE missions_itemreqs.mission_id = ?";
		$itemSth = ConnectionFactory::SelectAsStatementHandler($query, array($missionID));	
		$itemIDsToQuantity = array();
		while ($row = $itemSth->fetch(PDO::FETCH_ASSOC)) {
			$itemID = $row["id"];
			$itemIDsToQuantity[$itemID] = $row["item_quantity"];
		}
                Lvl6MemCache::addCache('missionRequiredItemsIDsToQuantity'.$missionID,$itemIDsToQuantity);               
		return $itemIDsToQuantity;
	}
        public static function getNextUnlockMissionLevel($playerLevel, $currentCityID){          
                $objItem = Lvl6MemCache::getCache('nextUnlockMissionLevel'.$playerLevel.$currentCityID);            
                if($objItem) {              
                    return $objItem;
                }
                $query = "SELECT * FROM missions WHERE min_level > ? AND city_id = ? ORDER BY min_level";
                $objItem = ConnectionFactory::SelectRowAsClass($query, array($playerLevel, $currentCityID), __CLASS__);
                Lvl6MemCache::addCache('nextUnlockMissionLevel'.$playerLevel.$currentCityID,$objItem);           
                return $objItem;
        }
		
	public function getName() {
		return $this->name;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getMinAgencySize() {
		return $this->min_agency_size;
	}
	
	public function getEnergyCost() {
		return $this->energy_cost;
	}
	
	public function getChanceOfLoot() {
		return $this->chance_of_loot;
	}
	
	public function getLootItemID() {
		return $this->loot_item_id;
	}
	
	public function getMinCashGained() {
		return $this->min_cash_gained;
	}
	
	public function getMaxCashGained() {
		return $this->max_cash_gained;
	}
	
	public function getRandomCashGained() {
		return rand($this->min_cash_gained, $this->max_cash_gained);
	}
	
	public function getExpGained() {
		return $this->exp_gained;
	}
	
	public function getCityID() {
		return $this->city_id;
	}
	
	public function getRankReqTimes($rank) {
            switch($rank) {
                    case 1:
                            return $this->rank_one_times;
                            break;
                    case 2:
                            return $this->rank_two_times;
                            break;
                    case 3:
                            return $this->rank_three_times;
                            break;
            }
	}
	
	public function getMinLevel() {
		return $this->min_level;
	}
	
	public function getDescription() {
		return $this->description;
	}
        public function getSound(){
            return $this->sound;
        }
	
}