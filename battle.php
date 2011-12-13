<?php
include_once('topmenu.php');
include_once ('properties/serverproperties.php');
include_once ('properties/constants.php');
include_once("classes/User.php");
include_once("classes/Item.php");
include_once("classes/Bounty.php");
include_once("classes/Utils.php");
include_once("properties/playertypeproperties.php");
$pageURL = '';
?>

<script language="javascript" type="text/javascript">

    function purchaseDiamond(diamondToAdd)
    {
<?php if (strtolower($deviceOS) != 'android') { ?>
    <?php if (isset($_SESSION['legacy'])) { ?>
                      window.location = "level6://develop.com?call=purchaseDiamondsForProductId:&param="+diamondToAdd;
                      //window.location = "level6://develop.com?call=purchaseDiamonds:&param="+diamondToAdd;
    <?php } else { ?>
                      displayAlertJS("Please update your current version to make in-app purchases");
    <?php } ?>
<?php } ?>	
    }


    // this method is called when user purchased diamonds successfully.
    function purchaseDiamonds(pageURL)
    {
<?php if (strtolower($deviceOS) != 'android') { ?>
                    $('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough diamonds. </p><p>Buy <?php echo PACKAGE1_DIAMONDS_COUNT; ?> diamonds for only $<?php echo PACKAGE1_DIAMONDS_COST; ?>!</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="javascript:" onClick="purchase(\''+pageURL+'\');" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
                    //	<a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> 
                    //alert('You did not enter a deposit amount.');
<?php } else { ?>
    	          
                    $('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough diamonds. </p><p>Buy <?php echo PACKAGE1_DIAMONDS_COUNT; ?> diamonds for only $<?php echo PACKAGE1_DIAMONDS_COST; ?>!</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="<?php echo $serverRoot.'googlecheckout/checkout_redirect.php'; ?>?item_name=<?php echo PACKAGE1_DIAMONDS_COUNT . ' Diamonds'; ?>&item_description=<?php echo 'Buy ' . PACKAGE1_DIAMONDS_COUNT . ' Diamonds for ' . PACKAGE1_DIAMONDS_COST; ?>&diamonds_to_add= <?php echo PACKAGE1_DIAMONDS_COUNT; ?>&price=<?php echo PACKAGE1_DIAMONDS_COST; ?>&udid=<?php echo $_SESSION['udid']; ?>&callback_url='+pageURL+'" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
    	
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
                //	diamondPurchased(1);
                purchaseDiamond("<?php echo PACKAGE1_DIAMONDS_ID; ?>");
            }

</script>


<?php
$battleMinLevel = 3;
if (isset($_SESSION['levelError'])) {
    //echo $_SESSION['levelError'];
    unset($_SESSION['levelError']);
    exit;
} else if ($playerLevel < $battleMinLevel) {
    $tooLowLevelString = '<div class="natsays">
	<h2>Attack</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can start fighting."</p>
	<p>"Come back at LVL ' . $battleMinLevel . '"</p>
	<a onclick="playSound(' . SOUND_CLICK . ')"  href="' . $serverRoot . 'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
    echo $tooLowLevelString;
    exit;
}
if ($user->is_first_battle == 0) {
    header("location: firstbattle.php");
    exit;
}

$bounties = Bounty::getBountiesCountForUser($user->getID());
$numBounties = count($bounties);

function displayNormalAttack($user, $serverRoot) {
    $enemy_html = '';
    $opponents = $user->getPotentialOpponents();
    if ($opponents) {
        $enemy_html = '<div id="one"><table class="pvp"><tr>
		<th>Opponent</th>
		<th class="armysize">Army Size</th>
		<th></th>
		</tr>';
        // treat each value as a row received from a PDO fetch
        foreach ($opponents as $opponent) {
            $id = $opponent->getID();
            $name = $opponent->getName();
            $opposingLevel = $opponent->getLevel();
            $opposingAgencySize = $opponent->getAgencySize();
            if ($user->checkSameUsersInAgency($_SESSION['userID'], $id) == false && $_SESSION['userID'] != $id) {
                $enemy_html .= '<tr>
                <td class="oppname">
                    <h5><a  onclick="playSound(' . SOUND_CLICK . ')" href="' . $serverRoot . 'externalplayerprofile.php?userID=' . $id . '">' . $name . '</a></h5>
                    <p>LVL ' . $opposingLevel . ' ' . getPlayerTypeFromTypeID($opponent->getType()) . '</p>
                </td>
                <td class="oppsize"><p>' . $opposingAgencySize . '</p></td>
                <td class="oppattack"><a href="javascript:" onclick="playAndRedirect(\'\',\'' . $serverRoot . 'backend/attackplayer.php?userID=' . $id . '&attack_type=normal\')" class="blackbutton">Attack</a></td>
            </tr>';
            }
        }
        $enemy_html .= '</table></div>';
    }
    echo $enemy_html;
}

function displayBountyAttack($user, $serverRoot) {

    $bounties = Bounty::getBountiesForUser($user->getID());
    $numBounties = count($bounties);
    $enemy_html = '';
    if ($numBounties > 0) {
        $enemy_html = '<div id="two">
		<table class="pvp hitlist">
		<tr>
			<th>Opponent</th>
			<th class="armysize">Bounty</th>
			<th></th>
		</tr>';
        foreach ($bounties as $bounty) {

            $id = $bounty->getTargetID();
            //is this safe..
            $userName = $bounty->name;
            $bountyAmount = $bounty->bounty;
            if ($user->checkSameUsersInAgency($_SESSION['userID'], $id) == false && $_SESSION['userID'] != $id) {
                $enemy_html .= '<tr>
                <td class="oppname">
                    <h5><a  onclick="playSound(' . SOUND_CLICK . ')"  href="' . $serverRoot . 'externalplayerprofile.php?userID=' . $id . '">' . $userName . '</a></h5>
					<p>LVL ' . $bounty->level . ' ' . getPlayerTypeFromTypeID($bounty->type) . '</p>

			    </td>
                <td class="oppsize"><p>$' . $bountyAmount . '</p></td>
                <td class="oppattack"><a  href="javascript:" onclick="playAndRedirect(\'\',\'' . $serverRoot . 'backend/attackplayer.php?userID=' . $id . '&attack_type=bounty&bountyAmount=' . $bountyAmount . '\')" class="blackbutton">Attack</a></td>
            </tr>';
            }

            //$_SESSION['bountyAmount'] = $bountyAmount;
        }
        $enemy_html .= '</table></div>';
    }
    echo $enemy_html;
}

/* function listItems($itemObjs, $itemIDsToQuantity) {	
  if (count($itemObjs) <= 0) echo "Nothing.";
  foreach ($itemObjs as $item) {
  echo $itemIDsToQuantity[$item->getID()] . "x " . $item->getName() . " ";
  }
  echo "<br>";
  } */
//$_SESSION['otherUserID'] = 30;
//echo $_SESSION['otherUserID'];
if (isset($_SESSION['otherUserID'])) {
    $otherUser = User::getUser($_SESSION['otherUserID']);
}

//$user = User::getUser($_SESSION['userID']);
$result_html = '';
$yourmobhtml = '';
$theirmobhtml = '';
$otherUserID = 0;
$cashLost = $fn->get('cash_lost');
$winnerDemage = $fn->get('winner_demage');
$looserDemage = $fn->get('looser_demage');
$firstBattle = $fn->get('first_battle');
$addBounty = $fn->get('addBounty');


// Session checks
if (isset($_SESSION['notEnoughHealth']) && $firstBattle == '') {
    //echo "You don't have enough health to battle! Please go heal yourself first. <br>";

    $result_html .= '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>You do not have enough health to battle!</h3>
			<p>Your only have <span class="healthinfo">' . $user->getHealth() . '/' . $user->getHealthMax() . '</span> health.</p>
			<a onclick="playSound(' . SOUND_CLICK . ')" href="' . $serverRoot . 'hospital.php" class="visithospital">Go to the Hospital</a>
			</div>
		</div>
	</div>';
    unset($_SESSION['notEnoughHealth']);
}
if (isset($_SESSION['otherNotEnoughHealth']) && $firstBattle == '') {
    //echo "Sorry, their health is too low to battle. Please choose a different target. <br>";
    $result_html .= '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>Sorry, their health is too low to battle. Please choose a different target.!</h3>
			</div>
		</div>
	</div>';
    unset($_SESSION['otherNotEnoughHealth']);
}

if ($addBounty) {
    //echo "You don't have enough stamina to battle! Please try again later. <br>";
    //$targetID =  $otherUser->get('targetID');
    //$otherUserName = $otherUser->name;
    $result_html .= '<div id="notification">
		<div class="notificationbox successbox">
			<h3>$' . $addBounty . ' is successfull added against 
                         <a onclick="playSound(' . SOUND_CLICK . ')"  href="' . $serverRoot . 'externalplayerprofile.php?userID=' . $otherUser->getID() . '" class="profile"">' . $otherUser->getName() . '</a>    
                        </h3>
			</div>
		</div>
	</div>';
    unset($_SESSION['notEnoughStamina']);
}
if (isset($_SESSION['notEnoughStamina']) && $firstBattle == '') {
    //echo "You don't have enough stamina to battle! Please try again later. <br>";
    $result_html .= '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>You don\'t have enough stamina to battle!</h3>
			<div class="refillinfo">
                                <h4>Refill Stamina</h4>
                                <p>For <strong>' . DIAMOND_COUNT_FOR_REFILL_ENERGY . '</strong> Diamonds</p>                        
				</div>';

    if ($user->getDiamonds() >= DIAMOND_COUNT_FOR_REFILL_STAMINA) {
        $result_html .='
				<a href="' . $serverRoot . 'backend/blackmarket.php?refill=stamina&page=backend/attackplayer.php?&diamonds=' . DIAMOND_COUNT_FOR_REFILL_STAMINA . '&userID=' . $_SESSION['otherUserID'] . '&attack_type=' . $_GET['attack_type'] . '" class="greenbutton accepttrade">Accept Trade</a>
			';
    } else {
        $pageURL = $serverRoot . 'backend/blackmarket.php?refill=stamina&page=backend/attackplayer.php?&diamonds=' . DIAMOND_COUNT_FOR_REFILL_STAMINA . '&userID=' . $_SESSION['otherUserID'] . '&attack_type=' . $_GET['attack_type'];

        $redirectURL = $serverRoot . 'backend/blackmarket.php?refill=stamina&from=google&page=' . base64_encode('backend/attackplayer.php?diamonds=' . DIAMOND_COUNT_FOR_REFILL_STAMINA . '&userID=' . $_SESSION['otherUserID'] . '&attack_type=' . $_GET['attack_type']);

        if (strtolower($_SESSION['device_os']) != 'android') {
            $result_html .='<div class="refillaccept">
				<a href="javascript:" onclick="purchaseDiamonds(\'' . $pageURL . '\');" class="greenbutton accepttrade">Accept Trade</a></div>';
        } else {
            $result_html .='<div class="refillaccept">
				<a href="javascript:" onclick="purchaseDiamonds(\'' . $redirectURL . '\');" class="greenbutton accepttrade">Accept Trade</a></div>';
        }
    }
    $result_html .='</div></div>';
    unset($_SESSION['notEnoughStamina']);
}
if (isset($_SESSION['won']) && $firstBattle == '') {
    if ($_SESSION['won'] == 'true') {
        //echo "Congratulations! You won! <br>";
        //echo "You gained " . $_SESSION['expGained'] . " experience! <br>";
        $_SESSION['play_attack_sound'] = SOUND_ATTACK;
        $result_html .= '<div id="notification">
		<div class="notificationbox successbox">
			<p><span class="won">You won the fight,</span>
			taking <strong>' . $looserDemage . ' damage</strong> while dealing <strong>' . $winnerDemage . ' damage</strong> to <a onclick="playSound(' . SOUND_CLICK . ')"  href="' . $serverRoot . 'externalplayerprofile.php?userID=' . $_SESSION['otherUserID'] . '" class="profile">' . $otherUser->getName() . '</a>.</p>
			 <p>You took <span class="cash">$' . $cashLost . '</span>, and gained <strong>' . $_SESSION['expGained'] . '</strong> experience points.</p>
			 ';
        if (isset($_SESSION['bounty'])) {
            //$user->updateUserCash($_SESSION['bountyAmount']);
            $update = Bounty::disableBountyAfterUser($_SESSION['otherUserID']);
        } else {
            
        }
    } else {
        $_SESSION['play_attack_sound'] = SOUND_ATTACK;
        $result_html .= ' <div id="notification">
		<div class="notificationbox notifybox">
		<p><span class="lost">You lost the fight,</span> taking <strong>' . $winnerDemage . ' damage</strong> while dealing <strong>' . $looserDemage . ' damage</strong> to  <a onclick="playSound(' . SOUND_CLICK . ')"  href="' . $serverRoot . 'externalplayerprofile.php?userID=' . $_SESSION['otherUserID'] . '" class="profile">' . $otherUser->getName() . '</a>.</p>
		<!--<p>You loose <span class="cash">$' . $cashLost . ' cash</span>, and gained <strong>0</strong> experience points.</p>-->';
    }
    unset($_SESSION['won']);
}

if ($firstBattle) {
    $_SESSION['play_attack_sound'] = SOUND_ATTACK;
    /* $result_html .= '<div id="notification">
      <div class="successmission">
      <h2 class="congrats">Congratulations!</h2>
      <div class="skillpoints">
      <h4>You leveled up to  4!</h4>
      <ul  style="padding:15px;">
      <li><em>+</em> 21 Experience</li>
      </ul>
      </div>
      </div>'; */
    $result_html .= '<div id="notification">
		<div class="notificationbox successbox">
			<p><span class="won">You won the fight,</span>
			taking <strong> ' . $_GET['winner_demage'] . ' damage</strong> while dealing <strong> ' . $_GET['looser_demage'] . ' damage</strong> to ' . DUMMY_SOLDIER_NAME_FOR_BATTLE . '.</p>
			<p>You took <span class="cash">$' . $_GET['cash_lost'] . ' cash</span>, and gained <strong>' . $_SESSION['expGained'] . '</strong> experience points.</p></div></div>';

    /* $result_html .= '<div id="notification">
      <div class="notificationbox successbox">
      <p><span class="won">Unlock Black Market</span></p>
      <p>'.BLACK_MARKET_UNLOCK_MSG.'<a href="'.$serverRoot.'blackmarket.php"  class="profile"> black market </a>'.BLACK_MARKET_UNLOCK_MSG1.'</p>
      </div></div>';
      $_SESSION['newLevel'] = 4;
      $user->addDiamonds(20,$user->getID()); */
}

/*
 * checking if the achievement earned or not
 */
if (isset($_SESSION['fallguy_level'])) {
    ?>
    <div class="achievementunlocked">
        <div class="achievementstatus">
            <img src="img/glove.png" />
            <p>Rank: <?php echo $_SESSION['fallguy_level']; ?></p>
        </div>
        <h2>Achievement Awarded!</h2>
        <p>You've earned the "<strong>Fall Guy</strong>" achievement by dying <?php echo $user->getFightsLost(); ?> times.  You have been awarded: 1 Skill Point.</p>
    </div>
    <?php
    $user->incrementUserSkillpoints(1);
    unset($_SESSION['fallguy_level']);
} else if (isset($_SESSION['marksman_level'])) {
    ?>	
    <div class="achievementunlocked">
        <div class="achievementstatus">
            <img src="img/glove.png" />
            <p>Rank: <?php echo $_SESSION ['marksman_level']; ?></p>
        </div>
        <h2>Achievement Awarded!</h2>
        <p>You've earned the "<strong>Marks Man</strong>" achievement by killing <?php echo $user->getUserkills(); ?> people.  You have been awarded: 1 Skill Point.</p>
    </div>	

    <?php
    $user->incrementUserSkillpoints(1);
    unset($_SESSION['marksman_level']);
} else if (isset($_SESSION['victor_level'])) { //checking for victor level acheivement
    ?>	
    <div class="achievementunlocked">
        <div class="achievementstatus">
            <img src="img/glove.png" />
            <p>Rank: <?php echo $_SESSION['victor_level']; ?></p>
        </div>
        <h2>Achievement Awarded!</h2>
        <p>You've earned the "<strong>Victor</strong>" achievement by winning <?php echo $user->getFightsWon(); ?> Fights.  You have been awarded: 1 Skill Point.</p>
    </div>	

    <?php
    $user->incrementUserSkillpoints(1);
    unset($_SESSION['victor_level']);
}
//checking if the monster level achievement is earned or not.
if (isset($_SESSION['monster_level'])) {
    ?>
    <div class="achievementunlocked">
        <div class="achievementstatus">
            <img src="img/glove.png" />
            <p>Rank: <?php echo $_SESSION['monster_level']; ?></p>
        </div>
        <h2>Achievement Awarded!</h2>
        <p>You've earned the "<strong>Monster</strong>" achievement by initiating <?php echo $user->getNumAttacks(); ?> Fights.  You have been awarded: 1 Skill Point.</p>
    </div>	
    <?php
    $user->incrementUserSkillpoints(1);
    unset($_SESSION['monster_level']);
}

// Level up check
if (isset($_SESSION['levelUp'])) {

    $newLevel = $user->getLevel(); //$_SESSION['newLevel'];
    $skillPointsGained = 0;
    if (isset($_SESSION['skillPointsGained'])) {
        $skillPointsGained = $_SESSION['skillPointsGained'];
    }

    switch ($newLevel) {           //checking for prodigy acheivement
        case PRODIGY_LEVEL_1: {
                displayProdigyAchievement($newLevel, 1);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        case PRODIGY_LEVEL_2 : {
                displayProdigyAchievement($newLevel, 2);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        case PRODIGY_LEVEL_3: {
                displayProdigyAchievement($newLevel, 3);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        case PRODIGY_LEVEL_4 : {
                displayProdigyAchievement($newLevel, 4);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        case PRODIGY_LEVEL_5: {
                displayProdigyAchievement($newLevel, 5);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        case PRODIGY_LEVEL_6 : {
                displayProdigyAchievement($newLevel, 6);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        case PRODIGY_LEVEL_7: {
                displayProdigyAchievement($newLevel, 7);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        case PRODIGY_LEVEL_8 : {
                displayProdigyAchievement($newLevel, 8);
                User::updateAchievementRank('prodigy_level', $user->getID());
                break;
            }
        default: {
                unset($_SESSION['levelUp']);
                unset($_SESSION['newLevel']);
                break;
            }
    }
    include_once("levelupnotice.php");
    unset($_SESSION['newLevel']);
    unset($_SESSION['skillPointsGained']);
    unset($_SESSION['levelUp']);
}

function displayProdigyAchievement($level, $rank) {
    global $user;
    ?>
    <div class="achievementunlocked">
        <div class="achievementstatus">
            <img src="img/glove.png" />
            <p>Rank: <?php echo $rank; ?></p>
        </div>
        <h2>Achievement Awarded!</h2>
        <p>You have earned the "<strong>Prodigy</strong>" achievement by Reaching LVL <?php echo $level; ?>.  You have been awarded: 1 Skill Point.</p>
    </div>
    <?php
    $user->incrementUserSkillpoints(1);
}

function ItemAtkCmp($item1, $item2) {
    return $item1->getAtkBoost() - $item2->getAtkBoost();
}

function listItems($itemObjs, $itemIDsToQuantity) {
    $html = '';
    if (count($itemObjs) <= 0) {
        //echo "Nothing.";
        $html .= '<li>No item used</li>';
    } else {
        foreach ($itemObjs as $item) {
            //echo $itemIDsToQuantity[$item->getID()] . "x " . $item->getName() . " ";
            $html .= '<li><img src="' . $item->getitemurl() . '"/>' . $item->getName() . ' x' . $itemIDsToQuantity[$item->getID()] . '</li>';
        }
    }

    return $html;
}

// display the items used in the battle
if (isset($_SESSION['userUsedItems']) && isset($_SESSION['otherUserUsedItems'])) {
    $userItemObjs = Item::getItems(array_keys($_SESSION['userUsedItems']));
    $otherUserItemObjs = Item::getItems(array_keys($_SESSION['otherUserUsedItems']));

    // usort returns a bool
    usort($userItemObjs, "Item::ItemAtkCmp");
    usort($otherUserItemObjs, "Item::ItemDefCmp");

    //echo "Your mob of " . $user->getAgencySize() . " used <br>";

    $yourmobhtml .='<ul class="weaponsused">
				<li><h3>Your mob used:</h3></li>';
    if (listItems($userItemObjs, $_SESSION['userUsedItems']) != '') {
        $yourmobhtml .=listItems($userItemObjs, $_SESSION['userUsedItems']);
    } else {
        $yourmobhtml .= '<li>Your opponent use no weapon</li>';
    }
    $yourmobhtml.=' </ul> ';


    // TODO make this a link to the other user's profile page
    //echo $otherUser->getName() . "'s mob of " . $otherUser->getAgencySize() . " used <br>";

    $theirmobhtml.='<ul class="weaponsused">
				<li><h3>Their mob used:</h3></li>';
    if (listItems($otherUserItemObjs, $_SESSION['otherUserUsedItems']) != '') {
        $theirmobhtml.=listItems($otherUserItemObjs, $_SESSION['otherUserUsedItems']);
    } else {
        $theirmobhtml .= '<li>Your Opponent use no weapon</li>';
    }
    $theirmobhtml.=' </ul> ';
    $result_html .= $yourmobhtml . $theirmobhtml . '
			<div class="leavecomment">
				<textarea placeholder="Leave a comment..." id="toggleSection" maxlength = "250" style="display:none;"></textarea>				
				<a href="#" id="toggleButton" class="blackbutton">Post Comment</a>
			</div>';

    if (isset($_SESSION['bounty'])) {
        //echo "bountyAmount".$_SESSION['bountyAmount'];
        unset($_SESSION['bountyAmount']);
        $bounties = Bounty::getBountiesCountForUser($user->getID());
        $numBounties = count($bounties);
    } else {
        $result_html .= ' <div class="attackagain">
									<a href="javascript:" onclick="playAndRedirect(\'' . SOUND_ATTACK . '\',\'' . $serverRoot . 'backend/attackplayer.php?userID=' . $_SESSION['otherUserID'] . '&attack_type=normal\')" class="blackbutton">Attack Again</a>
								</div>';
    }
    $result_html .= '</div>
		</div>';


    $otherUserID = $_SESSION['otherUserID'];
    unset($_SESSION['userUsedItems']);
    unset($_SESSION['otherUserUsedItems']);
    unset($_SESSION['otherUserID']);
}

echo $result_html;

if ($user->getType()) {
    ?>

    <!--<div style="display:none;" >
    	<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/battle.php' method='POST'>
    <input type='hidden' name='battleTab' value='normal' />
    <input type='submit' value='Attack an Enemy Agency'/>
    	</form>
    	<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/battle.php' method='POST'>
    <input type='hidden' name='battleTab' value='bounty' />
    <input type='submit' value='Check the Bounty List'/>
    	</form>
    </div>
    -->
    <div id="tabs">
        <div class="container missionsmenu pvp">
            <ul id="menu2" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                <li class="tab ui-state-default ui-corner-top  ui-tabs-selected ui-state-active">
    <!--                         <a href="<?php echo $serverRoot; ?>battle.php?battleTab=normal">Attack Rival Armies
                    </a>
                    -->
                    <a href="#one">Attack Rival Armies</a>
                </li>
                <li class="tab ui-state-default ui-corner-top">
                    <!--                            
                                                            <a href="<?php echo $serverRoot; ?>battle.php?battleTab=bounty">Hit List (<?php echo $numBounties ?>)</a>
                    -->
                    <a id="hitlist" class="" href="#two">Hit List (<?php echo $numBounties ?>)</a>

                </li>
            </ul>
        </div>
    <?php
    displayNormalAttack($user, $serverRoot);
    displayBountyAttack($user, $serverRoot);
    ?>

    </div> 



    <div style="display: none;">
        <div id="inline1" style="">

        </div>
    </div>

    <a class="inlinecontent abshide" href="#inline1"> </a>

    <!--<ul id="menu2">
    			<li class="tab"><a href="battle.php?battleTab=normal">Attack Rival Armies</a></li>
    			<li class="tab"><a href="battle.php?battleTab=bounty">Hit List() </a></li>
    		</ul>
    	</div>	-->


    <?php
    /*
      if (isset($_GET['battleTab'])) {
      if ($_GET['battleTab'] == 'normal') {
      displayNormalAttack($user,$serverRoot);
      }
      if ($_GET['battleTab'] == 'bounty') {
      displayBountyAttack($user,$serverRoot);
      }
      } else {
      if (isset($_SESSION['battleTab'])) {
      if ($_SESSION['battleTab'] == 'bounty') {
      displayBountyAttack($user,$serverRoot);
      }
      unset($_SESSION['battleTab']);
      } else {
      displayNormalAttack($user,$serverRoot);
      }
      }
     */
} else {
    echo "<script>location.href='.$serverRoot.'chooseclasspage.php'</script>";
}


if ($numBounties <= 0) {
    ?>
    <script>
        $('#hitlist').addClass('disabled');	
    </script>	
    <?php
}
if (isset($_GET['attack_type'])) {
    if ($_GET['attack_type'] == 'bounty') {
        ?>

        <script>
        <?php if ($numBounties > 0) { ?>		
                                $( "#tabs" ).tabs({
                                    selected: 1
                                });
        <?php } else { ?>
                                $( "#tabs" ).tabs({
                                    selected: 0
                                });
        <?php } ?>
        			
        			
        </script>


        <?php
    }
}
?>
<script>
    $( "#tabs" ).tabs({
<?php if ($numBounties > 0) { ?>
                            select: function(event, ui) {
                                $('#notification').html(' ');
                                $('#hitlist').removeClass('disabled');
                            }
<?php } else { ?>
                            $('#hitlist').addClass('disabled');
<?php } ?>
                    });
</script>
<script language="javascript">
    $(document).ready(function() {
        $('#toggleButton').click(function() {
            if( $('#toggleSection').css('display')!='none' ){
                if($('#toggleSection').val() != '')
                { 
                    var frmData = 'receiver_id='+<?php echo $otherUserID ?> +'&sender_id='+ <?php echo $_SESSION['userID'] ?> +'&content='+$('#toggleSection').val();
                    contentsAjaxObj = callAjax('ajaxComents.php', frmData,'' , "showSuccess( 'Congratulations','Comments Posted successfully');" , 'POST');
                }
                else
                {
                    showSuccess('Error','Please Enter your comment');
                }
	    			
            }
	    	  	
            $('#toggleSection').toggle();
            return false;
        });
             
<?php if (isset($_SESSION['play_attack_sound'])) { ?>
                         playSound('<?php echo SOUND_ATTACK ?>');
    <?php unset($_SESSION['play_attack_sound']);
} ?>                     
	     
                 });
                 function showSuccess(title,message)
                 {
                    displayAlertJS(message);
                    //alert('message added successfully');
                    // $('#inline1').html('<h5 class="popupweaponheader">'+title +'</h5><br/><p> '+ message +'</p><div class="popupbuy"  style="margin:10px auto 0 auto;"><a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
                    // $('.abshide').click();
		
                     $('#toggleSection').val(' ');
                 }
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
//$fn-> printArray($_SESSION);
?>