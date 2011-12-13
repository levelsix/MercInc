<?php
include_once 'classes/Cities.php';
include_once 'classes/Mission.php';
include_once 'classes/UserMissionData.php';
include_once 'classes/User.php';
include_once 'properties/serverproperties.php';
include_once 'properties/constants.php';
include_once("topmenu.php");
$pageURL;
$encoded_url;
$redirectURL;
//$fn->printArray($_SESSION);
if($user->getLevel() >= 3 ){
    if( $user->getName()=='' )
    {
        echo "<script>location.href='{$serverRoot}chooseplayername.php'</script>";
        echo "exit;";
    } else if( $user->getType()== '' ) {
        echo "<script>location.href='{$serverRoot}chooseclasspage.php'</script>";
        echo "exit;";
    }
}
?>
<script language="javascript" type="text/javascript">
function purchaseDiamond(diamondToAdd)
{

	  <?php if(isset($_SESSION['legacy'])){ ?>
			window.location = "level6://develop.com?call=purchaseDiamondsForProductId:&param="+diamondToAdd;
					//window.location = "level6://develop.com?call=purchaseDiamonds:&param="+diamondToAdd;
	  <?php } else { ?>
				 displayAlertJS("Please update your current version to make in-app purchases");	  
	  <?php } ?>
}

function purchaseDiamonds(pageURL)
{
         <?php 
		global $user;
		$requiredDiamonds = ($diamondsArr['numDiamonds'] - $user->getDiamonds());
		$diamonCost = getPurchasedNumDiamondsCost($requiredDiamonds); 
	?>
	<?php if(strtolower($_SESSION['device_os']) != 'android'){ ?>
			$('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough diamonds. </p><p>Buy <?php echo PACKAGE1_DIAMONDS_COUNT; ?> diamonds for only $<?php echo PACKAGE1_DIAMONDS_COST; ?>!</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="javascript:" onClick="purchase(\''+pageURL+'\');" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
//	<a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> 
		//alert('You did not enter a deposit amount.');
	<?php } else { ?>
	
			$('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough diamonds. </p><p>Buy <?php echo PACKAGE1_DIAMONDS_COUNT; ?> diamonds for only $<?php echo PACKAGE1_DIAMONDS_COST; ?>!</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="<?php echo $serverRoot.'googlecheckout/checkout_redirect.php'; ?>?item_name=<?php echo PACKAGE1_DIAMONDS_COUNT.' Diamonds '; ?>&item_description=<?php echo 'Buy '.PACKAGE1_DIAMONDS_COUNT.' Diamonds for '.PACKAGE1_DIAMONDS_COST; ?>&diamonds_to_add= <?php echo PACKAGE1_DIAMONDS_COUNT; ?>&price=<?php echo PACKAGE1_DIAMONDS_COST; ?>&udid=<?php echo $_SESSION['udid']; ?>&callback_url='+pageURL+'" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
	
	<?php } ?>	
	$('.abshide').click();
}
function closePopUp()
{
	$('#fancybox-close').click();
}
function purchase(url)
{

	$('#fancybox-close').click();
	$('#notification').html('');
	redirectURL = url;
	purchaseDiamond("<?php echo PACKAGE1_DIAMONDS_ID; ?>");
}
</script>
<?php
//$userMissionExists = User::getMissionExists($_SESSION['userID']);
    if($user->getExperience() < 1){
        if(!isset($_GET['firstmission'])){
            header("location: firstmission.php");
            exit;
        }
    }
$requiredItemsPopupHTML = array();
function getCityNameFromCityID($cityID) {
    $citiesListObj = Cities::getAllCities();
    foreach ($citiesListObj as $cities) {
        if ($cityID == $cities->getID()) {
            return $cities->getName();
        }
    }
}
function showMoneyBoughtNotification() {
	global $serverRoot;
	$requestURL = $serverRoot.'backend/shopitemaction.php?actionToDo=bulkBuy&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID'];
	echo '
	<div class="successmessage">
    	<p class="successfailuremessage"><span class="success line_height_3" >Congratuations! </span> You have successfully got <span class="cost">$'.$_GET['cash'] .'</span> for <strong>'.$_GET['diamonds'].'</strong> Diamonds<a href="'.$requestURL.'" class="notification_domission">Buy Items</a></p>
    </div>';
}
function needEnergyNotifications($serverRoot,$user) {
    //$user = User::getUser($_SESSION['userID']);
    $html = '<div class="needenergy">
			<h2>Mission Incomplete</h2>
			<p>You only have :</p>
			<ul class="missingequipment">
                        <p class="energyleft"><strong>' . $user->getEnergy() . '</strong></p>
                        <p class="whatyouneed">You need ' .$_SESSION['needMoreEnergy']. ' more energy to complete that mission. Wait for your energy to reload or refill using Diamonds.</p>
                        <div class="refillinfo">
				<h4>Refill Energy</h4>
				<p>For <strong>'.DIAMOND_COUNT_FOR_REFILL_ENERGY.'</strong> Diamonds</p>
			</div>';
			if($user->getDiamonds() >= DIAMOND_COUNT_FOR_REFILL_ENERGY)
			{
				$html .='<div class="refillaccept">
						<a href="'.$serverRoot.'backend/blackmarket.php?refill=energy&page=backend/domission.php?&diamonds='.DIAMOND_COUNT_FOR_REFILL_ENERGY.'&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID'].'" class="greenbutton accepttrade">Accept Trade</a>
					</div>';
				
			}
			else{
		global $diamondsArr;
		$diamondsArr['numDiamonds'] = DIAMOND_COUNT_FOR_REFILL_ENERGY;
		$pageURL = $serverRoot.'backend/blackmarket.php?refill=energy&page=backend/domission.php?diamonds='.DIAMOND_COUNT_FOR_REFILL_ENERGY.'&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID'];
		
		$redirectURL =	$serverRoot.'backend/blackmarket.php?refill=energy&from=google&page='.base64_encode('backend/domission.php?diamonds='.DIAMOND_COUNT_FOR_REFILL_ENERGY.'&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID']);
		
		if(strtolower($_SESSION['device_os']) != 'android'){
					$html .='<div class="refillaccept">
					<a href="javascript:" onclick="purchaseDiamonds(\''.$pageURL.'\');" class="greenbutton accepttrade">Accept Trade</a>
				</div>';	
				}else{
					
					$html .='<div class="refillaccept">
					<a href="javascript:" onclick="purchaseDiamonds(\''.$redirectURL.'\');" class="greenbutton accepttrade">Accept Trade</a></div>';
					
				}
				

			}
             $html .=' </div>';
    unset($_SESSION['missionfail']);
    unset($_SESSION['needMoreEnergy']);
    return $html;
}
function showNeedMoreArmy($serverRoot){
    if (isset($_SESSION['needMoreAgency'])) {
        $html = '<div class="needbiggerarmy">
                    <p>You need a bigger army to complete that mission. Go <a href="'.$serverRoot.'invite.php">invite friends</a> to join your army.
                </div>';
        unset($_SESSION['needMoreAgency']);
        return $html;
    }
}
function showFailureNotifications($serverRoot) {
	global $serverRoot;
 if (isset($_SESSION['itemsMissing'])) {
    $html = '<div class="failmission">
			<h2>Mission Incomplete</h2>
			<p>You need the following to complete:</p>
			<ul class="missingequipment">';

   
        $itemsMissingArray = $_SESSION['itemsMissing'];
        $items = Item::getItems(array_keys($itemsMissingArray));
        $totalCost = 0;
        foreach ($items as $item) {
            $html .= '<li><img src=" '.$serverRoot.$item->getImage() .'" />' . $item->getName() . ' <em>x' . $itemsMissingArray[$item->getID()] . '</em></li>';
            $totalCost += $item->getPrice() * $itemsMissingArray[$item->getID()];
        }
        unset($_SESSION['itemsMissing']);
   
    unset($_SESSION['missionfail']);
    $requestURL = $serverRoot.'backend/shopitemaction.php?actionToDo=bulkBuy&totalPrice='.$totalCost.'&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID'];
    $html .= '      </ul>
                    <div class="buymissingequip">
                        <p>Buy the equipment for <span class="cash">$'.$totalCost.'</span></p>
                        <a class="blackyellowbutton" href="'.$requestURL.'">Buy Now</a>
                    </div>
                </div>';
    return $html;
 }
}

function showSuccessNotifications($serverRoot) {
    $html = '<div class="successmission">
        <h2 class="congrats">Congratulations!</h2>
        ';
    if (isset($_SESSION['levelUp'])) {
    	if(isset($_SESSION['prodigy_level'])) {
    		displayProdigyAchievement($_SESSION['prodigy_level']);	
    	}
       if(isset($_SESSION['levelUp'])){
            $html .= '<div class="skillpoints">
				<h4>You LVL up to ' . $_SESSION['newLevel'] . '!</h4>';
                if(isset($_SESSION['skillPointsGained']) && $_SESSION['skillPointsGained'] > 0)
				$html .= '<a class="blackbutton skillpointbutton" href="'.$serverRoot.'profile.php?selectedtab=skillpoints">Spend Skill Points &#187; </a>';
            $html .= '</div>';
       }
//            $skillPointsGained = $_SESSION['skillPointsGained'];
        unset($_SESSION['newLevel']);
        unset($_SESSION['levelUp']);
    } else {
        $html .= '<h2>Mission Accomplished!</h2>';
    }
    $html .= '<div class="gained">
                    <ul>
                        <li><h3>You gained</h3></li>
                        <li><em>+</em> $' . $_SESSION['baseCashGained'] . '</li>
                        <li><em>+</em> ' . $_SESSION['baseExpGained'] . ' Experience</li>';
    if(isset($_SESSION['skillPointsGained']) && $_SESSION['skillPointsGained'] > 0){
        $html .= '<li><em>+</em> ' . $_SESSION['skillPointsGained'] . ' Skill points</li>';
        unset($_SESSION['skillPointsGained']);
    }


    if (isset($_SESSION['gainedLootItemID'])) {
        $item = Item::getItem($_SESSION['gainedLootItemID']);
        $html .= '<li><em>+</em> 1 ' . $item->getName() . '</li>
                        <li><img src="'.$item->getImage().'" /></li>';
        unset($_SESSION['gainedLootItemID']);
    }

    $html .='    </ul>
                </div>
                <div class="used">
                    <ul>
                        <li><h3>You Lost </h3></li>';
    if (isset($_SESSION['itemsLost'])) {
        $itemsLostArray = $_SESSION['itemsLost'];

        $itemsLost = Item::getItems($itemsLostArray);
        foreach ($itemsLost as $item) {
            $html .= '<li>- ' . $item->getName() . '</li>';
        }
        unset($_SESSION['itemsLost']);
    }
    $html .= '<li>- ' . $_SESSION['energyLost'] . ' Energy</li>';

    if(!isset($_GET['firstmission'])){        
        $missionID = $_GET['missionID'];
        $cityID =  $_GET['cityID'];

        $html .= '</ul>
                        <a class="doagain" href="'.$serverRoot.'backend/domission.php?missionID=' . $missionID . '&currentMissionCity=' . $cityID . '&cityID=' . $cityID . '">Do Again</a>';
    }
    $html .= '</div> ';
    unset($_SESSION['baseCashGained']);
    unset($_SESSION['baseExpGained']);
    unset($_SESSION['energyLost']);
    unset($_SESSION['missionsuccess']);
    return $html;
}
function displayMasterAchievement ($numMissions,$rank) {
	global $user;
?>
	<div class="achievementunlocked">
	<div class="achievementstatus">
		<img src="img/glove.png" />
		<p>Rank: <?php echo $rank; ?></p>
	</div>
	<h2>Achievement Awarded!</h2>
	<p>You have earned the "<strong>Master</strong>" achievement by Completing <?php echo $numMissions; ?> missions.  You have been awarded: 1 Skill Point.</p>
</div>
<?php 	
$user->incrementUserSkillpoints(1);
}
function displayProdigyAchievement ($rank) {
	global $user;
?>
	<div class="achievementunlocked">
	<div class="achievementstatus">
		<img src="img/glove.png" />
		<p>Rank: <?php echo $rank; ?></p>
	</div>
	<h2>Achievement Awarded!</h2>
	<p>You have earned the "<strong>Prodigy</strong>" achievement by Reaching LVL <?php echo $_SESSION['newLevel']; ?> .  You have been awarded: 1 Skill Point.</p>
</div>
<?php 	
$user->incrementUserSkillpoints(1);
}
function displayMissions($playerLevel, $cityIDsToRankAvail, $currentCityID, $serverRoot, $requiredItemsPopupHTML) {
	global $user;
    if (isset($currentCityID)) {
        $visibleMissions = Mission::getMissionsInCityGivenPlayerLevel($playerLevel, $currentCityID);
        $visibleUnlockMissions = Mission::getUnlockMissionsInCity($playerLevel, $currentCityID);
        if (count($visibleMissions) == 0 && count($visibleUnlockMissions) == 0) {
            echo "No missions available in this city";
        } else {
            $cityRank = -1;
            foreach ($cityIDsToRankAvail as $cityID => $rankAvail) {
                if ($cityID == $currentCityID) {
                    $cityRank = $cityIDsToRankAvail[$cityID];
                }
            }
            foreach ($visibleMissions as $visibleMission) {
                displayMissionInfo($visibleMission, $playerLevel, $cityRank, $currentCityID, $serverRoot, $requiredItemsPopupHTML);
            }
            foreach ($visibleUnlockMissions as $visibleUnlockMission) {
                displayMissionInfo($visibleUnlockMission, $playerLevel, $cityRank, $currentCityID, $serverRoot, $requiredItemsPopupHTML);
            }
        }
    } else {
    }
}
function itemRequiredPopUp($item, $quantity, $requiredItemsPopupHTML, $serverRoot, $cityID, $playerLevel ){
	global $user;
    if($playerLevel < 4){
        $itemPopUPhtml = '<div style="display: none;">
                    <div id="inline'.$item->getID().$quantity.'-'.$cityID.'"    >
                    <h5 class="popupweaponheader"> Equipment</h5>
                    <p style="text-align:center;">
                        <br>
                        You need more experience before 
                        you can buy equipment.
                        <br>
                        "Come back at LVL 4"
                    </p>
                    <br>
                            
            </div>';
            echo $itemPopUPhtml;
    }
    $itemQuantity = 0;
    if($item->getQuanitybyUserId($user->getID()) > 0) {
    	$itemQuantity = $item->getQuanitybyUserId($user->getID());
    }
    $itemPopUPhtml = '<div style="display: none;">
                    <div id="inline'.$item->getID().$quantity.'-'.$cityID.'" style="width:98%;">
                    <h5 class="popupweaponheader">'.$item->getName().'</h5>
                            <div class="popuppicture">
                                    <img src="'.$item->getImage().'">
                            </div>
                            <div class="popupinfo">
                                    <ul class="weaponinfo">
                                            <li>$'.$item->getPrice().'</li>
                                            <li>Attack: '.$item->getAtkBoost().'</li>
                                            <li>Defense: '.$item->getDefBoost().'</li>
                                            <li>Owned: '.$itemQuantity.'</li>
                                    </ul>
                            </div>
                            <div class="popupbuy">
                                    <p>'.$quantity.' for<br />
                                    <em>$'.($item->getPrice() * $quantity).'</em></p>
                                    <a class="blackbutton popupbuybutton" href="'.$serverRoot.'backend/shopitemaction.php?actionToDo=buy&storePrice='.$item->getPrice().'&itemID='.$item->getID().'&request_quantity='.$quantity.'&cityID='.$cityID.'">Buy</a>
                    </div>
            </div>';
   echo $itemPopUPhtml;
    //array_push($requiredItemsPopupHTML, $itemPopUPhtml);
    //print_r($requiredItemsPopupHTML);
    
}
function displayMissionInfo($mission, $playerLevel, $cityRank, $currentCityID, $serverRoot, $requiredItemsPopupHTML) {
    $isMissionLocked = false;
    if (missionIsLocked($mission, $playerLevel)) {
        $isMissionLocked = true;
    }
    $missionID = $mission->getID();
    if($isMissionLocked){
        ?>
        <div class="lockedupgeneric">
		<h3><?php echo $mission->getName(); ?></h3>
		<p>Unlock at LVL <?php echo $mission->getMinLevel(); ?></p>
	</div>
        <?php
    } else {
    ?>
    <div class="mission">
        <table class="topmission">
            <tr>
                <td>
                <h4><?php
                        echo $mission->getName().
                        $isMissionLocked;
                    ?>
                </h4>
                    <?php
                        $userMissionData = UserMissionData::getUserMissionData($_SESSION['userID'], $mission->getID());
                        $completionPercent;
                        if ($cityRank == 4) {
                            $completionPercent = 100;
                            $cityRank = 3;
                        } else {
                            $userTimesMissionDoneInThisRank = 0;
                            if ($userMissionData) {
                                $userTimesMissionDoneInThisRank = $userMissionData->getRankTimes($cityRank);
                            }
                            $missionTimesToMasterRank = $mission->getRankReqTimes($cityRank);
                            if ($userTimesMissionDoneInThisRank >= $missionTimesToMasterRank) {
                                $completionPercent = 100;
                            } else {
                                $completionPercent = number_format($userTimesMissionDoneInThisRank / $missionTimesToMasterRank, 2) * 100;
                            }
                        }
                    ?>
                </td>
                <td>
                <div class="missionrank">
                        <img src="img/percentimage4.png"
                                 alt="9.5%"
                                 height="9"
                                 class="percentImageMissions"
                                 style="background-position: <?php echo (100 - $completionPercent); ?>% 0pt;" />
                        <p><?php echo $completionPercent; ?>% Rank <?php echo $cityRank; ?></p>
                </div>
                </td>
            </tr>
        </table>
        
        <div class="missioninfo">
            <ul>
                <li><strong>+ $<?php echo $mission->getMinCashGained(); ?> - $<?php echo $mission->getMaxCashGained(); ?></strong></li>
                <li>+ <?php echo $mission->getExpGained(); ?> Experience</li>

                <?php
                $lootItemID = $mission->getLootItemID();
                if ($lootItemID) {
                    $lootItem = Item::getItem($lootItemID);
                    ?>
                    <li><em> Chance of Loot</em></li>

                    <?php
                }

                $itemIDsToQuantity = Mission::getMissionRequiredItemsIDsToQuantity($missionID);
                $itemIDsToItems = Item::getItemIDsToItems(array_keys($itemIDsToQuantity));
                $userItemsdetails = User::getUsersItemsIDsToQuantity($_SESSION['userID']);
                $requiredItemsHTML = '';
                foreach ($itemIDsToQuantity as $key => $value) {
                    $item = $itemIDsToItems[$key];
                    if (isset($userItemsdetails[$item->getID()]) && $userItemsdetails[$item->getID()] >= $value) {
                        $requiredItemsHTML .= '<li><img src="'.$item->getImage().'">x' . $value . ' </li>';
                    } else {
                        $requiredItemsHTML .= '<li><a class="inlinecontent" href="#inline'.$item->getID().$value.'-'.$mission->getCityID().'"><img src="'.$item->getImage().'"><span>x' . $value . '</span></a></li>';
                        itemRequiredPopUp($item, $value, $requiredItemsPopupHTML, $serverRoot, $mission->getCityID(), $playerLevel);
                    }
                }

                if (!missionIsLocked($mission, $playerLevel)) {
                    $url = $serverRoot.'backend/domission.php?missionID='.$mission->getID().'&currentMissionCity='.$currentCityID.'&cityID='.$currentCityID.'&energyRequired='.$mission->getEnergyCost();
                    ?>
                </ul>
                <div class="domission">
                    <a href="<?php echo $url; ?>">
                        Do Mission</a>
                </div>
                <?php
            }
            ?>

        </div>
        <div class="missionreq">
            <h5>Required <em><?php echo $mission->getEnergyCost(); ?> Energy</em></h5>
            <ul class="reqitems">
                <?php echo $requiredItemsHTML; ?>
            </ul>
        </div>
    </div>
	<?php
    }
}
function missionIsLocked($mission, $playerLevel) {
    if ($mission->getMinLevel() > $playerLevel) {
        return true;
    }
    return false;
}

function listCities($cityIDsToRankAvail) {

    //
    $html = '';
    $tabIDs = array('one', 'two', 'three', 'four');
    $IDcounter = 0;
    foreach ($cityIDsToRankAvail as $cityID => $rankAvail) {
        $cityName = getCityNameFromCityID($cityID);
        $html .= '<li class="tab"><a href="#'.$tabIDs[$IDcounter++].'" >' . $cityName . '</a></li>';
    }
    return $html;
}

function showJustUnlockedMissionRank() {
    $justUnlockedMissionRank = $_SESSION['justUnlockedThisMissionRank'];
     $html = '<h5>Mission Mastered!</h5>
                <p class="masteredinfo">You have been rewarded with an extra
                    <span class="cash">$'.$_SESSION['extraCashGained'].'</span>,
                    and '.$_SESSION['extraExpGained'].' experience.
                    <strong>Master all the "'.getCityNameFromCityID($_SESSION['currentMissionCity']).'" missions to unlock exclusive rewards.</strong>
                </p>';
    unset($_SESSION['justUnlockedRankMissionName']);
    unset($_SESSION['extraCashGained']);
    unset($_SESSION['extraExpGained']);
    unset($_SESSION['justUnlockedThisMissionRank']);
    return $html;    
}
function showJustUnlockedCityRank() {
    $justUnlockedCityRank = $_SESSION['justUnlockedThisCityRank'];
    $html = '<h6>Congrats! You Ranked Up!</h6>
                <p class="masteredinfo">
                    You have mastered all of the Rank '.($justUnlockedCityRank - 1).' "'.getCityNameFromCityID($_SESSION['currentMissionCity']).'" missions</p>
                     ';                                
    unset($_SESSION['justUnlockedThisCityRank']);
    return $html;
}
function showFirstMissionNotifications(){
    $html = '<div class="quicktut">
                    <h3>You\'re becoming a great soldier</h3>
                    <ul>
                            <li>Missions earn cash and experience.</li>
                            <li>Each mission requires energy and equipment.</li>
                    </ul>
            </div>';
    unset($_SESSION['firstmission']);
    return $html;
}

$notifications = '';

if (isset($_SESSION['master_level'])) {
	$numMissions = $user->getNumMissionsCompleted();
	displayMasterAchievement($numMissions, $_SESSION['master_level']);
	$user->incrementUserSkillpoints(1);
	unset($_SESSION['master_level']);
}

if(isset($_SESSION['missionEquipmentBought'])){
    if($_SESSION['missionEquipmentBought'] == 'true' && !isset($_SESSION['failureType'])){
        $url = $serverRoot.'backend/domission.php?missionID='.$_GET['missionID'].'&currentMissionCity='.$_GET['cityID'].'&cityID='.$_GET['cityID'];
        $notifications = '<div class="successmessage">
                            <p class="successfailuremessage"><span class="success line_height_3" >Success!</span> '.$_SESSION['missionEquipmentBoughtDesc'].'<a href="'.$url.'" class="notification_domission">Do Mission</a>
                        </p>
                        </div>';
        unset ($_SESSION['missionEquipmentBoughtDesc']);
    } else if($_SESSION['missionEquipmentBought'] == 'true' && isset($_SESSION['failureType']) && $_SESSION['failureType'] == 'lockedItem') {
    	$notifications = '<div class="successmessage">
                    <p class="successfailuremessage"><span class="failure">Failure!</span> Some locked items cannot be bought. </p>
                </div>';
    	unset ($_SESSION['failureType']);
    }
    unset ($_SESSION['missionEquipmentBought']);
}
     else if (isset($_SESSION['failureType'])) {
     	/*
     	 * to determine the type of failure it can be either because all the items were locked. or user doesnt have enough money
     	 */
    	switch ($_SESSION['failureType']) { 		
    		case "lockedItem" : {
    			$notifications = '<div class="successmessage">
                    <p class="successfailuremessage"><span class="failure">Failure!</span> All the items you requested are currently locked.</p>
                </div>';
    			break;
    		}
    		case "noMoney" : {
				unset ($_SESSION['failureType']);
    			$totalPrice = $_SESSION['totalPrice'];
    			$requiredCash = ($totalPrice - $user->getCash());
    			$diamondsArr = calculateNumDiamondsToCash($requiredCash,$user->getCash(),$user->getLevel());
    			$notifications = 
    			'<div class="needenergy">
					<h2>Not enough Money</h2> 
					<p class="whatyouneed">You currently have $'.$user->getCash().'</p>
                        <p class="whatyouneed">You need additional $'.$requiredCash.' to buy these equipments. </p>
                        <div class="refillinfo">
                			<h4>Recieve <span class="cost"> $'.$diamondsArr['cashForDiamonds'].'</span></h4>
							<p>For <strong>10</strong> Diamonds</p>
						</div>';
						
						if($user->getDiamonds() >= $diamondsArr['numDiamonds'])
						{
							$notifications .='
							<div class="refillaccept">
									<a href="'.$serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&page=choosemission.php?&diamonds='.$diamondsArr['numDiamonds'].'&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID'].'" class="greenbutton accepttrade">Accept Trade</a>
							</div>';
							
						}
						else
						{
							$pageURL = $serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&page=choosemission.php?&diamonds='.$diamondsArr['numDiamonds'].'&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID'];

                                                        $redirectURL =	$serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&from=google&page='.base64_encode('choosemission.php?&diamonds='.$diamondsArr['numDiamonds'].'&cityID='.$_GET['cityID'].'&missionID='.$_GET['missionID']);

                                                        if(strtolower($_SESSION['device_os']) != 'android'){
                                                        $notifications .='<div class="refillaccept">
                                                            <a href="javascript:" onclick="purchaseDiamonds(\''.$pageURL.'\');" class="greenbutton accepttrade">Accept Trade</a>
                                                        </div>';	
                                                        }else{

                                                                $notifications .='<div class="refillaccept">
                                                                <a href="javascript:" onclick="purchaseDiamonds(\''.$redirectURL.'\');" class="greenbutton accepttrade">Accept Trade</a></div>';

                                                        }						
						}
    			break;
    		}
                case "notEnoughLevel" : {
                    $notifications = '<div class="successmessage">
                        <p class="successfailuremessage"><span class="failure">Failure!</span> Your level is less than miminum level to shop for items</p>
                    </div>';
                    break;
                }
    		default:
    			$notifications = '<div class="successmessage">
                    <p class="successfailuremessage"><span class="failure">Failure!</span> Failure.. Unable to perform this operation at the moment</p>
                </div>';
    		break;
    	}
        unset ($_SESSION['failureType']);
    }
if (isset($_REQUEST['postedCityID'])) {
    $_SESSION['currentMissionCity'] = $_REQUEST['postedCityID'];
}
if(isset($_GET['status']) && $_GET['status']== "success") {
	showMoneyBoughtNotification();
}
if (isset($_SESSION['needMoreEnergy']) && $_SESSION['missionfail'] == true) {
    $notifications .= needEnergyNotifications($serverRoot,$user);
}

if (isset($_SESSION['missionfail']) && $_SESSION['missionfail'] == true) {
        $notifications .= showNeedMoreArmy($serverRoot);
        $notifications .= showFailureNotifications($serverRoot);
}

if(isset($_SESSION['firstmission'])){
    $notifications .= showFirstMissionNotifications();
}

if (isset($_SESSION['missionsuccess']) && $_SESSION['missionsuccess'] == true) {
    $notifications .= showSuccessNotifications($serverRoot);
}

if (isset($_SESSION['justUnlockedThisMissionRank'])) {
    $notifications .= showJustUnlockedMissionRank();
}

if (isset($_SESSION['justUnlockedThisCityRank'])) {
    $notifications .= showJustUnlockedCityRank();
}

$cityIDsToRankAvail = User::getAvailableCityIDsToRankAvail($_SESSION['userID']);

$notifications .= '</div>';
?>
<div style="display: none;">
		<div id="inline1" style=""></div>
	</div>
	<a class="inlinecontent abshide" href="#inline1"> </a>

<div id="notification">
    <?php echo $notifications; ?>
</div>
<div id="tabs">
    <div class="container missionsmenu">
        <ul id="menu2">
            <?php echo listCities($cityIDsToRankAvail); ?>
        </ul>
    </div>
    <div id="one">
        <?php
            $currentCityID = 1;
            $nextMissionLevel = 0;
            if(Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)){
                $nextMissionLevel = Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)->getMinLevel();
            }
            if($nextMissionLevel > 0 ) {
                echo '<p class="unlock2"><span>Unlock more missions at LVL'.$nextMissionLevel.'</span></p>';
            }
            displayMissions($playerLevel, $cityIDsToRankAvail, $currentCityID, $serverRoot, $requiredItemsPopupHTML);
        ?>
    </div>
    <div id="two">
        <?php
            $currentCityID = 2;
            $nextMissionLevel = 0;
            if(Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)){
                $nextMissionLevel = Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)->getMinLevel();
            }
            if($nextMissionLevel > 0 ) {
                echo '<p class="unlock2"><span>Unlock more missions at LVL'.$nextMissionLevel.'</span></p>';
            }
            displayMissions($playerLevel, $cityIDsToRankAvail, $currentCityID, $serverRoot, $requiredItemsPopupHTML);
        ?>
    </div>
    <div id="three">
        <?php
            $currentCityID = 3;
            $nextMissionLevel = 0;
            if(Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)){
                $nextMissionLevel = Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)->getMinLevel();
            }
            if($nextMissionLevel > 0 ) {
                echo '<p class="unlock2"><span>Unlock more missions at LVL'.$nextMissionLevel.'</span></p>';
            }
            displayMissions($playerLevel, $cityIDsToRankAvail, $currentCityID, $serverRoot, $requiredItemsPopupHTML);
        ?>
    </div>
    <div id="four">
        <?php
            $currentCityID = 4;
            $nextMissionLevel = 0;
            if(Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)){
                $nextMissionLevel = Mission::getNextUnlockMissionLevel($playerLevel, $currentCityID)->getMinLevel();
            }
            if($nextMissionLevel > 0 ) {
                echo '<p class="unlock2"><span>Unlock more missions at LVL'.$nextMissionLevel.'</span></p>';
            }
            displayMissions($playerLevel, $cityIDsToRankAvail, $currentCityID, $serverRoot, $requiredItemsPopupHTML);
        ?>
    </div>
    

</div>
    <script>
    $( "#tabs" ).tabs({ 
        selected: <?php if(isset($_GET['cityID'])) { echo ($_GET['cityID'] - 1); } else { echo '0';} ?> }
    );
    $( "#tabs" ).tabs({
        select: function(event, ui) {
            $('#notification').html(' ');
            $('.achievementunlocked').html(' ');
        }
    });
     function closePpoup(){
	$('#fancybox-close').click();
    }

</script>
    <?php
    include_once 'footer.php';
?>
