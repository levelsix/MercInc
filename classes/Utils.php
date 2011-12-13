<?php
/*
 * returns the concatenation of each element in the array separated by
 * the delimiter
 */

function getArrayInString($array, $delim) {
	$arrlength = count($array);
	$toreturn = "";
	for ($i = 0; $i < $arrlength; $i++) {
		$toreturn .= $array[$i];
		if ($i != $arrlength-1) {
			$toreturn .= " " . $delim . " ";
		}
	}
	return $toreturn;
}
// Returns the number of skill points gained in a level up
// Returns 0 if no level up (i.e. no skill points gained)
// Updates the user object and the database if a level up occurs
function checkLevelUp($user) {

	$currLevel = $user->getLevel();
	$totalExp = $user->getExperience();
	$requireExp = $user->getNextLevelExperiencePoints();
	$refillEnergy = $user->getEnergyMax();
	$refillHealth = $user->getHealthMax();
	$refillStamina = $user->getStaminaMax();


	if($totalExp >= $requireExp){
		$newLevel = $currLevel + 1;
	}
	// Currently just takes 10 exp to level up at each level
	//$newLevel = floor($totalExp / 10);

	if ($newLevel > $currLevel) {
		$skillPointsGained = 0;
		if($newLevel > 4){
			$skillPointsGained = 3 * ($newLevel - $currLevel);
			if($newLevel % 5 == 0){
				$skillPointsGained += 2;
			}
		}

		$user->updateLevel($newLevel, $skillPointsGained);
		$user->refill($refillHealth, $refillEnergy, $refillStamina);
		$_SESSION['levelUp'] = true;
		return $skillPointsGained;
	}
	return 0;
}
// Returns n random integers in the range [0, $max)
function getRandomIntegers($n, $max) {
	$randomIntegers = array();
	while (count($randomIntegers) < $n) {
		$randomInt = rand(0, $max - 1);
		if (!isset($randomIntegers[$randomInt])) {
			$randomIntegers[$randomInt] = 1;
		}
	}
	return $randomIntegers;
}
function calculateHealCost($currentHealth , $maxHealth , $level)
{

	$health = $maxHealth - $currentHealth;
	$lvlValue = pow ( $level ,3 );
	return  ceil($lvlValue * $health * 0.05);
}
function calculateMoneyLoss($cash, $level)
{
	$randomValue = rand (1 ,2 );
	$money = round(min($cash * ($randomValue/10), $level * 75000));
	return $money;
}
function itemType($is_special, $is_common)
{
	if($is_special == 0 && $is_common == 0)
	return $type ='<span class="commonitem">Common</span>';

	if($is_special == 0 && $is_common == 1)
	return $type ='<span class="uncommonitem">Uncommon</span>';

	if($is_special == 1 && $is_common == 0)
	return $type ='<span class="rareitem">Rare</span>';

	if($is_special == 1 && $is_common == 1)
	return $type ='<span class="epicitem" style="color:#FF004E;">Epic</span>';
}
function diamondToCash($userLevel,$userCash)
{
	/*Let L = the user’s level
	 Let C = the final cash amount
	 C = CEILING(0.8 * L^3) * 1000
	 Where L^3 = L * L * L*/
	return ceil(0.8 * ($userLevel*$userLevel*$userLevel)) *1000;
}
function calculateNumDiamondsToCash($requiredCash,$userCash,$userLevel) {

	//$arrayReturn['cashForTenDiamonds'] = diamondToCash($userLevel,$userCash);
	 
	$cashForTenDiamonds = diamondToCash($userLevel,$userCash);
	if($cashForTenDiamonds >= $requiredCash ) {
		$arrayReturn['numDiamonds'] = 10;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*2) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 20;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*2;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*3) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 30;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*3;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*4) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 40;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*4;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*5) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 50;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*5;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*6) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 60;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*6;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*7) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 70;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*7;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*8) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 80;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*8;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*9) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 90;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*9;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*10) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 100;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*10;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*11) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 110;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*11;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*12) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 120;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*12;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*13) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 130;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*13;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*14) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 140;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*14;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*15) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 150;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*15;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*16) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 160;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*16;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*17) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 170;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*17;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*18) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 180;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*18;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*19) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 190;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*19;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*20) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 200;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*20;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*21) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 210;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*21;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*22) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 220;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*22;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*23) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 230;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*23;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*24) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 240;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*24;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*25) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 250;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*25;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*26) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 260;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*26;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*27) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 270;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*27;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*28) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 280;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*28;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*29) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 290;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*29;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*30) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 300;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*30;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*31) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 310;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*31;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*32) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 320;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*32;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*33) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 330;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*33;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*34) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 340;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*34;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*35) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 350;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*35;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*36) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 360;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*36;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*37) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 370;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*37;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*38) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 380;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*38;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*39) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 390;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*39;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*40) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 400;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*40;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*41) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 410;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*41;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*42) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 420;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*42;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*43) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 430;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*43;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*44) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 440;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*35;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*45) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 450;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*45;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*46) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 460;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*46;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*47) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 470;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*47;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*48) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 480;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*48;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*49) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 490;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*49;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*50) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 500;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*50;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*60) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 600;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*60;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*61) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 610;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*61;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*62) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 620;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*62;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*63) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 630;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*63;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*64) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 640;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*64;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*65) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 650;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*65;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*66) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 660;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*66;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*67) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 670;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*67;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*68) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 680;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*68;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*69) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 690;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*69;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*70) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 700;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*70;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*71) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 710;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*71;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*72) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 720;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*72;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*73) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 730;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*73;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*74) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 740;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*74;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*75) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 750;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*75;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*76) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 760;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*76;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*77) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 770;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*77;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*78) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 780;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*72;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*79) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 790;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*79;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*80) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 800;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*80;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*81) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 810;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*81;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*82) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 820;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*82;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*83) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 830;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*83;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*84) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 840;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*84;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*85) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 850;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*85;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*86) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 860;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*86;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*87) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 870;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*87;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*88) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 880;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*88;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*89) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 890;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*89;
		return $arrayReturn;
	}
	else if ( ($cashForTenDiamonds*90) >= $requiredCash) {
		$arrayReturn['numDiamonds'] = 900;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*90;
		return $arrayReturn;
	}
	else {
		$arrayReturn['numDiamonds'] = 2000;
		$arrayReturn['cashForDiamonds'] = $cashForTenDiamonds*200;
		return $arrayReturn;
	}
}
function getItemTypeToDiv($itemType)
{
	switch ($itemType) {
		case 1: {
			return "#one";
			break;
		}
		case 2: {
			return "#two";
			break;
		}
		case 3: {
			return "#three";
			break;
		}
		case 4: {
			return "#four";
			break;
		}
		default: {
			return "#one";
			break;
		}
	}
}


function getPurchasedNumDiamondsCost ($numDiamonds) {

	if($numDiamonds <= PACKAGE1_DIAMONDS_COUNT) {
		$arrayReturn['numberOfDiamonds'] = PACKAGE1_DIAMONDS_COUNT;
		$arrayReturn['costOfDiamonds'] = PACKAGE1_DIAMONDS_COST;
		$arrayReturn['packageID'] = PACKAGE1_DIAMONDS_ID;
		return $arrayReturn;
	}
	else if($numDiamonds <= PACKAGE2_DIAMONDS_COUNT) {
		$arrayReturn['numberOfDiamonds'] = PACKAGE2_DIAMONDS_COUNT;
		$arrayReturn['costOfDiamonds'] = PACKAGE2_DIAMONDS_COST;
		$arrayReturn['packageID'] = PACKAGE2_DIAMONDS_ID;
		return $arrayReturn;
	}
	else if($numDiamonds <= PACKAGE3_DIAMONDS_COUNT) {
		$arrayReturn['numberOfDiamonds'] = PACKAGE3_DIAMONDS_COUNT;
		$arrayReturn['costOfDiamonds'] = PACKAGE3_DIAMONDS_COST;
		$arrayReturn['packageID'] = PACKAGE3_DIAMONDS_ID;
		return $arrayReturn;
	}
	else if($numDiamonds <= PACKAGE4_DIAMONDS_COUNT) {
		$arrayReturn['numberOfDiamonds'] = PACKAGE4_DIAMONDS_COUNT;;
		$arrayReturn['costOfDiamonds'] = PACKAGE4_DIAMONDS_COST;
		$arrayReturn['packageID'] = PACKAGE4_DIAMONDS_ID;
		return $arrayReturn;
	}
	else if($numDiamonds <= PACKAGE5_DIAMONDS_COUNT) {
		$arrayReturn['numberOfDiamonds'] = PACKAGE5_DIAMONDS_COUNT;;
		$arrayReturn['costOfDiamonds'] = PACKAGE5_DIAMONDS_COST;
		$arrayReturn['packageID'] = PACKAGE5_DIAMONDS_ID;
		return $arrayReturn;
	}
}
function logMessageToFile($message, $filename) {
	$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/logs/'.$filename, 'a');
	fwrite($fp,$message."\n");
	fclose($fp);
}

function convertUrlQuery($query) { 
    $queryParts = explode('&', $query); 
    $params = array(); 
    foreach ($queryParts as $param) { 
        $item = explode('=', $param); 
        $params[$item[0]] = $item[1]; 
    } 
    
    return $params; 
} 



?>