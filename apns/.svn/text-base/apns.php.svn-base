
<?PHP

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
$args = (!empty($_GET)) ? $_GET:array('task'=>$argv[1]);

// CREATE APNS OBJECT, WITH DATABASE OBJECT AND ARGUMENTS
$apns = new APNS($db, $args);
?>

