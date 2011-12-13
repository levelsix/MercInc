<?php
include_once("../properties/serverproperties.php");
include_once("../classes/Bounty.php");
include_once("../classes/User.php");
require_once '../classes/common_functions.php';

$fn = new common_functions();
session_start();
$userID = $_SESSION['userID'];
//echo '<br/>';
$serverRoot;
$targetID = $fn->get('targetID');
//echo '<br/>';
$payment = $fn->get('bountyAmount');

$payment = $payment - ceil($payment * 0.1);
//echo '<br/>';

//echo '<br/>';
$userBounty= User::getUser($userID);
$targetName = $userBounty->getName();
if ($payment > $userBounty->getCash()) {
	header("Location: {$serverRoot}externalplayerprofile.php?error=notEnoughCashForBounty&userID=$targetID");
}
else if( $userBounty -> checkSameUsersInAgency($userID , $targetID ) == true )
{
	$_SESSION['sameAgencyUser'] = true;
	header("Location: {$serverRoot}externalplayerprofile.php?error=sameAgencyUser&userID=$targetID");

} else {
	$bounty = Bounty::createBounty($userID, $targetID,$payment  );
	if (!$bounty) {
		//echo '!$bounty found';
		$fn->redirect("{$serverRoot}externalplayerprofile.php?error=unKnownError&userID=$targetID");
	}
	
	if (!$userBounty->updateUserCash($fn->get('bountyAmount')*-1)){
		//$fn->redirect("../errorpage.html");
		$fn->redirect("{$serverRoot}externalplayerprofile.php?error=unKnownError&userID=$targetID");
	}
	$_SESSION['battleTab'] = 'bounty';
        $_SESSION['otherUserID'] = $targetID;
	$fn->redirect("{$serverRoot}battle.php?addBounty=$payment&attack_type=bounty&targetID=$targetID&targetName=$targetName#two");
}

?>