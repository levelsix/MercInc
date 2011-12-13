<?php
/*
 *  Register any device for C2DM notifications. Get the parameter from device and registration token with corresponding user
 * 
 */
include_once '../classes/User.php';

if( isset ($_POST['registrationID']) && $_POST['registrationID'] != '' &&  $_POST['registrationID'] != null) 
{
    $registrationToken = $_POST['registrationID'];
}else if( isset ($_GET['registrationID']) && $_GET['registrationID'] != '' &&  $_GET['registrationID'] != null) 
{
    $registrationToken = $_GET['registrationID'];    
}
if( isset ($_POST['udid']) && $_POST['udid'] != '' &&  $_POST['udid'] != null) 
{
    $udid = $_POST['udid'];
}else if( isset ($_GET['udid']) && $_GET['udid'] != '' &&  $_GET['udid'] != null) 
{
    $udid = $_GET['udid'];
}
if($registrationToken != null && $udid !=null){           
    $success = User::updateUserC2DMRegistrationToken($udid, $registrationToken);
    echo $success;    
}else{
     echo 0;
}
?>
