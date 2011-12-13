<?php    
    include_once '../properties/googlecheckout_account.php';
    include_once './properties/googlecheckout_account.php';
    include_once("googlecart.php");
    include_once("googleitem.php");

    function buy_diamonds($item_name, $item_description, $diamonds, $item_price, $udid, $callback_url=NULL) {
        $merchant_id = MERCHANT_ID;  // Your Merchant ID
        $merchant_key = MERCHANT_KEY;  // Your Merchant Key
        $server_type = CHECKOUT_SERVER_TYPE;
        $currency = CHECKOUT_CURRENCY;

        //print_r($_SESSION);

        $cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);
        $total_count = 1;

        $item_1 = new GoogleItem($item_name, $item_description, $total_count, $item_price);
        $item_1->SetMerchantPrivateItemData(new MerchantPrivateItemData(array("user-id" => $udid, "diamonds" => $diamonds)));

        $cart->AddItem($item_1);

        // Specify "Return to xyz" link

        if($callback_url != NULL){
            $cart->SetContinueShoppingUrl($callback_url);
        } else {
            $cart->SetContinueShoppingUrl(CHECKOUT_COUNTINUE_URL."?success=1&amount=".$diamonds);
        }
			
        
        // This will do a server-2-server cart post and send an HTTP 302 redirect status
        // This is the best way to do it if implementing digital delivery
        // More info http://code.google.com/apis/checkout/developer/index.html#alternate_technique
/*		echo "<PRE>";
		print_r($cart);
		echo "</PRE>";
		die();*/

        list($status, $error) = $cart->CheckoutServer2Server();


        // if i reach this point, something was wrong
        echo "An error had ocurred: <br />HTTP Status: " . $status. ":";
        echo "<br />Error message:<br />";
        echo $error;
    }

    function generate_checkout_btn($item_name, $item_description, $item_qty, $item_price, $diamonds, $callback_url=NULL) {
        // Create a new shopping cart object
        $merchant_id = MERCHANT_ID;  // Your Merchant ID
        $merchant_key = MERCHANT_KEY;  // Your Merchant Key
        $server_type = CHECKOUT_SERVER_TYPE;
        $currency = CHECKOUT_CURRENCY;

        $cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);

        // Create an item to be added in cart
        $item_1 = new GoogleItem($item_name,$item_description, $item_qty, $item_price);

        // Add <merchant-private-item-data>
        //$item_1->SetMerchantPrivateItemData(new MerchantPrivateItemData(array("user-id" => $_SESSION['udid'])));
        $item_1->SetMerchantPrivateItemData(new MerchantPrivateItemData(array("user-id" => $_SESSION['udid'], "diamonds" => $diamonds)));

        // Add items to the cart
        $cart->AddItem($item_1);

        // Add <merchant-private-data>
        //$cart->SetMerchantPrivateData(new MerchantPrivateData(array("user-id" => $_SESSION['userID'])));

        // Specify "Return to xyz" link
        if($callback_url != NULL){
            $cart->SetContinueShoppingUrl($callback_url);
        } else {
            $cart->SetContinueShoppingUrl(CHECKOUT_COUNTINUE_URL."?success=1&amount=".$diamonds);
        }

        // Display a small size button
        return $cart->CheckoutButtonCode("small", true, "en_US", false);
    }
?>