<?php
include_once("../classes/ConnectionFactory.php");
include_once("../classes/DiamondPurchasedHistory.php");
include_once("../classes/User.php");
include_once '../classes/InAppHttpRequest.php';
include_once '../properties/constants.php';

$valid_request = false;
// make this false to put this on production 
$isSandbox = true;
$diamonds = 0;
$amount = 0;

if (isset($_GET['udid'])) {
    $udid = $_GET['udid'];        
}else if(isset($_GET['UDID'])) {
    $udid = $_GET['UDID'];
}else {
    $fp = fopen('log.txt', 'a');
    fwrite($fp, 'invlaid parameters'. "\r\n");
    fclose($fp);
    echo "invalid parameter";
    exit;
}

/**
     * Verify a receipt and return receipt data
     * http://www.phpriot.com/articles/verifying-app-store-receipts-php-curl
     * @param   string  $receipt    Base-64 encoded data
     * @param   bool    $isSandbox  Optional. True if verifying a test receipt
     * @throws  Exception   If the receipt is invalid or cannot be verified
     * @return  array       Receipt info (including product ID and quantity)
     */
    function getReceiptData($receipt, $isSandbox = false) {
        // determine which endpoint to use for verifying the receipt
        if ($isSandbox) {
            $endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';
        }
        else {
            $endpoint = 'https://buy.itunes.apple.com/verifyReceipt';
        }
 
        // build the post data
        $postData = json_encode(
            array('receipt-data' => $receipt)
        );
 
        // create the cURL request
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
 
        // execute the cURL request and fetch response data
        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $errmsg   = curl_error($ch);
        curl_close($ch);
 
        // ensure the request succeeded
        if ($errno != 0) {
            throw new Exception($errmsg, $errno);
        }
 
        // parse the response data
        $data = json_decode($response);
 
        // ensure response data was a valid JSON string
        if (!is_object($data)) {
            throw new Exception('Invalid response data');
        }
 
        // ensure the expected data is present
        if (!isset($data->status) || $data->status != 0) {
            throw new Exception('Invalid receipt');
        }
 
        // build the response array with the returned data
        return array(
            'quantity'       =>  $data->receipt->quantity,
            'product_id'     =>  $data->receipt->product_id,
            'transaction_id' =>  $data->receipt->transaction_id,
            'purchase_date'  =>  $data->receipt->purchase_date,
            'app_item_id'    =>  $data->receipt->app_item_id,
            'bid'            =>  $data->receipt->bid,
            'bvrs'           =>  $data->receipt->bvrs
        );
    }
 
    // fetch the receipt data and sandbox indicator from the post data
    $httpRequest = new InAppHttpRequest();
    $receipt   = $httpRequest->body();  
    // verify the receipt
    try {
        // receipt is valid, now do something with $recipt
        $recipt = getReceiptData($receipt, $isSandbox);
        // Get all recipt information
        $quantity = $recipt['quantity'];
        $product_id = $recipt['product_id'];
        $transaction_id = $recipt['transaction_id'];
        $purchase_date = $recipt['purchase_date'];
        $app_item_id = $recipt['app_item_id'];
        $bid = $recipt['bid'];
        $bvrs = $recipt['bvrs'];
        $fp = fopen('log.txt', 'a');
        fwrite($fp, 'Log trans'.date('l jS \of F Y h:i:s A')."\r\n");
        foreach ($recipt as $key => $value) {
            fwrite($fp, $key . '=>' . $value . "\r\n");
        }
        fwrite($fp, 'udid=>' . $udid. "\r\n");        
        fclose($fp);       
    }
    catch (Exception $ex) {
        // unable to verify receipt, or receipt is not valid
        $fp = fopen('log.txt', 'a');
        fwrite($fp, 'Exception occured on Recipt validation:'.date('l jS \of F Y h:i:s A')."\r\n");
        fwrite($fp, 'Exception :'.$ex."\r\n");            
        fwrite($fp, 'udid:'.$udid."\r\n");
        fclose($fp);
        echo "failure";
        exit;
    }

// check transtion already exists or not, if exist its invalid transction otherwise valid
// $transaction_id - unique indentifier of transction from app in app purchase
function validTransction($transaction_id) {   
    if(DiamondPurchasedHistory::transactionIdExists($transaction_id) != null){
        return false;
    }else{
         return true;
    }    
}
// check the request is valid from Level6 game not browser etc
// We have to change the valid request procedure for Android requests
function detectLevel6Request() {
    if ((stripos($_SERVER['HTTP_USER_AGENT'], LEVL6_IOS_REQUEST) > -1) && (stripos($_SERVER['HTTP_USER_AGENT'], LEVL6_SOCKET_REQUEST) > -1)) {
        return true;
    }
    else
        return false;    
}

switch ($product_id) {
    case PACKAGE1_DIAMONDS_ID: {
            $diamonds = PACKAGE1_DIAMONDS_COUNT;
            $amount = PACKAGE1_DIAMONDS_COST;
            break;
        }
    case PACKAGE2_DIAMONDS_ID: {
             $diamonds = PACKAGE2_DIAMONDS_COUNT;
            $amount = PACKAGE2_DIAMONDS_COST;
            break;
        }
    case PACKAGE3_DIAMONDS_ID: {
             $diamonds = PACKAGE3_DIAMONDS_COUNT;
            $amount = PACKAGE3_DIAMONDS_COST;
            break;
        }
    case PACKAGE4_DIAMONDS_ID: {
             $diamonds = PACKAGE4_DIAMONDS_COUNT;
            $amount = PACKAGE4_DIAMONDS_COST;
            break;
        }
    case PACKAGE5_DIAMONDS_ID: {
            $diamonds = PACKAGE5_DIAMONDS_COUNT;
            $amount = PACKAGE5_DIAMONDS_COST;
            break;
        }
}
$valid_transction = validTransction($transaction_id);
$valid_request = detectLevel6Request();
$level = User::getUserLevel($udid);

if($valid_request){
    if (DiamondPurchasedHistory::addPurchasedDiamond($udid, $amount, $diamonds, $product_id,$transaction_id,$purchase_date, $app_item_id,$quantity,$bid,$bvrs,$valid_transction, $level)) {
        if (User::userExists($udid) != null && $valid_transction) {
            if (User::addUserPurchasedDiamonds($udid, $diamonds)) {
                echo "success";
                exit;
            } else {
                $fp = fopen('log.txt', 'a');
                fwrite($fp, 'Enable award diamond to user'.date('l jS \of F Y h:i:s A')."\r\n");
                foreach ($recipt as $key => $value) {
                    fwrite($fp, $key . '=>' . $value . "\r\n");
                }
                fwrite($fp, 'udid=>' . $udid. "\r\n");
                fwrite($fp, 'amount=>' . $amount. "\r\n");
                fclose($fp);
                echo "failure";
                exit;
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
            echo "failure";
            exit;
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
        echo "failure";
        exit;
    }
}else{
        $fp = fopen('log.txt', 'a');
        fwrite($fp, 'Invalid request'.date('l jS \of F Y h:i:s A')."\r\n");
        foreach ($_SERVER as $key => $value) {
            fwrite($fp, $key . '=>' . $value . "\r\n");
        }        
        fclose($fp);
        echo "failure";
        exit;
}
?>
