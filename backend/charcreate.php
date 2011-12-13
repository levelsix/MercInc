<?php

include_once("../properties/playertypeproperties.php");
include_once("../properties/playerinitproperties.php");
include_once("../properties/serverproperties.php");
include_once("../classes/User.php");


session_start();


/*
  $playertype = $_POST['playertype'];
  if (strcmp($playertype, $playertype1) == 0) {
  $attack = $playertype1atk;
  $defense = $playertype1def;
  }
  else if (strcmp($playertype, $playertype2) == 0) {
  $attack = $playertype2atk;
  $defense = $playertype2def;
  }
  else if (strcmp($playertype, $playertype3) == 0) {
  $attack = $playertype3atk;
  $defense = $playertype3def;
  }
 */
//createUserUUID

$user;

if (isset($_GET['UDID']) && trim($_GET['UDID']) != "") {
    $user = User::getUdidUser($_GET['UDID']);
    if (empty($user)) {
        $os = '';
        if (isset($_GET['os'])) {
            $os = $_GET['os'];
        }else if (isset($_GET['OS'])) {
            $os = $_GET['OS'];
        }
        $mac = '';
        if (isset($_GET['mac'])) {
            $mac = $_GET['mac'];
        } else if (isset($_GET['MAC'])) {
            $mac = $_GET['MAC'];
        }
    	if(isset($_GET['legacy'])){
            $legacy = $_GET['legacy'];
            $_SESSION['legacy'] = true;
        }
        $user = User::createUserUdid($_GET['UDID'], $os, $mac);
        $_SESSION['userID'] = $user->getID();
		$_SESSION['udid'] = $user->getUDID();    	
        header("Location: {$serverRoot}choosemission.php");
        exit;
    } else if (!empty($user)) {
        if(isset($_GET['legacy'])){
            $legacy = $_GET['legacy'];
            $_SESSION['legacy'] = true;
        }
        $_SESSION['userID'] = $user->getID();
		$_SESSION['udid'] = $user->getUDID();
        $param .= '&legacy='.$_GET['legacy'];
        header("Location: {$serverRoot}charhome.php");
        exit;
    } else {
        header("Location: {$serverRoot}errorpage.html");
        exit;
    }
}else{
    header("Location: {$serverRoot}errorpage.html");
    exit;
}
?>