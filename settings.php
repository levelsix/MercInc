<?php
	$isAjaxPage = true;
	include_once("topmenu.php");
       $fn->printArray($_GET);
	echo $splashSettings = $fn->get('splash_settings');
	echo $soundSettings = $fn->get('sound_settings');
	echo $vibrationSettings = $fn->get('vibration_settings');
	echo $commentSettings = $fn->get('comment_settings');
       echo $notificationSettings = $fn->get('notification_settings');

	$userID = $_SESSION['userID'];
	$user = User::getUser($userID);
	if($soundSettings == 'true')
		$soundSettings = 1;
	else
		$soundSettings = 0;

	if($vibrationSettings == 'true')
		$vibrationSettings = 1;
	else
	   	$vibrationSettings = 0;

	if($commentSettings == 'true')
		$commentSettings = 1;
	else
		$commentSettings = 0;

	if($notificationSettings == 'true')
		$notificationSettings = 1;
	else
		$notificationSettings = 0;

        if($splashSettings == 'true')
		$splashSettings = 1;
	else
		$splashSettings = 0;

	echo $result = $user->updateUserSettings($soundSettings, $vibrationSettings, $commentSettings, $notificationSettings, $splashSettings, $userID );


?>
