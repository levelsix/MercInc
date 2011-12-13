<?php
include_once("topmenu.php");
include_once 'classes/Item.php';
include_once("properties/playertypeproperties.php");
include_once("classes/Comments.php");
include_once("classes/blackMarket.php");
$userID = $_SESSION['userID'];
$profileMinLevel = 5;

function displayItemInfo($id,$imageUrl,$name,$quantity,$atkBoost,$defBoost,$upkeep,$price) {
 	$itemPopUPhtml = '<div style="display: none;">
                    	<div id="inline'.$id.'" style="width:98%;">
                    	<h5 class="popupweaponheader">'.$name.'</h5>
                            <div class="popuppicture">
                                    <img src="'.$imageUrl.'">
                            </div>
                            <div class="popupinfo">
                                    <ul class="weaponinfo">
                                            <li>$'.$price.'</li>
                                            <li>Attack: '.$atkBoost.'</li>
                                            <li>Defense: '.$defBoost.'</li>
                                            <li>Owned: '.$quantity.'</li>
                                            <li>Upkeep: '.$upkeep.'</li>
                                    </ul>
                            </div>
                            </div>
            		</div>';
   	echo $itemPopUPhtml;
}

function displayEstateInfo($id,$imageUrl,$name,$quantity,$income,$price) {
	$estatePopUPhtml = '<div style="display: none;">
                    	<div id="estate'.$id.'" style="width:98%;">
                    	<h5 class="popupweaponheader">'.$name.'</h5>
                            <div class="popuppicture">
                                    <img src="'.$imageUrl.'">
                            </div>
                            <div class="popupinfo">
                                    <ul class="weaponinfo">
                                            <li>$'.$price.'</li>
                                            <li>Owned: '.$quantity.'</li>
                                            <li>Income: $'.$income.'</li>
                                    </ul>
                            </div>
                            </div>
            		</div>';
   	echo $estatePopUPhtml;
}   
 
if(isset($_SESSION['levelError']))
{
    echo $_SESSION['levelError'];
    unset($_SESSION['levelError']);
    exit;
}
else if ($playerLevel<$profileMinLevel)
{
    $tooLowLevelString = '<div class="natsays">
	<h2>Profile</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can view profile."</p>
	<p>"Come back at LVL '.$profileMinLevel.'"</p>
	<a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
    echo $tooLowLevelString;
    exit;
}
//$user = User::getUser($userID);
if (!$user) {
    // Redirect to error page. this isnt working. b/c theres text above?
    header("Location: {$serverRoot}errorpage.html");
    exit;
	
}
$blackMarketItems ;


?>
<div id="profiletop">
    <img src="<?php echo $serverRoot.getPlayerTypeFromTypeImageURL($user->getType()); ?>" >
    <h2><?php echo $user->getName(); ?></h2>
    <h4>LVL <?php echo $user->getLevel(); ?> <?php echo getPlayerTypeFromTypeID($user->getType()); ?></h4>
</div>

<div id="tabs">
    <div class="container missionsmenu">
        <ul id="menu2">
            <li class="tab"><a href="#one">Stats</a></li>
            <li class="tab"><a href="#two">Skills</a></li>
            <li class="tab"><a href="#three">Badges</a></li>
            <li class="tab"><a href="#four">Comments</a></li>
        </ul>
    </div>	
    <div id="one">
        <h1 class="profileheader">Stats</h1>
        <div class="statsbg">
            <table class="stats">
                <tr>
                    <td class="title">Missions</td>
                    <td><?php echo $user->getNumMissionsCompleted(); ?></td>
                    <td class="title">Experience</td>
                    <td><?php echo $user->getExperience(); ?></td>

                </tr>
                <tr>
                    <td class="title">Kills</td>
                    <td><?php echo $user->getUserKills(); ?></td>
                    <td class="title">Fights Lost</td>
                    <td><?php echo $user->getFightsLost(); ?></td>

                </tr>
                <tr>
                    <td class="title">Deaths</td>
                    <td><?php echo $user->getUserDeaths(); ?></td>
                    <td class="title">Fights Won</td>
                    <td><?php echo $user->getFightsWon(); ?></td>
                </tr>
            </table>		
        </div>

        <h1 class="profileheader">Cashflow</h1>
        <div class="statsbg">
            <p class="cashflowequation"><span class="incomecash"><?php echo '$' . $user->getIncome(); ?></span> - <span class="upkeepcash"><?php echo '$' . $user->getUpkeep(); ?></span> = <span class="finalcash"><?php echo '$' . $user->getNetIncome(); ?></span> Every hour.</p>
        </div>

     <div class="achievements">
     <?php 
     $achievementss = User::getUserAchievementRanks($userID);
     foreach ($achievementss as $achievements) {
     ?>  
            <h2>Achievements</h2>
            <ul class="achievementlist">
                <li><div class="achievementstatus">
                        <img src="img/glove.png">
                        <p class="ranknumberp"><?php echo $achievements['victor_level']; ?></p>
                        <div class="meter">
                            <span style="width: <?php echo ($achievements['victor_level']/6)*100;?>%" ></span>
                        </div>
                    </div>
                    <h3>Victor</h3>
                </li>
                <li><div class="achievementstatus">
                        <img src="img/glove.png">
                        <p class="ranknumberp"><?php echo $achievements['fall_guy_level']; ?></p>
                        <div class="meter">
                            <span style="width: <?php echo ($achievements['fall_guy_level']/6)*100;?>%" ></span>
                        </div>
                    </div>
                    <h3>Fall Guy</h3>
                </li>
                <li><div class="achievementstatus">
                        <img src="img/glove.png">
                        <p class="ranknumberp"><?php echo $achievements['marksman_level']; ?></p>
                        <div class="meter">
                            <span style="width: <?php echo ($achievements['marksman_level']/6)*100;?>%" ></span>
                        </div>
                    </div>
                    <h3>Marksman</h3>
                </li>
            </ul>

            <ul class="achievementlist">
                <li><div class="achievementstatus">
                        <img src="img/glove.png">
                        <p class="ranknumberp"><?php echo $achievements['master_level']; ?></p>
                        <div class="meter">
                            <span style="width: <?php echo ($achievements['master_level']/6)*100;?>%" ></span>
                        </div>
                    </div>
                    <h3>Master</h3>
                </li>
                <li><div class="achievementstatus">
                        <img src="img/glove.png">
                        <p class="ranknumberp"><?php echo $achievements['monster_level']; ?></p>
                        <div class="meter">
                            <span style="width: <?php echo ($achievements['monster_level']/6)*100;?>%" ></span>
                        </div>
                    </div>
                    <h3>Monster</h3>
                </li>
                
                <li><div class="achievementstatus">
                        <img src="img/glove.png">
                        <p class="ranknumberp"><?php echo $achievements['prodigy_level']; ?></p>
                        <div class="meter">
                            <span style="width: <?php echo ($achievements['prodigy_level']/6)*100;?>%" ></span>
                        </div>
                    </div>
                    <h3>Prodigy</h3>
                </li>

            </ul>
<?php 
     }
?>
        </div>

        <h1 class="profileheader">Weapons</h1>
        <div class="statsbg">
            <ul class="weaponslist">

                <?php
                $userItems = $user->getUsersItems($userID);

                foreach ($userItems as $item) {
    	
                	$imageUrl = $item->image_url;
                	$name = $item->item_name;
                	$quantity = $item->quantity;
                	$atkBoost = $item->atk_boost;
                	$defBoost = $item->def_boost;
                	$upkeep = $item->item_upkeep;
                	$id = $item->item_id;
                	$price = $item->item_price;
                    if ($item->item_type == 1) {
                    	?>
                        <!-- <li><a class="inlinecontent" href="<?php echo"#inline".$id ; ?>"  onclick=" displayAlert(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$atkBoost.'\', \''.$defBoost.'\', \''.$upkeep.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->image_url; ?> "> </a><p>x<?php echo $item->quantity; ?></p></li> -->
                        <li><a class="" href="<?php echo"#inline".$id ; ?>"  onclick=" displayAlert(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$atkBoost.'\', \''.$defBoost.'\', \''.$upkeep.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->image_url; ?> "> </a><p>x<?php echo $item->quantity; ?></p></li>
                        <?php
                        //displayItemInfo($id,$imageUrl,$name,$quantity,$atkBoost,$defBoost,$upkeep,$price);
                    }
                }
                ?>
            </ul>
        </div>

        <h1 class="profileheader">Protection</h1>
        <div class="statsbg">
            <ul class="weaponslist">
                <?php
                foreach ($userItems as $item) {
                	
                    if ($item->item_type == 2) {
                                $imageUrl = $item->image_url;
                		$name = $item->item_name;
                		$quantity = $item->quantity;
                		$atkBoost = $item->atk_boost;
                		$defBoost = $item->def_boost;
                		$upkeep = $item->item_upkeep;
                		$id = $item->item_id;
                		$price = $item->item_price;
                        ?>
                        <!-- <li><a class="inlinecontent" href="<?php echo"#inline".$id ; ?>"  onclick=" displayAlert(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$atkBoost.'\', \''.$defBoost.'\', \''.$upkeep.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->image_url; ?> "> </a><p>x<?php echo $item->quantity; ?></p></li> -->
                          <li><a class="" href="<?php echo"#inline".$id ; ?>"  onclick=" displayAlert(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$atkBoost.'\', \''.$defBoost.'\', \''.$upkeep.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->image_url; ?> "> </a><p>x<?php echo $item->quantity; ?></p></li>
                        <?php
                        // displayItemInfo($id,$imageUrl,$name,$quantity,$atkBoost,$defBoost,$upkeep,$price);
                    }
                }
                ?>	
            </ul>
        </div>
        <h1 class="profileheader">Vehicles</h1>
        <div class="statsbg">
            <ul class="weaponslist">
                <?php
                foreach ($userItems as $item) {
                	
                    if ($item->item_type == 3) {
                    	$imageUrl = $item->image_url;
                		$name = $item->item_name;
                		$quantity = $item->quantity;
                		$atkBoost = $item->atk_boost;
                		$defBoost = $item->def_boost;
                		$upkeep = $item->item_upkeep;
                		$id = $item->item_id;
                		$price = $item->item_price;
                        ?>
                        <!-- <li><a class="inlinecontent" href="<?php echo"#inline".$id ; ?>"  onclick=" displayAlert(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$atkBoost.'\', \''.$defBoost.'\', \''.$upkeep.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->image_url; ?> "></a> <p> x<?php echo $item->quantity; ?></p></li> -->
                        <li><a class="" href="<?php echo"#inline".$id ; ?>"  onclick=" displayAlert(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$atkBoost.'\', \''.$defBoost.'\', \''.$upkeep.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->image_url; ?> "></a> <p> x<?php echo $item->quantity; ?></p></li>
                        <?php
                        //displayItemInfo($id,$imageUrl,$name,$quantity,$atkBoost,$defBoost,$upkeep,$price);
                    }
                }
                ?>	
            </ul>
        </div>
        <h1 class="profileheader">Real Estate</h1>
        <div class="statsbg">
            <ul class="weaponslist">
                <?php
                $real_esate = User::getUsersRealestates($userID);
                foreach ($real_esate as $item) {
                		$imageUrl = $item->estate_image;
                		$name = $item->estate_name;
                		$quantity = $item->estate_quantity;
                		$income = $item->estate_income;
                		$id = $item->estate_id;
                		$price = $item->estate_price;
                    ?>
                <!-- <li><a class="inlinecontent" href="<?php echo"#estate".$id ;?>" onclick=" displayAlert(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$atkBoost.'\', \''.$defBoost.'\', \''.$upkeep.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->estate_image; ?> "> </a><p> x<?php echo $item->estate_quantity; ?></p></li> -->
                <li><a class="" href="<?php echo"#estate".$id ;?>" onclick="displayAlertRealEstate(<?php echo '\''.$name.'\', \''.$quantity.'\', \''.$income.'\', \''.$price.'\''; ?>);"><img src="<?php echo $item->estate_image; ?> "> </a><p> x<?php echo $item->estate_quantity; ?></p></li>
                    <?php
                    //displayEstateInfo($id,$imageUrl,$name,$quantity,$income,$price);
                }
                ?>	
            </ul>
        </div>

    </div>
    <div id="two">
    	<?php if(isset($_GET['status']) && $_GET['status'] == "failure") {
    	?>
    	<div class="successmessage">
			<p class="successfailuremessage"><span class="failure">Failure!</span> You don't have enough skill points.</p>
		</div>
    	<?php 
    	}
    		$skillPoints = $user->getSkillPoints();
    	?>
        <p class="pointspend">You have <em><?php echo $skillPoints; ?></em> remaining skill points.</p>
		<table class="maxlist">
			<tr>
			<td class="maxname attack"><p>Attack<br/><em>1 Point for +1 Attack</em></p></td>
			<td class="maxnumber"><?php echo $user->getAttack(); ?></td>
			<?php if($skillPoints > 0) { ?>
            	<td class="maxincrease"><a class="blackbutton" href="<?php echo $serverRoot; ?>backend/useskill.php?attributeToIncrease=attack">Increase</a></td>
            <?php } else { ?>
            	<td class="maxincrease"><a class="disabledincrease">Increase</a></td>
            <?php }?>
			</tr>
		</table>
		<table class="maxlist">
			<tr>
			<td class="maxname defense"><p>Defense<br/><em>1 Point for +1 Defense</em></p></td>
			<td class="maxnumber"><?php echo $user->getDefense(); ?></td>
			<?php if($skillPoints > 0) { ?>
            	<td class="maxincrease"><a class="blackbutton" href="<?php echo $serverRoot; ?>backend/useskill.php?attributeToIncrease=defense">Increase</a></td>
            <?php } else { ?>
            	<td class="maxincrease"><a class="disabledincrease">Increase</a></td>
            <?php }?>
			</tr>
		</table>
		<table class="maxlist">
			<tr>
			<td class="maxname energy"><p>Energy<br/><em>1 Point for +1 Energy</em></p></td>
			<td class="maxnumber"><?php echo $user->getEnergyMax(); ?></td>
			<?php if($skillPoints > 0) { ?>
            	<td class="maxincrease"><a class="blackbutton" href="<?php echo $serverRoot; ?>backend/useskill.php?attributeToIncrease=energymax">Increase</a></td>
            <?php } else { ?>
            	<td class="maxincrease"><a class="disabledincrease">Increase</a></td>
            <?php }?>
			</tr>
		</table>
		<table class="maxlist">
			<tr>
			<td class="maxname health"><p>Health<br/><em>1 Point for +10 Health</em></p></td>
			<td class="maxnumber"><?php echo $user->getHealthMax(); ?></td>
			<?php if($skillPoints > 0) { ?>
            	<td class="maxincrease"><a class="blackbutton" href="<?php echo $serverRoot; ?>backend/useskill.php?attributeToIncrease=healthmax">Increase</a></td>
            <?php } else { ?>
            	<td class="maxincrease"><a class="disabledincrease">Increase</a></td>
            <?php }?>
			</tr>
		</table>
		<table class="maxlist">
			<tr>
			<td class="maxname stamina"><p>Stamina<br/><em>2 Points for +1 Stamina</em></p></td>
			<td class="maxnumber"><?php echo $user->getStaminaMax(); ?></td>
			 <?php if($skillPoints > 1) { ?>
            	<td class="maxincrease"><a class="blackbutton" href="<?php echo $serverRoot; ?>backend/useskill.php?attributeToIncrease=staminamax">Increase</a></td>
            <?php } else { ?>
            	<td class="maxincrease"><a class="disabledincrease">Increase</a></td>
            <?php }?>
			</tr>
		</table>
    </div>
   <div id="three">
	<div class="natsays">
		<p><strong>Natalie says:</strong></p>
		<h2>No Ability Badges</h2>
		<p>"You dont have any Ability badges yet."</p>
		<p>"Do some missions to get Ability badges"</p>
		<a href="<?php echo $serverRoot; ?>choosemission.php" class="blackyellowbutton notready">Missions</a>
	</div>
	<?php 
	function notificationHTML($level, $title, $desc, $serverRoot){
     $HTML = '<div class="natsays">
	<h2>'.$title.'</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can '.$desc.'"</p>
	<p>"Come back at LVL '.$level.'"</p>
	<a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
     return $HTML;
}
?>
</div>
  <script>
      /*
	$(document).ready(function() {
		$(".inlinecontent").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
				});
				});
	*/
	$.fx.off = !$.fx.off;
	
	touchMove = function(event) {
	// Prevent scrolling on this element
	event.preventDefault();
	};
        
	</script>
	<script language="javascript" type="text/javascript">
	function validateComment() {
		   var commentText = $('#coment').val();
			if (commentText.trim() == '' ) 
			{
                                displayAlertJS('You must enter some text to post a comment'); 
//				$('#inlinex').html('<h5 class="popupweaponheader">Error</h5><br/><p> You must enter some Text to post a comment</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
//				$('.abshide').click();
				return false;
			}
			else if (commentText.length >250) {
                                displayAlertJS('Character length must be less than 251 characters'); 
//				$('#inlinex').html('<h5 class="popupweaponheader">Error</h5><br/><p> The length of the comment must be less than 251 characters</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
//				$('.abshide').click();
				return false;
			}

	}
	
	function closePpoup() {
		$('#fancybox-close').click();
	 }		
	</script>
	
<div style="display: none;">
	<div id="inlinex" style="width:98%;">
	
	</div>
</div>
    <div id="four">
    <a class="inlinecontent abshide" href="#inlinex"> </a>
    <form method="POST" action="backend/commentactions.php?action=post" onsubmit="return validateComment();">
    <!--   onKeyDown="limitText(this.form.content,this.form.countdown,250);" -->
        <textarea id ="coment" class="comment" name="content" maxlength="250" ></textarea>
        <input type="submit" class="commentbutton" value="Post Comment" />
        <input type="hidden" value="<?php echo $userID ;?>" name="sender_id" >
        <input type="hidden" value="<?php echo $userID ;?>" name="receiver_id" >
    </form>
    <?php 
    	$myComments = Comments::getUserComments($userID);
    	foreach($myComments as $comment)
    	{
    		$sender = User::getUser($comment->getSenderID ());
    ?>
        <div class="commentpost">
            <a href="#"><?php echo $sender->getName();  ?></a>
            <a class="commentdelete" href="backend/commentactions.php?action=delete&id=<?php echo $comment->getCommentID (); ?>">Delete</a>
            <p><?php echo $comment->getContent(); ?></p>
        </div>
  <?php 
    	}
  ?>
    </div>
</div>
</body>
<script>
    <?php if(isset($_GET['selectedtab']) && $_GET['selectedtab'] == "skillpoints" ) { ?>
    $( "#tabs" ).tabs({
         selected: 1
    });
    <?php } ?>
</script>