<?php
include_once("../classes/ConnectionFactory.php");
include_once("../properties/serverproperties.php");
include_once("../classes/User.php");
include_once("../classes/UserTimers.php");
include_once("../classes/RealEstate.php");
include_once("../classes/Mission.php");
include_once("../classes/Item.php");
include_once '../classes/common_functions.php';

ob_start();
session_start();
$SELL_RATIO = .6;
$fn = new common_functions();

$actionToDo = $_GET['actionToDo'];
if(isset($_GET['itemID']))
    $itemID = $_GET['itemID'];
if(isset($_GET['storePrice']))
    $storePrice = $_GET['storePrice'];
$user = User::getUser($_SESSION['userID']);
if(isset($itemID))
    $item_details = Item::getItem($itemID);
$status = "success";

if(isset($_GET['oldUserQuantity'])) {
		$owned_quantity = $_GET['oldUserQuantity'];
	}	
if(isset($_GET['request_quantity'])) {
	$request_quantity = $_GET['request_quantity'];
	$required_money = ( $storePrice * $request_quantity );	
	if( $user->getCash() < $required_money ) {
	    if(isset($_GET['cityID'])){
	        $_SESSION['missionEquipmentBought'] = 'false';
	        $_SESSION['missionEquipmentBoughtDesc'] = 'You don\'t have enough money to buy that.';
	        header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']);
	        exit();
	    }
		header("Location: ".$serverRoot."equipment.php?status=needmoney&itemid=$itemID&requestquantity=$request_quantity&requiredcash=$required_money");
		exit();
	}
}
if ($actionToDo == 'buy' ) {
    //echo $item_details->getMinLevel() ." <= ". $user->getLevel();
    //die();
        if($item_details->getMinLevel() <= $user->getLevel()){
            $user->incrementUserItem($itemID,$request_quantity );
            $user->updateUserCash(-$storePrice*$request_quantity);
            $upkeepAmount = Item::getItemUpkeep($itemID);
            if($upkeepAmount > 0 ){
                $timers = UserTimers::getTimers($_SESSION['userID']);
                if($timers){
                    $income_timer = $timers->getIncomeTimer();
                    if(!$fn->isTimeSet($income_timer)){
                        UserTimers::updateIncomeTimer($user->getID());
                    }
                }
            }
            $user->incrementUserUpkeep($upkeepAmount*$request_quantity);
            if(isset($_GET['cityID'])) {
                    $_SESSION['missionEquipmentBought'] = 'true';
                    $_SESSION['missionEquipmentBoughtDesc'] = 'You bought '.$request_quantity.' '.$item_details->getName().'.';
                    header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']."&missionID=".$_GET['missionID']);
                    exit();
            }
            else {
                    $toReturn = $_GET['toReturn'];
                    header("Location: {$serverRoot}equipment.php?action=".$actionToDo."&status=success&itemid=$itemID".'&quantity_bought='.$request_quantity.'#'.$toReturn);
                    exit;
            }
        }else{
             $_SESSION['missionEquipmentBought'] = 'false';
            $_SESSION['missionEquipmentBoughtDesc'] = ' You do not buy locked item.';
            header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']."&missionID=".$_GET['missionID']);
            exit();
         }
    
} else if ($actionToDo == 'sell') {
	
	if($owned_quantity < 1){ /* if user has less than 1 item he cannot sell that item*/
		die('you dont have this item');
	}
	/*decrementing user itemsm updating cash and performing other calculations necessary */
	$user->decrementUserItem($itemID, 1);
	$user->updateUserCash($storePrice*$SELL_RATIO);
	$upkeepAmount = Item::getItemUpkeep($itemID);
	$user->decrementUserUpkeep($upkeepAmount);
        if(!Item::getHasItem($_SESSION['userID']) && !RealEstate::getHasRealEstate($_SESSION['userID'])) {
            $timers = UserTimers::getTimers($_SESSION['userID']);
            if($timers){
                $income_timer = $timers->getIncomeTimer();
                if($fn->isTimeSet($income_timer)){
                    UserTimers::stopIncomeTimer($user->getID());
                }
            }
            
        }    
    /* redirection according to which page user have come from  */
	if(isset($_GET['cityID'])) {
		$_SESSION['missionEquipmentBought'] = 'true';
   		$_SESSION['missionEquipmentBoughtDesc'] = 'You bought '.$request_quantity.' '.$item_details->getName().'.';
   		header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']."&missionID=".$_GET['missionID']);
        exit();
	}
	else {
		 $toReturn = $_GET['toReturn'];
         header("Location: {$serverRoot}equipment.php?action=".$actionToDo."&status=success&itemid=$itemID.'#'.$toReturn");
         exit;
    }
} else if($actionToDo == 'sellLootedItem') {
	if($owned_quantity < 1){
			die('you dont have this item');
	}		
	$user->decrementUserLootedItem($itemID, 1);
	$user->updateUserCash($storePrice*$SELL_RATIO);
	$upkeepAmount = Item::getItemUpkeep($itemID);
	$user->decrementUserUpkeep($upkeepAmount);		
	header("Location: {$serverRoot}profile.php#three");
	exit();
} else if($actionToDo == 'bulkBuy'){
	
    $missionID = $_GET['missionID'];
    $itemIDsToQuantity = Mission::getMissionRequiredItemsIDsToQuantity($missionID);
    $itemIDsToItems = Item::getItemIDsToItems(array_keys($itemIDsToQuantity));
    $totalUpkeepAmount = 0;
    if($user->getLevel() < 4 ) {
        $_SESSION['failureType'] = 'notEnoughLevel';
        header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']."&missionID=".$_GET['missionID']);
        exit();
    }
    if (isset($_GET['totalPrice'])) {
    	$totalPrice = $_GET['totalPrice'];
    }
    if ($user->getCash() >= $totalPrice) {
//    	foreach($itemIDsToQuantity as $key => $value){
//            $item = $itemIDsToItems[$key];
//    		if($item->getMinLevel() > $user->getLevel()){
//    			$_SESSION['missionEquipmentBought'] = 'false';
//    			$_SESSION['failureType'] = 'lockedItem';
//    			header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']."&missionID=".$_GET['missionID']);
//    			exit();
//    	}
//    }
    		
        foreach($itemIDsToQuantity as $key => $value){
             $item = $itemIDsToItems[$key];
             if($item->getMinLevel() > $user->getLevel()) {
             	$_SESSION['failureType'] = 'lockedItem';
             } 
             else if( $user->getCash() >= $totalPrice ){
                 $itemID = $item->getID();
                 $itemQuantity = $item->getQuanitybyUserId($user->getID());
                 if ($itemQuantity < $value) {
                 $totalRequiredQuantity = $value-$itemQuantity;
                 $user->incrementUserItem($item->getID(), $totalRequiredQuantity);
                 $upkeepAmount = Item::getItemUpkeep($itemID);
                 $totalUpkeepAmount = $upkeepAmount;
                 $itemQuantity = $item->getQuanitybyUserId($user->getID());
                 $user->incrementUserUpkeep($upkeepAmount*$totalRequiredQuantity);
                 $user->updateUserCash(-$item->getPrice() * $totalRequiredQuantity);
                 $totalPrice -= $item->getPrice() * $totalRequiredQuantity;
                 $_SESSION['missionEquipmentBought'] = 'true';
                 $_SESSION['missionEquipmentBoughtDesc'] = ' You bought all required items.'; 
                }
             }
        }
           
        if($totalUpkeepAmount > 0){
            $timers = UserTimers::getTimers($_SESSION['userID']);
            if($timers){
                $income_timer = $timers->getIncomeTimer();
                if(!$fn->isTimeSet($income_timer)){
                    UserTimers::updateIncomeTimer($user->getID());
                }
            }
        }
        header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']."&missionID=".$_GET['missionID']);
        exit();
    } else if ($user->getCash() < $totalPrice) {
//            $_SESSION['missionEquipmentBought'] = 'false';
            $_SESSION['failureType'] = 'noMoney';
            $_SESSION['totalPrice'] = $totalPrice;
            header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']."&missionID=".$_GET['missionID']);
        exit();
     }
    
}else {
        if(isset($_GET['cityID'])){
            header("Location: {$serverRoot}choosemission.php?cityID=".$_GET['cityID']);
            exit();
        } else {
            header("Location: {$serverRoot}equipment.php?action=".$actionToDo."&status=success&itemid=$itemID".'&quantity_bought='.$request_quantity);
            exit();
        }
}
?>