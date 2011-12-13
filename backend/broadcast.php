<?php
include_once '../classes/Comments.php';
include_once '../classes/User.php';
include_once("../properties/serverproperties.php");
session_start();
$user = User::getUser( $_SESSION['userID']);
$location = $_GET['location'];
//die();
if(isset($_GET['action']) && $_GET['action']== "post"){
	
	$content = $_POST['content'];
	$time = strftime("%c");
	$success = $user->postBroadcastMessage($content);
	if($location == "charhome") {
		header("location:{$serverRoot}charhome.php#four");
		exit;
	}
	else if ($location == "recruit"){
		header("location:{$serverRoot}invite.php?status=broadcastSuccess#two");
		exit;
	}
}
else if (isset($_GET['action']) && $_GET['action']== "remove") {
	
	$id = $_GET['id'];
	$user->deleteBroadcastMessage($id);
	if($location == "charhome") {
		header("location:{$serverRoot}charhome.php#four");
		exit;
	}
	else if ($location == "recruit") {
		header("location:{$serverRoot}invite.php#two");
		exit;
	}
}
?>