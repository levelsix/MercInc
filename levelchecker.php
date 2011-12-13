<?php
include_once 'config.php';
session_start();
$missionsMinLevel = 1;
$homeMinLevel = 2;
$battleMinLevel = 3;
$shopItemMinLevel = 4;
$shopREMinLevel = 4;
$profileMinLevel = 5;
$hospitalMinLevel = 5;
$recruitMinLevel = 6;
$blackMarketMinLevel = 3;

$user = User::getUser($_SESSION['userID']);
$level = $user->getLevel();

function notificationHTML($level, $title, $desc, $serverRoot){
     $HTML = '<div class="natsays">
	<h2>'.$title.'</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can '.$desc.'"</p>
	<p>"Come back at level '.$level.'"</p>
	<a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
     return $HTML;
}

//TODO: refactor js to have absolute paths
$tooLowLevelString = '<div class="natsays">
	<h2>Attack</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can start fighting."</p>
	<p>"Come back at level three"</p>
	<a href="choosemission.php" class="blackyellowbutton notready">Missions</a>
</div>';

if ($_GET['pageRequestType'] == "mission"){
	if ($level >=  $missionsMinLevel) {
		echo "<script>location.href='{$serverRoot}choosemission.php'</script>";
        exit;
	} else {		
		//$_SESSION['levelError'] = $tooLowLevelString . $missionsMinLevel . " to do missions";
            $_SESSION['levelError'] = $missionsMinLevel;
            echo "<script>location.href='{$serverRoot}choosemission.php'</script>";
            exit;
	}
} else if ($_GET['pageRequestType'] == "battle"){
	if ($level >=  $battleMinLevel) {
		echo "<script>location.href='{$serverRoot}battle.php'</script>";
                exit;
	} else {		
		//$_SESSION['levelError'] = $tooLowLevelString . $battleMinLevel . " to battle";
                $_SESSION['levelError']=  notificationHTML($battleMinLevel, 'Battle', 'start fighting', $serverRoot);
                echo "<script>location.href='{$serverRoot}battle.php'</script>";
                exit;
	}
} else if ($_GET['pageRequestType'] == "shopitem"){
	if ($level >=  $shopItemMinLevel) {
		echo "<script>location.href='{$serverRoot}equipment.php'</script>";
                exit;
	} else {
		//$_SESSION['levelError']= $tooLowLevelString . $shopItemMinLevel . " to shop";
                $_SESSION['levelError']=  notificationHTML($shopItemMinLevel, 'Equipment', 'buy equipment', $serverRoot);
                echo "<script>location.href='{$serverRoot}equipment.php'</script>";
                exit;
	}
} else if ($_GET['pageRequestType'] == "shoprealestate"){
	if ($level >=  $shopREMinLevel) {
		echo "<script>location.href='{$serverRoot}shoprealestatelist.php'</script>";
                exit;
	} else {
		//$_SESSION['levelError'] = $tooLowLevelString . $shopREMinLevel . " to shop";
                $_SESSION['levelError']=  notificationHTML($shopREMinLevel, 'Real Estate', 'buy property', $serverRoot);
                echo "<script>location.href='{$serverRoot}shoprealestatelist.php'</script>";
                exit;
	}
} else if ($_GET['pageRequestType'] == "recruit"){
	if ($level >=  $recruitMinLevel) {
		echo "<script>location.href='{$serverRoot}invite.php'</script>";
                exit;
	} else {
		//$_SESSION['levelError'] = $tooLowLevelString . $recruitMinLevel . " to recruit";
                 $_SESSION['levelError']=  notificationHTML($recruitMinLevel, 'Recruit', 'recruit your army', $serverRoot);
                echo "<script>location.href='{$serverRoot}invite.php'</script>";
                exit;
	}
} else if ($_GET['pageRequestType'] == "home"){
	if ($level >=  $homeMinLevel) {
		echo "<script>location.href='{$serverRoot}charhome.php'</script>";
                exit;
	} else {
		//$_SESSION['levelError'] = $tooLowLevelString . $homeMinLevel . " to get to home";
            $_SESSION['levelError']=  notificationHTML($homeMinLevel, 'Home', 'visit this page', $serverRoot);
                echo "<script>location.href='{$serverRoot}charhome.php'</script>";
                exit;
	}
} else if ($_GET['pageRequestType'] == "profile"){
    	if ($level >=  $profileMinLevel) {
		echo "<script>location.href='{$serverRoot}  profile.php'</script>";
                exit;
	} else {
		//$_SESSION['levelError'] =  $tooLowLevelString . $profileMinLevel . " to get to profile";
                $_SESSION['levelError']=  notificationHTML($profileMinLevel, 'Profile', 'view profile', $serverRoot);
                echo "<script>location.href='{$serverRoot}charprofile.php'</script>";
                exit;
	}
} else if ($_GET['pageRequestType'] == "hospital"){
	if ($level >=  $hospitalMinLevel) {
		echo "<script>location.href='{$serverRoot}hospital.php'</script>";
                exit;
	} else {
		//$_SESSION['levelError'] =  $tooLowLevelString . $hospitalMinLevel . " to get to hospital";
                $_SESSION['levelError']=  notificationHTML($hospitalMinLevel, 'Hospital', 'heal from hospital', $serverRoot);
                echo "<script>location.href='{$serverRoot}hospital.php'</script>";  
                exit;
	}
}else if ($_GET['pageRequestType'] == "blackMarket"){
	if ($level >=  $blackMarketMinLevel) {
		echo "<script>location.href='{$serverRoot}blackmarket.php'</script>";
                exit;
	} else {
		//$_SESSION['levelError'] =  $tooLowLevelString . $hospitalMinLevel . " to get to hospital";
                $_SESSION['levelError']=  notificationHTML($blackMarketMinLevel, 'BlackMarket', 'view Black Market', $serverRoot);
                echo "<script>location.href='{$serverRoot}hospital.php'</script>";  
                exit;
	}
}
if(isset ($_SESSION['levelError']))
{
    $tooLowLevelString = '<div class="natsays">
	<h2>Attack</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can start fighting."</p>
	<p>"Come back at level '.$_SESSION['levelError'].'"</p>
	<a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
    $_SESSION['levelError'] = $tooLowLevelString;
    header("Location: {$serverRoot}charhome.php");
    exit;
}
?>