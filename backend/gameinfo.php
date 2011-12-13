<?php

include_once("../properties/playertypeproperties.php");
include_once("../properties/playerinitproperties.php");
include_once("../properties/serverproperties.php");
include_once("../classes/User.php");

$resp = array();
if (isset($_GET['UDID']) && trim($_GET['UDID']) != "") {
    $user = User::getUdidUser($_GET['UDID']);
    if (!empty($user)) {
        $resp['splash'] = $user->getSplashSettings() == 0 ? 0 : 1;
        $resp['tutorial'] = 0;
    } else {
        $resp['splash'] = 1;
        $resp['tutorial'] = 1;
    }
    echo json_encode($resp);
}
?>