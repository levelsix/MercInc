<?php

	//include_once("topmenu.php");
	include_once("google_checkout.php");

	if(isset($_GET['item_name']) && isset($_GET['item_description']) && isset($_GET['price']) && isset($_GET['diamonds_to_add'])) {
		//echo "buy_diamonds";
		buy_diamonds($_GET['item_name'], 
					 $_GET['item_description'], 
					 $_GET['diamonds_to_add'], 
					 $_GET['price'], 
					 $_GET['udid'], 
					 $_GET['callback_url'].'&from=google&page='.$_GET['page']);
	}
?>