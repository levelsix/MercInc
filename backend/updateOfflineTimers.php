<?php
session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../classes/UserTimers.php';
include_once '../classes/User.php';
include_once '../properties/constants.php';
include_once '../properties/stringconstants.php';
include_once '../classes/common_functions.php';
include_once '../apns/send_push_notifications.php';
include_once '../c2dm/send_to_c2dm.php';
include_once '../properties/c2dm_account.php';



$notifyingUsers = User::updateHealthOfflineTimer($health_increase_timer);
echo "<pre>";
print_r($notifyingUsers);
echo "------------------ END Health----------------------";
if(count($notifyingUsers) > 0 ){
    $message = NOTIFICATION_HEALTH_FULL;
    foreach($notifyingUsers as $user){
        if(strtolower($user->getDeviceOS()) == 'android'){
            authenticateAndSend($user->getC2DMToken(), $message, $c2dm_user_name,$c2dm_password,$c2dm_source,$c2dm_service);
        } else {
            getdeviceTokenAndSendMessage($user->getUDID(), $message);
        }
    }
}

$notifyingStaminaUsers = User::updateStaminaOfflineTimer($stamina_increase_timer);
print_r($notifyingStaminaUsers);
echo "------------------ END Stamina----------------------";
if(count($notifyingStaminaUsers) > 0 ){
    $message = NOTIFICATION_STAMINA_FULL;
    foreach($notifyingStaminaUsers as $user){
        if(strtolower($user->getDeviceOS()) == 'android'){
            authenticateAndSend($user->getC2DMToken(), $message, $c2dm_user_name,$c2dm_password,$c2dm_source,$c2dm_service);
        } else {
            getdeviceTokenAndSendMessage($user->getUDID(), $message);
        }
    }
}


$notifyingEnergyUsers = User::updateEnergyOfflineTimer($energy_increase_timer);
print_r($notifyingEnergyUsers);
echo "------------------ END Energy----------------------";
if(count($notifyingEnergyUsers) > 0 ){
    $message = NOTIFICATION_ENERGY_FULL;
    foreach($notifyingEnergyUsers as $user){
        if(strtolower($user->getDeviceOS()) == 'android'){
            authenticateAndSend($user->getC2DMToken(), $message, $c2dm_user_name,$c2dm_password,$c2dm_source,$c2dm_service);
        } else {
            getdeviceTokenAndSendMessage($user->getUDID(), $message);
        }
    }
}

/*
if(User::updateStaminaOfflineTimer($stamina_increase_timer)){
    echo "Users Stamina updated";
    echo "<br><br>";
}
if(User::updateEnergyOfflineTimer($energy_increase_timer)){
   echo "Users Energy updated" ;
   echo "<br><br>";
}

if(User::updateEnergyOfflineTimerSpecialUsers($energy_increase_timer_special)){
   echo "Special Users Energy updated" ;
   echo "<br><br>";
}
if(User::updateHealthOfflineTimerSpecialUsers($health_increase_timer_special)){
    echo "Special Users Health updated";
    echo "<br><br>";
}

*/

?>
