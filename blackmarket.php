<?php
include_once("topmenu.php");
include_once("classes/User.php");
include_once("classes/Utils.php");
include_once("properties/constants.php");
include_once("properties/playertypeproperties.php");
include_once 'googlecheckout/google_checkout.php';
$pageURL = $serverRoot.'backend/blackmarket.php?page=blackmarket'; 

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
$blackMarketMinLevel = 4;
if(isset($_SESSION['levelError']))
{
    //echo $_SESSION['levelError'];
    unset($_SESSION['levelError']);
    exit;
}
else if ($user->getLevel()<$blackMarketMinLevel)
{
    $tooLowLevelString = notificationHTML($blackMarketMinLevel, 'BlackMarket', 'view Black Market', $serverRoot);
    echo $tooLowLevelString;
    exit;
}

//if(isset($_SESSION['userID']))
	//$user = User::getUser($_SESSION['userID']);

	

$updatedParm = $fn->get('refill');
$updateSoldier = $fn->get('update_soldier');
$updateName = $fn->get('update_name');
$updateClass = $fn->get('update_class');
$updateCash = $fn->get('update_cash');
$blackMarketGoods = $fn->get('blackMarketGoods');
$stolenGoods = $fn->get('stolenGoods');
$addDiamonds = $fn->get('addDiamonds');
$error = $fn->get('error');
$result_html = '';
$diamondCountForCash = DIAMOND_COUNT_FOR_CASH;
//$diamondCashReceived = DIAMOND_COUNT_RECEIVED;
$diamondCountForRefillEnergy = DIAMOND_COUNT_FOR_REFILL_ENERGY;
$diamondCountForRefillHealth = DIAMOND_COUNT_FOR_REFILL_HEALTH;
$diamondCountForRefillStamina = DIAMOND_COUNT_FOR_REFILL_STAMINA;

$diamondCashReceived =  diamondToCash($user->getLevel(),$user->getCash());

/*if($user->getStamina() >50)
{
	$diamondCountForRefillStamina = $diamondCountForRefillStamina/2;
	//echo 'diamondCountForRefillStamina '.$diamondCountForRefillStamina.'<br>';
}

if($user->getHealth() >50)
{
	$diamondCountForRefillHealth = $diamondCountForRefillHealth/2;
	//echo 'diamondCountForRefillHealth '.$diamondCountForRefillHealth.'<br>';
	
}

if( $user->getEnergy()>50)
{
	$diamondCountForRefillEnergy = $diamondCountForRefillEnergy/2;
	//echo 'diamondCountForRefillEnergy '.$diamondCountForRefillEnergy.'<br>';
}*/



$diamondCountForSoldier = DIAMOND_COUNT_FOR_SOLDIER;
$diamondCountForClass = DIAMOND_COUNT_FOR_CLASS;
$diamondCountForStolenGoods = DIAMOND_COUNT_FOR_STOLEN_GOODS;
$diamondCountForBlackMarketGoods = DIAMOND_COUNT_FOR_MARKET_GOODS;
$diamondCountForName = DIAMOND_COUNT_FOR_NAME;

if($error == 'notEnoughDiamonds')
{
		$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="failure">Error!</span> Sorry you don\'t have enough diamonds.</p>
		</div>
	</div>';
	
}

if($error == 'updationError')
{
		$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="failure">Error!</span> Unknow error occur</p>
		</div>
	</div>';
	
}

if( isset($_GET['success']) && $_GET['success'] == 1 ){
	$addDiamonds = $_GET['amount'];
}

if($addDiamonds)
{
		$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> '.$addDiamonds.' Diamonds has successfully been added in your treasure.</p>
		</div>
	</div>';
	
}
if($updateCash )
{
		$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> $'.$updateCash.' has been successfully added in your cash.</p>
		</div>
	</div>';
	
}


if( $updatedParm != '')
{
		$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> Your '.$updatedParm.' has been successfully reset.</p>
		</div>
	</div>';
	
}
if( $updateSoldier != '')
{
		$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> Soldier has been recruited Successfully.</p>
		</div>
	</div>';
	
}
if( $updateName != '')
{
		$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> Your name is changed to '.$updateName.'.</p>
		</div>
	</div>';
	
}

if($updateClass != '')
{
	$updateClass = getPlayerTypeFromTypeID($updateClass);
	$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> Your class has been changed to '.$updateClass.'.</p>
		</div>
	</div>';
}
if($stolenGoods != '')
{
	$stolenGoods = itemType($fn->get('is_special'),$fn->get('is_common'));    
	$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> You got '.$fn->get('itemName').' '.$stolenGoods.' Stolen Good.</p>
		</div>
	</div>';
}

if($blackMarketGoods != '')
{
	$blackMarketGoods = itemType($fn->get('is_special'),$fn->get('is_common'));      
	   
	$result_html .= '<div id="notification">
		<div class="successmessage">
			<p class="successfailuremessage"><span class="success">Success!</span> You got '.$fn->get('itemName').' '.$blackMarketGoods.' Black Market Goods.</p>
		</div>
	</div>';
}
 
?>

<script language="javascript" type="text/javascript">

$(document).ready(function(){
	$('div[align="center"]').attr("align", "left");
	$('input[name="Checkout"]').attr("src", "img/buy_button.gif");
	$('input[name="Checkout"]').attr("style", "padding:0px !important; margin:0px !important;");
});

function purchaseDiamond(diamondToAdd)
{
	redirectURL = '<?php echo  $serverRoot.'backend/blackmarket.php?page=blackmarket'; ?>'; 
	<?php if(strtolower($deviceOS) != 'android'){ ?>
 		 <?php if(isset($_SESSION['legacy'])){ ?>
				window.location = "level6://develop.com?call=purchaseDiamondsForProductId:&param="+diamondToAdd;
                //window.location = "level6://develop.com?call=purchaseDiamonds:&param="+diamondToAdd;
		  <?php } else { ?>
					 displayAlertJS("Please update your current version to make in-app purchases");	  
		  <?php } ?>
	
	<?php } else{ ?>
	
	<?php } ?>

	//var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?update_diamond=true'+"&diamonds="+diamondToAdd;
	//window.location.href = path;
	//exit;
}
function checkDiamonds()
{
	
	if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForCash ?>)
	{
	   var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?update_cash='+<?php echo $diamondCashReceived?>+"&diamonds="+<?php echo $diamondCountForCash?>;
	   window.location.href = path;
	   exit;
	}
	else
	{
		var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> Your need '+<?php echo $diamondCountForCash - $user->getDiamonds(); ?>+' more Diamonds to get cash.</p></div>';
		$('#error_notification').html(notofication);
		$('#error_notification').show();
		$('#notification').hide();
		//alert('You need ' + <?php echo $diamondCountForCash - $user->getDiamonds(); ?> +" more Diamonds to get the cash");	
	}
}

function refillUserSettings()
{
	$("select option:selected").each(function () {
			//alert($(this).text());
			if($(this).text() == 'Health')
			{
				//alert('i am in health'+<?php echo $diamondCountForRefillHealth ?>);
				if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForRefillHealth ?>)
				{
				   if(<?php echo $user->getHealthMax(); ?> == <?php echo $user->getHealth(); ?>)
				   {	
						var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>health is already on full .</p></div>';
						$('#error_notification').html(notofication);
						$('#notification').hide();
						$('#error_notification').show();
						//alert('health is already on full');	
						return;
				   }
				   else
				   {
					var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?refill='+$(this).text()+"&diamonds="+<?php echo $diamondCountForRefillHealth?>;
					window.location.href = path;
					exit;  
				   }
				   
				}
				else
				{
					var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>You need<?php echo $diamondCountForRefillHealth - $user->getDiamonds(); ?> more Diamonds to refill your health.</p></div>';
								$('#error_notification').html(notofication);
								$('#notification').hide();
								$('#error_notification').show();
					//alert(+'You need ' + <?php echo $diamondCountForRefillHealth - $user->getDiamonds(); ?> +" more Diamonds to refill your health");	
				}
			}
			else if ($(this).text() == 'Stamina')
			{
				if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForRefillStamina ?>)
				{				
					if(<?php echo $user->getStaminaMax(); ?> == <?php echo $user->getStamina(); ?>)
					{	
						var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>Stamina is already on full .</p></div>';
						$('#error_notification').html(notofication);
						$('#notification').hide();
						$('#error_notification').show();
						//alert('Stamina is already on full');
						return;	
					}
				   else
				   {
					var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?refill='+$(this).text()+"&diamonds="+<?php echo $diamondCountForRefillStamina?>;
					window.location.href = path;
					exit;  
				   }
				}
				else
				{
					var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>You need <?php echo $diamondCountForRefillStamina - $user->getDiamonds(); ?> more Diamonds to refill your Stamina.</p></div>';
								$('#error_notification').html(notofication);
								$('#notification').hide();
								$('#error_notification').show();
					//alert(+'You need ' + <?php echo $diamondCountForRefillStamina - $user->getDiamonds(); ?> +" more Diamonds to refill your health");	
				}
					
			}
			else if($(this).text() == 'Energy')
			{
				//alert('i am in health'+<?php echo $diamondCountForRefillEnergy ?>);
				if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForRefillEnergy ?>)
				{			
				   if(<?php echo $user->getEnergyMax(); ?> == <?php echo $user->getEnergy();?>)
				   {	
						var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>Energy is already on full .</p></div>';
						$('#error_notification').html(notofication);
						$('#notification').hide();
						$('#error_notification').show();
						//alert('Energy is already on full');	
						return;	
				   }
				   else
				   {
					var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?refill='+$(this).text()+"&diamonds="+<?php echo $diamondCountForRefillEnergy?>;
					window.location.href = path;
					exit;  
				   }
				}
				else
				{
					var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span>You need<?php echo $diamondCountForRefillEnergy - $user->getDiamonds(); ?> more Diamonds to refill your Energy.</p></div>';
								$('#error_notification').html(notofication);
								$('#notification').hide();
								$('#error_notification').show();
					//alert(+'You need ' + <?php echo $diamondCountForRefillEnergy - $user->getDiamonds(); ?> +" more Diamonds to refill your health");	
				}
			}
			
	   });
}



function addSoldier()
{
	//alert("i am called");
	if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForSoldier ?>)
	{
		var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?add_soldier=1'+"&diamonds="+<?php echo $diamondCountForSoldier?>;
	   	window.location.href = path;
	   	exit;
	}
	else 
	{
		var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> Your need '+<?php echo $diamondCountForSoldier - $user->getDiamonds(); ?>+' more Diamonds to hire Soldier.</p></div>';
		$('#error_notification').html(notofication);
		$('#notification').hide();
		$('#error_notification').show();
		//alert('You need ' + <?php echo $diamondCountForSoldier - $user->getDiamonds(); ?> +" more Diamonds to hire Soldier");	
	}
	
	
}

function closePopUp()
{
	$('#fancybox-close').click();
}

function changeYourName()
{
    if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForName ?>)
	{
	   var path = '<?php echo  $serverRoot; ?>chooseplayername.php?page=blackmarket'+"&diamonds="+<?php echo $diamondCountForName?>;
	   window.location.href = path;
	   exit;
	}
	else
	{
		var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> Your need '+<?php echo $diamondCountForName - $user->getDiamonds(); ?>+' more Diamonds to change your name.</p></div>';
		$('#error_notification').html(notofication);
		$('#notification').hide();
		$('#error_notification').show();
		//alert('You need ' + <?php echo $diamondCountForName - $user->getDiamonds(); ?> +" more Diamonds to get the cash");	
	}
   
}

function chooseYouClass()
{
	if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForClass ?>)
	{
		var path = '<?php echo  $serverRoot; ?>chooseclasspage.php?page=blackmarket'+"&diamonds="+<?php echo $diamondCountForClass?>;
	   	window.location.href = path;
	   	exit;
	}
	else 
	{
		var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> Your need '+<?php echo $diamondCountForClass - $user->getDiamonds(); ?>+' more Diamonds to change Class.</p></div>';
		$('#error_notification').html(notofication);
		$('#notification').hide();
		$('#error_notification').show();
		//alert('You need ' + <?php echo $diamondCountForClass - $user->getDiamonds(); ?> +" more Diamonds to change Class");	
	}
}
/*
$diamondCountForStolenGoods 
$diamondCountForBlackMarketGoods 

*/

function refill()
 {
	 alert("tessssst");
 }

function tradeStolenGoods()
{
	if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForStolenGoods ?>)
	{
		var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?stolen_goods=1'+"&diamonds="+<?php echo $diamondCountForStolenGoods?>;
	   	window.location.href = path;
	   	exit;
	}
	else 
	{
		var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> Your need '+<?php echo $diamondCountForStolenGoods - $user->getDiamonds(); ?>+' more Diamonds to get Stolen Goods.</p></div>';
		$('#error_notification').html(notofication);
		$('#notification').hide();
		$('#error_notification').show();
//		alert('You need ' + <?php echo $diamondCountForStolenGoods - $user->getDiamonds(); ?> +" more Diamonds to get Black Market stolen goods");	
	}
}

function tradeBlackMarketGoods()
{
	if(<?php echo $user->getDiamonds(); ?> >=<?php echo $diamondCountForBlackMarketGoods ?>)
	{
		var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?blackmarket_goods=1'+"&diamonds="+<?php echo $diamondCountForBlackMarketGoods?>;
	   	window.location.href = path;
	   	exit;
	}
	else 
	{
		var  notofication = '<div class="successmessage"><p class="successfailuremessage"><span class="failure">Error!</span> Your need '+<?php echo $diamondCountForBlackMarketGoods - $user->getDiamonds(); ?>+' more Diamonds to get Black Market Goods.</p></div>';
		$('#error_notification').html(notofication);
		$('#notification').hide();
		$('#error_notification').show();
		//alert('You need ' + <?php echo  $diamondCountForBlackMarketGoods - $user->getDiamonds(); ?> +" more Diamonds to get Black Market Goods");	
	}
}


function resetCharacter()
{
	$('#inline1').html('<h5 class="popupweaponheader">Reset Character</h5><br/><p>Do you really want to rest the game? All progress will be lost</p><div class="popupbuy"  style="margin:10px auto 0 auto;"> <a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">OK</a></div>');
		//alert('You did not enter a deposit amount.');
	$('.abshide').click();
}

</script>


<h3 class="miniheader">
		The Black Market:<br />
		<em>Trade your diamonds for black market goods.</em>
	</h3>
	<div id="topcode">
		<h2 class="diamonds"><span>Diamonds <em><?php echo  $user->getDiamonds(); ?></em></span></h2>
	</div>
    <div id="error_notification" style="display:none;">
		
	</div>
    
    
	<?php echo  $result_html?>
    
    
    
	<div class="stolengoods">
    
    
		<div class="goodsinfo">
		<h4>Stolen Goods</h4>
		<p>Trade for <strong>50</strong> Diamonds</p>		
			<img src="img/stolengoods1.png" width="89">
		</div>
		<div class="goodsbuy">
			<a href="javascript:" onclick="tradeStolenGoods();" class="acceptgoods stolengoods">Accept Goods</a>
		</div>
		<ul>
			<li>54% chance for <span class="commonitem">Common</span></li>
			<li>35% chance for <span class="uncommonitem">Uncommon</li>
			<li>10% chance for <span class="rareitem">Rare</li>
			<li>1% chance for <span class="epicitem">Epic</li>
		</ul>
	</div>

<!--	
	<div class="stolengoods">
		<div class="goodsinfo">
		<h4>Blackmarket Goods</h4>
		<p>Trade for <strong>50</strong> Diamonds</p>		
			<img src="img/stolengoods1.png" width="89">
		</div>
		<div class="goodsbuy">
			<a href="javascript:" onclick="tradeBlackMarketGoods();" class="acceptgoods stolengoods">Accept Goods</a>
		</div>
		<ul>
			<li>54% chance for <span class="commonitem">Common</span></li>
			<li>35% chance for <span class="uncommonitem">Uncommon</li>
			<li>10% chance for <span class="rareitem">Rare</li>
			<li>1% chance for <span class="epicitem">Epic</li>
		</ul>
	</div>
-->		
        
        
	<div class="stolengoods">
	<div class="goodsinfo">
		<h4>Recieve <span class="moneygoods">$<?php echo $diamondCashReceived ?></span></h4>
		<p>Accept for <strong><?php echo  $diamondCountForCash?></strong> Diamonds</p>	
	</div>
	<div class="goodsbuy">
		<a href="#" onClick="checkDiamonds();" class="acceptgoods">Accept Goods</a>
	</div>	
	</div>
	
	<div class="stolengoods">
	<div class="goodsinfo">
		<h4>Refill 
		<form>
            <select id="refill" onchange="refill()">
              <option value="Energy">Energy</option>
              <option value="Stamina">Stamina</option>
              <option value="Health">Health</option>
            </select>
        </form>
		</h4>
		<p id="refillText">Trade for <strong><?php echo $diamondCountForRefillEnergy ?></strong> Diamonds</p>	
	</div>
	<div class="goodsbuy">
		<a href="#" onClick="refillUserSettings();" class="acceptgoods">Accept Goods</a>
	</div>	
	</div>

    <div style="display: none;">
		<div id="inline1" style=""></div>
	</div>
	<a class="inlinecontent abshide" href="#inline1"> </a>

	<div class="stolengoods">
	<div class="goodsinfo">
		<h4>Add 1 Solider</h4>
		<p>Trade for <strong>20</strong> Diamonds</p>	
	</div>
	<div class="goodsbuy">
		<a href="#"  onClick="addSoldier();" class="acceptgoods">Accept Goods</a>
	</div>	
	</div>
	
	<div class="stolengoods">
	<div class="goodsinfo">
		<h4>Change your Name</h4>
		<p>Trade for <strong>30</strong> Diamonds</p>	
	</div>
	<div class="goodsbuy">
		<a href="#" onClick="changeYourName();" class="acceptgoods">Accept Goods</a>
	</div>	
	</div>
	
	<div class="stolengoods">
	<div class="goodsinfo">
		<h4>Change your Class</h4>
		<p>Trade for <strong>50</strong> Diamonds</p>	
	</div>
	<div class="goodsbuy">
		<a href="#" onClick="chooseYouClass();" class="acceptgoods">Accept Goods</a>
	</div>	
	</div>
	
	<!--<div class="stolengoods">
	<div class="goodsinfo">
		<h4>Reset your Character</h4>
		<p>For Free</p>	
	</div>

	<div class="goodsbuy">
		<a href="javascript:" class="acceptgoods">Accept Goods</a>
	</div>	
	</div>-->
	
	<h3 class="miniheader">Get your Diamonds</h3>
	<div class="stolengoods">
	<div class="goodsinfo">
		<h5><?php echo PACKAGE1_DIAMONDS_COUNT ?> Diamonds</h5>
		<p class="costamount">$<?php echo PACKAGE1_DIAMONDS_COST ?></p>
	</div>
	<div class="goodsbuy">
		<?php if(strtolower($deviceOS) == 'android'){ 
		echo generate_checkout_btn(PACKAGE1_DIAMONDS_COUNT." Diamonds", "Get ".PACKAGE1_DIAMONDS_COUNT." Diamonds for $".PACKAGE1_DIAMONDS_COST, 1, PACKAGE1_DIAMONDS_COST, PACKAGE1_DIAMONDS_COUNT,$pageURL );
		} else {?>
        <a href="javascript:" onclick='purchaseDiamond("<?php echo PACKAGE1_DIAMONDS_ID; ?>")' class="acceptgoods">Buy Diamonds</a>
        <?php } ?>
	</div>	
	</div>
	
	<div class="stolengoods">
	<div class="goodsinfo">
    	<h5><?php echo PACKAGE2_DIAMONDS_COUNT ?> Diamonds</h5>
		<p class="costamount">$<?php echo PACKAGE2_DIAMONDS_COST ?></p>
	</div>
	<div class="goodsbuy">
       <?php if(strtolower($deviceOS) == 'android'){ 
	   			 echo generate_checkout_btn(PACKAGE2_DIAMONDS_COUNT." Diamonds", "Get ".PACKAGE2_DIAMONDS_COUNT." Diamonds for $".PACKAGE2_DIAMONDS_COST, 1, PACKAGE2_DIAMONDS_COST, PACKAGE2_DIAMONDS_COUNT,$pageURL ); 
	   } else { ?>
     	<a href="javascript:" onclick='purchaseDiamond("<?php echo PACKAGE2_DIAMONDS_ID; ?>")' class="acceptgoods">Buy Diamonds</a> 
    		<?php } ?>    
	</div>
    	
	</div>
	
	
	<div class="stolengoods">
	<div class="goodsinfo">
		<h5><?php echo PACKAGE3_DIAMONDS_COUNT ?> Diamonds</h5>
		<p class="costamount">$<?php echo PACKAGE3_DIAMONDS_COST ?></p>
	</div>
	<div class="goodsbuy">
        <?php if(strtolower($deviceOS) == 'android'){ 
		 echo generate_checkout_btn(PACKAGE3_DIAMONDS_COUNT." Diamonds", "Get ".PACKAGE3_DIAMONDS_COUNT." Diamonds for $".PACKAGE3_DIAMONDS_COST, 1, PACKAGE3_DIAMONDS_COST, PACKAGE3_DIAMONDS_COUNT,$pageURL );
		 } else { ?>
			<a href="javascript:" onclick='purchaseDiamond("<?php echo PACKAGE3_DIAMONDS_ID; ?>")' class="acceptgoods">Buy Diamonds</a>
        <?php } ?>  
	</div>	
	</div>
	
	<div class="stolengoods">
	<div class="goodsinfo">
		<h5><?php echo PACKAGE4_DIAMONDS_COUNT ?> Diamonds</h5>
		<p class="costamount">$<?php echo PACKAGE4_DIAMONDS_COST; ?></p>
	</div>
	<div class="goodsbuy">
    	 <?php if(strtolower($deviceOS) == 'android'){ 	
        	echo generate_checkout_btn(PACKAGE4_DIAMONDS_COUNT." Diamonds", "Get ".PACKAGE4_DIAMONDS_COUNT." Diamonds for $".PACKAGE4_DIAMONDS_COST, 1, PACKAGE4_DIAMONDS_COST, PACKAGE4_DIAMONDS_COUNT,$pageURL ); 
		} else { ?> 
        	<a href="javascript:" onclick='purchaseDiamond("<?php echo PACKAGE4_DIAMONDS_ID; ?>")' class="acceptgoods">Buy Diamonds</a>
        <?php } ?>  
	</div>	
	</div>
	
	<div class="stolengoods">
	<div class="goodsinfo">
		<h5><?php echo PACKAGE5_DIAMONDS_COUNT ?> Diamonds</h5>
		<p class="costamount">$<?php echo PACKAGE5_DIAMONDS_COST ?></p>
	</div>
	<div class="goodsbuy">
	<?php if(strtolower($deviceOS) == 'android'){ 	
        echo generate_checkout_btn(PACKAGE5_DIAMONDS_COUNT." Diamonds", "Get ".PACKAGE5_DIAMONDS_COUNT." Diamonds for $".PACKAGE5_DIAMONDS_COST, 1, PACKAGE5_DIAMONDS_COST, PACKAGE5_DIAMONDS_COUNT,$pageURL ); 
		} else { ?> 
			<a href="javascript:" onclick='purchaseDiamond("<?php echo PACKAGE5_DIAMONDS_ID; ?>")' class="acceptgoods">Buy Diamonds</a>
        <?php } ?>   
	</div>	
	</div>
    
    <script>
    $("select").change(function () {
	  var str = "Trade for <strong>";
          $("select option:selected").each(function () {
                if( $(this).text() == "Energy")
				str += "<?php echo $diamondCountForRefillEnergy ?></strong> Diamonds";
				if( $(this).text() == "Health")
					str += "<?php echo $diamondCountForRefillHealth ?></strong> Diamonds";
				if( $(this).text() == "Stamina")
					str += "<?php echo $diamondCountForRefillStamina ?></strong> Diamonds";	
				  });
			
			$("#refillText").html(str);
          
        });
        
</script>
<?php
include_once 'footer.php';
?>