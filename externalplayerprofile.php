<?php
include_once("topmenu.php");
include_once("classes/User.php");
include_once("classes/Item.php");
include_once("properties/playertypeproperties.php");
include_once("classes/Comments.php");


$error = $fn->get('error');
$userID = $fn->get('userID');
$result_html = '';
if ($error == 'notEnoughCashForBounty') {
    echo $result_html = '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>Sorry, you don\'t have enough cash!</h3>
			</div>
		</div>
	</div>';
} else if ($error == 'unKnownError') {
    echo $result_html = '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>Sorry, Can\'t add Bounty!</h3>
			</div>
		</div>
	</div>';
} else if ($error == 'sameAgencyUser') {
    echo $result_html = '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>Sorry, you can\'t add Bounty against your agency members!</h3>
			</div>
		</div>
	</div>';
} else {

    $profileUser = User::getUser($userID);
    if (!$profileUser) {
        header("Location: $serverRoot");
        exit;
    }
}


$user = User::getUser($userID); // other user 
$userCurrent = User::getUser($_SESSION['userID']); // current user
//$fn -> printArray($user);
if (!$user) {
    // Redirect to error page. this isnt working. b/c theres text above?
    //header("Location: $serverRoot/errorpage.html");
    exit;
}
?>


<script language="javascript" type="text/javascript">
    function validateComment() {
	
        var commentText = $('#coment').val();
	   
        if (commentText.trim() == '' ) 
        {
            displayAlertJS('This field cannot be left blank');                    
            //			$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> you must enter some Text to post a comment</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
            //			$('.abshide').click();
            return false;
        }else if (commentText.length >250) {
            displayAlertJS('Character length must be less than 251 characters'); 
            //				$('#inlinex').html('<h5 class="popupweaponheader">Error</h5><br/><p> The length of the comment must be less than 251 characters</p><div class="popupbuy"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
            //				$('.abshide').click();
            return false;
        }
    }
    function validateBountyAmount() {
        var bounty = document.getElementById('bountyAmount').value;
        console.log(bounty.trim());
        if (bounty.trim() == '') 
        {
            //alert('You did not enter a bounty.');
            displayAlertJS('This field cannot be left blank');
            //		$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> This field cannot be left blank.</p><div class="popupbuy" style="margin:10px auto 0 auto;">  <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
            //		$('.abshide').click();
            return false;
        }
	
        if (isNaN(bounty.trim())) 
        {
            displayAlertJS('Bounty is not a number');
            //		$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> That bounty is not a number.</p><div class="popupbuy" style="margin: 10px auto 0 auto;"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
            //		$('.abshide').click();
            //alert('That bounty is not a number.');
            return false;
        }
        if (bounty.trim() < 0) 
        {
            displayAlertJS("You need a positive number bounty.");
            /*$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> You need a positive number bounty.</p><div class="popupbuy" style="margin: 10px auto 0 auto;"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
                $('.abshide').click();*/
            //alert('You need a positive number bounty.');
            return false;
        }
	
        if( bounty.trim() < 5000) 
        {
		
            displayAlertJS("Minimum bounty amount is 5000.");
            /*$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> Minimum bounty amount is 5000.</p><div class="popupbuy" style="margin: 10px auto 0 auto;"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
                $('.abshide').click();*/
            //alert('You need a positive number bounty.');
            return false;
        }

        if(bounty.trim() > <?php echo $userCurrent->getCash(); ?>)
        {
            displayAlertJS("You don\'t have that much cash");
            /*$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> you don\'t have that much cash.</p><div class="popupbuy" style="margin: 10px auto 0 auto;"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
                $('.abshide').click();*/
            return false;
        }
	
        return true;
    }
    $(function()
    {

        $('#btnAddBounty').click(function(){ 
            $('#add_bounty_amount').show();
            focusObjId("bountyAmount");
        
        });
        $('#placeBounty').click(function(){ 
            $('#pBounty').click();
        });
        $('#btnAttack').click(function(){ 
		
            $('#attackUser').click();
        });
	
    });
    function closePpoup(){
        $('#fancybox-close').click();
    }
</script>


<div style="display: none;">
    <div id="inline1" style="">

    </div>
</div>
<a class="inlinecontent abshide" href="#inline1"> </a>

<div style="display: none;">
    <div id="inline2 style="">

</div>
</div>
<a class="inlinecontent abshide" href="#inline2"> </a>


<div id="profiletop">
    <img src="<?php echo $serverRoot . getPlayerTypeFromTypeImageURL($user->getType()); ?>" >
    <h2><?php echo $user->getName(); ?></h2>
    <h4>LVL <?php echo $user->getLevel(); ?> <?php echo getPlayerTypeFromTypeID($user->getType()); ?></h4>
</div>

<div id="tabs">

    <div class="container missionsmenu">
        <ul id="menu2">
            <li class="tab"><a href="#one">Stats</a></li>
            <li class="tab"><a href="#two">Comments</a></li>
        </ul>
    </div>	

<?php if (!$user->checkSameUsersInAgency($_SESSION['userID'], $userID) && $userID != $_SESSION['userID']) { ?>
        <div class="playeroptions">
            <a href="<?php echo $serverRoot . 'backend/attackplayer.php?page=externalProfile&userID=' . $userID . '&attack_type=normal'; ?> " id="btnAttack"  class="blackyellowbutton">Attack</a>        
            <a href="#" id="btnAddBounty"  class="blackyellowbutton">Add to hitlist</a>
        </div>
    <?php } ?>
    <br class="cr"/>
    <div id="add_bounty_amount" name='add_bounty_amount' style="display:none;">
        <h3 class="miniheader">Enter your bounty amount:</h3>
        <form action='<?php echo $serverRoot; ?>backend/addtobountylist.php' id="frm_add_bounty" onsubmit='return validateBountyAmount();' method='GET'>
            <fieldset class="top">
                <input type='hidden' name='targetID' value='<?php echo $userID; ?>'/>
                <input type='hidden' name='page' value='externalProfile'/>
                <input  type='text' maxlength="15" id="bountyAmount" name="bountyAmount" class="textbox">
                <input type="button" class="blackyellowbutton" value="Place Bounty" name="placeBounty" id="placeBounty">
                <p class="note">Note: There is a 10% fee for any bounty.</p>
                <input type="submit" class="abshide" value="Place Bounty" name="pBounty" id="pBounty">
            </fieldset>
        </form>
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
                    <td class="title">Fights Won</td>
                    <td><?php echo $user->getFightsWon(); ?></td>
                    <td class="title">Fights Lost</td>
                    <td><?php echo $user->getFightsLost(); ?></td>

                </tr>
                <tr>
                    <td class="title">Deaths</td>
                    <td><?php echo $user->getUserDeaths(); ?></td>
                    <td class="title">Kills</td>
                    <td><?php echo $user->getUserKills(); ?></td>
                </tr>
            </table>		
        </div>

        <!--<h1 class="profileheader">Cashflow</h1>
		<div class="statsbg">
        <p class="cashflowequation"><span class="incomecash">$27,000</span> - <span class="upkeepcash">$2,500</span> = <span class="finalcash">+ $24,500</span> Every hour.</p>
		</div>-->

        <div class="achievements">
            <h2>Achievements</h2>
        <?php
        $achievementss = User::getUserAchievementRanks($userID);
        foreach ($achievementss as $achievements) {
            ?>  
                <ul class="achievementlist">
                    <li><div class="achievementstatus">
                            <img src="img/glove.png">
                            <p class="ranknumberp"><?php echo $achievements['victor_level']; ?></p>
                            <div class="meter">
                                <span style="width: <?php echo ($achievements['victor_level'] / 6) * 100; ?>%" ></span>
                            </div>
                        </div>
                        <h3>Victor</h3>
                    </li>
                    <li><div class="achievementstatus">
                            <img src="img/glove.png">
                            <p class="ranknumberp"><?php echo $achievements['fall_guy_level']; ?></p>
                            <div class="meter">
                                <span style="width: <?php echo ($achievements['fall_guy_level'] / 6) * 100; ?>%" ></span>
                            </div>
                        </div>
                        <h3>Fall Guy</h3>
                    </li>
                    <li><div class="achievementstatus">
                            <img src="img/glove.png">
                            <p class="ranknumberp"><?php echo $achievements['marksman_level']; ?></p>
                            <div class="meter">
                                <span style="width: <?php echo ($achievements['marksman_level'] / 6) * 100; ?>%" ></span>
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
                                <span style="width: <?php echo ($achievements['master_level'] / 6) * 100; ?>%" ></span>
                            </div>
                        </div>
                        <h3>Master</h3>
                    </li>
                    <li><div class="achievementstatus">
                            <img src="img/glove.png">
                            <p class="ranknumberp"><?php echo $achievements['monster_level']; ?></p>
                            <div class="meter">
                                <span style="width: <?php echo ($achievements['monster_level'] / 6) * 100; ?>%" ></span>
                            </div>
                        </div>
                        <h3>Monster</h3>
                    </li>

                    <li><div class="achievementstatus">
                            <img src="img/glove.png">
                            <p class="ranknumberp"><?php echo $achievements['prodigy_level']; ?></p>
                            <div class="meter">
                                <span style="width: <?php echo ($achievements['prodigy_level'] / 6) * 100; ?>%" ></span>
                            </div>
                        </div>
                        <h3>Prodigy</h3>
                    </li>
    <?php
}
?>
            </ul>

        </div>
        <h1 class="profileheader">Weapons</h1>
        <div class="statsbg">
            <ul class="weaponslist">

<?php
$userItems = $user->getUsersItems($userID);
foreach ($userItems as $item) {
    if ($item->item_type == 1) {
        $imageUrl = $item->image_url;
        $name = $item->item_name;
        $quantity = $item->quantity;
        $atkBoost = $item->atk_boost;
        $defBoost = $item->def_boost;
        $upkeep = $item->item_upkeep;
        $id = $item->item_id;
        $price = $item->item_price;
        ?>
                        <li><a class="" href="<?php echo"#inline" . $id; ?>"  onclick=" displayAlert(<?php echo '\'' . $name . '\', \'' . $quantity . '\', \'' . $atkBoost . '\', \'' . $defBoost . '\', \'' . $upkeep . '\', \'' . $price . '\''; ?>);"><img src="<?php echo $item->image_url; ?> "> </a><p>x<?php echo $item->quantity; ?></p></li>
                        <?php
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
                        <li><a class="" href="<?php echo"#inline" . $id; ?>"  onclick=" displayAlert(<?php echo '\'' . $name . '\', \'' . $quantity . '\', \'' . $atkBoost . '\', \'' . $defBoost . '\', \'' . $upkeep . '\', \'' . $price . '\''; ?>);"><img src="<?php echo $item->image_url; ?> "> </a><p>x<?php echo $item->quantity; ?></p></li>
                        <?php
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
                        <li><a class="" href="<?php echo"#inline" . $id; ?>"  onclick=" displayAlert(<?php echo '\'' . $name . '\', \'' . $quantity . '\', \'' . $atkBoost . '\', \'' . $defBoost . '\', \'' . $upkeep . '\', \'' . $price . '\''; ?>);"><img src="<?php echo $item->image_url; ?> "> </a><p>x<?php echo $item->quantity; ?></p></li>
                        <?php
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
                    <li><a class="" href="<?php echo"#estate" . $id; ?>" onclick=" displayAlertRealEstate(<?php echo '\'' . $name . '\', \'' . $quantity . '\', \'' . $income . '\', \'' . $price . '\''; ?>);"><img src="<?php echo $item->estate_image; ?> "> </a><p> x<?php echo $item->estate_quantity; ?></p></li>
                    <?php
                }
                ?>	
            </ul>
        </div>

        </p>
    </div>
    <div id="two">
        <a class="inlinecontent abshide" href="#inline1"> </a>
        <form method="POST" action="backend/commentactions.php?action=post&screen=externalProfile"  onsubmit="return validateComment();"  >
            <textarea id ="coment" class="comment" name="content" maxlength = "250"></textarea>
            <input type="submit" class="commentbutton" value="Post Comment" />
            <input type="hidden" value="<?php echo $_SESSION['userID']; ?>" name="sender_id" >
            <input type="hidden" value="<?php echo $userID; ?>" name="receiver_id" >
        </form>
<?php
$myComments = Comments::getUserComments($userID);
foreach ($myComments as $comment) {
    $sender = User::getUser($comment->getSenderID());
    ?>
            <div class="commentpost">
                <a href="profile.php"><?php echo $sender->getName(); ?></a>

            <?php if ($comment->getSenderID() == $_SESSION['userID']) {
                ?>
                    <a class="commentdelete" href="backend/commentactions.php?action=delete&receiver_id=<?php echo $userID; ?>&screen=externalProfile&id=<?php echo $comment->getCommentID(); ?>">Delete</a>
        <?php
    }
    ?>
                <p><?php echo $comment->getContent(); ?></p>
            </div>
                <?php
            }
            ?>
    </div>


    <!--  Action buttons
    Give option to attack, add to bounty list -->
    <!--
    <form action='<?php $serverRoot ?>/addplayertobounty.php' method='POST'>
    <input type='hidden' name='targetID' value='<?php echo $userID; ?>'/>
    <input type='submit' value='Add to Bounty List'/>
    </form>-->

    <script>
        $(document).ready(function() {
            $(".inlinecontent").fancybox({
                'titlePosition'		: 'inside',
                'transitionIn'		: 'none',
                'transitionOut'		: 'none'
            });
	
            $('#bountyAmount').numeric({ negative : false , decimal : false});
            $( "#tabs" ).tabs({
                select: function(event, ui) {
                       //$(".playeroptions").hide();
                       $('#add_bounty_amount').hide();
                    }
                });
        });
	
        $.fx.off = !$.fx.off;
	
        touchMove = function(event) {
            // Prevent scrolling on this element
            event.preventDefault();
        };
        
    </script>
<?php
include_once 'footer.php';
?>