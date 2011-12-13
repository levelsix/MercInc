<?php
session_start();
include_once '../classes/User.php';
include_once("../properties/serverproperties.php");

$userID = $_SESSION['userID'];
$user = User::getUser($userID);
$playerType = $_POST['playertype'];
$user->setType($playerType);

if(isset($_POST['page']) && trim($_POST['page']) == 'mission'){
   // header("Location: ".$serverRoot."chooseplayername.php?page=".$_POST['page']."&cityID=".$_POST['cityID']."&missionID=".$_POST['missionID']);
   header("Location: ".$serverRoot."firstbattle.php");
   exit;
}


if(isset($_POST['page']) && trim($_POST['page']) == 'blackmarket')
{
	$user->updateUserDiamonds($_POST['diamonds']);
	header("Location: {$serverRoot}blackmarket.php?update_class=$playerType");
	exit(0);
}


header("Location: {$serverRoot}index.php?UDID=".$user->getUDID());
exit;
?>