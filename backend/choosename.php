<?php
session_start();
include_once '../classes/User.php';
 include_once("../properties/serverproperties.php");

$userID = $_SESSION['userID'];
$user = User::getUser($userID);
$playername = $_POST['playername'];
$user->setName($playername);
if(isset($_POST['page']) && trim($_POST['page']) == 'mission'){
     header("Location: ".$serverRoot."chooseclasspage.php?page=".$_POST['page']."&cityID=".$_POST['cityID']."&missionID=".$_POST['missionID']);
    //header("Location: ".$serverRoot."choosemission.php?cityID=".$_POST['cityID']."&missionID=".$_POST['missionID']);
   // header("Location: ".$serverRoot."choosemission.php?cityID=".$_POST['cityID']."&missionID=".$_POST['missionID']);
    exit;
}

if(isset($_POST['page']) && trim($_POST['page']) == 'blackmarket')
{
	$user->updateUserDiamonds($_POST['diamonds']);
	header("Location: {$serverRoot}blackmarket.php?update_name=$playername");
	exit(0);
}


header("Location: {$serverRoot}chooseclasspage.php");
exit;
?>