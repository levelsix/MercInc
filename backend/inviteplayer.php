<?php
	include_once("../properties/serverproperties.php");
	include_once("../classes/User.php");
	session_start();

	$agencyCode = strtoupper($_GET['agencyCode']);
	$user = User::getUser($_SESSION['userID']); 
	if( ($agencyCode == $user->getID()) || ($agencyCode == strtoupper($user->getAgencyCode())) ) {
		header("Location: ".$serverRoot."invite.php?status=selfRequest");
   		exit;
	}
	
	if($_GET['type'] == "lvl6id") {
		$result = $user->invitePlayerUsingLvl6id($agencyCode);	
	}
	else if ($_GET['type'] == "soldiercode"){
		$result = $user->invitePlayerUsingAgencycode($agencyCode);
	}
	if ($result == "noUserWithAgencyCode") {
		header("Location: {$serverRoot}invite.php?status=noUserWithAgencyCode");
		exit;
	}
	elseif ($result == "noUserWithLvl6id") {	
		header("Location: {$serverRoot}invite.php?status=noUserWithLvl6id");
		exit;
	}
	if ($result == "success") {
		header("Location: {$serverRoot}invite.php?status=success");
		exit;
	}
	if ($result == "alreadyExisting") {
		header("Location: {$serverRoot}invite.php?status=alreadyExisting");
		exit;
	}
	if ($result == "fail") {
		header("Location: {$serverRoot}invite.php?status=fail");
		exit;
	}	
	header("Location: {$serverRoot}invite.php");
?>