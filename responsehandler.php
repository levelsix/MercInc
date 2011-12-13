<?php

/**
 * Copyright (C) 2007 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

 /* This is the response handler code that will be invoked every time
  * a notification or request is sent by the Google Server
  *
  * To allow this code to receive responses, the url for this file
  * must be set on the seller page under Settings->Integration as the
  * "API Callback URL'
  * Order processing commands can be sent automatically by placing these
  * commands appropriately
  *
  * To use this code for merchant-calculated feedback, this url must be
  * set also as the merchant-calculations-url when the cart is posted
  * Depending on your calculations for shipping, taxes, coupons and gift
  * certificates update parts of the code as required
  *
  */

  //chdir("..");
  include_once 'properties/constants.php';
  require_once('googlecheckout/googleresponse.php');
  require_once('googlecheckout/googlemerchantcalculations.php');
  require_once('googlecheckout/googleresult.php');
  require_once('googlecheckout/googlerequest.php');

  include_once("classes/ConnectionFactory.php");
  include_once("classes/DiamondPurchasedHistory.php");
  include_once("classes/User.php");
  include_once("classes/InAppHttpRequest.php");
  include_once("properties/constants.php");

  define('RESPONSE_HANDLER_ERROR_LOG_FILE', 'logs/googleerror.log');
  define('RESPONSE_HANDLER_LOG_FILE', 'logs/googlemessage.log');

  $merchant_id = MERCHANT_ID;  // Your Merchant ID
  $merchant_key = MERCHANT_KEY;  // Your Merchant Key
  $server_type = CHECKOUT_SERVER_TYPE;  // change this to go live
  $currency = CHECKOUT_CURRENCY;  // set to GBP if in the UK

  $Gresponse = new GoogleResponse($merchant_id, $merchant_key);

  $Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);

  //Setup the log file
  $Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE, 
                                        RESPONSE_HANDLER_LOG_FILE, L_ALL);

  // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
  $xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
  if (get_magic_quotes_gpc()) {
    $xml_response = stripslashes($xml_response);
  }
  list($root, $data) = $Gresponse->GetParsedXML($xml_response);
  $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);

  $status = $Gresponse->HttpAuthentication();
  if(! $status) {
    die('authentication failed');
  }

  switch ($root) {
    case "new-order-notification": {
		$serial_number = $data[$root]["serial-number"];		
		$Gresponse->SendAck($serial_number, false);
		
		$items = get_arr_result($data[$root]['shopping-cart']['items']['item']);
		$item_name = $items[0]['item-name']['VALUE'];
		$unit_price = $items[0]['unit-price']['VALUE'];
		$item_quantity = $items[0]['quantity']['VALUE'];
		$user_id = $items[0]['merchant-private-item-data']['user-id']['VALUE'];
		$diamonds = $items[0]['merchant-private-item-data']['diamonds']['VALUE'];
		
		$google_order_number = $data[$root]['google-order-number']['VALUE'];
		
		//TODO: Make the entry in the database for local use...
		$udid = $user_id;
		$amount = $unit_price;		
		$product_id = "";
		$transaction_id = $google_order_number;
		$purchase_date = $data[$root]['timestamp']['VALUE'];
		$app_item_id = "";
		$quantity = 1;
		$bid = "";
		$bvrs = "";
		$valid_transction = 1;
		$level = User::getUserLevel($udid);
		
		if (DiamondPurchasedHistory::addPurchasedDiamond($udid, $amount, $diamonds, $product_id,$transaction_id,$purchase_date, $app_item_id,$quantity,$bid,$bvrs,$valid_transction, $level)) {
			if (User::userExists($udid) != null) {
				if (User::addUserPurchasedDiamonds($udid, $diamonds)) {
					$Grequest->SendDeliverOrder($google_order_number);
					break;
				} else {
					$fp = fopen('log.txt', 'a');
					fwrite($fp, 'Enable award diamond to user'.date('l jS \of F Y h:i:s A')."\r\n");
					foreach ($recipt as $key => $value) {
						fwrite($fp, $key . '=>' . $value . "\r\n");
					}
					fwrite($fp, 'udid=>' . $udid. "\r\n");
					fwrite($fp, 'amount=>' . $amount. "\r\n");
					fclose($fp);
					break;
				}
			} else {
				$fp = fopen('log.txt', 'a');
				fwrite($fp, 'User not exist or request in invalid'.date('l jS \of F Y h:i:s A')."\r\n");
				foreach ($recipt as $key => $value) {
					fwrite($fp, $key . '=>' . $value . "\r\n");
				}
				fwrite($fp, 'udid=>' . $udid. "\r\n");
				fwrite($fp, 'amount=>' . $amount. "\r\n");
				fclose($fp);
				break;
			}
		} else {
			$fp = fopen('log.txt', 'a');
			fwrite($fp, 'Enable to save diamond_history'.date('l jS \of F Y h:i:s A')."\r\n");
			foreach ($recipt as $key => $value) {
				fwrite($fp, $key . '=>' . $value . "\r\n");
			}
			fwrite($fp, 'udid=>' . $udid. "\r\n");
			fwrite($fp, 'amount=>' . $amount. "\r\n");
			fclose($fp);
			break;
		}		
		break;
    }
	
	case 'authorization-amount-notification': {
		$serial_number = $data[$root]["serial-number"];		
		$Gresponse->SendAck($serial_number, true);
	}
    
    default:
      //$Gresponse->SendBadRequestStatus("Invalid or not supported Message");
	  $Gresponse->SendAck();
      break;
  }
  
  /* In case the XML API contains multiple open tags
     with the same value, then invoke this function and
     perform a foreach on the resultant array.
     This takes care of cases when there is only one unique tag
     or multiple tags.
     Examples of this are "anonymous-address", "merchant-code-string"
     from the merchant-calculations-callback API
  */
  function get_arr_result($child_node) {
    $result = array();
    if(isset($child_node)) {
      if(is_associative_array($child_node)) {
        $result[] = $child_node;
      }
      else {
        foreach($child_node as $curr_node){
          $result[] = $curr_node;
        }
      }
    }
    return $result;
  }

  /* Returns true if a given variable represents an associative array */
  function is_associative_array( $var ) {
    return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
  }
?>
