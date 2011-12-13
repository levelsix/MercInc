<?php
include_once("../classes/User.php");
include_once '../properties/serverproperties.php';

$attribute = $_GET['attributeToIncrease'];

session_start();
$user = User::getUser($_SESSION['userID']);

if( ($user->getSkillPoints() > 0 && $attribute != "staminamax")  || ($user->getSkillPoints() > 1 )) {
	$user->useSkillPoint($attribute);
	header("Location: {$serverRoot}profile.php?status=success#two");
	exit;
}
else {
	header("Location: {$serverRoot}profile.php?status=failure#two");
	exit;
}

?>