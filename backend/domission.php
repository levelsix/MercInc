<?php
include_once("../classes/ConnectionFactory.php");
include_once("../classes/Mission.php");
include_once("../classes/User.php");
include_once("../classes/Item.php");
include_once("../classes/UserMissionData.php");
include_once("../classes/LevelExperiencePoints.php");
include_once ("../properties/serverproperties.php");
include_once("../properties/constants.php");
session_start();
/*
if(isset($_GET['firstmission']) && $_GET['firstmission'] == "true"){
    $firstMissionCash = rand(FIRST_MISSION_MIN_CASH, FIRST_MISSION_MAX_CASH);
    User::updateFirstMissionData($_SESSION['userID'], $firstMissionCash, FIRST_MISSION_EXPERIENCE_POINTS, FIRST_MISSION_ENERGY);
    $_SESSION['missionsuccess'] = true;
    $_SESSION['baseCashGained'] = $firstMissionCash;
    $_SESSION['baseExpGained'] = FIRST_MISSION_EXPERIENCE_POINTS;
    $_SESSION['energyLost'] = FIRST_MISSION_ENERGY;
    $_SESSION['firstmission'] = true;
    header("Location: ".$serverRoot."choosemission.php?firstmission=true");
    exit();
}
 */
function redirect($location) {
	header("Location: $location");
	exit;
}
function agencyIsLargeEnough($mission, $user) {
	
	$minAgencySize = $mission->getMinAgencySize();
	$playerAgencySize = $user->getAgencySize();
	
	$playerHasEnoughAgency = ($playerAgencySize >= $minAgencySize);
	if (!$playerHasEnoughAgency) {
		$_SESSION['needMoreAgency'] = $minAgencySize - $playerAgencySize;
	}
	return $playerHasEnoughAgency;
}
function playerHasEnoughEnergy($mission, $user) {
	$minEnergy = $mission->getEnergyCost();
	$playerEnergy = $user->getEnergy();
	$playerHasEnoughEnergy = $playerEnergy >= $minEnergy;
	if (!$playerHasEnoughEnergy) {
		$_SESSION['needMoreEnergy'] = $minEnergy - $playerEnergy;
	}

	return $playerHasEnoughEnergy;
}
function playerHasRequireditems($requiredItemIDsToQuantity, $userItemIDsToQuantity) {
	$itemsMissing = array();
	$playerHasAllRequiredItems = true;
		
	foreach ($requiredItemIDsToQuantity as $reqItemID => $quantityReq) {	

		if (array_key_exists($reqItemID, $userItemIDsToQuantity)) {
			$userQuantity = $userItemIDsToQuantity[$reqItemID];
			if ($userQuantity < $quantityReq)  {
				$playerHasAllRequiredItems = false;
				$amountMissing = $quantityReq - $userQuantity;
				$itemsMissing[$reqItemID] = $amountMissing;
			}
		} else {
			$playerHasAllRequiredItems = false;
			$itemsMissing[$reqItemID] = $quantityReq;
		}
	}
	if (!$playerHasAllRequiredItems) {
		$_SESSION['itemsMissing'] = $itemsMissing;
	}
	return $playerHasAllRequiredItems;
}
//under this model, cityrank doesnt increase until every missions currRank is ready at new number
//currRank should really be rankMissionIsReadyFor
function handleRanks($user, $mission) {
	$userMissionData = UserMissionData::getUserMissionData($user->getID(), $mission->getID());
	if (!$userMissionData) {
		$userMissionData = UserMissionData::createUserMissionData($user->getID(), $mission->getID());
	} else {
		$userMissionData->completeMission($mission);
	}
}




$missionID=$_REQUEST['missionID'];
$cityID = $_REQUEST['cityID'];
$userID=$_SESSION['userID'];


$mission = Mission::getMission($missionID);
if (!$mission) {
	redirect("{$serverRoot}errorpage.html");
        exit;
}

$user = User::getUser($userID);
if (!$user) {
	redirect("{$serverRoot}errorpage.html");
        exit;
}
$requiredItemIDsToQuantity = Mission::getMissionRequiredItemsIDsToQuantity($missionID);
$userItemIDsToQuantity = User::getUsersItemsIDsToQuantity($user->getID());

$doMission = true;
if (!agencyIsLargeEnough($mission, $user)) {
	$doMission = false;
}
if (!playerHasEnoughEnergy($mission, $user)) {
	$doMission = false;
}
if (!playerHasRequireditems($requiredItemIDsToQuantity, $userItemIDsToQuantity)) {
	$doMission = false;
}
function associateItemsWithIDs($items) {
	$toreturn = array();
	foreach ($items as $item) {
		$toreturn[$item->getID()] = $item;
	}
	return $toreturn;
}
if ($doMission) {
	$_SESSION['missionsuccess'] = "true";
        $_SESSION['missionsound'] = $mission->getSound();
	
	$itemsLost = array();
	$hasLostItems = false;
	$missionItems = Item::getItemIDsToItems(array_keys($requiredItemIDsToQuantity));
	foreach ($requiredItemIDsToQuantity as $reqItemID => $quantityReq) {
		$random = rand(0, 100);
		$missionItem = $missionItems[$reqItemID];
		$chanceLoss = $missionItem->getChanceOfLoss();		
		if ($random < $chanceLoss*100) {
			if (!$user->decrementUserItem($reqItemID, 1)) {
				redirect("{$serverRoot}errorpage.html");
                                exit;
			} else {
                                $upkeepAmount = Item::getItemUpkeep($reqItemID);
                                $user->decrementUserUpkeep($upkeepAmount);
				$userItemIDsToQuantity[$reqItemID]--;
				$hasLostItems = true;
				array_push($itemsLost, $reqItemID);
			}
		}
	}
	if ($hasLostItems) {
		$_SESSION['itemsLost'] = $itemsLost;
	}
	
	//energy lost
	$_SESSION['energyLost']=$mission->getEnergyCost();
	
	//loot gained
	$random = rand(0, 100);
	$chanceLoot = $mission->getChanceOfLoot();
	if (($random < $chanceLoot * 100) && ($mission->getLootItemID() > 0)) {
		$lootItemID = $mission->getLootItemID();
		if (!$user->incrementUserItem($lootItemID, 1)) {
			redirect("{$serverRoot}errorpage.html");
		} else {
			$userItemIDsToQuantity[$lootItemID]++;
			$_SESSION['gainedLootItemID'] = $lootItemID;
		}		
	}
	
	handleRanks($user, $mission);
	
	// Get multiplier based on whether or not the player completed a mission rank
	$multiplier = 1;
	if (isset($_SESSION['justUnlockedThisMissionRank'])) {
		$multiplier = $_SESSION['justUnlockedThisMissionRank'];
	}
	
	$cashGained = $mission->getRandomCashGained();
	$_SESSION['baseCashGained'] = $cashGained;
	
	$expGained = $mission->getExpGained();
	$_SESSION['baseExpGained'] = $expGained;
	
	if (isset($_SESSION['justUnlockedThisMissionRank'])) {
		$_SESSION['extraCashGained'] = $cashGained * ($multiplier - 1);
		$_SESSION['extraExpGained'] = $expGained * ($multiplier - 1);
	}
	
	if (!$user->updateUserEnergyCashExpCompletedmissions($mission->getEnergyCost(), $cashGained*$multiplier, $expGained*$multiplier)) {
		redirect("$serverRoot/errorpage.html");
	}
	else {
		switch ($user->getNumMissionsCompleted()) {
			case MASTER_LEVEL_1 : {
				$_SESSION['master_level'] = 1;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			case MASTER_LEVEL_2 : {
				$_SESSION['master_level'] = 2;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			case MASTER_LEVEL_3 : {
				$_SESSION['master_level'] = 3;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			case MASTER_LEVEL_4 : {
				$_SESSION['master_level'] = 4;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			case MASTER_LEVEL_5 : {
				$_SESSION['master_level'] = 5;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			case MASTER_LEVEL_6 : {
				$_SESSION['master_level'] = 6;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			case MASTER_LEVEL_7 : {
				$_SESSION['master_level'] = 7;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			case MASTER_LEVEL_8 : {
				$_SESSION['master_level'] = 8;
				User::updateAchievementRank('master_level', $user->getID());
				break;
			}
			default: {
				if (isset($_SESSION['master_level'])) {
					unset($_SESSION['master_level']);
				}
			}
			
		}
	}

	// Level up check
	$skillPointsGained = checkLevelUp($user);
        if ($skillPointsGained > 0 || $user->getLevel() < 5) {
		//$_SESSION['levelUp'] = 1;
		$_SESSION['newLevel'] = $user->getLevel();
	switch ($user->getLevel()) {
		case PRODIGY_LEVEL_1: {
			$_SESSION['prodigy_level'] = 1;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		case PRODIGY_LEVEL_2 : {
			$_SESSION['prodigy_level'] = 2;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		case PRODIGY_LEVEL_3: {
			$_SESSION['prodigy_level'] = 3;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		case PRODIGY_LEVEL_4 : {
			$_SESSION['prodigy_level'] = 4;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		case PRODIGY_LEVEL_5: {
			$_SESSION['prodigy_level'] = 5;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		case PRODIGY_LEVEL_6 : {
			$_SESSION['prodigy_level'] = 6;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		case PRODIGY_LEVEL_7: {
			$_SESSION['prodigy_level'] = 7;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		case PRODIGY_LEVEL_8 : {
			$_SESSION['prodigy_level'] = 8;
			User::updateAchievementRank('prodigy_level', $user->getID());
			break;
		}
		default:{
			unset($_SESSION['prodigy_level']);
			break;
		}
	}
                if($skillPointsGained > 0){
                    $_SESSION['skillPointsGained'] = $skillPointsGained;
                }
                if($_SESSION['newLevel'] == 3 && $user->getType() == ""){
                    $_SESSION['currentMissionCity'] = $_REQUEST['currentMissionCity'];
                    header("Location: ".$serverRoot."chooseplayername.php?page=mission&cityID=".$cityID."&missionID=".$missionID);
                    exit;
                }

	}
}
else {
	$_SESSION['missionfail'] = "true";
}
$_SESSION['currentMissionCity'] = $_REQUEST['currentMissionCity'];
$isFirst = "";

if(isset($_GET['firstmission']) && $_GET['firstmission'] == "true"){
    $isFirst = "&firstmission=true";
    //$_SESSION['firstmission'] = true;
}

header("Location: ".$serverRoot."choosemission.php?cityID=".$cityID."&missionID=".$missionID.$isFirst);

?>