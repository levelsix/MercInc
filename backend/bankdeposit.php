<?php

include_once("../properties/serverproperties.php");
include_once("../classes/User.php");

session_start();

//print_r($_POST);
$userID = $_SESSION['userID'];
$amount = intval($_POST['dAmount']);
//exit();
if (!is_numeric($amount) || strrchr($amount, '.')) {
	$_SESSION['notValid'] = 'true';
	header("Location: $serverRoot/bank.php");
	exit;
}

$user = User::getUser($userID);

if ($amount > $user->getCash()) {
	$_SESSION['notEnoughCash'] = 'true';
	header("Location: $serverRoot/bank.php");
	exit;
}

$toBeDeposited = floor(0.9 * $amount);

if (!$user->depositBankDeductCash($amount, $toBeDeposited)) {
	header("Location: {$serverRoot}errorpage.html");
	exit;
}

$_SESSION['deposited'] = $toBeDeposited;
header("Location: {$serverRoot}bank.php");
exit;
?>