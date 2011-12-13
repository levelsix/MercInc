<?php
include_once("topmenu.php");
include_once("properties/itemtypeproperties.php");
include_once 'classes/Utils.php';
include_once 'properties/constants.php';
?>
<script type="text/javascript">
    function calculatePrice(price,quantity) {
        $('#m').html('$'+quantity*price)	
    }   
</script>
<script language="javascript">
    $(document).ready(function() {
        $('#toggleButton').click(function() {
            $('#toggleSection').toggle();
            return false;
        });
    });
</script>
<?php

$shopItemMinLevel = 4;
if(isset($_SESSION['levelError']))
{
    echo $_SESSION['levelError'];
    unset($_SESSION['levelError']);
    exit;
}
else if ($playerLevel<$shopItemMinLevel)
{
    $tooLowLevelString = '<div class="natsays">
	<h2>Equipment</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can buy equipment."</p>
	<p>"Come back at LVL '.$shopItemMinLevel.'"</p>
	<a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
    echo $tooLowLevelString;    
    exit;
} 
$itemIDsToItems = Item::getItemIDsToItemsVisibleInShop($playerLevel);
//$user = User::getUser($_SESSION['userID']);
//$num = count($itemIDsToItems);
if (isset($_POST['itemTab'])) {
    $item_type_selected = $_POST['itemTab'];
} else {
    $item_type_selected = 1;
}
?>
<div id="notification">
<?php

if (isset($_GET['itemid'])) {
    $item_id = ($_GET['itemid']);
    $item = Item::getItem($item_id);
    if (isset($_GET['quantity_bought'])) {
        $quantity_bought = $_GET['quantity_bought'];
    } else {
        $quantity_bought = 1;
    }
    if ($_GET['status'] == "success" && $_GET['action'] != "sell") { /* Refactoring required here to make action = buy instead of != sell */
        ?>
            <div class="successmessage" style="display:visible; " >
                <h2>Success!</h2>
                <form method="GET" action ="<?php echo $serverRoot; ?>backend/shopitemaction.php">
                    <p class="whatyouneed">You bought <?php echo $quantity_bought; ?> <?php echo $item->getName(); ?> for $<?php echo $item->getPrice() * $quantity_bought; ?>.</p>
                    <a class="blackbutton successbuymore" id="toggleButton">Buy more</a>
                    <div id="toggleSection" class="buymore" style="display:none">
                        <p class="buymore">Buy
                            <select name="request_quantity" onchange="calculatePrice(<?php echo $item->getPrice(); ?>,this.value)">
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

                            <input type="hidden" name ="actionToDo" value="buy"></input>
                            <input type="hidden" name ="itemID" value="<?php echo $item_id; ?>"></input>
                            <input type="hidden" name ="storePrice" value="<?php echo $item->getPrice(); ?>"></input>
                            more for <span id="m"><?php echo '$' . $item->getPrice(); ?></span><input type="submit" value="Buy"></p>

                    </div>				
                </form>
            </div>
        <?php
    } else if ($_GET['status'] == "needmoney") {
    	$equipmentCash = $_GET['requiredcash'];
    	$quantity = $_GET['requestquantity'];
		if(!$quantity){
			$quantity =1;
		}
    	$requiredCash = $equipmentCash - $user->getCash();
    	$diamondsArr = calculateNumDiamondsToCash($requiredCash,$user->getCash(),$user->getLevel());
        $notifications = 
    			'<div class="needenergy">
					<h2>You don\'t have the cash!</h2> 
					<p class="whatyouneed">You dont have enough cash to buy '.$quantity.'x '. $item->getName().'</p>
                        <p class="whatyouneed">You need additional $'.$requiredCash.' to buy these equipments. </p>
                        <div class="refillinfo">
							<h4>Recieve <span class="cost"> $'.$diamondsArr['cashForDiamonds'].'</span></h4>
							<p>For <strong>'.$diamondsArr['numDiamonds'].'</strong> Diamonds</p>	
						</div>';
   						 if($user->getDiamonds() >= $diamondsArr['numDiamonds'])
						{
							$notifications .='<div class="refillaccept">
									<a href="'.$serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&page=equipment.php?&itemID='.$item->getID().'&diamonds='.$diamondsArr['numDiamonds'].'&itemType='.$item->getType().'&request_quantity='.$quantity.'" class="greenbutton accepttrade">Accept Trade</a>
								</div>';
						}
						else
						{
						$pageURL =$serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&page=equipment.php?&itemID='.$item->getID().'&diamonds='.$diamondsArr['numDiamonds'].'&itemType='.$item->getType().'&request_quantity='.$quantity;
						
						
						$redirectURL =	$serverRoot.'backend/blackmarket.php?update_cash='.$diamondsArr['cashForDiamonds'].'&from=google&page='.base64_encode('equipment.php?&itemID='.$item->getID().'&diamonds='.$diamondsArr['numDiamonds'].'&itemType='.$item->getType().'&request_quantity='.$quantity);
						
							if(strtolower($_SESSION['device_os']) != 'android'){
								$notifications .='<div class="refillaccept">
								<a href="javascript:" onclick="purchaseDiamonds(\''.$pageURL.'\');" class="greenbutton accepttrade">Accept Trade</a></div>';	
							}else{
								$notifications .='<div class="refillaccept">
								<a href="javascript:" onclick="purchaseDiamonds(\''.$redirectURL.'\');" class="greenbutton accepttrade">Accept Trade</a></div>';
							}		
						}
						$notifications .= '</div>';
		echo $notifications;
		
		unset($_SESSION['cashForDiamonds']);
    }
    else if ($_GET['status'] == "success" && $_GET['action'] == "sell") {
    	?>
    	 <div class="successmessage" style="display:visible; " >
             <h2>Success!</h2>              
             <p class="whatyouneed">You Sold <?php echo $item->getName(); ?> for $<?php echo $item->getPrice() * 0.6; ?>.</p>		
         </div>
<?php           
    } else if ($_GET['status'] == "refill") {
    	global $serverRoot;
    	$itemID  = $_GET['itemid']; 
    	$quantity = $_GET['requestquantity']; 
		if(!$quantity){
			$quantity =1;
		}			
    	$item_details = Item::getItem($itemID);
    	$storePrice = $item_details->getPrice();
    	$itemType = $item_details->getType();
    	$requestURL = $serverRoot.'backend/shopitemaction.php?actionToDo=buy&storePrice='.$storePrice.'&itemID='.$itemID.'&request_quantity='.$quantity.'&toReturn='.$itemType.'';
		echo '
		<div class="successmessage">
    		<p class="successfailuremessage"><span class="success line_height_3" >Congratuations! </span> You have successfully got <span class="cost">$'.$_GET['cash'] .'</span> for <strong>'.$_GET['diamonds'].'</strong> Diamonds<a href="'.$requestURL.'" class="notification_domission">Buy Item</a></p>
    	</div>';
    }
?>	
        <div class="needenergy" style="display:none;" >
            <h2>You need Diamonds!</h2>
            <p class="whatyouneed">Visit the black market to find diamonds.</p>
            <div class="refillinfo">
                <h4>Get Diamonds</h4>
            </div>
            <div class="refillaccept">
                <a href="#" class="greenbutton accepttrade">Go Now &#187;</a>
            </div>
        </div>
    <?php
}
?>
</div>
<div id="content" class="equipment">

    <div id="tabs">

        <div class="container missionsmenu">
            <ul id="menu2">
                <li class="tab"><a href="#one">Weapons</a></li>
                <li class="tab"><a href="#two">Protection</a></li>
                <li class="tab"><a href="#three">Vehicles</a></li>
            </ul>
        </div>	
        <h3>Current Equipment Upkeep: <em><?php echo '$' . $user->getUpkeep(); ?></em></h3>
        <p>During battles, your army will automatically use your best equipment. Each solider will use 1 weapon, 1 protection unit, and 1 vehicle.</p>

        <div id="one">
    <?php
    $item_type_selected = 1;
    // echo $item_type_selected; 
    $userItemIDsToQuantity = User::getUsersItemsIDsToQuantity($_SESSION['userID']);
    $lockedItems = Item::get_locked_items_visible_in_shop($playerLevel, $item_type_selected);
    foreach ($lockedItems as $itemID => $item) {
     ?>
     <p class="unlock2"><span>Unlock more Weapons at LVL <?php echo $item->getMinLevel(); ?></span></p>
            <?php
                break;
            }
            foreach ($itemIDsToItems as $itemID => $item) {
                if ($item->getType() == $item_type_selected) {
                	$itemName = $item->getName();
                    $itemType = getItemTypeFromTypeID($item->getType());
                    $itemPrice = $item->getPrice();
                    $itemUpkeep = $item->getUpkeep();
                    $itemImage = $item->getImage();
                    $attackBoost = $item->getAtkBoost();
                    $defBoost = $item->getDefBoost();
                    $isBuyable = $item->getIsBuyable();
                    $quantity = 0;
                    if ($userItemIDsToQuantity && array_key_exists($itemID, $userItemIDsToQuantity)) {
                        $quantity = $userItemIDsToQuantity[$item->getID()];
                    }
                    if(($isBuyable == 0 && $quantity > 0) || $isBuyable == 1) {
                    ?>
                    <div class="pieceequipment">
                        <div class="equipmentimage">
                            <img src="<?php echo $itemImage; ?>" />
                        </div>
                        <div class="equipmentinfo">
                            <h4><?php echo $itemName; ?></h4>
                            <p><span class="attack"><?php echo $attackBoost; ?></span>
                                &nbsp;/&nbsp; 
                                <span class="defense"><?php echo $defBoost; ?></span>
                            </p>
                                 <?php if ($itemUpkeep > 0) { ?>
                                <p class="upkeep"><?php echo '$' . $itemUpkeep . '  Upkeep'; ?></p>
                                 <?php } ?>
                            <ul>
                                <li class="buy">
                                     <?php echo '$' . $itemPrice; ?><br />
                                     <?php if($isBuyable == 1) { 
                                     ?>                                    
                                    	<a href="<?php echo $serverRoot; ?>backend/shopitemaction.php?actionToDo=buy&storePrice=<?php echo $itemPrice; ?>&itemID=<?php echo $itemID; ?>&request_quantity=1&toReturn=one">Buy</a>
                                    <?php
									 }
									 else {
									 ?>
									 <p class="inactivebutton">Buy</p>
									 <?php
									 } 
									 ?>
                                </li>
                                <li class="sell">
                                    Owned: <?php echo $quantity; ?> <br />
                                        <?php
                                        if ($quantity > 0) {
                                        ?>
                                        <a href="<?php echo $serverRoot; ?>backend/shopitemaction.php?actionToDo=sell&storePrice=<?php echo $itemPrice; ?>&itemID=<?php echo $itemID; ?>&oldUserQuantity=<?php echo $quantity; ?>&action=sell&toReturn=one">Sell</a>
                                            <?php
                                        } else {
                                            ?>
                                            <p class="inactivebutton">Sell</p>
                                            <?php
                                        }
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
           			<?php
                    }
                }
            }

            foreach ($lockedItems as $itemID => $item) {
            	if ($item->getType() == $item_type_selected) {
                	$itemName = $item->getName();
                    $itemType = getItemTypeFromTypeID($item->getType());
                    $itemPrice = $item->getPrice();
                    $itemUpkeep = $item->getUpkeep();
                    $itemImage = $item->getImage();
                    $isBuyable = $item->getIsBuyable();
             ?>
                    <div class="pieceequipment realestate lockedup">
                        <div class="equipmentimage">
                            <img src="img/biglock.png" />

                        </div>
                        <div class="equipmentinfo">
                            <h4><?php echo $itemName; ?></h4>
                            <h5>Unlock at LVL <?php echo $item->getMinLevel(); ?></h5>
                            <ul>
                                <li class="inactive">
                                    <p class="inactivebutton">Buy</p>
                                </li>
                                <li class="inactive">
                                    <p class="inactivebutton">Sell</p>
                                </li>
                            </ul>
                        </div>
                    </div>
        	<?php
            	}
            }
			?>
        </div>
        <div id="two">
            <?php
                $item_type_selected = 2;
                $userItemIDsToQuantity = User::getUsersItemsIDsToQuantity($_SESSION['userID']);
                $lockedItems = Item::get_locked_items_visible_in_shop($playerLevel, $item_type_selected);
                 foreach ($lockedItems as $itemID => $item) {
                    ?>
                    <p class="unlock2"><span>Unlock more Protection at LVL <?php echo $item->getMinLevel(); ?></span></p>
                    <?php
                    break;
               }
             foreach ($itemIDsToItems as $itemID => $item) {
                if ($item->getType() == $item_type_selected) {
                    $itemName = $item->getName();
                    $itemType = getItemTypeFromTypeID($item->getType());
                    $itemPrice = $item->getPrice();
        
                    $itemUpkeep = $item->getUpkeep();
                    $itemImage = $item->getImage();
                    $attackBoost = $item->getAtkBoost();
                    $defBoost = $item->getDefBoost();
                    $quantity = 0;
                    $isBuyable = $item->getIsBuyable();

                    if ($userItemIDsToQuantity && array_key_exists($itemID, $userItemIDsToQuantity)) {
                        $quantity = $userItemIDsToQuantity[$item->getID()];
                    }
                    if(($isBuyable == 0 && $quantity > 0) || $isBuyable == 1) {
                    ?>
                    <div class="pieceequipment">
                        <div class="equipmentimage">
                            <img src="<?php echo $itemImage; ?>" />
                        </div>
                        <div class="equipmentinfo">
                            <h4><?php echo $itemName; ?></h4>
                            <p><span class="attack"><?php echo $attackBoost; ?></span>
                                &nbsp;/&nbsp; 
                                <span class="defense"><?php echo $defBoost; ?></span>
                            </p>
                                <?php if ($itemUpkeep > 0) { ?>
                                   <p class="upkeep"><?php echo '$' . $itemUpkeep . '  Upkeep'; ?></p>
                                <?php } ?>
                            <ul>
                                <li class="buy">
                                <?php echo '$' . $itemPrice; ?><br />
                                    <?php if($isBuyable == 1) { 
                                     ?>                                    
                                    	<a href="<?php echo $serverRoot; ?>backend/shopitemaction.php?actionToDo=buy&storePrice=<?php echo $itemPrice; ?>&itemID=<?php echo $itemID; ?>&request_quantity=1&toReturn=two">Buy</a>
                                    <?php
									 }
									 else {
									 ?>
									 <p class="inactivebutton">Buy</p>
									 <?php
									 } 
									 ?>
                                </li>
                                <li class="sell">
                                    Owned: <?php echo $quantity; ?> <br />
                                    <?php
                                    if ($quantity > 0) {
                                        ?>
                                        <a href="<?php echo $serverRoot; ?>backend/shopitemaction.php?actionToDo=sell&storePrice=<?php echo $itemPrice; ?>&itemID=<?php echo $itemID; ?>&oldUserQuantity=<?php echo $quantity; ?>&action=sell&toReturn=two">Sell</a>
                                        <?php
                                    } else {
                                        ?>
                                        <p class="inactivebutton">Sell</p>
                                    <?php
                                    }
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                        <?php
                        
                    }
                }
            }

             foreach ($lockedItems as $itemID => $item) {
                    if ($item->getType() == $item_type_selected) {
                        $itemName = $item->getName();
                        $itemType = getItemTypeFromTypeID($item->getType());
                        $itemPrice = $item->getPrice();
                        $itemUpkeep = $item->getUpkeep();
                        $itemImage = $item->getImage();
                        ?>
                    <div class="pieceequipment realestate lockedup">
                        <div class="equipmentimage">
                            <img src="img/biglock.png" />

                        </div>
                        <div class="equipmentinfo">
                            <h4><?php echo $itemName; ?></h4>
                            <h5>Unlock at LVL <?php echo $item->getMinLevel(); ?></h5>
                            <ul>
                                <li class="inactive">
                                    <p class="inactivebutton">Buy</p>
                                </li>
                                <li class="inactive">
                                    <p class="inactivebutton">Sell</p>
                                </li>
                            </ul>
                        </div>
                    </div>

            <?php
                }
            }
            ?>
        </div>
        <div id="three">
            <?php
            $item_type_selected = 3;
            // echo $item_type_selected; 
            $userItemIDsToQuantity = User::getUsersItemsIDsToQuantity($_SESSION['userID']);
            $lockedItems = Item::get_locked_items_visible_in_shop($playerLevel, $item_type_selected);
            foreach ($lockedItems as $itemID => $item) {
                ?>
                <p class="unlock2"><span>Unlock more Vehicles at LVL <?php echo $item->getMinLevel(); ?></span></p>
                <?php
                break;
            }
            foreach ($itemIDsToItems as $itemID => $item) {
                if ($item->getType() == $item_type_selected) {

                    $itemName = $item->getName();
                    $itemType = getItemTypeFromTypeID($item->getType());
                    $itemPrice = $item->getPrice();
                    $itemUpkeep = $item->getUpkeep();
                    $itemImage = $item->getImage();
                    $attackBoost = $item->getAtkBoost();
                    $defBoost = $item->getDefBoost();
                    $quantity = 0;
                    $isBuyable = $item->getIsBuyable();

                    if ($userItemIDsToQuantity && array_key_exists($itemID, $userItemIDsToQuantity)) {
                        $quantity = $userItemIDsToQuantity[$item->getID()];
                    }
                    if(($isBuyable == 0 && $quantity > 0) || $isBuyable == 1) {
                    ?>
                    <div class="pieceequipment">
                        <div class="equipmentimage">
                            <img src="<?php echo $itemImage; ?>" />
                        </div>
                        <div class="equipmentinfo">
                            <h4><?php echo $itemName; ?></h4>
                            <p><span class="attack"><?php echo $attackBoost; ?></span>
                                &nbsp;/&nbsp; 
                                <span class="defense"><?php echo $defBoost; ?></span>
                            </p>
                                <?php if ($itemUpkeep > 0) { ?>
                                <p class="upkeep"><?php echo '$' . $itemUpkeep . '  Upkeep'; ?></p>
                                <?php } ?>
                            <ul>
                                <li class="buy">
                                	<?php echo '$' . $itemPrice; ?><br />
                                	    <?php if($isBuyable == 1) { 
                                	     ?>                                    
                                	    	 <a href="<?php echo $serverRoot; ?>backend/shopitemaction.php?actionToDo=buy&storePrice=<?php echo $itemPrice; ?>&itemID=<?php echo $itemID; ?>&request_quantity=1&toReturn=three">Buy</a>
                                	    <?php
										 }
										 else {
										 ?>
											 <p class="inactivebutton">Buy</p>
										 <?php
										 } 
										 ?>
                                </li>
                                <li class="sell">
                                    Owned: <?php echo $quantity; ?> <br />
                                    <?php
                                    if ($quantity > 0) {
                                        ?>
                                        <a href="<?php echo $serverRoot; ?>backend/shopitemaction.php?actionToDo=sell&storePrice=<?php echo $itemPrice; ?>&itemID=<?php echo $itemID; ?>&oldUserQuantity=<?php echo $quantity; ?>&action=sell&toReturn=three">Sell</a>
                                            <?php
                                        } else {
                                            ?>
                                        <p class="inactivebutton">Sell</p>
                                        <?php
                                    }
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
<?php
                    }
                }
            }
                            /**
                             * Dislpalying the locked items 
                             */
			foreach ($lockedItems as $itemID => $item) {
            	if ($item->getType() == $item_type_selected) {
                	$itemName = $item->getName();
                    $itemType = getItemTypeFromTypeID($item->getType());
                    $itemPrice = $item->getPrice();
                    $itemUpkeep = $item->getUpkeep();
                    $itemImage = $item->getImage();
?>
                    <div class="pieceequipment realestate lockedup">
                        <div class="equipmentimage">
                            <img src="img/biglock.png" />

                        </div>
                        <div class="equipmentinfo">
                            <h4><?php echo $itemName; ?></h4>
                            <h5>Unlock at LVL <?php echo $item->getMinLevel(); ?></h5>
                            <ul>
                                <li class="inactive">
                                    <p class="inactivebutton">Buy</p>
                                </li>
                                <li class="inactive">
                                    <p class="inactivebutton">Sell</p>
                                </li>
                            </ul>
                        </div>
                    </div>
<?php
            }
        }
?>
        </div>
    </div>
</div>
<div style="display: none;">
		<div id="inline1" style=""></div>
	</div>
	<a class="inlinecontent abshide" href="#inline1"> </a>
<script type="text/javascript">
    <!--
    $( "#tabs" ).tabs({
        select: function(event, ui) {
            $('#notification').html('');
        }
    });
    //-->
</script>
<script language="javascript" type="text/javascript">

function purchaseDiamond(diamondToAdd)
{
	<?php if(strtolower($deviceOS) != 'android'){ ?>		
		  <?php if(isset($_SESSION['legacy'])){ ?>
						window.location = "level6://develop.com?call=purchaseDiamondsForProductId:&param="+diamondToAdd;
						//window.location = "level6://develop.com?call=purchaseDiamonds:&param="+diamondToAdd;
		  <? } else { ?>
								 displayAlertJS("Please update your current version to make in-app purchases");
		  <? } ?>
	 <?php } ?>
}


function purchaseDiamonds(pageURL)
{
        <?php
	  	global $user;
	  	$requiredDiamonds =  ($diamondsArr['numDiamonds'] - $user->getDiamonds());
	  	$diamonCost = getPurchasedNumDiamondsCost($requiredDiamonds);

	  ?>
	<?php if(strtolower($deviceOS) != 'android'){ ?>
		<?php $diamonCost = getPurchasedNumDiamondsCost($diamondsArr['numDiamonds']); ?>
		$('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough Diamonds </p><p>Buy <?php echo $diamonCost['numberOfDiamonds']; ?> diamonds for only $<?php echo $diamonCost['costOfDiamonds']; ?>!</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="javascript:" onClick="purchase(\''+pageURL+'\');" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
	//	<a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> 
			//alert('You did not enter a deposit amount.');
	<?php } else { ?>
	<?php $diamonCost = getPurchasedNumDiamondsCost($diamondsArr['numDiamonds']); ?>
		$('#inline1').html('<h5 class="popupweaponheader">Purchase Diamonds</h5><br/><p>You don\'t have enough Diamonds </p><p>Buy <?php echo $diamonCost['numberOfDiamonds']; ?> diamonds for only $<?php echo $diamonCost['costOfDiamonds']; ?>!</p><div class="popupbuy"  style="margin:10px auto 0 auto;width:80%;"> <a href="<?php echo $serverRoot.'googlecheckout/checkout_redirect.php'; ?>?item_name=<?php echo $diamonCost['numberOfDiamonds'].' Diamonds '; ?>&item_description=<?php echo 'Buy ' .$diamonCost['numberOfDiamonds'].' Diamonds for '.$diamonCost['costOfDiamonds']; ?>&diamonds_to_add= <?php echo $diamonCost['numberOfDiamonds']; ?>&price=<?php echo $diamonCost['costOfDiamonds']; ?>&udid=<?php echo $_SESSION['udid']; ?>&callback_url='+pageURL+'" class="blackbutton popupbuybutton">Buy</a><a href="javascript:" onClick="closePopUp()" class="blackbutton popupbuybutton">Cancel</a> </div>');
	
	<?php } ?>		
	$('.abshide').click();
	<?php 
		unset($_SESSION['numDiamonds']);
	?>
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
<?
include_once 'footer.php';
?>