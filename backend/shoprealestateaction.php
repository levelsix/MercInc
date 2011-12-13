<?php
session_start();
include_once("../classes/User.php");
include_once("../classes/RealEstate.php");
include_once("../properties/serverproperties.php");
include_once ('../properties/constants.php');
include_once ('../classes/UserTimers.php');
include_once("../classes/Item.php");
include_once '../classes/common_functions.php';


$fn = new common_functions();
$actionToDo = $_REQUEST['actionToDo'];
$userCash = $_REQUEST['userCash'];
$reID = $_REQUEST['realEstateID'];
$realEstate = RealEstate::getRealEstate($reID);
$incomeChange = $realEstate->getIncome();
$purchasePrice = $_REQUEST['purchasePrice'];
$quantity = 1;
$userOwnedQuantity = 0;

$user = User::getUser($_SESSION['userID']);

if(isset($_GET['quantity'])){
    $quantity = $_GET['quantity'];
    $userOwnedQuantity = $_GET['previousQuanity'];
    $totalQuantity = $quantity + $userOwnedQuantity;
    if($quantity > 1){
        $originalPrice = $_GET['originalPrice'];
		
        $totalPurchasePrice = 0;
        for($i = ($userOwnedQuantity + 1); $i <= $totalQuantity; $i++){
            $price = $originalPrice + (INCREASE_REAL_ESTATE_PERCENTAGE * $originalPrice) * $i;
            $totalPurchasePrice += $price;
        }
        $purchasePrice = $totalPurchasePrice;
    }
}
if ($actionToDo == 'buy' && ($userCash >= $purchasePrice)) {
        $timers = UserTimers::getTimers($_SESSION['userID']);
        if($timers){
            $income_timer = $timers->getIncomeTimer();    
            if(!$fn->isTimeSet($income_timer)){
                UserTimers::updateIncomeTimer($user->getID());
            }
        }
        $incomeChange *= $quantity;
        $user->incrementUserRealEstate($reID, $quantity);
		$user->updateUserCashAndIncome($purchasePrice*-1, $incomeChange);   
        header("Location: {$serverRoot}shoprealestatelist.php?action=buy&error=false&previousQuanity=".$userOwnedQuantity."&quantity=".$quantity."&itemID=".$reID."&purchasePrice=".$purchasePrice);
        exit;
} else if ($actionToDo == 'sell') {
        $sellBasePrice = $_REQUEST['sellBasePrice'];
		$user->decrementUserRealEstate($reID, 1);
        $user->updateUserCashAndIncome($sellBasePrice*SELL_RATIO, $incomeChange*-1);
        if(!Item::getHasItem($_SESSION['userID']) && !RealEstate::getHasRealEstate($_SESSION['userID'])) {
            $timers = UserTimers::getTimers($_SESSION['userID']);
            if($timers){
                $income_timer = $timers->getIncomeTimer();
                if($fn->isTimeSet($income_timer)){
                    UserTimers::stopIncomeTimer($user->getID());
                }
            }
            
        }
        header("Location: {$serverRoot}shoprealestatelist.php?action=sell&error=false&itemID=".$reID."&purchasePrice=".$purchasePrice);
        exit;
} else {
//	echo "{$serverRoot}shoprealestatelist.php?action=buy&error=true&previousQuanity=".$userOwnedQuantity."&quantity=".$quantity."&itemID=".$reID."&purchasePrice=".$_REQUEST['purchasePrice'];
	
   // header("Location: {$serverRoot}shoprealestatelist.php?action=buy&error=true&itemID=".$reID."&purchasePrice=".$purchasePrice);
   header("Location: {$serverRoot}shoprealestatelist.php?action=buy&error=true&previousQuanity=".$userOwnedQuantity."&quantity=".$quantity."&itemID=".$reID."&purchasePrice=".$_REQUEST['purchasePrice']);
    exit;
}
?>