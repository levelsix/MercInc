<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . "/topmenu.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/properties/itemtypeproperties.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/Item.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/User.php");


$itemIDsToItems = Item::getItemIDsToItemsVisibleInShop($playerLevel);
$num = count($itemIDsToItems);

if (isset($_POST['itemTab'])) {
	$item_type_selected = $_POST['itemTab'];
} else {
	$item_type_selected = 1;
}
?>

<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/shopitemlist.php' method='POST'>
<input type='hidden' name='itemTab' value='1' />
<input type='submit' value='<?php echo getItemTypeFromTypeID(1);?>s'/>
</form>

<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/shopitemlist.php' method='POST'>
<input type='hidden' name='itemTab' value='2' />
<input type='submit' value='<?php echo getItemTypeFromTypeID(2);?>s'/>
</form>

<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/shopitemlist.php' method='POST'>
<input type='hidden' name='itemTab' value='3' />
<input type='submit' value='<?php echo getItemTypeFromTypeID(3);?>s'/>
</form>

<?php 
echo ucfirst(getItemTypeFromTypeID($item_type_selected)) . "s";
print "<br><br>";
$userItemIDsToQuantity = User::getUsersItemsIDsToQuantity($_SESSION['userID']);
foreach ($itemIDsToItems as $itemID => $item) {
	if ($item->getType() == $item_type_selected) {
		
		print "Item: " . $item->getName() . "<br>";
		
		$itemType = getItemTypeFromTypeID($item->getType());
		$itemPrice = $item->getPrice();
		$itemUpkeep = $item->getUpkeep();
		$itemImage = $item->getImage();
			
		$quantity = 0;
		
		if ($userItemIDsToQuantity && array_key_exists($itemID, $userItemIDsToQuantity)) {
			$quantity = $userItemIDsToQuantity[$item->getID()];
		}
		?>

		Type: <?php echo $itemType;?><br>
		Minimum level: <?php echo $item->getMinLevel();?><br>
		Price: <?php echo $itemPrice; ?><br>
		Quantity Owned: <?php echo $quantity; ?><br>
		Upkeep : <?php echo $itemUpkeep; ?><br>
		Image url : <?php echo $itemImage; ?>
<?php 	
		if (($playerCash >= $itemPrice)){
?>
			<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/backend/shopitemaction.php' method='post'>
			<input type='hidden' name='actionToDo' value='buy' />
			<input type='hidden' name='storePrice' value='<?php echo $itemPrice;?>' />
			<input type='hidden' name='itemID' value='<?php echo $itemID;?>' />
			<input type='submit' value='Buy' />
			</form>
<?php 
		} else {
			echo "you can't buy this item (you don't have enough cash or it's locked)<br>";
		}
		if ($quantity >= 1 ) {
?>
			<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/backend/shopitemaction.php' method='post'>
			<input type='hidden' name='actionToDo' value='sell' />
			<input type='hidden' name='storePrice' value='<?php echo $itemPrice;?>' />
			<input type='hidden' name='itemID' value='<?php echo $itemID;?>' />
			<input type='hidden' name='oldUserQuantity' value='<?php echo $quantity;?>' />
			<input type='submit' value='Sell' />
			</form>
<?php 
		} else {
			print "you can't sell this item (don't have any or it's locked)<br>";
		}

		
		print "<br><br>";
	}
}	

$lockedItems = Item::get_locked_items_visible_in_shop($playerLevel,$item_type_selected);
foreach ($lockedItems as $itemID => $item) {
	if ($item->getType() == $item_type_selected) {
		
		print "<b>LOCKED</b> <br>";
		
		print "Item: " . $item->getName() . "<br>";
		$itemType = getItemTypeFromTypeID($item->getType());
		$itemPrice = $item->getPrice();
		$itemUpkeep = $item->getUpkeep();
		$itemImage = $item->getImage();

?>
	Type: <?php echo $itemType;?><br>
	Minimum level: <?php echo $item->getMinLevel();?><br>
	Price: <?php echo $itemPrice?><br>
	Upkeep : <?php echo $itemUpkeep?><br>
	Image url : <?php echo $itemImage; ?>
	<br>
	<br>
<?php
	}
} 
?>