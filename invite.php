<?php 
include_once("topmenu.php");
include_once("properties/playertypeproperties.php");
include_once("properties/constants.php");
        $recruitMinLevel = 6;
        if(isset($_SESSION['levelError']))
        {
            echo $_SESSION['levelError'];
            unset($_SESSION['levelError']);
            exit;
        }
        else if ($playerLevel<$recruitMinLevel)
        {
            $tooLowLevelString = '<div class="natsays">
                <h2>Recruit</h2>
                <p><strong>Natalie says:</strong></p>
                <p>"You need more experience before you can recruit your army."</p>
                <p>"Come back at LVL '.$recruitMinLevel.'"</p>
                <a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
            </div>';
            echo $tooLowLevelString;
            exit;
        } 

		$user = User::getUser($_SESSION['userID']);
		$dummyCount = $user -> dummy_user_count;
  ?>
  <div id="notification">
  <?php if(isset($_GET['status']) && ($_GET['status'] == "noUserWithAgencyCode") )
  {
  ?>
  	<div class="successmessage">
		<p class="successfailuremessage"><span class="failure">Failure!</span><?php echo NO_USER_WITH_SOLDIER_CODE; ?></p>
	</div>
  <?php 
  }
  else if (isset($_GET['status']) && ($_GET['status'] == "noUserWithLvl6id")) {
  ?>
   	<div class="successmessage">
		<p class="successfailuremessage"><span class="failure">Failure!</span> <?php echo NO_USER_WITH_LEVEL6_ID; ?></p>
	</div>
  <?php 
  }
  else if (isset($_GET['status']) && ($_GET['status'] == "success"))
  {
  ?>
  	<div class="successmessage">
		<p class="successfailuremessage"><span class="success">Success!</span> <?php echo REQUEST_SUCCESSFULLY_SENT; ?> </p>
	</div>
  <?php 
  }
  else if (isset($_GET['status']) && ($_GET['status'] == "alreadyExisting"))
  {
  ?>
  	<div class="successmessage">
		<p class="successfailuremessage"><span class="failure">Failure!</span><?php echo ALREADY_IN_AGENCY; ?></p>
	</div>
  <?php 
  }
  else if (isset($_GET['status']) && ($_GET['status'] == "selfRequest"))
  {
  ?>
 	 <div class="successmessage">
		<p class="successfailuremessage"><span class="failure">Failure!</span> <?php echo SELF_REQUEST; ?></p>
	</div>
  <?php 
  }
  else if (isset($_GET['status']) && ($_GET['status'] == "broadcastSuccess"))
  {
  ?>
 	 <div class="successmessage">
		<p class="successfailuremessage"><span class="success">Success!</span> <?php echo BORADCAST_SUCCESSFULLY_SENT; ?> </p>
	</div>
  <?php 
  }
  ?>
  </div>
  
  
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
  
<?php 
	include_once 'properties/serverproperties.php';
	/*
	 * copying your agency in all the arrays
	 */
	$existingAgencyUsers = User::getUsersInAgency($user->getID());
	$experienceLeadeboard = $existingAgencyUsers;
	$fightsLeaderboard = $existingAgencyUsers;
	$hitlistLeaderboard = $existingAgencyUsers;
	$missionsLeaderboard = $existingAgencyUsers;
	
	/*
	 * appending the current logged in user in all arrays 
	 */
	$experienceLeadeboard[count($existingAgencyUsers)] = $user;
	$fightsLeaderboard[count($existingAgencyUsers)] = $user;
	$hitlistLeaderboard[count($existingAgencyUsers)] = $user;
	$missionsLeaderboard[count($existingAgencyUsers)] = $user;
	
	function displayPendingInvitations($user) {
		$pendingAgencyInviteUsers = $user->getPendingAgencyInviteUsers();
		if(count($pendingAgencyInviteUsers)>0) {
			echo '<p class="info">The following soldiers have invited to join their army. If you accept, they will also become a member of you mob, allowing you to win more fights and complete more missions.</p>';
		}
		foreach($pendingAgencyInviteUsers as $pendingUser) {
			$inviterID = $pendingUser->getID();
			$inviterName = $pendingUser->getName();
	?>
			<tr>
				<td><a class="profilelink" href="externalplayerprofile.php?userID=<?php echo $inviterID; ?>"><?php echo $inviterName; ?></a>
					<p>LVL <?php echo $pendingUser->getLevel(); echo  getPlayerTypeFromTypeID($pendingUser->getType());?></p>
				</td>
				<td class="decisionbutton">
					<a href="backend/respondtoinvitation.php?accepted=false&inviterID=<?php echo $inviterID; ?>" class="redbutton">Reject</a>
					<a href="backend/respondtoinvitation.php?accepted=true&inviterID=<?php echo $inviterID; ?>" class="green2button">Accept</a>
				</td>
			</tr>			
	<?php
	}

}
function displayMyAgency($user ,$dummyCount =0) {
		global $existingAgencyUsers;
		foreach ($existingAgencyUsers as $agencyUser) {
			$soldierID = $agencyUser->getID();
			$soldierName = $agencyUser->getName();
?>
			<tr>
				<td><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?>"></td>
				<td><a class="profilelink" href="externalplayerprofile.php?userID=<?php echo $soldierID; ?>"><?php echo $soldierName; ?></a>
				<p>LVL <?php echo $agencyUser->getLevel(); echo  getPlayerTypeFromTypeID($agencyUser->getType());?> </p>
				</td>
				<td><a href="backend/deleteagencyuser.php?soldier_id=<?php echo $soldierID; ?>" class="blackbutton">Remove</a></td>
			</tr>	
<?php 
		}
		
		for($i=0;$i<$dummyCount;$i++)
		{
?>			
			<tr>
				<td><img class="icon" src="img/natalieicon.png"></td>
				<td><a class="profilelink" href="javascript:"><?= DUMMY_SOLDIER_NAME ?></a></td>
                <td></td>
			</tr>

<?php 			
		}
			 
		
		
}
function getLeaderboards () {
	global $experienceLeadeboard; 
	global $fightsLeaderboard;
	global $hitlistLeaderboard;
    global $missionsLeaderboard;
    
    usort($experienceLeadeboard, 'sortExperience');
    usort($fightsLeaderboard, 'sortFightswon');
    usort($hitlistLeaderboard, 'sortHitlist');
    usort($missionsLeaderboard, 'sortMissions');

}
function sortExperience ($lhs , $rhs) { 
	if($lhs->getExperience() == $rhs->getExperience() ) {
		return 0;
	}
	if ($lhs->getExperience() < $rhs->getExperience()) {
		return 1;
	}
	return -1;
}
function sortFightswon($lhs , $rhs) {
	if($lhs->getFightsWon() == $rhs->getFightsWon() ) {
		return 0;
	}
	if ($lhs->getFightsWon() < $rhs->getFightsWon()) {
		return 1;
	}
	return -1;
}
function sortHitlist($lhs , $rhs) {
	if($lhs->getUserKills() == $rhs->getUserKills() ) {
		return 0;
	}
	if ($lhs->getUserKills() < $rhs->getUserKills() ) {
		return 1;
	}
	return -1;
}
function sortMissions($lhs , $rhs) {
	if($lhs->getNumMissionsCompleted() == $rhs->getNumMissionsCompleted() ) {
		return 0;
	}
	if ($lhs->getNumMissionsCompleted() < $rhs->getNumMissionsCompleted() ) {
		return 1;
	}
	return -1;
}

?>

<a class="inlinecontent abshide" href="#inline1"> </a>
<div id="content">

	<div id="tabs">

	<div class="container missionsmenu pvp">
		<ul id="menu2">
			<li class="tab"><a href="#one">Recruit</a></li>
			<li class="tab"><a href="#two">My Mob (<?php echo $user->getAgencySize();?>)</a></li>
			<li class="tab"><a href="#three">Leaderboard</a></li>
		</ul>
	</div>	

<div id="one">

	<div id="topcode">
		<h2 class="id">YOUR SOLDIER CODE: <em><?php echo $user->getAgencyCode(); ?></em></h2>
	</div>
	
		<h3 class="miniheader">Invite using a soldier code:</h3>
			<p class="info">If you know your friend's soldier code, enter it here to invite them to your army.</p>
	
	<form action="<?php echo $serverRoot?>backend/inviteplayer.php?type=soldiercode" onsubmit="return validateAgencycode();"  method="GET">
		<input type="text" class="textbox" name="agencyCode" id="agencyCode" />
		<input type="hidden" class="textbox" name="type" value="soldiercode" />
		<input type="submit" class="invite" value="Invite" />
	</form>
	
	<h3 class="miniheader">Invite using a Lvl 6 code:</h3>
	<p class="info">If you know your friend's Lvl 6 code, enter it here to invite them to your army.</p>
	
	<form action="<?php echo $serverRoot?>backend/inviteplayer.php?type=lvl6id" onsubmit="return validateUserId();"  method="GET">
		<input type="text" class="textbox" name="agencyCode" id="lvl6id" />
		<input type="hidden" class="textbox" name="type" value="lvl6id" />
		<input type="submit" class="invite" value="Invite" />
	</form>
	<br>
	<h3 class="miniheader">Invitations from other soldiers:</h3>
		<table class="acceptreject">
		<?php 
		 	displayPendingInvitations($user);
		?>
		</table>
</div>
	<script language="javascript" type="text/javascript">
	function validateUserId() {
		   var agencyCode = $('#lvl6id').val();
			$('#notification').html(' ');
			
			if (agencyCode.trim() == '') 
			{
				$('#notification').html('<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>  LVL6 ID code cannot be left blank .</p> </div>');
//				$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> Please enter valid level6 id</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
//				//alert('You did not enter a deposit amount.');
//				$('.abshide').click();
				return false;
			}
	}
	function validateAgencycode() {
		   var agencyCode = $('#agencyCode').val();
			$('#notification').html(' ');
			if (agencyCode.trim() == '') 
			{	
				$('#notification').html('<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>  Agency code cannot be left blank .</p> </div>');
//				$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> Agency code cannot be left blank !</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
//				$('.abshide').click();
				return false;
			}
			else if(agencyCode.length !=6 || !isAlphaNumeric(agencyCode)) {
				$('#notification').html('<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>  Please enter valid 6-digit alpha-numeric agency code .</p> </div>');
//				$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> Please enter valid 6-digit alpha-numeric agency code</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
//				$('.abshide').click();
				return false;
			}
	}
	function validateBroadcast() {
		var content = $('#broadcastContent').val();
		$('#notification').html(' ');
		if (content.trim() == '') 
		{
			$('#notification').html('<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> You must enter some text to broadcast a message .</p> </div>');
//			$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> You must enter some text to be broadcasted</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
//			$('.abshide').click();
			return false;
		}
		else if (content.length > 250) 
		{
			$('#notification').html('<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> Broadcast message length must be less than 250 .</p> </div>');
//			$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> The length of broadcast message must be less than 251 characters text</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
//			$('.abshide').click();
			return false;
		}
	}
	function isAlphaNumeric(sText)
	{
	   var ValidChars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.";
	   var IsNumber=true;
	   var Char; 
	   for (i = 0; i < sText.length && IsNumber == true; i++) 
	      { 
	      Char = sText.charAt(i); 
	      if (ValidChars.indexOf(Char) == -1) 
	         {
	         IsNumber = false;
	         }
	      }
	   return IsNumber;
	}
	function closePpoup() {
		$('#fancybox-close').click();
	 }		
	</script>
	
<div style="display: none;">
	<div id="inline1" style="width:98%;">
	
	</div>
</div>

<div id="two">

	<div id="topcode">
		<h2 class="id">Your Mob Size: <em><?php echo $user->getAgencySize(); ?></em></h2>
	</div>
	<h3 class="broadcastmessage">Broadcast a message to your mob</h3>
	<form action="backend/broadcast.php?action=post&location=recruit" onsubmit="return validateBroadcast();" method="POST">
            <textarea class="comment" name="content" id="broadcastContent" maxlength = "250"></textarea>
<!--            <input type="hidden" name="location" value="recruit" />-->
            <input type="submit" class="commentbutton" value="Broadcast Message" />
    </form>
	<table class="mymob">
		
	<?php
		displayMyAgency($user , $dummyCount );
	?>
		
		</table>

</div>
<div id="three">
	<div class="leaderboard">
	<?php
		getLeaderboards ();
		
	?>
	<a href="#" class="goleft"><span>Go Left</span></a>
	<div class="">
	
	<div class="focusitem active">
	<div class="leadertitle"><h2>Experience</h2></div>
	<table class="leadertable">
		<tr>
			<th>Rank</th>
			<th></th>
			<th>Name</th>
			<th class="rankitemnumber">Experience</th>
		</tr>
	<?php
		$counter = 1; 
		foreach ($experienceLeadeboard as $agencyUser)
		{
			if($agencyUser->getID() == $user->getID()) {
	?>
			<tr class="myplayer">
				<td class="rankleader"><?php echo $counter++; ?></td>
				<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?>" width="42" height = "42" ></td>
				<td class="rankname"><p><a class="profile" href="profile.php"><?php echo $agencyUser->getName();?></a><br /><em>LVL <?php  echo $agencyUser->getLevel(); echo ' '.getPlayerTypeFromTypeID($agencyUser->getType()); ?> </em></p></td>
				<td class="rankrank"><?php echo $agencyUser->getExperience(); ?></td>
			</tr>
	<?php 
			}
			else {	
	?>
		<tr>
			<td class="rankleader"><?php echo $counter++; ?></td>
			<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?> " width="42" height = "42" ></td>
			<td class="rankname"><p><a class="profile" href="externalplayerprofile.php?userID=<?php echo $agencyUser->getID();?> "><?php echo $agencyUser->getName();?></a><br /><em>Level <?php  echo $agencyUser->getLevel(); echo ' '.getPlayerTypeFromTypeID($agencyUser->getType()); ?> </em></p></td>
			<td class="rankrank"><?php echo $agencyUser->getExperience(); ?></td>
		</tr>
	<?php
			}
		}
	?>
	</table>
	
	</div>
	
	<div class="focusitem notactive">
	<div class="leadertitle"><h2>Fights Won</h2></div>
	<table class="leadertable">
		<tr>
			<th>Rank</th>
			<th></th>
			<th>Name</th>
			<th class="rankitemnumber"> Won</th>
		</tr>
			<?php
		$counter = 1; 
		foreach ($fightsLeaderboard as $agencyUser)
		{
			if($agencyUser->getID() == $user->getID()) {
	?>
			<tr class="myplayer">
				<td class="rankleader"><?php echo $counter++; ?></td>
				<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?>" width="42" height = "42" ></td>
				<td class="rankname"><p><a class="profile" href="profile.php"><?php echo $agencyUser->getName();?></a><br /><em>LVL <?php  echo $agencyUser->getLevel(); echo ' '.getPlayerTypeFromTypeID($agencyUser->getType()); ?></em></p></td>
				<td class="rankrank"><?php echo $agencyUser->getFightsWon(); ?></td>
			</tr>
	<?php 
			}
			else {	
	?>
		<tr>
			<td class="rankleader"><?php echo $counter++; ?></td>
			<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?>" width="42" height = "42" ></td>
			<td class="rankname"><p><a class="profile" href="externalplayerprofile.php?userID=<?php echo $agencyUser->getID();?> "><?php echo $agencyUser->getName();?></a><br /><em>LVL <?php  echo $agencyUser->getLevel(); echo ' '.getPlayerTypeFromTypeID($agencyUser->getType()); ?></em></p></td>
			<td class="rankrank"><?php echo $agencyUser->getFightsWon(); ?></td>
		</tr>
	<?php
			}
		}
	?>
	</table>
	</div>
	
	<div class="focusitem notactive">
	<div class="leadertitle"><h2>Hit List Kills</h2></div>
	<table class="leadertable">
		<tr>
			<th>Rank</th>
			<th></th>
			<th>Name</th>
			<th class="rankitemnumber">Kills</th>
		</tr>
	<?php
		$counter = 1; 
		foreach ($hitlistLeaderboard as $agencyUser)
		{
			if($agencyUser->getID() == $user->getID()) {
	?>
			<tr class="myplayer">
				<td class="rankleader"><?php echo $counter++; ?></td>
				<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?>" width="42" height = "42" ></td>
				<td class="rankname"><p><a class="profile" href="profile.php"><?php echo $agencyUser->getName();?></a><br /><em>LVL <?php  echo $agencyUser->getLevel(); echo ' '.getPlayerTypeFromTypeID($agencyUser->getType()); ?> </em></p></td>
				<td class="rankrank"><?php echo $agencyUser->getUserKills(); ?></td>
			</tr>
	<?php 
			}
			else {	
	?>
		<tr>
			<td class="rankleader"><?php echo $counter++; ?></td>
			<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?>" width="42" height = "42" ></td>
			<td class="rankname"><p><a class="profile" href="externalplayerprofile.php?userID=<?php echo $agencyUser->getID();?> "><?php echo $agencyUser->getName();?></a><br /><em>LVL <?php  echo $agencyUser->getLevel(); echo ' '.getPlayerTypeFromTypeID($agencyUser->getType()); ?></em></p></td>
			<td class="rankrank"><?php echo $agencyUser->getUserKills(); ?></td>
		</tr>
	<?php
			}
		}
	?>
	</table>
	
	</div>
	
	<div class="focusitem notactive">
	<div class="leadertitle"><h2>Missions Completed</h2></div>
	<table class="leadertable">
		<tr>
			<th>Rank</th>
			<th></th>
			<th>Name</th>
			<th class="rankitemnumber">Missions</th>
		</tr>
	<?php
		$counter = 1; 
		foreach ($missionsLeaderboard as $agencyUser)
		{
			if($agencyUser->getID() == $user->getID()) {
	?>
			<tr class="myplayer">
				<td class="rankleader"><?php echo $counter++; ?></td>
				<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?>" width="42" height = "42" ></td>
				<td class="rankname"><p><a class="profile" href="profile.php"><?php echo $agencyUser->getName();?></a><br /><em>LVL <?php  echo $agencyUser->getLevel(); echo ' '. getPlayerTypeFromTypeID($agencyUser->getType()); ?> </em></p></td>
				<td class="rankrank"><?php echo $agencyUser->getNumMissionsCompleted(); ?></td>
			</tr>
	<?php 
			}
			else {	
	?>
		<tr>
			<td class="rankleader"><?php echo $counter++; ?></td>
			<td class="rankicon"><img class="icon" src="<?php echo getPlayerTypeFromTypeImageURL($agencyUser->getType()); ?> "width="42" height = "42" ></td>
			<td class="rankname"><p><a class="profile" href="externalplayerprofile.php?userID=<?php echo $agencyUser->getID();?> "><?php echo $agencyUser->getName();?></a><br /><em>LVL <?php  echo $agencyUser->getLevel(); echo ' '.getPlayerTypeFromTypeID($agencyUser->getType());  ?> </em></p></td>
			<td class="rankrank"><?php echo $agencyUser->getNumMissionsCompleted(); ?></td>
		</tr>
	<?php
			}
		}
	?>	
	</table>
	
	</div>
	
	</div>
	<a href="#" class="goright"><span>Go Right</span></a>
</div>


</div>
</div>
</div>
<script type="text/javascript">
    <!--
    $( "#tabs" ).tabs({
        select: function(event, ui) {
            $('#notification').html('');
        }
    });
    //-->
</script>
<?php
	include_once 'footer.php';
?>
