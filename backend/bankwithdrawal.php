<?php
include_once("../classes/User.php");
include_once '../properties/serverproperties.php';
session_start();
if(isset($_SESSION['userID']))
	$userID = $_SESSION['userID'];
	
if(isset($_POST['wAmount']))
	$amount = intval($_POST['wAmount']);
	
if (!is_numeric($amount) || strrchr($amount, '.')) {
	$_SESSION['notValid'] = 'true';
	header("Location: {$serverRoot}bank.php");
	exit;
}

$user = User::getUser($userID);

if ($amount > $user->getBankBalance()) {
	$_SESSION['notEnoughBalance'] = 'true';
	header("Location: {$serverRoot}bank.php");
	exit;
}


if (!$user->withdrawBankGainCash($amount)) {
	header("Location: {$serverRoot}errorpage.html");
	exit;
}

$_SESSION['withdrew'] = $amount;
header("Location: {$serverRoot}bank.php");
exit;
?>