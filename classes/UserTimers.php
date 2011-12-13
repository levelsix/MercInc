<?php
include_once 'ConnectionFactory.php';
class UserTimers {

	private $user_id;
	private $income_timer;
	private $health_timer;
	private $energy_timer;
	private $stamina_timer;
	
	function __construct() {
	}

        public static function getTimers($user_id){
            $query = "SELECT * FROM user_timers Where user_id = :userID";
            return ConnectionFactory::SelectRowAsClass($query, array("userID" => $user_id), __CLASS__);
        }

        public static function updateIncomeTimer($user_id){
            $query = "update user_timers set income_timer = NOW() Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }

        public static function incrementIncomeTimer($user_id, $increment){
            $query = "update user_timers set income_timer = DATE_ADD(income_timer, INTERVAL '".$increment."' MINUTE) Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }

        public static function incremenEnergyTimer($user_id, $increment){
            $query = "update user_timers set energy_timer = DATE_ADD(energy_timer, INTERVAL '".$increment."' MINUTE) Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }
        
        public static function incremenHealthTimer($user_id, $increment){
            $query = "update user_timers set health_timer = DATE_ADD(health_timer, INTERVAL '".$increment."' MINUTE) Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }

        public static function incremenStaminaTimer($user_id, $increment){
            $query = "update user_timers set stamina_timer = DATE_ADD(stamina_timer, INTERVAL '".$increment."' MINUTE) Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }

        public static function stopIncomeTimer($user_id){
            $query = "update user_timers set income_timer = '0000-00-00 00:00:00' Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
                return true;
            }
        }

        public static function updateHealthTimer($user_id){
            $query = "update user_timers set health_timer = NOW() Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }

        public static function stopHealthTimer($user_id){
            $query = "update user_timers set health_timer = '0000-00-00 00:00:00' Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
                return true;
            }
        }
        
        public static function updateEnergyTimer($user_id){
            $query = "update user_timers set energy_timer = NOW() Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }

        public static function stopEnergyTimer($user_id){
            $query = "update user_timers set energy_timer = '0000-00-00 00:00:00' Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
                return true;
            }
        }

        public static function updateStaminaTimer($user_id){
            $query = "update user_timers set stamina_timer = NOW() Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
            //$success = ConnectionFactory::updateTableRowRelativeBasic("user_timers", $params, $conditions);
                return true;
            }
        }

        public static function stopStaminaTimer($user_id){
            $query = "update user_timers set stamina_timer = '0000-00-00 00:00:00' Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
                return true;
            }
        }

        public static function resetTimer($user_id){
            $query = "update user_timers set stamina_timer = NOW(), health_timer = NOW(), energy_timer = NOW() Where user_id = ".$user_id;
            if(ConnectionFactory::getAssociativeArray($query)) {
                return true;
            }
        }

        public static function getTimersInSeconds($user_id){
            $query = "SELECT TIMESTAMPDIFF(SECOND, income_timer, NOW()) as income_timer, TIMESTAMPDIFF(SECOND, health_timer, NOW()) as health_timer,
                TIMESTAMPDIFF(SECOND, energy_timer, NOW()) as energy_timer, TIMESTAMPDIFF(SECOND, stamina_timer, NOW()) as stamina_timer FROM user_timers Where user_id = :userID";
            return ConnectionFactory::SelectRowAsClass($query, array("userID" => $user_id), __CLASS__);
        }
        
        public static function isUSerExists($user_id){
            $user_data = ConnectionFactory::SelectAsStatementHandler(
		"SELECT user_id FROM user_timers WHERE user_id = ? ",
		array($user_id));
            if($user_data->rowCount() > 0){
                return true;
            } else {
                return false;
            }

        }

        public function getIncomeTimer(){
            return $this->income_timer;
        }
        public function getHealthTimer(){
            return $this->health_timer;
        }
        public function getEnergyTimer(){
            return $this->energy_timer;
        }
        public function getStaminaTimer(){
            return $this->stamina_timer;
        }
}

?>
