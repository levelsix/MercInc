<?php
session_start();
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../classes/UserTimers.php';
include_once '../classes/User.php';
include_once '../classes/RealEstate.php';
include_once '../classes/Item.php';
include_once '../properties/constants.php';
include_once '../classes/common_functions.php';

$user = User::getUser($_SESSION['userID']);
$PlayerNetIncome = $user->getNetIncome();
$PlayerIncome = $user->getIncome();
$playerCash = $user->getCash();
$playerStamina = $user->getStamina();
$playerHealth = $user->getHealth();
$playerEnergy = $user->getEnergy();
$playerStaminaMax = $user-> getStaminaMax();
$playerHealthMax = $user->getHealthMax();
$playerEnergyMax = $user->getEnergyMax();
$PlayerUpkeep = $user->getUpkeep();
$updatedCash = 0;

$request_timer = '';
$increment_value = '';

$fn = new common_functions();




if(isset($_GET)){

    if(!isset($_GET['operation'])){
        if(isset($_GET['request_timer']) && $_GET['request_timer'] == "income_timer"){
            $increment_value = $_GET['increment_value'];
            $request_timer = $_GET['request_timer'];
            //if(UserTimers::incrementIncomeTimer($_SESSION['userID'], $increment_value)){
            if(UserTimers::updateIncomeTimer($_SESSION['userID'])){
                if($PlayerNetIncome > 0){
                    $updatedCash = $playerCash + $PlayerNetIncome;
                }else {
                    $userUpkeepItemsDetails = Item::getUserUpkeepItems($_SESSION['userID']);
                    $allUpkeepItems = array();
                    foreach($userUpkeepItemsDetails as $item){
                        array_push($allUpkeepItems, array('type' => 'item', 'id' => $item->getID(), 'upkeep' => $item->getUpkeep(), 'quantity' => $item->getQuantity(), 'price' => $item->getPrice()));

                    }
                    $fn->aasort($allUpkeepItems, "upkeep");
                    $allUpkeepItems = array_reverse($allUpkeepItems);

                    $count = 0;
                    if(count($allUpkeepItems) > 0 ){
                    while (($playerCash + $PlayerNetIncome) < 0 ) {
                        if($allUpkeepItems[$count]['quantity'] > 0){
                            $amount = 0;
                            echo $PlayerNetIncome ." < ". $playerCash."<br>";
                            $id = $allUpkeepItems[$count]['id'];
                            echo $id."<-id<br>";
                            $upkeep = $allUpkeepItems[$count]['upkeep'];
                            echo $upkeep."<-upkeep<br>";
                            if($allUpkeepItems[$count]['type'] == 'item'){
                                $amount = $allUpkeepItems[$count]['price'];
                                $user->decrementUserItem($id, 1);
                                $user->updateUserCash($amount * SELL_RATIO);
                            } else if ($allUpkeepItems[$count]['type'] == 'realEstate'){
                                $realEstate = RealEstate::getRealEstate($id);
                                $incomeChange = $realEstate->getIncome();
                                $amount = $allUpkeepItems[$count]['price'] + (INCREASE_REAL_ESTATE_PERCENTAGE * $allUpkeepItems[$count]['price']) * $allUpkeepItems[$count]['quantity'];
                                $user->decrementUserRealEstate($id, 1);
                                $user->updateUserCashAndIncome($amount*SELL_RATIO, $incomeChange*-1);
                            }
                            echo $amount."<-amount<br>";
                            $user->decrementUserUpkeep($upkeep);
                            $playerCash += $amount * SELL_RATIO;
                            echo $playerCash."<-player cash<br>";
                            $PlayerUpkeep -= $upkeep;
                            echo $PlayerUpkeep."<-player upkeep<br>";
                            $PlayerNetIncome = $PlayerIncome - $PlayerUpkeep;
                            echo $PlayerNetIncome."<-player net income<br>";
                            $allUpkeepItems[$count]['quantity']--;
                            echo $allUpkeepItems[$count]['quantity']."<-quantity<br>";
                        } else {
                            $count++;
                        }
                    }
                    }
                }
            $updatedCash = $playerCash + $PlayerNetIncome;
          //  echo "===========>".$updatedCash;
            if(User::updateUserIncome($_SESSION['userID'], $updatedCash)){
                echo "updated_cash=".$updatedCash;
                echo "&netIncome=".$PlayerNetIncome;
                echo "&success";
            }
        } else {
            echo "failure";
        }

    }
    }
    if(isset($_GET['request_timer']) && $_GET['request_timer'] == "energy_timer"){
        if(isset($_GET['operation'])){
            UserTimers::stopEnergyTimer($_SESSION['userID']);
            exit;
        }
        $request_timer = $_GET['request_timer'];
        $increment_value = $_GET['increment_value'];
        //if(UserTimers::incremenEnergyTimer($_SESSION['userID'], $increment_value * ENERGY_INCREASE_TIME)){
        if(UserTimers::updateEnergyTimer($_SESSION['userID'])){
            $playerEnergy++;
            $refill_energy = $playerEnergy;
            if($playerEnergy > $playerEnergyMax){
                $refill_energy = $playerEnergyMax;
            }
            if(User::updateUserEnergy($_SESSION['userID'], $refill_energy)){
                echo "success";
            }
        } else {
            echo "failure";
        }

    }
    if(isset($_GET['request_timer']) && $_GET['request_timer'] == "health_timer"){
        if(isset($_GET['operation'])){
            UserTimers::stopHealthTimer($_SESSION['userID']);
            exit;
        }
        $request_timer = $_GET['request_timer'];
        $increment_value = $_GET['increment_value'];
        //if(UserTimers::incremenHealthTimer($_SESSION['userID'], $increment_value * HEALTH_INCREASE_TIME)){
        if(UserTimers::updateHealthTimer($_SESSION['userID'])){
            $playerHealth++;
            $refill_health = $playerHealth;
            if($playerHealth > $playerHealthMax){
                $refill_health = $playerHealthMax;
            }
            if(User::updateUserHealth($_SESSION['userID'], $refill_health)){
                echo "success";
            }
        } else {
            echo "failure";
        }

    }
    if(isset($_GET['request_timer']) && $_GET['request_timer'] == "stamina_timer"){
        if(isset($_GET['operation'])){
            UserTimers::stopStaminaTimer($_SESSION['userID']);
            exit;
        }
        $request_timer = $_GET['request_timer'];
        $increment_value = $_GET['increment_value'];
        //if(UserTimers::incremenStaminaTimer($_SESSION['userID'], $increment_value * STAMINA_INCREASE_TIME)){
        if(UserTimers::updateStaminaTimer($_SESSION['userID'])){
            $playerStamina++;
            $refill_stamina = $playerStamina;
            if($playerStamina > $playerStaminaMax){
                $refill_stamina = $playerStaminaMax;
            }
            if(User::updateUserStamina($_SESSION['userID'], $refill_stamina)){
                echo "success";
            }
        } else {
            echo "failure";
        }

    }
}
 
?>
