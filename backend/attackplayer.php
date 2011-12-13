<?php 
ob_start();
session_start();
include_once("../classes/ConnectionFactory.php");
include_once("../properties/serverproperties.php");
include_once("../classes/User.php");
include_once("../classes/Item.php");
include_once("../classes/Utils.php");
include_once("../properties/constants.php");


//print_r($_GET);

$maxDamage = 24;
$id = $_SESSION['userID'];
$otherUserID = $_GET['userID'];
$attack_type = $_GET['attack_type'];
$_SESSION['otherUserID'] = $otherUserID;
$user = User::getUser($id);
//$healthLoss = -15;
$cashLost  = 0;
$winnerDemage = 0;
$looserDemage = 0;

/*
Let S = Attack or Defense skill points, based on whether the user is the attacker or defender
Let I = The total attack/defense of the items used in the battle, based on whether the user is the attacker or defender
Let A = The user’s agency size
Let F = The final combined stat (attack or defense)
Then F = RAND(ROUND(0.9 * (A * S + I / 4)), ROUND(1.1 * (A * S + I / 4)))
To put it into words, we take (skill points times agency size) and add (total item stats divided by 4), and then multiply by 0.9 and 1.1 and return a random number between those two totals.
Note that the S and I values are already passed into the computeStat() function and the function should return F. Note also that A (agency size) should be passed into computeStat() so the function header needs to be adjusted, as do the two calls to computeStat() in backend/attackplayer.php.
*/

function computeStat($skillPoints, $itemPoints ,$agencySize) {
	$randomizedStat = rand(round(0.9 *($agencySize*$skillPoints +$itemPoints/4)), round(1.1 *($agencySize*$skillPoints +$itemPoints/4)));
	return $randomizedStat;
}

function getItemStats($topItems, $statType) {
	$itemObjs = Item::getItems(array_keys($topItems), $statType);
	
	$totalStat = 0;
	foreach ($itemObjs as $item) {
		$itemID = $item->getID();
		if ($statType == "attack") {
			$stat = $item->getAtkBoost();
		} else if ($statType == "defense") {
			$stat = $item->getDefBoost();
		}
		$totalStat += $stat * $topItems[$itemID];
	}
	return $totalStat;
}


if(isset($attack_type) && $attack_type =="bounty") 
{
	$_SESSION['bounty'] = true;   
}
else
{
	unset($_SESSION['bounty']);
}

// Stamina check
$userStamina = $user->getStamina();
if ($userStamina <= 0) {
	$_SESSION['notEnoughStamina'] = 1;
	header("Location: {$serverRoot}battle.php?error_reason=notEnoughStamina&attack_type=".$attack_type);
	exit;
}

// Health checks
$userHealth = $user->getHealth();
//echo ( $userHealth .' '. ($maxDamage + 1) );
//die();

$otherUser = User::getUser($otherUserID);


if ($userHealth <= ($maxDamage + 1) ) {
	$_SESSION['notEnoughHealth'] = 1;
	header("Location: {$serverRoot}battle.php?error_reason=notEnoughHealth&attack_type=".$attack_type);
	exit;
}
$otherUserHealth = $otherUser->getHealth();
if( !isset( $_SESSION['bounty'] ) )  /* if it is not the bounty fight*/
{
	if ($otherUserHealth < ($maxDamage + 1)) {
		$_SESSION['otherNotEnoughHealth'] = 1;
		header("Location: {$serverRoot}battle.php?error_reason=otherNotEnoughHealth&attack_type=".$attack_type);
		exit;
	}
}
$userUsedItems = User::getUsersTopItemsByStatIDsToQuantity($id, $user->getAgencySize(), "attack");
$otherUserUsedItems = User::getUsersTopItemsByStatIDsToQuantity($otherUserID, $otherUser->getAgencySize(), "defense");

$userAttack = computeStat($user->getAttack(), getItemStats($userUsedItems, "attack"),$user->getAgencySize());
$otherUserDefense = computeStat($otherUser->getDefense(), getItemStats($otherUserUsedItems, "defense"),$otherUser->getAgencySize());


$val1 = max($userAttack, $otherUserDefense);
//echo '<br />';
$val2 = min($userAttack, $otherUserDefense);
$randomVal = rand($maxDamage - 10, $maxDamage);

$winnerDemage = $randomVal;
if($val1 != 0   || $val2 !=0)  
	$looserDemage = max(1, floor($val2/$val1 * $randomVal));
else 
	$looserDemage = 1;
	
$user->incrementNumAttacks();

switch ($user->getNumAttacks()) {
	case MONSTER_LEVEL_1: {
		$_SESSION['monster_level'] = 1;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	case MONSTER_LEVEL_2: {
		$_SESSION['monster_level'] = 2;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	case MONSTER_LEVEL_3: {
		$_SESSION['monster_level'] = 3;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	case MONSTER_LEVEL_4: {
		$_SESSION['monster_level'] = 4;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	case MONSTER_LEVEL_5: {
		$_SESSION['monster_level'] = 5;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	case MONSTER_LEVEL_6: {
		$_SESSION['monster_level'] = 6;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	case MONSTER_LEVEL_7: {
		$_SESSION['monster_level'] = 7;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	case MONSTER_LEVEL_8: {
		$_SESSION['monster_level'] = 8;
		User::updateAchievementRank('monster_level', $user->getID());
		break;
	}
	default: {
		if (isset($_SESSION['monster_level'])) {
			unset($_SESSION['monster_level']);
		}
	}
}

if ($userAttack > $otherUserDefense) { // user wins
	$_SESSION['won'] = 'true';
	$winner = $id;
	$loser = $otherUserID;
	$expGained = rand(1, 5);
	$_SESSION['expGained'] = $expGained;
	if( isset( $_SESSION['bounty'] ) )  /* if it was the bounty fight   */
	{
		User::updateKillsDeaths("win", $id);
		User::updateKillsDeaths("lose", $otherUserID);
		$user->incrementKills();
		$otherUser->incrementDeaths();
		$user->updateUserCash($_GET['bountyAmount']);
		$cashLost = $_GET['bountyAmount'];
		
		if($otherUser->getHealth()> 1)		
        	$winnerDemage = $otherUser->getHealth()-1;        
		
		if($otherUser->getHealth()<= 1)
			$winnerDemage =1;
			
	
		//echo	$winnerDemage.'<br />'	;
		//echo	$looserDemage	;
        $user->updateHealthStaminaFightsExperience($looserDemage*-1, -1, 0, 0, $expGained);
        $otherUser->updateHealthStaminaFightsExperience($winnerDemage*-1, 0, 0, 0, 0);
                
		/*
		 * Checking for markman achievement for current user
		 */
		switch ($user->getUserKills()) {
			case MARKSMAN_LEVEL_1:{
				$_SESSION['marksman_level'] = 1;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			case MARKSMAN_LEVEL_2:{
				$_SESSION['marksman_level'] = 2;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			case MARKSMAN_LEVEL_3:{
				$_SESSION['marksman_level'] = 3;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			case MARKSMAN_LEVEL_4:{
				$_SESSION['marksman_level'] = 4;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			case MARKSMAN_LEVEL_5:{
				$_SESSION['marksman_level'] = 5;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			case MARKSMAN_LEVEL_6:{
				$_SESSION['marksman_level'] = 6;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			case MARKSMAN_LEVEL_7:{
				$_SESSION['marksman_level'] = 7;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			case MARKSMAN_LEVEL_8:{
				$_SESSION['marksman_level'] = 8;
				User::updateAchievementRank('marksman_level', $user->getID());
				break;
			}
			default:{
				if(isset($_SESSION['marksman_level'])) {
					unset($_SESSION['marksman_level']);
				}
			}
		}
		
	}
	else{	
         $user->updateHealthStaminaFightsExperience($looserDemage*-1, -1, 1, 0, $expGained);
         $otherUser->updateHealthStaminaFightsExperience($winnerDemage*-1, 0, 0, 1, 0);	
		/*
		 * checking for fallguy achievement of other user
		 */
		switch ($otherUser->getFightsLost()) {
			case FALL_GUY_LEVEL_1:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 1);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
			case FALL_GUY_LEVEL_2:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 2);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
			case FALL_GUY_LEVEL_3:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 3);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
			case FALL_GUY_LEVEL_4:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 4);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
			case FALL_GUY_LEVEL_5:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 5);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
			case FALL_GUY_LEVEL_6:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 6);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
			case FALL_GUY_LEVEL_7:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 7);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
			case FALL_GUY_LEVEL_8:{
				User::addOfflineAchievement($otherUser->getID(), 'fallguy', 8);
				User::updateAchievementRank("fall_guy_level", $otherUser->getID());
				break;
			}
		}
		/* 
		 * checking for victor achievement for current user
		 */
		switch ($user->getFightsWon()) {
			case VICTOR_LEVEL_1:{
				$_SESSION['victor_level'] = 1;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			case VICTOR_LEVEL_2:{
				$_SESSION['victor_level'] = 2;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			case VICTOR_LEVEL_3:{
				$_SESSION['victor_level'] = 3;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			case VICTOR_LEVEL_4:{
				$_SESSION['victor_level'] = 4;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			case VICTOR_LEVEL_5:{
				$_SESSION['victor_level'] = 5;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			case VICTOR_LEVEL_6:{
				$_SESSION['victor_level'] = 6;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			case VICTOR_LEVEL_7:{
				$_SESSION['victor_level'] = 7;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			case VICTOR_LEVEL_8:{
				$_SESSION['victor_level'] = 8;
				User::updateAchievementRank('victor_level', $user->getID());
				break;
			}
			default:{
				if(isset($_SESSION['victor_level'])) {
					unset($_SESSION['victor_level']);
				}
			}
		}
		$cashLost = calculateMoneyLoss($otherUser->getCash(), $otherUser->getLevel());
		$user->updateUserCash($cashLost);
		$otherUser->updateUserCash($cashLost*-1);
		$user->battleHistory($id ,$_SESSION['otherUserID'], $winnerDemage, 1 ,$cashLost );
		unset($_SESSION['bounty']);
		}
		
} 
else { // user loses
	$_SESSION['won'] = 'false';
	$winner = $otherUserID;
	$loser = $id;
	$expGained = rand(1, 3);
       
       
	if(isset( $_SESSION['bounty'])) //// Bounty case 
	{
		User::updateKillsDeaths("win", $otherUserID);
		User::updateKillsDeaths("lose",$id);
		$user->incrementDeaths();
		$otherUser->incrementKills();
		//$cashLost = 0;
		$user->updateHealthStaminaFightsExperience($winnerDemage * -1, -1, 0, 0, 0);
        $otherUser->updateHealthStaminaFightsExperience($looserDemage *-1, 0, 0, 0, $expGained);
                
                
		/*
		 * Checking for other user's Marksman level
		 */
		switch ($otherUser->getUserKills()) {
			case MARKSMAN_LEVEL_1:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 1);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
			case MARKSMAN_LEVEL_2:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 2);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
			case MARKSMAN_LEVEL_3:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 3);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
			case MARKSMAN_LEVEL_4:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 4);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
			case MARKSMAN_LEVEL_5:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 5);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
			case MARKSMAN_LEVEL_6:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 6);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
			case MARKSMAN_LEVEL_7:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 7);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
			case MARKSMAN_LEVEL_8:{
				User::addOfflineAchievement($otherUser->getID(), 'marksman', 8);
				User::updateAchievementRank("marksman_level", $otherUser->getID());
				break;
			}
		}
		
	}
	else { // Battle case 
             $user->updateHealthStaminaFightsExperience($winnerDemage * -1, -1, 0, 1, 0);
             $otherUser->updateHealthStaminaFightsExperience($looserDemage * -1, 0, 1, 0, $expGained);
            	/*
		 * checking for other users victor achievement
		 */
		switch ($otherUser->getFightsWon()) {
			case VICTOR_LEVEL_1:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 1);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
			case VICTOR_LEVEL_2:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 2);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
			case VICTOR_LEVEL_3:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 3);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
			case VICTOR_LEVEL_4:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 4);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
			case VICTOR_LEVEL_5:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 5);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
			case VICTOR_LEVEL_6:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 6);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
			case VICTOR_LEVEL_7:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 7);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
			case VICTOR_LEVEL_8:{
				User::addOfflineAchievement($otherUser->getID(), 'victor', 8);
				User::updateAchievementRank("victor_level", $otherUser->getID());
				break;
			}
		}	
		/*
		 * Checking for current User's Fall Guy Level
		 */
		switch ($user->getFightsLost()) {
			case FALL_GUY_LEVEL_1:{
				$_SESSION['fallguy_level'] = 1;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			case FALL_GUY_LEVEL_2:{
				$_SESSION['fallguy_level'] = 2;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			case FALL_GUY_LEVEL_3:{
				$_SESSION['fallguy_level'] = 3;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			case FALL_GUY_LEVEL_4:{
				
				$_SESSION['fallguy_level'] = 4;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			case FALL_GUY_LEVEL_5:{
				$_SESSION['fallguy_level'] = 5;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			case FALL_GUY_LEVEL_6:{
				$_SESSION['fallguy_level'] = 6;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			case FALL_GUY_LEVEL_7:{
				$_SESSION['fallguy_level'] = 7;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			case FALL_GUY_LEVEL_8:{
				$_SESSION['fallguy_level'] = 8;
				User::updateAchievementRank("fall_guy_level", $user->getID());
				break;
			}
			default:{
				if(isset($_SESSION['fallguy_level'])) {
					unset($_SESSION['fallguy_level']);
				}
			}
		}
		//$cashLost = 0;
		$cashLost = calculateMoneyLoss($user->getCash(), $user->getLevel());
		$otherUser->updateUserCash($cashLost);
		$user->updateUserCash($cashLost*-1);
		$user->battleHistory($id ,$_SESSION['otherUserID'], $looserDemage, 0 ,$cashLost );
		unset($_SESSION['bounty']);
	}
}
$_SESSION['userUsedItems'] = $userUsedItems;
$_SESSION['otherUserUsedItems'] = $otherUserUsedItems;

// Level up check 
$skillPointsGained = checkLevelUp($user);
if ($skillPointsGained > 0) 
{	
	$_SESSION['levelUp'] = 1;
	$_SESSION['newLevel'] = $user->getLevel();
	$_SESSION['skillPointsGained'] = $skillPointsGained;
}

//include_once 'classes/ConnectionFactory.php';
//ConnectionFactory::printLog();
//$winnerDemage $looserDemage

header("Location: {$serverRoot}battle.php?attack_type=".$attack_type."&cash_lost=$cashLost&winner_demage=$winnerDemage&looser_demage=$looserDemage");
exit;
?>