<?php
include_once("../classes/User.php");

$sound = $_POST['sound'];
$comment_notif = $_POST['comment_notifications'];
$stat_notif = $_POST['stat_notifications'];

session_start();
$user = User::getUser($_SESSION['userID']);
$user-> updateUserSettings($sound,$comment_notif,$stat_notif);

?>