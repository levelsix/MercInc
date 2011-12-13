<?PHP
include_once '../classes/User.php';
include_once '../classes/ConnectionFactory.php';
/**
 * @category Apple Push Notification Service using PHP & MySQL
 * @package APNS
 * @author Peter Schmalfeldt <manifestinteractive@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link http://code.google.com/p/easyapns/
 */

/**
 * Begin Document
 */

// AUTOLOAD CLASS OBJECTS... YOU CAN USE INCLUDES IF YOU PREFER
//if(!function_exists("__autoload")){
//	function __autoload($class_name){
//		require_once('class_'.$class_name.'.php');
//	}
//}
//
//// CREATE DATABASE OBJECT ( MAKE SURE TO CHANGE LOGIN INFO IN CLASS FILE )
//$db = new DbConnect();
//$db->show_errors();
//
//// FETCH $_GET OR CRON ARGUMENTS TO AUTOMATE TASKS
//$apns = new APNS($db);

/**
/*	ACTUAL SAMPLES USING THE 'Examples of JSON Payloads' EXAMPLES (1-5) FROM APPLE'S WEBSITE.
 *	LINK:  http://developer.apple.com/iphone/library/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/ApplePushService/ApplePushService.html#//apple_ref/doc/uid/TP40008194-CH100-SW15
 */
function sendMessage($deviceToken) {

// Put your private key's passphrase here:
    $passphrase = 'pushchat';

// Put your alert message here:
    $message = "Welcome to LVL6 game";

////////////////////////////////////////////////////////////////////////////////

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
    $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);

   // Create the payload body
    $body['aps'] = array(
        'alert' => $message,
        'sound' => 'default'
    );

// Encode the payload as JSON
    $payload = json_encode($body);

// Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));

    if (!$result){
        echo 'Message not delivered' . PHP_EOL;
        $fp = fopen('log.txt', 'a');
        fwrite($fp, 'Exception occured on Recipt validation:'.date('l jS \of F Y h:i:s A')."\r\n");
        fwrite($fp, 'Exception :'.$ex."\r\n");            
        fwrite($fp, 'udid:'.$udid."\r\n");
        fclose($fp);
    }
    else
        echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
    fclose($fp);
}

function sendMessageToDevice($deviceToken, $message) {

 
// Put your private key's passphrase here:
    $passphrase = 'pushchat';

// Put your alert message here:
    //$message = "Welcome to LVL6 game";

////////////////////////////////////////////////////////////////////////////////

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
    $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);

   // Create the payload body
    $body['aps'] = array(
        'alert' => $message,
        'sound' => 'default'
    );

// Encode the payload as JSON
    $payload = json_encode($body);

// Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));

    if (!$result)
        echo 'Message not delivered' . PHP_EOL;
    else
        echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
    fclose($fp);
}

function getdeviceTokenAndSendMessage($udid, $message){
    echo "UDID = ".$udid;
    $query = "select apns_devices.pid, apns_devices.devicetoken from apns_devices WHERE apns_devices.deviceuid = '".$udid."'";
    $devices = ConnectionFactory::SelectRowsAsClasses($query, array(), __CLASS__);
    print_r($device);
    die();
    //$inactiveUsers = User::lastTwoDayInactive();
    foreach ($devices as $device){
        echo "DEVICE TOKEN = ".$device->devicetoken;
        sendMessageToDevice($device->devicetoken, $message);

    }
}


$query = "select apns_devices.pid, apns_devices.devicetoken from apns_devices ";

$devices= ConnectionFactory::getAssociativeArray($query);
//$inactiveUsers = User::lastTwoDayInactive();
foreach ($devices as $device){    
    sendMessage($device['devicetoken']);

}

//    $conditions = array();
//    
//   // $conditions['deviceuid'] = $user['udid'];    
//    $deviceToken = ConnectionFactory::getAssociativeArray('devicetoken', 'apns_devices', $conditions);
//    echo $deviceToken;
//    if($userPid>0){
//        sendMessage(sendMessage);
////        $apns->newMessage($userPid);    
////        $apns->addMessageAlert("You didn'nt login from last two days");    
////        $apns->queueMessage();    
//    }
//}

?>

