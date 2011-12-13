<?php

include_once("../properties/serverproperties.php");
include_once("../classes/User.php");
echo "<pre>";

$users_data = User::getUsersWithNoSoldierCode();

$soldierCode = new SoldierCode();

foreach ($users_data as $user) {

    $code = SoldierCode::getSoldierCode();
    
    if($code[0]->getCode() != ""){
        User::updateSoldierCode($user->getID(), $code[0]->getCode());
        $soldierCode->deleteCode($code[0]->getCode());
        echo ": Updated Soldier Code <br>";
    }
}
?>
