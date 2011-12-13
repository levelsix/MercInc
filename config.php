<?php

/*
	Creating constants for heavily used paths makes things a lot easier.
	
*/
//Controller for every functions. 
defined("BACKEND_PATH")
	or define("BACKEND_PATH", dirname(__FILE__) . '/backend/');
	
// These are basically of database tables, We will interact with database using these classes.
defined("CLASSES_PATH")
	or define("CLASSES_PATH", dirname(__FILE__) . '/classes/');

// These are all views used in application.
defined("VIEWS_PATH")
	or define("VIEWS_PATH", dirname(__FILE__) . '/views/');

// These are all views used in application.
defined("PROPERTIES_PATH")
	or define("PROPERTIES_PATH", dirname(__FILE__) . '/properties/');

/*
	Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);
require_once CLASSES_PATH.'ConnectionFactory.php';
require_once CLASSES_PATH.'Utils.php';
require_once PROPERTIES_PATH.'serverproperties.php';
include_once CLASSES_PATH."Item.php";
include_once CLASSES_PATH . "Mission.php";
include_once CLASSES_PATH . "User.php";
include_once CLASSES_PATH . "UserMissionData.php";
include_once 'properties/serverproperties.php';


?>