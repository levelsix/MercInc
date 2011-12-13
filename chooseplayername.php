<?php
    include_once("topmenu.php");
	include_once("properties/constants.php");
	$user = User::getUser($_SESSION['userID']);
	$userName = $user->getName();
?>
<script language="javascript" type="text/javascript">
function validateForm()
{
	if( $.trim($('.nickname').val()) == '' )
	{
		var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error! </span><?php echo NAME_MISSING_ERROR ?></p></div>';
		$('#error_notification').html(notofication);
		$('#error_notification').show();
		return false;
	}
	
	<?php if($user->getLevel() > 3) { ?>
	var value = $('#playername').val();
	//alert(value);
		if( value == "<?php echo $userName; ?>"){    
			alert('You already have the same name');
			//displayAlertJS("<?php echo SAME_NAME_ERROR ?>");
			return false;	
		 } 
	 <?php } ?>
	
	return true;
}

</script>	

<div id="error_notification" style="display:none;">
</div>
    <div class="tutorialheader">
		<h1>Mercenaries, Inc.</h1>
    </div>
    <form action='<?php echo $serverRoot; ?>backend/choosename.php' method='POST' onsubmit="return validateForm();">
    
	<?php if( isset($_GET['page']) && $_GET['page'] == 'blackmarket'){ ?>
        
        <input type="hidden" name="diamonds" value="<?php echo $_GET['diamonds']; ?>" />
        <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
        
    <?php } else if(isset($_GET['page'])){ ?>
    
        <input type="hidden" name="cityID" value="<?php echo $_GET['cityID']; ?>" />
        <input type="hidden" name="missionID" value="<?php echo $_GET['missionID']; ?>" />
        <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
    
	<?php } ?>
	<div class="natsays">
            <p><strong>Natalie Says:</strong> "You've done good so far. I knew we'd made a good choice with you. Now that you've passed your training we'll need something to call you."</p>
	</div>
	<div class="tutorialchoice">
            <h2>Choose your Nickname:</h2>
            <input class="nickname" type="text" id="playername" name="playername" value="<?php echo $userName ?>" maxlength="14">
            <input type="submit" class="blackyellowbutton" value="Select Name" />
        </div>
    </form>