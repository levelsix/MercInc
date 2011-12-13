<?php
session_start();
include_once("properties/playertypeproperties.php");
include_once("properties/serverproperties.php");
include_once("properties/constants.php");
include_once 'classes/User.php';
$user = User::getUser($_SESSION['userID']);
$userType = $user->getType();
if(!$userType)
	$userType = 1;
?>
<!DOCTYPE html>
<html>
<head>
      <link rel="stylesheet" type="text/css" href="css/style.css">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
		<script type="text/javascript" src="js/jquery-1.6.2.js"></script>
		<script>
			$(document).ready(function(){
				//add the Selected class to the checked radio button
				$('input:checked').parent().addClass("selected");
				//If another radio button is clicked, add the select class, and remove it from the previously selected radio
				$('input').click(function () {
					$('input:not(:checked)').parent().removeClass("selected").find("span").html;
					$('input:checked').parent().addClass("selected").find("span").html;
				});
			});	

		function displayAlertJS(msg){
			window.location = "level6://develop.com?call=displayAlert:&param="+msg;
		}
			
		function checkClass()
		{
			<?php if($user->getLevel() > 3) { ?>
			var value = $("input[@name=playertype]:checked").val();
			if( value == <?php echo $userType ?>){    
				//alert('you already have the same class');
				displayAlertJS("<?php echo SAME_CLASS_ERROR ?>");
				return false;	
			 } 
			 <?php } ?>
		}
		</script>
		<style>

		</style>
</head>
<?php


?>
<body>
	<div class="tutorialheader">
            <h1>Mercenaries, Inc.</h1>
    </div>
	<div class="natsays">
	<p><strong>Natalie Says:</strong> "I like that name. Now that I know you a bit better, you can decide what type of solider you'll be, this determines what your strength will be"</p>
	</div>
	<div class="tutorialchoice">
	<h2>Select your Class:</h2>
        <form action='<?php echo $serverRoot; ?>backend/chooseclass.php' method='POST' onSubmit="return checkClass();">
            <?php if(isset($_GET['page'])  && trim($_GET['page']) == 'blackmarket' ){ ?>
               <input type="hidden" name="diamonds" value="<?php echo $_GET['diamonds']; ?>" />
		       <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
            <?php } else if(isset($_GET['page'])){ ?>
                <input type="hidden" name="cityID" value="<?php echo $_GET['cityID']; ?>" />
                <input type="hidden" name="missionID" value="<?php echo $_GET['missionID']; ?>" />
                <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
            <?php } ?>
            <div class="chooseclass">
                    <input type="radio" name="playertype" value="1" id="heavy" <?php if ($userType == 1){echo 'checked="checked"' ;}?> >
                    <label class="selectclass" for="heavy">
                    <img src="img/heavyarms.png">
                    <h4>Heavy Arms</h4>
                    <em>Gains Energy Faster</em>

                </label>
            </div>
            <div class="chooseclass">
                <input type="radio" name="playertype" value="2" id="special" <?php if ($userType == 2){echo 'checked="checked"' ;}?>>
                <label class="selectclass" for="special">
                    <img src="img/specialist.png">
                    <h4>Specialist</h4>
                    <em>Gains Cash Faster</em>
                </label>
            </div>
            <div class="chooseclass">
                <input type="radio" name="playertype" value="3" id="marine" <?php if ($userType == 3){echo 'checked="checked"' ;}?>>
                <label class="selectclass" for="marine">
                    <img src="img/marine.png">
                    <h4>Marine</h4>
                    <em>Heals Faster</em>
                </label>
            </div>
            <div class="continue">
                <input type="submit" class="blackyellowbutton selectclassbtn" value="Select This Class" />
            </div>
        </form>
    </div>
		
</body>
</html>