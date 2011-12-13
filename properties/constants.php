<?php

/*
 * Google checkout constants
 */
//define('MERCHANT_ID', "375138340322524");
//define('MERCHANT_KEY', "away3TjyVjh18rhQmubsyA");
//define('CHECKOUT_SERVER_TYPE', "sandbox");
//define("CHECKOUT_CURRENCY", "USD");
//define("CHECKOUT_COUNTINUE_URL", "http://50.18.188.59/lvl6_dev/blackmarket.php");

define("LEVL6_IOS_REQUEST", "level6");
define("LEVL6_SOCKET_REQUEST", "CFNetwork");
$experience_points = 21;
$required_experience_points = 92;

define("REQUIRED_EXPERIENCE_POINTS_FIRST_BATTLE", 92);
define("EXPERIENCE_POINTS_FIRST_BATTLE", 21);

define("USER_DEFAULT_DIAMONDS", 20);
define("SOUND_ATTACK", "click.mp3");
define("SOUND_CLICK", "MenuClick.wav");

/* 
 * Numeric value constants
 */
define('COMMON_ITEMS', 1);
define('UN_COMMON_ITEMS', 2);
define('RARE_ITEMS', 3);
define('EPIC_ITEMS', 4);
define('DUMMY_SOLDIER_NAME', 'Hired Gun');
define('COMMON_ITEMS_PERCENTAGE', 54);
define('UN_COMMON_ITEMS_PERCENTAGE', 35);
define('RARE_ITEMS_PERCENTAGE', 10);
define('EPIC_ITEMS_PERCENTAGE', 1);
define('BOUNTY_MESSAGE_AFTER_COMPLETION', 'body is buried, Natalie calls to congratulate you, \"Nice work, there are a lot of armies out there to get you, take it upon yourself to attack them.\"');

define('DIAMOND_COUNT_FOR_CASH', 10);
define('DIAMOND_COUNT_RECEIVED', 34500);
define('DIAMOND_COUNT_FOR_REFILL_ENERGY', 10);
define('DIAMOND_COUNT_FOR_REFILL_HEALTH', 5);
define('DIAMOND_COUNT_FOR_REFILL_STAMINA', 10);
define('DIAMOND_COUNT_FOR_SOLDIER', 20);
define('DIAMOND_COUNT_FOR_CLASS', 50);
define('DIAMOND_COUNT_FOR_STOLEN_GOODS', 50);
define('DIAMOND_COUNT_FOR_MARKET_GOODS', 50);
define('DIAMOND_COUNT_FOR_NAME', 30);
define('DUMMY_SOLDIER_NAME_FOR_BATTLE', "CoopaTroopa");
define('BLACK_MARKET_GOODS', 2);
define('STOLEN_GOODS', 1);


define('PACKAGE1_DIAMONDS_COUNT', 60);
define('PACKAGE1_DIAMONDS_COST', 3.99);
define('PACKAGE2_DIAMONDS_COUNT', 160);
define('PACKAGE2_DIAMONDS_COST', 9.99);
define('PACKAGE3_DIAMONDS_COUNT', 350);
define('PACKAGE3_DIAMONDS_COST', 19.99);
define('PACKAGE4_DIAMONDS_COUNT', 900);
define('PACKAGE4_DIAMONDS_COST', 49.99);
define('PACKAGE5_DIAMONDS_COUNT', 2000);
define('PACKAGE5_DIAMONDS_COST', 99.99);
define('BLACK_MARKET_UNLOCK_MSG', 'You also got 20 diamonds. Go to');
define('BLACK_MARKET_UNLOCK_MSG1', 'to get exciting items.');
define('SAME_CLASS_ERROR', 'You already have the same class.');

define('PACKAGE1_DIAMONDS_ID','com.lvl6.mercenaryinc.packageone');
define('PACKAGE2_DIAMONDS_ID','com.lvl6.mercenaryinc.packagetwo');
define('PACKAGE3_DIAMONDS_ID','com.lvl6.mercenaryinc.packagethree');
define('PACKAGE4_DIAMONDS_ID','com.lvl6.mercenaryinc.packagefour');
define('PACKAGE5_DIAMONDS_ID','com.lvl6.mercenaryinc.packagefive');

define('NUM_RECORDS',10);

define('SAME_NAME_ERROR', 'You already have the same name.');
define('NAME_MISSING_ERROR', 'Your need to enter name.');

define('DB_SERVER' , 'localhost');
define('DB_USER' , 'mercenaryinc');
define('DB_NAME' , 'root');
define('DB_PSWD' , '');


/*$comomItems = 54;
$uncommonItems = $comomItems+35;
$rareItems = $comomItems+$uncommonItems+10;
$epicItems = $comomItems+$uncommonItems+$rareItems+1;*/

define('INCREASE_REAL_ESTATE_PERCENTAGE', .1);
define('SELL_RATIO', .6);

define('FIRST_MISSION_MIN_CASH', 100);
define('FIRST_MISSION_MAX_CASH', 300);
define('FIRST_MISSION_EXPERIENCE_POINTS', 1);
define('FIRST_MISSION_ENERGY', 7);

$income_increase_timer = 60;
$health_increase_timer = 4;
$energy_increase_timer = 5;
$stamina_increase_timer = 3;

$income_increase_timer_special = 50;
$health_increase_timer_special = 3.5;
$energy_increase_timer_special = 4;




define('INCOME_INCREASE_TIME', 60);
define('HEALTH_INCREASE_TIME', 4);
define('ENERGY_INCREASE_TIME', 5);
define('STAMINA_INCREASE_TIME', 3);

define ('VICTOR_LEVEL_1',1);
define ('VICTOR_LEVEL_2',5);
define ('VICTOR_LEVEL_3',10);
define ('VICTOR_LEVEL_4',150);
define ('VICTOR_LEVEL_5',250);
define ('VICTOR_LEVEL_6',500);
define ('VICTOR_LEVEL_7',1500);
define ('VICTOR_LEVEL_8',2500);

define ('FALL_GUY_LEVEL_1',1);
define ('FALL_GUY_LEVEL_2',5);
define ('FALL_GUY_LEVEL_3',10);
define ('FALL_GUY_LEVEL_4',25);
define ('FALL_GUY_LEVEL_5',50);
define ('FALL_GUY_LEVEL_6',500);
define ('FALL_GUY_LEVEL_7',1000);
define ('FALL_GUY_LEVEL_8',4000);

define ('MARKSMAN_LEVEL_1',1);
define ('MARKSMAN_LEVEL_2',5);
define ('MARKSMAN_LEVEL_3',10);
define ('MARKSMAN_LEVEL_4',25);
define ('MARKSMAN_LEVEL_5',100);
define ('MARKSMAN_LEVEL_6',250);
define ('MARKSMAN_LEVEL_7',500);
define ('MARKSMAN_LEVEL_8',1000);

define ('MASTER_LEVEL_1',10);
define ('MASTER_LEVEL_2',20);
define ('MASTER_LEVEL_3',40);
define ('MASTER_LEVEL_4',100);
define ('MASTER_LEVEL_5',200);
define ('MASTER_LEVEL_6',500);
define ('MASTER_LEVEL_7',1000);
define ('MASTER_LEVEL_8',5000);

define ('MONSTER_LEVEL_1',1);
define ('MONSTER_LEVEL_2',10);
define ('MONSTER_LEVEL_3',50);
define ('MONSTER_LEVEL_4',150);
define ('MONSTER_LEVEL_5',600);
define ('MONSTER_LEVEL_6',1250);
define ('MONSTER_LEVEL_7',3000);
define ('MONSTER_LEVEL_8',5500);

define ('PRODIGY_LEVEL_1',5);
define ('PRODIGY_LEVEL_2',10);
define ('PRODIGY_LEVEL_3',15);
define ('PRODIGY_LEVEL_4',25);
define ('PRODIGY_LEVEL_5',40);
define ('PRODIGY_LEVEL_6',60);
define ('PRODIGY_LEVEL_7',85);
define ('PRODIGY_LEVEL_8',115);

?>