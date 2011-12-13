<?php
include_once("../classes/User.php");
include_once '../properties/serverproperties.php'; 
session_start();
$user = User::getUser($_SESSION['userID']);
//$serverRoot= 'lvl6_jira/';
if( isset($_GET['healCost']) )
{
	$healCost = $_GET['healCost'];
        $_SESSION['heal_cost'] = $healCost; 
	if($user->healAtHospital($healCost) )
	{
		header("Location: {$serverRoot}hospital.php?heal=true");
	}
	else
	{
		header("Location: {$serverRoot}hospital.php?heal=false");
	}
}

?>