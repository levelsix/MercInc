<?php
include_once 'topmenu.php';
include_once 'classes/Comments.php';
include_once("classes/User.php");
include_once 'properties/playertypeproperties.php';

$homeMinLevel = 2;
if(isset($_SESSION['levelError']))
{
    echo $_SESSION['levelError'];
    unset($_SESSION['levelError']);
    exit;
}
else if ($playerLevel<$homeMinLevel)
{
    $tooLowLevelString = '<div class="natsays">
	<h2>Home</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can visit this page."</p>
	<p>"Come back at LVL '.$homeMinLevel.'"</p>
	<a  onclick="playSound('.SOUND_CLICK.')" href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
    echo $tooLowLevelString;
    exit;
} 


function loggedInYesterday($login) {
	$today = date('Y-m-d') . " 08:00:00";
	$yesterday = date('Y-m-d', time() - (60 * 60 * 24)) . " 08:00:00";

	$currentTime = date('H:i:s');
	if ($currentTime < "08:00:00") {
		$today = date('Y-m-d', time() - (60 * 60 * 24)) . " 08:00:00";
		$yesterday = date('Y-m-d', time() - (2 * 60 * 60 * 24)) . " 08:00:00";
	}

	if ($login < $today && $login > $yesterday)  {
		return true;
	}
	return false;
}

function getWeeklyBonusItemID() {
	// Stub implementation
	return 1;
}

$lastLogin = $user->getLastLogin();

$currentDate = date('Y-m-d H:i:s');
$currentTime = date('H:i:s');
$dailyBonusDate = date('Y-m-d') . " 08:00:00";
if ($currentTime < "08:00:00") {
	// Use yesterday's date as daily bonus time
	$dailyBonusDate = date('Y-m-d', time() - (60 * 60 * 24)) . " 08:00:00";
}

$loggedInYesterday = loggedInYesterday($lastLogin);

if (strcmp($currentDate, $dailyBonusDate) >= 0) {
	// <= or < here?
	if (strcmp($lastLogin, $dailyBonusDate) < 0) {
		$dailyBonusAmount = rand(500, 10000);

		if (!$loggedInYesterday) { // User didn't log in yesterday, cash bonus only
			$user->updateCashNumConsecLogin($dailyBonusAmount, 1);
			$_SESSION['dailyBonus'] = $dailyBonusAmount;
		} else { // User did log in yesterday
			// Daily bonus over the week and weekly bonus
			$pastDailyBonuses = $user->getDailyBonuses();

			$numRows = count($pastDailyBonuses);

			// No daily bonuses for this user yet
			if ($numRows == 0) {
				// Insert a row for this user in the user_dailybonuses table
				$user->updateDailyBonus($dailyBonusAmount, 0);

				// Update the user's cash, num consec days logged in, and login time
				$user->updateCashNumConsecLogin($dailyBonusAmount, 1);
				$_SESSION['dailyBonus'] = $dailyBonusAmount;
			} else { // Update daily bonuses for this user
				$numConsecDays = $user->getNumConsecDaysPlayed();

				// Get the daily bonuses and pass it into the session to display
				$_SESSION['allPastBonuses'] = $pastDailyBonuses;
				unset($_SESSION['allPastBonuses']['user_id']); // Don't pass along the user ID

				$user->updateDailyBonus($dailyBonusAmount, $numConsecDays);

				if ($numConsecDays == 6) {
					// weekly bonus
					$weeklyBonusItemID = getWeeklyBonusItemID();

					//updateUserItems($db, $id, $weeklyBonusItemID);

					$user->updateCashNumConsecLogin(0, 0);

					$_SESSION['weeklyBonus'] = $weeklyBonusItemID;
				} else { // cash bonus only
					$user->updateCashNumConsecLogin($dailyBonusAmount, $numConsecDays + 1);
					$_SESSION['dailyBonus'] = $dailyBonusAmount;
				}
			}
		}
	} else {
		$user->updateLogin();
	}
} else {
	$user->updateLogin();
}


// Daily and weekly bonus check
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";
if (isset($_SESSION['allPastBonuses'])) {
    $i = 1;
    foreach ($_SESSION['allPastBonuses'] as $value) {
        if ($value == 0)
            break;        
        $i++;
    }
    unset($_SESSION['allPastBonuses']);
}
 

if (isset($_SESSION['levelError'])) {
     print $_SESSION['levelError'] ;
     unset($_SESSION['levelError']);
}

if (isset($_SESSION['dailyBonus'])) {
   $message = "You found $" . $_SESSION['dailyBonus'] . " cash as daily bonus.";
    bonuses('Daily Bonus', $message);

    unset($_SESSION['dailyBonus']);
} else if (isset($_SESSION['weeklyBonus'])) {
    $item = Item::getItem($_SESSION['weeklyBonus']);
    $message = "For playing the last 7 days, you received one " . $item->getName() . "!";
    bonuses('Weekly Bonus', $message);
    unset($_SESSION['weeklyBonus']);
}
function bonuses($bonusType, $message){

    ?>
    <script>
        $(document).ready(function() {
            $('#inline1').html('<h5 class="popupweaponheader"><?php echo $bonusType; ?></h5><br/><p> <?php echo $message; ?></p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
            //alert('You did not enter a deposit amount.');
            $('#inline1').show();
            $('.abshide').click();
            console.log($('#inline1'));
        });
         function closePpoup(){
			$('#fancybox-close').click();
    	}
		
    </script>
    <?php
}
function getFallguyDeaths ($rank) {
	switch ($rank) {
		case 1: {
			return FALL_GUY_LEVEL_1;
			break;
		}
		case 2:{
			return FALL_GUY_LEVEL_2;
			break;
		}
		case 3: {
			return FALL_GUY_LEVEL_3;
			break;
		}
		case 4:{
			return FALL_GUY_LEVEL_4;
			break;
		}
		case 5: {
			return FALL_GUY_LEVEL_5;
			break;
		}
		case 6:{
			return FALL_GUY_LEVEL_6;
			break;
		}
		case 7: {
			return FALL_GUY_LEVEL_7;
			break;
		}
		case 8:{
			return FALL_GUY_LEVEL_8;
			break;
		}
	}
}
function getVictorWins($rank) {
	switch ($rank) {
		case 1: {
			return VICTOR_LEVEL_1;
			break;
		}
		case 2:{
			return VICTOR_LEVEL_2;
			break;
		}
		case 3: {
			return VICTOR_LEVEL_3;
			break;
		}
		case 4:{
			return VICTOR_LEVEL_4;
			break;
		}
		case 5: {
			return VICTOR_LEVEL_5;
			break;
		}
		case 6:{
			return VICTOR_LEVEL_6;
			break;
		}
		case 7: {
			return VICTOR_LEVEL_7;
			break;
		}
		case 8:{
			return VICTOR_LEVEL_8;
			break;
		}
	}
}
function getMarksmanKills ($rank) {
	switch ($rank) {
		case 1: {
			return MARKSMAN_LEVEL_1;
			break;
		}
		case 2:{
			return MARKSMAN_LEVEL_2;
			break;
		}
		case 3: {
			return MARKSMAN_LEVEL_3;
			break;
		}
		case 4:{
			return MARKSMAN_LEVEL_4;
			break;
		}
		case 5: {
			return MARKSMAN_LEVEL_5;
			break;
		}
		case 6:{
			return MARKSMAN_LEVEL_6;
			break;
		}
		case 7: {
			return MARKSMAN_LEVEL_7;
			break;
		}
		case 8:{
			return MARKSMAN_LEVEL_8;
			break;
		}
	}
}
/*
 * Getting all arrrays to show in bottom notifications
 */
$myComments = Comments::getUserComments($user->getID());
$battleHistory = User::getBattleHistory($_SESSION['userID']);
$broadcastmsg = $user->getBroadcastMessages();
// this function needs to be change 
function displayComment($index) {
			global $myComments;
			$sender = User::getUser($myComments[$index]->getSenderID ());
    		$senderName = $sender->getName();
    		$commentID = $myComments[$index]->getCommentID ();
    		$content = $myComments[$index]->getContent();
			$html = '<div class="newsinfo">
                <p class="infonews"><strong><a href="#">'.$senderName.'</a> commented</strong><br />
                    "'.$content.'"
                </p>	
            </div>';
            echo $html;
}
function displayBroadcast($index) {
		global $broadCasts;
		$sender = User::getUser($broadCasts[$index]['sender_id']);
                $senderName = $sender->getName();
                $senderID = $sender->getID(); 
                $content = $broadCasts[$index]["content"];
                $msgID = $broadCasts[$index]["id"];
		$html = 
				'<div class="newsinfo">
                	<p class="infonews"><strong><a href="#">'.$senderName.'</a> broadcasted</strong><br />
                  	  "'.$content.'"
               	 </p>	
            	</div>';
		echo $html;
}
function displayBattle($index) {
		global $battleHistory;
		$user_id = $battleHistory[$index]->user_id;
		//$rival_name = '';//$battle->rival_name;
		$opponent_id = $battleHistory[$index]->opponent_id;
		$damage_taken = $battleHistory[$index]->damage_taken;
		$won = $battleHistory[$index]->won.'<br/>';
		$cash_lost = $battleHistory[$index]->cash_lost;
		if($won == 0)
		{	
		   $result = User::getUser($user_id);
		  // $fn->printArray($result);
		   $rival_name = $result->getName();
			$html = '<div class="newsinfo">
				<p class="wonfight"><strong><a href="externalplayerprofile.php?userID='.$user_id.'">'.$rival_name.'</a> attacked you and you won.</strong><br />
						You lost '.$damage_taken.' health and win $'.$cash_lost.'.
					</p>
				</div>';
		}
		else
		{
			$result = User::getUser($user_id);
		  // $fn->printArray($result);
		   $rival_name = $result->getName();
			$html = 
			'<div class="newsinfo">
				<p class="lostfight"><strong><a href="externalplayerprofile.php?userID='.$user_id.'">'.$rival_name.'</a> attacked you and  you lost.</strong><br />
					You lost '.$damage_taken.' health and lost $'.$cash_lost.'.
				</p>
			</div>';			
		}
		echo $html;
}
function displayMarksmanAchievement($rank) {
?>
<div class="achievementunlocked">
	<div class="achievementstatus">
		<img src="img/glove.png" />
		<p>Rank: <?php echo $rank; ?></p>
	</div>
	<h2>Achievement Awarded!</h2>
	<p>You've earned the "<strong>Marksman</strong>" achievement by Killing <?php echo getMarksmanKills($rank); ?> people.  You have been awarded: 1 Skill Point.</p>
</div>
<?php 
}
function displayFallguyAchievement($rank) {
?>
<div class="achievementunlocked">
	<div class="achievementstatus">
		<img src="img/glove.png" />
		<p>Rank: <?php echo $rank; ?></p>
	</div>
	<h2>Achievement Awarded!</h2>
	<p>You've earned the "<strong>Fall Guy</strong>" achievement by losing <?php echo getFallguyDeaths($rank); ?>Fights.  You have been awarded: 1 Skill Point.</p>
</div>
<?php 
}
function displayVictorAchievement($rank) {
?>
<div class="achievementunlocked">
	<div class="achievementstatus">
		<img src="img/glove.png" />
		<p>Rank: <?php echo $rank; ?></p>
	</div>
	<h2>Achievement Awarded!</h2>
	<p>You've earned the "<strong>Victor</strong>" achievement by winning <?php echo getVictorWins($rank); ?>Fights .  You have been awarded: 1 Skill Point.</p>
</div>
<?php 
}
function displayOfflineAchievements() {
	global $user;
	$userAchievements = $user->getOfflineAchievements();
	foreach ($userAchievements as $achievement) {
		if($achievement['achievement_name'] == "marksman") {
			displayMarksmanAchievement($achievement['achievement_level']);
			$user->incrementUserSkillpoints(1);
		}
		else if($achievement['achievement_name'] == "fallguy") {
			displayFallguyAchievement($achievement['achievement_level']);
			$user->incrementUserSkillpoints(1);
		}
		else if($achievement['achievement_name'] == "victor") {
			displayVictorAchievement($achievement['achievement_level']);
			$user->incrementUserSkillpoints(1);
		}
	}
}

$allNotifications = array();
$result_html = '';
//$user = User::getUser($_SESSION['userID']);
//$fn->printArray($battleHistory);
$battleIndex=0;
if(count($battleHistory) > 0)
{
	foreach ($battleHistory as $battle) 
	{
		$user_id = $battle->user_id;
		//$rival_name = '';//$battle->rival_name;
		$opponent_id = $battle->opponent_id;
		$damage_taken = $battle->damage_taken;
		$won = $battle->won.'<br/>';
		$cash_lost = $battle->cash_lost;
		$allNotifications['btl'.$battleIndex] = $battle->date;
		if($won == 0)
		{	
		  // $result = User::getUser($user_id);
		  // $fn->printArray($result);
		   $rival_name = User::getUserName($user_id);//$result->getName();
			$result_html .= '<div class="newsinfo">
				<p class="wonfight"><strong><a onclick="playSound('.SOUND_CLICK.')"  href="externalplayerprofile.php?userID='.$user_id.'">'.$rival_name.'</a> attacked you and You Won.</strong><br />
						You lost '.$damage_taken.' health and win $'.$cash_lost.'.
					</p>
				</div>';
			$battleIndex++;
		}
		else
		{
			//$result = User::getUser($user_id);
		  // $fn->printArray($result);
		    $rival_name = User::getUserName($user_id);
			$result_html .= 
			'<div class="newsinfo">
				<p class="lostfight"><strong><a onclick="playSound('.SOUND_CLICK.')"  href="externalplayerprofile.php?userID='.$user_id.'">'.$rival_name.'</a> attacked you and  you lost.</strong><br />
					You lost '.$damage_taken.' health and lost $'.$cash_lost.'.
				</p>
			</div>';			
			$battleIndex++;
		}
	}
}
else
{
//	$result_html .= '<div class="successmessage">			<p class="successfailuremessage"><span class="failure">Sorry!</span>'.NO_RECORD_AVAILABLE.'</p>		</div>';
}
$commentsHtml = '';
    $commentIndex = 0;	
    if(count($myComments) == 0) {    
    	//$commentsHtml .=   	'<div class="successmessage">			<p class="successfailuremessage"><span class="failure">Sorry!</span>'.NO_RECORD_AVAILABLE.'</p>		</div>';
    	}
    else 
    {
    	foreach($myComments as $comment)
    	{
    		$sender = User::getUser($comment->getSenderID ());
    		$senderName = $sender->getName();
    		$senderID = $sender->getID();
    		$commentID = $comment->getCommentID ();
    		$content = $comment->getContent();
    		$allNotifications['cmt'.$commentIndex] = $comment->getTimePosted();
        	$commentsHtml .= 
        	'<div class="commentpost">
				<a href="'.$serverRoot.'externalplayerprofile.php?userID='.$senderID.'">'.$senderName.' </a>
        		<a onclick="playSound('.SOUND_CLICK.')" class="commentdelete" href="backend/commentactions.php?action=delete&id='.$commentID.';&screen=home">Delete</a>
            	<p>'. $content.'</p>
        	</div>';
        	$commentIndex++;
    	}
    } 
    
    $broadCastHtml = '';
    $broadCastIndex = 0;
    $broadcastmsg = $user->getBroadcastMessages();
    $broadCasts = array();
    foreach ($broadcastmsg as $message) 
    {
    		$broadCasts[$broadCastIndex] = $message;
            $sender = User::getUser($message['sender_id']);
            $senderName = $sender->getName();
    		$senderID = $sender->getID(); 
    		$content = $message["content"];
    		$msgID = $message["id"];
    		$allNotifications['brd'.$broadCastIndex] = $message["time_posted"];
    		if($senderID == $user->getID()) {
    		$broadCastHtml .= '      
	            <div class="commentpost">
	                <a href="'.$serverRoot.'externalplayerprofile.php?userID='.$senderID.'">'.$senderName.'</a>
	                <a onclick="playSound('.SOUND_CLICK.')" class="commentdelete" href="backend/broadcast.php?action=remove&id='.$msgID.'&location=charhome">Delete</a>
	                <p>'.$content.'</p>
	            </div>';
    			$broadCastIndex++;;
    		}
    		else {
    			$broadCastHtml .= '      
	            <div class="commentpost">
                        <a onclick="playSound('.SOUND_CLICK.')" href="'.$serverRoot.'externalplayerprofile.php?userID='.$senderID.'">'.$senderName.'</a>
	                <p>'.$content.'</p>
	            </div>';
    			$broadCastIndex++;
    		}
    }	
?>
 <script>
	$(document).ready(function() {
		$(".inlinecontent").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
				});
				});
	
	$.fx.off = !$.fx.off;
	
	touchMove = function(event) {
	// Prevent scrolling on this element
	event.preventDefault();
	};
</script>
<a class="inlinecontent abshide" href="#inline1"> </a>
<?php 
displayofflineAchievements();
$user->removeOfflineAchievements();
?>
<div id="personalinfo">
    <div id="diamonds">
        <a class="diamondbutton" href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>blackmarket.php' )"><?php echo $user->getDiamonds();?> Diamonds</a>
        <p><?php echo $user->getName(); ?></p>
    </div>	
    <div id="soldiers">
<a  onclick="playSound('<?php echo SOUND_CLICK ?>')"  class="soldierbutton <?php echo getPlayerTypeFromTypeCSS($user->getType());?>" href="<?php echo $serverRoot.'invite.php' ?>">
<?php if($user->getAgencySize() > 1){
                echo $user->getAgencySize()." Soldiers";
            } else {
                echo "1 Soldier";
            }?>
            </a>
        <?php
            $soldier_code = '';
            if($user->getAgencyCode() != ""){
                $soldier_code = $user->getAgencyCode();
            } else {
                $soldierCode = new SoldierCode();
		$code = SoldierCode::getSoldierCode();
                    if($code[0]->getCode() != ""){
                    $user->updateUserSoldierCode($code[0]->getCode());
                    $soldierCode->deleteCode($code[0]->getCode());
                }
            }
        ?>
        <p>S.Code: <?php echo $user->getAgencyCode(); ?></p>
    </div>			

</div>
<div id="mainbuttons">

    <ul>
        <li><a href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>levelchecker.php?pageRequestType=mission' )"  ontouchstart="" class="missions">Missions</a></li>
        <li class="csstest"><a  href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>levelchecker.php?pageRequestType=battle' )" class="attack">Attack</a></li>
        <li><a  href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>levelchecker.php?pageRequestType=shopitem' )"  class="equipment">Equipment</a></li>
        <li><a  href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>levelchecker.php?pageRequestType=shoprealestate' )" class="realestate">Real Estate</a></li>
    </ul>

</div>

<div id="secondarybuttons">

    <ul>
        <li><a href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>bank.php' )">Bank</a></li>
        <li><a href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>levelchecker.php?pageRequestType=hospital' )">Hospital</a></li>
        <li><a  href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>levelchecker.php?pageRequestType=blackMarket' )">Dealer</a><span class="diamondbubble"><?php echo $user->getDiamonds();?></span></li>
        <li><a  href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>profile.php' )">My Profile</a></li>
        <li><a href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>invite.php' )">My Army</a></li>
        <li class="last"><a href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>faq.php' )" class="help">Help</a></li>	
        <li class="last"><a href="javascript:" onclick="playAndRedirect('<?php echo SOUND_CLICK ?>','<?php echo $serverRoot ?>preferences.php' )"  class="settings">Settings</a></li>	

    </ul>

</div>
<div id="news">

    <h2 class="gamefeed"><em>My Game Feed</em></h2>

    <div id="tabs">
<?php 

$broadcastMsgs = $user->getBroadcastMessages();
$battleHistory = User::getBattleHistory($user->getID());
$allTab = array();
?>
        <div class="container missionsmenu">
            <ul id="menu2">
                <li class="tab"><a  onclick="playSound('<?php echo SOUND_CLICK ?>')"  href="#one">All</a></li>
                <li class="tab"><a  onclick="playSound('<?php echo SOUND_CLICK ?>')"  href="#two">Attacks</a></li>
                <li class="tab"><a  onclick="playSound('<?php echo SOUND_CLICK ?>')"  href="#three">Comments</a></li>
                <li class="tab"><a  onclick="playSound('<?php echo SOUND_CLICK ?>')"  href="#four">Broadcasts</a></li>
            </ul>
        </div>	


	<div id="one">
    <div style="clear:both"></div>
      <?php         
        arsort($allNotifications);
      	$count = 0;
      	foreach ($allNotifications as $key=>$notification) {
      		if(substr($key, 0,3) == 'cmt') {
      			displayComment(substr($key, -1, 1));
      		}
      		elseif (substr($key,0,3) == 'btl') {
      			displayBattle(substr($key, -1, 1));
      		}
      		elseif (substr($key,0,3) == 'brd') {
      			displayBroadcast(substr($key, -1, 1));
      		}
      		$count++;
      		if($count == NUM_RECORDS) {
      			break;
      		}
      	}
?>
    </div>
        <div id="two" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
        	<?php echo $result_html ;?>
        </div>
        <div id="three">
    		<?php echo $commentsHtml; ?>
        </div>
        <div id="four">
        	<form action="backend/broadcast.php?action=post&location=charhome" onsubmit = "return validateBroadcast();" method="POST">
            <textarea class="comment" name="content" id="broadcastContent" maxlength = "250" ></textarea>
            <input type="submit" class="commentbutton" value="Broadcast Message" />
            </form>
         	<?php 
            		echo $broadCastHtml;
         	?>
        </div>
    </div>
</div>
<script language="javascript" type="text/javascript">
function validateBroadcast() {
	var content = $('#broadcastContent').val();
	$('#notification').html(' ');
	if (content.trim() == '') 
	{
		$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> You must enter some text to be broadcasted</p><div class="popupbuy"> <a href="javascript:void(0)" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
		$('.abshide').click();
		return false;
	}
	else if (content.length > 250) 
	{
		$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> The length of broadcast message must be less than 251 characters text</p><div class="popupbuy"> <a href="javascript:void(0)" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
		$('.abshide').click();
		return false;
	}
}
function closePpoup() {
	$('#fancybox-close').click();
}
</script>
<div style="display: none;">
	<div id="inline1" style="width:98%;">
	
	</div>
</div>
<?php
include_once 'footer.php';    
?>