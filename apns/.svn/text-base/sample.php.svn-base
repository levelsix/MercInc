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
if(!function_exists("__autoload")){
	function __autoload($class_name){
		require_once('class_'.$class_name.'.php');
	}
}

// CREATE DATABASE OBJECT ( MAKE SURE TO CHANGE LOGIN INFO IN CLASS FILE )
$db = new DbConnect();
$db->show_errors();

// FETCH $_GET OR CRON ARGUMENTS TO AUTOMATE TASKS
$apns = new APNS($db);

/**
/*	ACTUAL SAMPLES USING THE 'Examples of JSON Payloads' EXAMPLES (1-5) FROM APPLE'S WEBSITE.
 *	LINK:  http://developer.apple.com/iphone/library/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/ApplePushService/ApplePushService.html#//apple_ref/doc/uid/TP40008194-CH100-SW15
 */
$inactiveUsers = User::lastTwoDayInactive();
foreach ($inactiveUsers as $user){
    $conditions = array();
    
    $conditions['deviceuid'] = $user['udid'];    
    $userPid = ConnectionFactory::SelectValue('pid', 'apns_devices', $conditions);
    if($userPid>0){
        $apns->newMessage($userPid);    
        $apns->addMessageAlert("You didn'nt login from last two days");    
        $apns->queueMessage();    
    }
//    die;
}
    
die;
 $apns->newMessage(1);
 $apns->addMessageAlert('Message received from Waseem2');    
 $apns->queueMessage();

 $apns->newMessage(2);
 $apns->addMessageAlert('Message received from Waseem2');     
 $apns->queueMessage();
 die;
// APPLE APNS EXAMPLE 1
$apns->newMessage(2);
$apns->addMessageAlert('Message received from Bob');
$apns->addMessageCustom('acme2', array('bang', 'whiz'));
$apns->queueMessage();

// APPLE APNS EXAMPLE 2
$apns->newMessage(2, '2010-01-01 00:00:00'); // FUTURE DATE NOT APART OF APPLE EXAMPLE
$apns->addMessageAlert('Bob wants to play poker', 'PLAY');
$apns->addMessageBadge(5);
$apns->addMessageCustom('acme1', 'bar');
$apns->addMessageCustom('acme2', array('bang', 'whiz'));
$apns->queueMessage();

// APPLE APNS EXAMPLE 3
$apns->newMessage(2);
$apns->addMessageAlert('You got your emails.');
$apns->addMessageBadge(9);
$apns->addMessageSound('bingbong.aiff');
$apns->addMessageCustom('acme1', 'bar');
$apns->addMessageCustom('acme2', 42);
$apns->queueMessage();

// APPLE APNS EXAMPLE 4
$apns->newMessage(1, '2010-01-01 00:00:00');  // FUTURE DATE NOT APART OF APPLE EXAMPLE
$apns->addMessageAlert(NULL, NULL, 'GAME_PLAY_REQUEST_FORMAT', array('Jenna', 'Frank'));
$apns->addMessageSound('chime');
$apns->addMessageCustom('acme', 'foo');
$apns->queueMessage();

// APPLE APNS EXAMPLE 5
$apns->newMessage(1);
$apns->addMessageCustom('acme2', array(5, 8));
$apns->queueMessage();

?>

