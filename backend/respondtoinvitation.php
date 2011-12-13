<?php
include_once("../classes/User.php");

session_start();

$accepted = $_GET['accepted'];
$inviterID = $_GET['inviterID'];
$userID = $_SESSION['userID'];

$user = User::getUser($userID);

if ($accepted == 'true') {
	$user->acceptInvite($inviterID);
} else {
	$user->rejectInvite($inviterID);
}

header("Location:../invite.php");
exit;
?>