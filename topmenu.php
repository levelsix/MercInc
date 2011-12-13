<?php
// Gzip 
ob_start("ob_gzhandler");
ob_start();
session_start();
/*
 * Main aplication file
 */
require_once 'config.php';
require_once 'constants.php';
require_once 'classes/common_functions.php';
require_once 'classes/UserTimers.php';
include_once 'classes/RealEstate.php';
include_once("properties/stringconstants.php");
include_once("properties/constants.php");
//include_once("googlecheckout/google_checkout.php");


//if(!isset($_SESSION['userID'])){
//    header("Location: {$serverRoot}index.php");
//}
$fn = new common_functions();


$user = User::getUser($_SESSION['userID']);

//if (!$user) {
//	// Redirect to error page
//	header("Location: {$serverRoot}errorpage.html");
//	exit;
//}
$playerName = $user->getName();
$playerLevel = $user->getLevel();
$playerType = $user->getType();
$playerCash = $user->getCash();
$playerStamina = $user->getStamina();
$playerHealth = $user->getHealth();
$playerEnergy = $user->getEnergy();
$playerStaminaMax = $user-> getStaminaMax();
$playerHealthMax = $user->getHealthMax();
$playerEnergyMax = $user->getEnergyMax();
$playerExp = $user->getExperience();
$playerDiamonds = $user->getDiamonds();
$PlayerIncome = $user->getIncome();
$PlayerNetIncome = $user->getNetIncome();
$PlayerUpkeep = $user->getUpkeep();
$uuid = $user->getUDID();
$deviceOS = $user->getDeviceOS();
$_SESSION['device_os'] = $deviceOS;
$_SESSION['udid'] = $uuid;
$PlayerAgencySize = count($user->getUsersInAgency($user->getID()));
$playerNextExperiencePoints = $user->getNextLevelExperiencePoints();
$previousExperiencePoints = $user->getPlayerPreviousLevel();
$soundSetting = $user->getSoundSettings();
$viberationSetting = $user->getVibrationSettings();




if(isset($playerType)) {
    if($playerType == 1){
        $energy_increase_timer -= 1;
    } else if($playerType == 2){
       $income_increase_timer -= 10;
    } else if($playerType == 3){
        $health_increase_timer -= 0.5;
    }
}


$expBarValue = 0;
if($playerNextExperiencePoints!=0){
    $expBarValue = ceil(( ($playerExp-$previousExperiencePoints) /($playerNextExperiencePoints-$previousExperiencePoints) * 100));
}

$timers = UserTimers::getTimers($_SESSION['userID']);

if($timers){
    $income_timer = 0;
    $health_timer = 0;
    $energy_timer = 0;
    $stamina_timer = 0;

    $income_timer_diff = $timers->getIncomeTimer();
    $health_timer_diff = $timers->getHealthTimer();
    $energy_timer_diff = $timers->getEnergyTimer();
    $stamina_timer_diff = $timers->getStaminaTimer();

$timers_in_seconds = UserTimers::getTimersInSeconds($_SESSION['userID']);

if($PlayerNetIncome != 0){
    if($fn->isTimeSet($income_timer_diff)){
        $income_timer = $timers_in_seconds->getIncomeTimer();
        if($income_timer < 0 ){
            UserTimers::updateIncomeTimer($_SESSION['userID']);
            $income_timer = 0;
        }
    } else {
        UserTimers::updateIncomeTimer($_SESSION['userID']);
    }
} else if($fn->isTimeSet($income_timer_diff)){
        UserTimers::stopIncomeTimer($_SESSION['userID']);
}
if($playerHealth < $playerHealthMax){
    if($fn->isTimeSet($health_timer_diff)){
        $health_timer = $timers_in_seconds->getHealthTimer();
        if($health_timer < 0 ){
            UserTimers::updateHealthTimer($_SESSION['userID']);
            $health_timer = 0;
        }
    } else  {
        UserTimers::updateHealthTimer($_SESSION['userID']);
    }
} else if($fn->isTimeSet(trim($health_timer_diff))){
    UserTimers::stopHealthTimer($_SESSION['userID']);
}
if($playerEnergy < $playerEnergyMax) {
    if($fn->isTimeSet($energy_timer_diff)){
        $energy_timer = $timers_in_seconds->getEnergyTimer();
        if($energy_timer < 0 ){
            UserTimers::updateEnergyTimer($_SESSION['userID']);
            $energy_timer = 0;
        }
    } else{
        UserTimers::updateEnergyTimer($_SESSION['userID']);
    }
} else if($fn->isTimeSet($energy_timer_diff)){
    UserTimers::stopEnergyTimer($_SESSION['userID']);
}
if($playerStamina < $playerStaminaMax){
    if($fn->isTimeSet($stamina_timer_diff)){
        $stamina_timer = $timers_in_seconds->getStaminaTimer();
        if($stamina_timer < 0 ){
            UserTimers::updateStaminaTimer($_SESSION['userID']);
            $stamina_timer = 0;
        }
    } else {
        UserTimers::updateStaminaTimer($_SESSION['userID']);
    }
} else if($fn->isTimeSet($stamina_timer_diff)){
    UserTimers::stopStaminaTimer($_SESSION['userID']);
}

$income_mins = floor($income_timer / 60);
$income_increment = floor($income_mins / $income_increase_timer);

if($income_increment > 0){
    $increment = $income_increment;
    //echo "<br> income increment  = ".$increment;
    if(UserTimers::incrementIncomeTimer($_SESSION['userID'], $increment * $income_increase_timer)){
        if($playerCash + $PlayerNetIncome > 0){
            $income_timer -= ($income_increment * $income_increase_timer * 60);
            $updatedCash = $increment * $PlayerNetIncome;
           // echo "<br> User cash = ".$playerCash;
            $playerCash += $updatedCash;
            //echo "<br> Updated cash = ".$playerCash;
            User::updateUserIncome($_SESSION['userID'], $playerCash);
        } else {
            $userUpkeepItemsDetails = Item::getUserUpkeepItems($_SESSION['userID']);
                    $allUpkeepItems = array();
                    foreach($userUpkeepItemsDetails as $item){
                        array_push($allUpkeepItems, array('type' => 'item', 'id' => $item->getID(), 'upkeep' => $item->getUpkeep(), 'quantity' => $item->getQuantity(), 'price' => $item->getPrice()));
                    }
                    $fn->aasort($allUpkeepItems, "upkeep");
                    $allUpkeepItems = array_reverse($allUpkeepItems);

                    $count = 0;
                    while (($playerCash + $PlayerNetIncome) < 0 ) {
                        if($allUpkeepItems[$count]['quantity'] > 0){
                            $amount = 0;
                            $id = $allUpkeepItems[$count]['id'];
                            $upkeep = $allUpkeepItems[$count]['upkeep'];
                            if($allUpkeepItems[$count]['type'] == 'item'){
                                $amount = $allUpkeepItems[$count]['price'];
                                $user->decrementUserItem($id, 1);
                                $user->updateUserCash($amount * SELL_RATIO);
                            }
                            $user->decrementUserUpkeep($upkeep);
                            $playerCash += $amount * SELL_RATIO;
                            $PlayerUpkeep -= $upkeep;
                            $PlayerNetIncome = $PlayerIncome - $PlayerUpkeep;
                            $allUpkeepItems[$count]['quantity']--;
                            $isupkeepItemSold = "&itemSold";
                        } else {
                            $count++;
                        }
                    }
                    $updatedCash = $playerCash + $PlayerNetIncome;
                    if(User::updateUserIncome($_SESSION['userID'], $updatedCash)){
                        $_SESSION['upkeep_item_sold'] = true;
                    }
                }
            }
        }

if($playerHealthMax > $playerHealth){
    $health_mins = floor($health_timer / 60);
    $health_increment = floor($health_mins / $health_increase_timer);
    //echo "<br> health increment = ".$health_increment;
    if($health_increment > 0){
        $new_health = $health_increment + $playerHealth;
        //echo "<br> New health = ".$new_health;
        if($new_health >= $playerHealthMax ){
            if(UserTimers::stopHealthTimer($_SESSION['userID'])){
                //echo "<br> Max health = ".$playerHealthMax;
                User::updateUserHealth($_SESSION['userID'], $playerHealthMax);
                $playerHealth = $playerHealthMax;
                $health_timer = 0;
            }
        } else {
             if(UserTimers::incremenHealthTimer($_SESSION['userID'], $health_increment * $health_increase_timer )){
                 User::updateUserHealth($_SESSION['userID'], $new_health);
                 $health_timer -= ($health_increment * $health_increase_timer)* 60;
                 $playerHealth = $new_health;
            }
        }
    }
}
if($playerEnergyMax > $playerEnergy){
    $energy_mins = floor($energy_timer / 60);
    $energy_increment = floor($energy_mins / $energy_increase_timer);
    if($energy_increment > 0){
        $new_energy = $energy_increment + $playerEnergy;
        if($new_energy >= $playerEnergyMax ){
            if(UserTimers::stopEnergyTimer($_SESSION['userID'])){
                User::updateUserEnergy($_SESSION['userID'], $playerEnergyMax);
                $energy_timer = 0;
                $playerEnergy = $playerEnergyMax;
            }
        } else {
             if(UserTimers::incremenEnergyTimer($_SESSION['userID'], $energy_increment * $energy_increase_timer )){
                 User::updateUserEnergy($_SESSION['userID'], $new_energy);
                 $energy_timer -= ($energy_increment * $energy_increase_timer)* 60;
                 $playerEnergy = $new_energy;
            }
        }
    }
}
if($playerStaminaMax  > $playerStamina){
    $stamina_mins = floor($stamina_timer / 60);
    $stamina_seconds = $stamina_timer % 60;
    $stamina_increment = floor($stamina_mins / $stamina_increase_timer);
    if($stamina_increment > 0){
        $new_stamina = $stamina_increment + $playerStamina;
        if($new_stamina >= $playerStaminaMax ){
            if(UserTimers::stopStaminaTimer($_SESSION['userID'])){
                User::updateUserStamina($_SESSION['userID'], $playerStaminaMax);
                $stamina_timer = 0;
                $playerStamina = $playerStaminaMax;
            }
        } else {
             if(UserTimers::incremenStaminaTimer($_SESSION['userID'], $stamina_increment * $stamina_increase_timer )){
                 User::updateUserStamina($_SESSION['userID'], $new_stamina);
                 $playerStamina = $new_stamina;
                 $stamina_timer -= ($stamina_increment * $stamina_increase_timer)* 60;
            }
        }
    }
}
}
if(trim($playerName) == "" || $playerType == ""){

     if($playerLevel >= 3 && (strrpos($_SERVER['REQUEST_URI'], "choosemission.php") < 1) && (strrpos($_SERVER['REQUEST_URI'], "chooseclasspage.php") < 1) && (strpos($_SERVER['REQUEST_URI'], "chooseplayername.php") < 1)){
        if(!$playerName){
            header("Location: {$serverRoot}chooseplayername.php");
            exit;
        } else if(!$playerType){
           header("Location: {$serverRoot}chooseclasspage.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>LVL 6</title>

        <link rel="stylesheet" type="text/css" href="css/toggle.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />

        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
        <script type="text/javascript" src="js/jquery-1.6.2.js"></script>
        <script type="text/javascript" src="js/jquery.ui.core.js"></script>
        <script type="text/javascript" src="js/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="js/jquery.ui.tabs.js"></script>
        <script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.js"></script>
        <script type="text/javascript" src="js/common_functions.js"></script>
        <script type="text/javascript" src="js/jquery.numeric.js"></script>
        <script type="text/javascript" src="js/jquery.edit.js"></script>
        <script type="text/javascript" src="js/divrotator.js"></script>


       <script language="javascript">
			var redirectURL;
            var default_timer = {
               incomeMinutes : <?php echo $income_increase_timer - 1; ?>,
               energyMinutes : <?php echo $energy_increase_timer - 1; ?>,
               healthMinutes : <?php echo round($health_increase_timer) - 1; ?>,
               staminaMinutes : <?php echo $stamina_increase_timer - 1; ?>
           };

           var max_value = {
               maxEnergy : <?php echo $playerEnergyMax; ?>,
               maxStamina : <?php echo $playerStaminaMax; ?>,
               maxHealth : <?php echo $playerHealthMax; ?>,
               netIncome : <?php echo abs($PlayerNetIncome); ?>

           };


           var cur_value = {
                energy : <?php echo $playerEnergy; ?>,
                stamina : <?php echo $playerStamina; ?>,
                health : <?php echo $playerHealth; ?>
           };

           var incomeAndUpkeepTimer = {
               seconds : <?php echo 59 - ($income_timer % 60); ?>,
               minutes : <?php echo ($income_increase_timer - 1) - floor($income_timer/60); ?>
           };

           var energyTimer = {
               seconds : <?php echo 59 - ($energy_timer% 60); ?>,
               minutes : <?php echo ($energy_increase_timer - 1) - floor($energy_timer / 60); ?>
           };

           var healthTimer = {
               seconds : <?php
                                if(isset($playerType) && $playerType == 3) {
                                    $health_timer += 30;
                                }
                                 echo 59 - ($health_timer% 60);

                         ?>,
               minutes : <?php echo (round($health_increase_timer) - 1) - floor($health_timer / 60); ?>
           };


           var staminaTimer = {
               seconds : <?php echo 59 - ($stamina_timer% 60); ?>,
               minutes : <?php echo ($stamina_increase_timer - 1) - floor($stamina_timer / 60); ?>
           };


		$(function() {
            $( "#tabs" ).tabs();
        });

		function playSound(fileName){
                    <?php if($soundSetting) {
                            if(strtolower($_SESSION['device_os']) == "android"){
                        ?>
                            window.Level6android.playAudio(fileName);
                        <?php
                            } else {
                         ?>
                             window.location = "level6://develop.com?call=playSound:&param="+fileName;
                    <?php }
                    }
                    ?>
		}
            function runViberation(){
                    <?php if($viberationSetting) {
                            if(strtolower($_SESSION['device_os']) == "android"){
					?>			
								window.Level6android.vibrate(2000);
                      <?php  }else{
                                ?>
                                window.location = "level6://develop.com?call=vibrateDevice:&param=1";
			                    <?php
                            }
                        }
                    ?>
		}
		function displayAlertJS(msg){
			<?php if(strtolower($_SESSION['device_os']) == "android"){ ?>
				  //alert(msg);
				window.Level6android.showInformationDialog('',msg,'OK');
			<?php }else{ ?>
				window.location = "level6://develop.com?call=displayAlert:&param="+msg;
			<?php } ?>
		}
		function diamondPurchased(response){
             if(response){
				if(redirectURL){
					window.location.href= redirectURL;
						exit;
					}							
			  }
		}
               
                function displayAlert(name,quantity,atkBoost,defBoost,upkeep,price) {
                    msg = name;
                    msg += "\\nPrice:$"+price;
                    msg += "\\nAttack:"+atkBoost;
                    msg += "\\nDefence:"+defBoost;
                    msg += "\\nOwned:"+quantity;
                    msg += "\\nUpkeep:$"+upkeep;

                    displayAlertJS(msg);
                }
                function displayAlertRealEstate(name,quantity,income,price) {
                    msg = name;
                    msg += "\\nPrice:$"+price;
                    msg += "\\nIncome:$"+income;
                    msg += "\\nOwned:"+quantity;

                    displayAlertJS(msg);
                }
    	$(document).ready(function() {
                $(".inlinecontent").fancybox({
                        'titlePosition'		: 'inside',
                        'transitionIn'		: 'none',
                        'transitionOut'		: 'none'
                });
                // Calling timer functions
                <?php
                if($PlayerNetIncome != 0){
                    echo "runIncomeIncreaseTimer();";
                }
                if($playerHealth < $playerHealthMax){
                    echo "runHealthTimer();";
                }
                if($playerEnergy < $playerEnergyMax){
                    echo "runEnergyTimer();";
                }
                if($playerStamina < $playerStaminaMax){
                    echo "runStaminaTimer();";
                }
                if(isset($_SESSION['missionsound']) && trim($_SESSION['missionsound']) != ""){
                    echo "playSound('".$_SESSION['missionsound']."')";
                    unset ($_SESSION['missionsound']);
                }

                ?>
               // updateTimer("health", 2);

                //runEnergyTimer();
                //runHealthTimer();
                //runStaminaTimer();

        });

        $.fx.off = !$.fx.off;

        touchMove = function(event) {
                // Prevent scrolling on this element
                event.preventDefault();
        }

        function playAndRedirect(fileName,url){

            if(url != ''){
                setTimeout(function(){
                        window.location.href = url;
                }, 100);
            }
			<?php if($soundSetting) {
					if(strtolower($_SESSION['device_os']) == "android"){
				?>
                        window.Level6android.playAudio(fileName);
                <?php
                        } else {
                ?>
                       window.location = "level6://develop.com?call=playSound:&param="+fileName;
            <?php }
                }
            ?>
            //window.location.href = url;
        }
        function updateTimer(timerName, incrementValue, el){
            var params = {
            'request_timer' : timerName,
            'increment_value' : incrementValue
            }
            $.get("<?php echo $serverRoot.'backend/updateTimer.php' ;?>", params,
               function(data) {
                   response_data = data.split('&');
                   //console.log(response_data);
                   if(response_data[2]){
                       data = response_data[2];
                   }
                   if(data == "success"){
                       if(timerName == 'income_timer'){
                            value = el.html();
                            /*
                            old_value = el.html();
                            old_value = parseInt(old_value.replace("$", ""));
                            netIncome = $('#netIncome').html();
                            netIncome = parseInt(netIncome.replace("$", ""));
                            old_value = old_value + netIncome;
                            console.log("====="+old_value);
                            el.html("$" + old_value);
                            */
                            value = parseInt(value.replace("$", ""));
                            userCash = response_data[0].split('=');
                            UserIncomePrice = response_data[1].split('=');
                            new_value = parseInt(userCash[1]);
                            max_value.netIncome = parseInt(Math.abs(UserIncomePrice[1]));
                            if(parseInt(UserIncomePrice[1]) < 0){
                                $('#netIncome').html("-$"+max_value.netIncome);
                            }else {
                                $('#netIncome').html("$"+max_value.netIncome);
                            }
                            if(response_data[3] == "itemSold"){
                                var  notification = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Alert!</span> Your high upkeep item has been sold.</p></div>';
                                $('#upkeep_item_sold_notification').html(notification);
                                $('#upkeep_item_sold_notification').show();
                            }
                            el.html("$" + new_value);
                       } else if(timerName == 'energy_timer'){
                           value = el.html();
                         //  console.log('energy '+ value);
                           new_value = parseInt(value) + 1;
                           if(new_value == max_value.maxEnergy){
                               $('#energyTime').html('Energy');
                           }
                           el.html(new_value);
                           cur_value.energy = new_value;
                       } else if(timerName == 'stamina_timer'){
                           value = el.html();
                          // console.log('stamina '+ value);
                           new_value = parseInt(value) + 1;
                           if(new_value == max_value.maxStamina){
                               $('#staminaTime').html('Stamina');
                           }
                           el.html(new_value);
                           cur_value.stamina = new_value;
                       } else if(timerName == 'health_timer'){
                           value = el.html();
                          // console.log('health '+ value);
                           new_value = parseInt(value) + 1;
                           if(new_value == max_value.maxHealth){
                               $('#healthTime').html('Health');
                           }
                           el.html(new_value);
                           cur_value.health = new_value;
                       }
                       // console.log("Data Loaded: " + data);
                    }else{
                       // console.log("Data Loaded: " + data);
                    }
               });
        }
        function stopTimer(timerName){
            var params = {
            'request_timer' : timerName,
            'operation' : 'stopTimer'
            }
            $.get("<?php echo $serverRoot.'backend/updateTimer.php' ;?>", params,
               function(data) {
                   if(data == "success"){
                       if(timerName == 'income_timer'){

                       } else if(timerName == 'energy_timer'){
                            $('#energyTime').html('Energy');
                       } else if(timerName == 'stamina_timer'){
                            $('#StaminaTime').html('Stamina');
                       } else if(timerName == 'health_timer'){
                            $('#healthTime').html('Health');
                       }
                    }else{
                       // console.log("Data Loaded: " + data);
                    }
               });
        }

        function runIncomeIncreaseTimer(){
            if(max_value.netIncome == 0 ){
                stopTimer('income_timer');
                return false;
            }
            //console.log(incomeAndUpkeepTimer.minutes);
           timeText = timeFormation(incomeAndUpkeepTimer.minutes, incomeAndUpkeepTimer.seconds );
            $('#cashTimer').html(timeText);

            if (incomeAndUpkeepTimer.seconds < 1){
                incomeAndUpkeepTimer.minutes = incomeAndUpkeepTimer.minutes - 1;
                if(incomeAndUpkeepTimer.minutes <0){
                   // console.log("Timer:: Increase Income and Deduct upKeep ");
                    el = $('.cashamount');
                    updateTimer('income_timer', 1, el)
                    incomeAndUpkeepTimer.minutes = default_timer.incomeMinutes;
                }
                incomeAndUpkeepTimer.seconds = 59;
            }
            incomeAndUpkeepTimer.seconds = incomeAndUpkeepTimer.seconds - 1;
            incomeTime = setTimeout(function(){
                        runIncomeIncreaseTimer()
                    },1000);
        }
        function runEnergyTimer(){
            if(max_value.maxEnergy <= cur_value.energy ){
                $('#energyTime').html('Energy');
                stopTimer('energy_timer');
                return false;
            }
            timeText = timeFormation(energyTimer.minutes, energyTimer.seconds );

            $('#energyTime').html(timeText);
           // console.log("energy => "+energyTimer.minutes+":"+energyTimer.seconds);

            if (energyTimer.seconds < 1){
                energyTimer.minutes = energyTimer.minutes - 1;
                if(energyTimer.minutes < 0){
                    //console.log("Timer:: Increase Energy ");
                    el = $('#playerEnergy');
                    updateTimer('energy_timer', 1, el)
                    energyTimer.minutes = default_timer.energyMinutes;
                }
                energyTimer.seconds=59;
            }
             energyTimer.seconds = energyTimer.seconds - 1;
            energyTime = setTimeout(function(){
                        runEnergyTimer()
                    },1000);
        }
        function runHealthTimer(){
            if(max_value.maxHealth <= cur_value.health ){
                $('#healthTime').html('Health');
                stopTimer('health_timer');
                return false;
            }
            timeText = timeFormation(healthTimer.minutes, healthTimer.seconds );

            $('#healthTime').html(timeText);
           // console.log("health => "+healthTimer.minutes+":"+healthTimer.seconds);

            if (healthTimer.seconds < 1){
                healthTimer.minutes = healthTimer.minutes - 1;
                if(healthTimer.minutes < 0){
                   // console.log("Timer:: Increase Health");
                    el = $('#playerHealth');
                    updateTimer('health_timer', 1, el)
                    healthTimer.minutes = default_timer.healthMinutes;
                    healthTimer.seconds = <?php if(isset($playerType) && $playerType == 3) {
                                                    echo 30;
                                                } else {
                                                    echo 60;
                                                }
                                            ?>;
                }
                if(healthTimer.seconds < 1){
                    healthTimer.seconds = 60;
                }
            }
            healthTimer.seconds = healthTimer.seconds - 1;
            healthTime = setTimeout(function(){
                        runHealthTimer()
                    },1000);
        }
        function runStaminaTimer(){
            if(max_value.maxStamina <= cur_value.stamina ){
                $('#StaminaTime').html('Stamina');
                stopTimer('stamina_timer');
                return false;
            }
            timeText = timeFormation(staminaTimer.minutes, staminaTimer.seconds );
            $('#staminaTime').html(timeText);
            if (staminaTimer.seconds < 1){
                staminaTimer.minutes = staminaTimer.minutes - 1;
                if(staminaTimer.minutes < 0){
                    if(max_value.maxStamina == cur_value.stamina ){
                        $('#StaminaTime').html('Stamina');
                    }
                    console.log("Timer:: Increase Stamina ");
                    el = $('#playerStamina');
                    updateTimer('stamina_timer', 1, el)
                    staminaTimer.minutes = default_timer.staminaMinutes;
                }
                staminaTimer.seconds=59;
            }
            staminaTimer.seconds = staminaTimer.seconds - 1;
            staminaTime = setTimeout(function(){
                        runStaminaTimer()
                    },1000);
        }
        function timeFormation(minutes, seconds){
            timeText = (minutes > 9 )? minutes : "0" + minutes;
            timeText += ":";
            timeText += (seconds > 9 )? seconds : "0" + seconds;
            return timeText;
        }

	</script>

	<style type="text/css">
        *{
            -webkit-touch-callout: none;
            // Disable selection/Copy of UIWebView
            -webkit-user-select: none;
        }
    </style>
</head>
	<body>


<div id="bar">

	<div id="topbar">

	<div id="cash">
		<p class="cashamount"><?php echo '$'.$playerCash;?></p>
                <p class="timeleft"><strong id="netIncome"><?php if($PlayerNetIncome < 0 ) echo "- "; ?>$<?php echo abs($PlayerNetIncome); ?></strong> in  <span id="cashTimer">00:00</span></p>
	</div>

	<div id="experience">
		<div id="percentbar">
			<img src="img/percentimage3.png"
				 alt="9.5%"
				 height="15"
	  			 class="percentImage"
	 			 style="background-position: <?php echo 100 - $expBarValue; ?>% 0pt;" />
		</div>
		<p class="experiencelabel">Exp: <strong><?php echo $playerExp; ?> </strong>/<?php echo $playerNextExperiencePoints; ?></p>
	</div>

	<div id="level">
            <a href="<?php echo $serverRoot.'charhome.php'; ?>" >Lvl<br />
		<em><?php echo $playerLevel;?> </em>
		</a>
	</div>

	</div>

	<div id="bottombar">
		<div id="health">
			<p id="healthbar"><strong id="playerHealth"><?php echo $playerHealth;?></strong>/<?php echo $playerHealthMax;?> <br />
			<em id="healthTime">Health</em>
			</p>
		</div>
		<div id="energy">
                    <p id="energybar"><strong id="playerEnergy"><?php echo $playerEnergy;?></strong>/<?php echo $playerEnergyMax;?><br />
			<em id="energyTime">Energy</em></p>
		</div>
		<div id="stamina">
			<p id="staminabar"><strong id="playerStamina"><?php echo $playerStamina;?></strong>/<?php echo $playerStaminaMax;?> <br />
			<em id="staminaTime">Stamina</em></p>
		</div>

	</div>

</div>


<div id="content">
    <div id="upkeep_item_sold_notification" <?php echo  isset($_SESSION['upkeepItemMessage'])? '':'style="display:none;"'; ?> >
        <?php
            if(isset($_SESSION['upkeep_item_sold'])){
                $_SESSION['upkeepItemMessage'] = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Alert!</span> Your high upkeep item has been sold.</p></div>';
                unset ($_SESSION['upkeep_item_sold']);
                header("location: {$serverRoot}charhome.php");
                exit;
            }
            if(isset($_SESSION['upkeepItemMessage'])){
                echo $_SESSION['upkeepItemMessage'];
                unset ($_SESSION['upkeepItemMessage']);
            }
        ?>
    </div>