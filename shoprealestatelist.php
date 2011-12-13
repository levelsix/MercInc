<?php 
include_once 'topmenu.php';
include_once("classes/RealEstate.php");
include_once ('properties/constants.php');
include_once ('classes/Utils.php');
include_once ('classes/lvl6MemCache.php');

$shopREMinLevel = 4;
if(isset($_SESSION['levelError']))
{
    echo $_SESSION['levelError'];
    unset($_SESSION['levelError']);
    exit;
}
else if ($playerLevel<$shopREMinLevel)
{
    $tooLowLevelString = '<div class="natsays">
	<h2>Real Estate</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can buy properties."</p>
	<p>"Come back at LVL '.$shopREMinLevel.'"</p>
	<a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
    echo $tooLowLevelString;
    exit;
} 

 function calculatePrice($original_price, $last_purchase_price, $previouse_quantity, $quantity) {
        $total_amount = 0;
        for($i = $previouse_quantity; $i < ($previouse_quantity + $quantity); $i++){
            $price = $original_price + (INCREASE_REAL_ESTATE_PERCENTAGE  * $original_price) * $i;
            $total_amount += $price;
        }
        return $total_amount;
    }


function realEstateIsLocked($re, $playerLevel) {
	if ($re->getMinLevel() == ($playerLevel + 1)) {
		return true;
	}
	return false;
}

function displayLockedRealEstates($lockedRealEstates){
    $html = '';
    foreach($lockedRealEstates as $lRealEstate){
        $html .= '<div class="pieceequipment realestate lockedup">
		<div class="equipmentimage">
			<img src="img/biglock.png" />
		</div>
		<div class="equipmentinfo">
			<h4>'.$lRealEstate->getName().'</h4>
			<h5>Unlock at LVL '.$lRealEstate->getMinLevel().'</h5>
				<ul>
				<li class="inactive">
				<p class="inactivebutton">Buy</p>
				</li>
				<li class="inactive">
				<p class="inactivebutton">Sell</p>
				</li>
			</ul>
		</div>
	</div>';
    }
    echo $html;
}
$userRealEstateIDsToQuantity = User::getUsersRealEstateIDsToQuantity($_SESSION['userID']);
if(isset($_GET['error'])){
    $realEstateId = $_GET['itemID'];
	$previousQuanity = $_GET['previousQuanity'];
	$quantity = $_GET['quantity'];
    $estate_data = RealEstate::getRealEstate($realEstateId);
    $estate_name = $estate_data->getName();
    $originalPrice = $estate_data->getPrice();
    $realEstatePrice = $_GET['purchasePrice'];
	$estPrice = $realEstatePrice * $quantity;
	$diamondsArr = 	calculateNumDiamondsToCash($estPrice,$user->getCash(),$user->getLevel());
    $notifications = '';
    
    if($_GET['error'] == "true"){

    $notifications .='<div id="notification">
	<div class="needenergy">
		<h2>You don\'t have the cash!</h2>
		<p class="whatyouneed">You don\'t have enough cash to buy '.$quantity.'x '. $estate_name.'.</p>
		<div class="refillinfo">
							<h4>Recieve <span class="cost"> $'.$diamondsArr['cashForDiamonds'].'</span></h4>
							<p>For <strong>'.$diamondsArr['numDiamonds'].'</strong> Diamonds</p>	
						</div>';
   						 if($user->getDiamonds() >= $diamondsArr['numDiamonds'])
						{
							$notifications .='<div class="refillaccept">
									<a href="'.$serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&page=shoprealestatelist.php?&itemID='.$realEstateId.'&diamonds='.$diamondsArr['numDiamonds'].'&previousQuanity='.$previousQuanity.'&quantity='.$quantity.'&purchasePrice='.$realEstatePrice.'&originalPrice='.$originalPrice.'" class="greenbutton accepttrade">Accept Trade</a>
								</div>';
						}
						else
						{
							$pageURL = $serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&page=shoprealestatelist.php?&itemID='.$realEstateId.'&diamonds='.$diamondsArr['numDiamonds'].'&previousQuanity='.$previousQuanity.'&quantity='.$quantity.'&purchasePrice='.$realEstatePrice.'&originalPrice='.$originalPrice;
							
							$redirectURL =	$serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&from=google&page='.base64_encode('shoprealestatelist.php?itemID='.$realEstateId.'&diamonds='.$diamondsArr['numDiamonds'].'&previousQuanity='.$previousQuanity.'&quantity='.$quantity.'&purchasePrice='.$realEstatePrice.'&originalPrice='.$originalPrice);	
							
						if(strtolower($_SESSION['device_os']) != 'android'){
								$notifications .='<div class="refillaccept">
								<a href="javascript:" onclick="purchaseDiamonds(\''.$pageURL.'\');" class="greenbutton accepttrade">Accept Trade</a></div>';	
							}else{
								$notifications .='<div class="refillaccept">
								<a href="javascript:" onclick="purchaseDiamonds(\''.$redirectURL.'\');" class="greenbutton accepttrade">Accept Trade</a></div>';
							}		
						}
	$notifications .= '</div></div>';
	
	echo $notifications;
	
    } else if( ($_GET['error'] == 'false') && ($_GET['action'] == 'buy')){
        $previousQuanity = $_GET['previousQuanity'];
        $quantity = $_GET['quantity'];
        $newPrice = $originalPrice + (INCREASE_REAL_ESTATE_PERCENTAGE*$originalPrice)*($quantity+$previousQuanity);
?>
    <div id="notification">
	<div class="successmessage">
		<h2>Success!</h2>
                <form method="GET" action ="<?php echo $serverRoot; ?>backend/shoprealestateaction.php">
                    <input type="hidden" name="userCash" value="<?php echo $playerCash;?>" />
                    <input type="hidden" name="realEstateID" value="<?php echo $realEstateId;?>" />
                    <input type="hidden" name="purchasePrice" value="<?php echo $realEstatePrice;?>" />
	                <input type="hidden" name="originalPrice" value="<?php echo $originalPrice;?>" />
                    
                    <input type="hidden" name="actionToDo" value="buy" />
                    <input type="hidden" name="previousQuanity" value="<?php echo ($previousQuanity + $quantity); ?>" />
                    <input type="hidden" name="originalPrice" value="<?php echo $originalPrice; ?>" />
		<p class="whatyouneed">You bought <?php echo $quantity.' '.$estate_name; ?> for $<?php echo $realEstatePrice; ?>.</p>
		<a class="blackbutton successbuymore" id="toggleButton">Buy more</a>
		<div id="toggleSection" class="buymore" style="display:none">
		<p class="buymore">Buy
                    <select name="quantity" onchange="calculatePrice(<?php echo $originalPrice; ?>, <?php echo $realEstatePrice;?>, <?php echo ($previousQuanity + $quantity); ?>, this.value)">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="25">25</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="250">250</option>
			<option value="500">500</option>
			<option value="1000">1000</option>
                    </select>
                    more for <span class="m" id="m">$<?php echo $newPrice; ?></span><input type="submit" value="Buy" /></p>
                    </div>
                </form>
            </div>
	</div>

<?php
    }
    else if($_GET['action'] == "sell" && $_GET['error'] == "false" ) {
?>
<div id="notification">
	<div class="successmessage" style="display:visible; " >
    	 <h2>Success!</h2>              
    	 <p class="whatyouneed">You Sold <?php echo $estate_name; ?> for $<?php echo $realEstatePrice * 0.6; ?>.</p>		
 	</div>
 </div>
<?php 
}
}
else if (isset($_GET['status']) && $_GET['status']== "refill") {
	global $userRealEstateIDsToQuantity;
	global $user;
	$estateId = $_GET['itemID'];
    $estate_data = RealEstate::getRealEstate($estateId);
    $estate_name = $estate_data->getName();
    $originalPrice = $estate_data->getPrice();
    $quantity = 0;
	if ($userRealEstateIDsToQuantity && array_key_exists($estateId, $userRealEstateIDsToQuantity)) {
		$quantity = $userRealEstateIDsToQuantity[$estateId];
	}
	$estatePrice = $originalPrice + (INCREASE_REAL_ESTATE_PERCENTAGE*$originalPrice)*$quantity;	
	$previousQuanity = $_GET['previousQuanity'];
	$buy_quantity = $_GET['quantity'];
    $realEstatePrice = $_GET['purchasePrice'];
	$requestURL = $serverRoot.'backend/shoprealestateaction.php?actionToDo=buy&previousQuanity='.$previousQuanity.'&quantity='.$buy_quantity.'&userCash='.$user->getCash().'&purchasePrice='.$realEstatePrice.'&realEstateID='.$estateId.'&originalPrice='.$originalPrice;

 	
	echo '
		<div class="successmessage">
    		<p class="successfailuremessage"><span class="success line_height_3" >Congratuations! </span> You have successfully bought <span class="cost">$'.$_GET['cash'] .'</span> for <strong>'.$_GET['diamonds'].'</strong> Diamonds<a href="'.$requestURL.'" class="notification_domission">Buy Estate</a></p>
    	</div>';
}
$lockedRealEstates = array_values(RealEstate::getLockedRealEstate($playerLevel));
?>
<div class="equipment">
    <div class="realestatetop">
        <h2>Real Estate</h2>

        <p>Income: <span class="income">$<?php echo $PlayerIncome; ?></span> / Upkeep: <span class="upkeep">$<?php echo $PlayerUpkeep; ?></span></p>
    </div>
    <div class="cashflowinfo">
	<p>Cash flow: <strong>$<?php echo ($PlayerIncome - $PlayerUpkeep); ?> </strong> every 60 minutes</p>
    </div>
    <?php if(sizeof($lockedRealEstates) > 0){ ?>
    <p class="unlock2"><span>Unlock more Real Estate at LVL <?php echo $lockedRealEstates[0]->getMinLevel(); ?></span></p>
    <?php         
        }
    ?>
	
<?php

$realEstateIDsToRealEstates = RealEstate::getRealEstateIDsToRealEstatesVisibleInShop($playerLevel);
$num = count($realEstateIDsToRealEstates);

if ($num == 0) { 
	echo "No real estate available.";
} else {
    $html = '';
	$userRealEstateIDsToQuantity = User::getUsersRealEstateIDsToQuantity($_SESSION['userID']);
	foreach ($realEstateIDsToRealEstates as $realEstateID => $realEstate) {
		$quantity = 0;
		
		if ($userRealEstateIDsToQuantity && array_key_exists($realEstateID, $userRealEstateIDsToQuantity)) {
			$quantity = $userRealEstateIDsToQuantity[$realEstate->getID()];
		}
		$realEstatePrice = $realEstate->getPrice() + (INCREASE_REAL_ESTATE_PERCENTAGE*$realEstate->getPrice())*$quantity;
                
                $html .= '<div class="pieceequipment realestate">
                                <div class="equipmentimage">
                                        <img src="'.$realEstate->getImage().'" />
                                        <h5>Income: <strong>$'.$realEstate->getIncome().'</strong></h5>

                                </div>
                                <div class="equipmentinfo">
                                        <h4>'.$realEstate->getName().'</h4> '; 
                $html .= '<ul>
                          <li class="buy">$'.$realEstatePrice.'<br />
                            <a href="'.$serverRoot.'backend/shoprealestateaction.php?actionToDo=buy&previousQuanity='.$quantity.'&quantity=1&userCash='.$playerCash.'&purchasePrice='.$realEstatePrice.'&realEstateID='.$realEstateID.'&originalPrice='.$realEstate->getPrice().'">Buy</a>
                          </li>
                          <li class="sell">
                          	Owned: '.$quantity.'<br />';
                        if ($quantity >= 1 && !realEstateIsLocked($realEstate, $playerLevel)) {

                            $previousPrice = $realEstatePrice - ($realEstatePrice * .1);
                            
                            $html .= '<a href="'.$serverRoot.'backend/shoprealestateaction.php?
                                actionToDo=sell&
                                sellBasePrice='.$previousPrice.'&
                                previousQuanity='.$quantity.'&
                                userCash='.$playerCash.'&
                                purchasePrice='.($realEstate->getPrice() + (INCREASE_REAL_ESTATE_PERCENTAGE*$realEstate->getPrice()*($quantity-1))).'&
                                realEstateID='.$realEstateID.'">Sell</a>';
                        
                            
                        } else {
                                $html .= '<p class="inactivebutton">Sell</p>';
                        }
                               $html .= '</li>
                                        </ul>
                                </div>
                        </div>';
	}
        echo $html;
}

displayLockedRealEstates($lockedRealEstates);

?>
</div>
<script language="javascript">
    $(document).ready(function() {
         $('#toggleButton').click(function() {
              $('#toggleSection').toggle();
              return false;
         });


    });
    function calculatePrice(original_price, last_purchase_price, previouse_quantity, quantity) {
        var total_amount = 0;
        for(var i = parseInt(previouse_quantity); i < (parseInt(previouse_quantity) + parseInt(quantity)); i++){
            var price = parseInt(original_price) + (<?php echo INCREASE_REAL_ESTATE_PERCENTAGE; ?> * parseInt(original_price)) * i;
            total_amount += parseInt(price);
        }
        $('#m').html('$'+total_amount);
    }
 
      
</script>

<div style="display: none;">
	<div id="inline1" style=""></div>
</div>
<a class="inlinecontent abshide" href="#inline1"> </a>

<script type="text/javascript">
	function purchaseDiamond(diamondToAdd)
	{
		  <?php if(isset($_SESSION['legacy'])){ ?>
			  	window.location = "level6://develop.com?call=purchaseDiamondsForProductId:&param="+diamondToAdd;
                                //window.location = "level6://develop.com?call=purchaseDiamonds:&param="+diamondToAdd;
		  <?php } else { ?>
					 displayAlertJS("Please update your current version to make in-app purchases");	  
		  <?php } ?>
		//var path = '<?php echo  $serverRoot; ?>backend/blackmarket.php?update_diamond=true'+"&diamonds="+diamondToAdd;
			//window.location.href = path;
		//exit;
	}

   function purchaseDiamonds(pageURL)
    {
	  <?php
	  	global $user;
	  	$requiredDiamonds =  ($diamondsArr['numDiamonds'] - $user->getDiamonds());
	  	$diamonCost = getPurchasedNumDiamondsCost($requiredDiamonds);

	  ?>
	  
	  <?php if(strtolower($_SESSION['device_os']) != 'android'){ ?>	
	  
	  $('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough Diamonds </p><p>Buy <?php echo $diamonCost['numberOfDiamonds']; ?> diamonds for $<?php echo $diamonCost['costOfDiamonds']; ?> only</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="javascript:" onClick="purchase(\''+pageURL+'\');" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
	  
	   	<?php } else { ?>
	
			$('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough Diamonds </p><p>Buy <?php echo $diamonCost['numberOfDiamonds']; ?> diamonds for only $<?php echo $diamonCost['costOfDiamonds']; ?>!</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="<?php echo $serverRoot.'googlecheckout/checkout_redirect.php'; ?>?item_name=<?php echo $diamonCost['numberOfDiamonds'].' Diamonds '; ?>&item_description=<?php echo 'Buy' .$diamonCost['numberOfDiamonds'].' Diamonds for '.$diamonCost['costOfDiamonds']; ?>&diamonds_to_add=<?php echo $diamonCost['numberOfDiamonds']; ?>&price=<?php echo $diamonCost['costOfDiamonds']; ?>&udid=<?php echo $_SESSION['udid']; ?>&callback_url='+pageURL+'" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
	
	<?php } ?>		
	  
//    	<a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> 
    		
    	$('.abshide').click();
    }
    function closePopUp()
    {
    	$('#fancybox-close').click();
    }
function purchase(url)
    {
    	$('#fancybox-close').click();
    	$('#notification').html('');
		redirectURL = url;
    	purchaseDiamond("<?php echo $diamonCost['packageID']; ?>");
    }
    
      
</script>

<?php
    include_once 'footer.php';
?>