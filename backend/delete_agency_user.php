<?php
include_once("../classes/ConnectionFactory.php");
include_once("../properties/serverproperties.php");
include_once("../classes/User.php");

$otherUserID = $_GET['soldier_id'];
session_start();
$user = User::getUser($_SESSION['userID']);

$user->deleteFromAgency($otherUserID,$user->getID());

User::decrementUserAgencySize($otherUserID);
User::decrementUserAgencySize($user->getID());

header("location:{$serverRoot}invite.php");
?>