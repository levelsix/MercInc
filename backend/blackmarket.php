<?php 
ob_start();
session_start();
include_once("../classes/ConnectionFactory.php");
include_once("../properties/constants.php");
include_once("../properties/serverproperties.php");
include_once("../classes/User.php");
include_once("../classes/Item.php");
include_once("../classes/Utils.php");
include_once("../classes/common_functions.php");
include_once '../googlecheckout/google_checkout.php';


$fn = new common_functions();

$userID = $_SESSION['userID'];
$user = User::getUser($userID);
$update_cash = $fn-> get('update_cash');
$diamonds = $fn-> get('diamonds');
$refill = $fn-> get('refill');
$attack_type = $fn-> get('attack_type');
$userID = $fn-> get('userID');
$itemID = $fn->get('itemID');
$itemType = $fn->get('itemType');
$missionID = $fn-> get('missionID');
$cityID = $fn-> get('cityID');
$add_soldier = $fn-> get('add_soldier');
$stolen_goods = $fn-> get('stolen_goods');
$blackmarket_goods = $fn-> get('blackmarket_goods');
$updateDiamond = $fn-> get('update_diamond');
$request_quantity = $fn-> get('request_quantity');
$quantity = $fn-> get('quantity');
$previousQuanity = $fn-> get('previousQuanity');
$purchasePrice = $fn-> get('purchasePrice');
//$page =  $fn-> get('page');

$from = $fn-> get('from');
//print_r($_REQUEST);
if($from == "google"){
	
	$page =  base64_decode($fn-> get('page'));
    $queryStringArr = parse_url($page);
	$queryParams = convertUrlQuery($queryStringArr['query']);
	//$fn->printArray($queryParams);
	
	$diamonds = $queryParams['diamonds'];
	$cityID= $queryParams['cityID'];
	$missionID = $queryParams['missionID'];
	
	if(isset($queryParams['diamonds'])){
		$diamonds = $queryParams['diamonds'];
	}
	if(isset($queryParams['cityID'])){
		$cityID = $queryParams['cityID'];
	}
	
	if(isset($queryParams['missionID'])){
		$missionID = $queryParams['missionID'];
	}
	
	if(isset($queryParams['attack_type'])){
		$attack_type = $queryParams['attack_type'];
	}

	if(isset($queryParams['request_quantity'])){
		$request_quantity = $queryParams['request_quantity'];
	}
		
	if(isset($queryParams['updateDiamond'])){
		$updateDiamond = $queryParams['updateDiamond'];
	}

	if(isset($queryParams['userID'])){
		$userID = $queryParams['userID'];
	}
	if(isset($queryParams['itemID'])){
		$itemID = $queryParams['itemID'];
	}
	
	if(isset($queryParams['itemType'])){
		$itemType = $queryParams['itemType'];
	}

	if(isset($queryParams['quantity'])){
		$quantity = $queryParams['quantity'];
	}
	if(isset($queryParams['previousQuanity'])){
		$previousQuanity = $queryParams['previousQuanity'];
	}
	if(isset($queryParams['purchasePrice'])){
		$purchasePrice = $queryParams['purchasePrice'];
	}
	
	
	
	$page = $queryStringArr['path'];
	$page .= '?';
	
} else{
	$page =  $fn-> get('page');
}

function getRandomType($comomItems ,$uncommonItems,$rareItems,$epicItems) {
	$randomizedStat = rand(1,100);
	$comomItems = $comomItems;
	$uncommonItems = $uncommonItems + $comomItems;
	$rareItems = $uncommonItems + $comomItems + $rareItems;
	$epicItems =  $uncommonItems + $comomItems + $rareItems+$epicItems;
	
	if( $randomizedStat > 0 && $randomizedStat <= $comomItems )
		return array(0,0);
	else if($randomizedStat >   $comomItems && $randomizedStat <= $uncommonItems )
		return array(0,1);
	else if( $randomizedStat > $uncommonItems && $randomizedStat <=  $rareItems )
		return array(1,0);
	else if($randomizedStat >  $rareItems && $randomizedStat <=$epicItems)
		return array(1,1);	
}

if($page=='blackmarket')
{
	header("Location: {$serverRoot}blackmarket.php");
	exit(0);	
}

/*if($user->getDiamonds >= $diamonds)
{
	 header("Location: {$serverRoot}blackmarket.php?error=notEnoughDiamonds");
	 exit(0);
}*/
if($update_cash > 0 )
{
	if( !$user->updateUserCash($update_cash) )
	{
	   header("Location: {$serverRoot}blackmarket.php?error=updationError");
	   exit(0);
	}
	if(!$user->updateUserDiamonds($diamonds))
	{
		header("Location: {$serverRoot}blackmarket.php?error=updationError");
		exit(0);
	}
if($page){
	if($page == "equipment.php?")
	{
		$toReturn =  getItemTypeToDiv($itemType);
		//&itemID='.$realEstateId.'&diamonds='.$_SESSION['numDiamonds']
		$url = $serverRoot.$page.'itemid='.$itemID.'&status=refill&cash='.$update_cash.'&diamonds='.$diamonds.'&requestquantity='.$request_quantity.$toReturn.'';
		header("Location: $url");
		exit(0);
	}else if($page == "shoprealestatelist.php?")
	{

		//&itemID='.$realEstateId.'&diamonds='.$_SESSION['numDiamonds']
		$url = $serverRoot.$page.'itemID='.$itemID.'&status=refill&cash='.$update_cash.'&diamonds='.$diamonds.'&quantity='.$quantity.'&request_quantity='.$request_quantity.'&previousQuanity='.$previousQuanity.'&purchasePrice='.$purchasePrice;
		header("Location: $url");
		exit(0);
		
	}
	else {
		$url = $serverRoot.$page.'cityID='.$cityID.'&missionID='.$missionID.'&status=success&cash='.$update_cash.'&diamonds='.$diamonds.'';
		header("Location: $url");
		exit(0);
	}
}
	
	header("Location: {$serverRoot}blackmarket.php?update_cash=$update_cash");
	exit(0);
}


if($refill)
{
		
	if( !$user->updateUserParams($refill) )
	{
	   header("Location: {$serverRoot}blackmarket.php?error=updationError");
	   exit(0);
	}

	if( !$user->updateUserDiamonds($diamonds) )
	{
		header("Location: {$serverRoot}blackmarket.php?error=updationError");
		exit(0);
	}
	
	if($page && $refill=='energy')
	{
		$url = $serverRoot.$page.'cityID='.$cityID.'&missionID='.$missionID;
		header("Location: $url");
		exit(0);
	}
	
	if($page && $refill=='stamina')
	{
		$url = $serverRoot.$page.'attack_type='.$attack_type.'&userID='.$userID;
		header("Location: $url");
		exit(0);
	}
		
	header("Location: {$serverRoot}blackmarket.php?refill=$refill");
	exit(0);
}

if($add_soldier)
{
	if( !$user->updateUserAgencyCount() )
	{
	   header("Location: {$serverRoot}blackmarket.php?error=updationError");
	   exit(0);
	}
	if( !$user->updateUserDiamonds($diamonds) )
	{
		header("Location: {$serverRoot}blackmarket.php?error=updationError");
		exit(0);
	}
	header("Location: {$serverRoot}blackmarket.php?update_soldier=true");
	exit(0);
}

if($stolen_goods)
{
	$item_type = getRandomType(COMMON_ITEMS_PERCENTAGE ,UN_COMMON_ITEMS_PERCENTAGE, RARE_ITEMS_PERCENTAGE, EPIC_ITEMS_PERCENTAGE);
    //0 for stolen 1 for blackmarket
	$blackMarketItems = Item::getBlackMarketItem( $item_type[0] ,$item_type[1]);
	$bm_id = $blackMarketItems[0] ->  getID();
	$bm_name = $blackMarketItems[0] -> getName();
	$bm_quantity = $blackMarketItems[0] -> getQuanitybyUserId($userID);
	//$bm_type = $blackMarketItems[0] -> bm_id;

	//$fn->printArray($blackMarketItems);
	//return;
	$update_items = Item::updateUserBlackmarketItems($_SESSION['userID'], $bm_id ,$bm_quantity );
		
	if( !$user-> updateUserDiamonds($diamonds) )
	{
		header("Location: {$serverRoot}blackmarket.php?error=updationError");
		exit(0);
	}
	
header("Location: {$serverRoot}blackmarket.php?stolenGoods=$item_type&itemName=$bm_name&itemID=$bm_id&is_special=$item_type[0]&is_common=$item_type[1]");
	exit(0);
}

if($blackmarket_goods)
{
	echo $item_type = getRandomType(COMMON_ITEMS_PERCENTAGE ,UN_COMMON_ITEMS_PERCENTAGE, RARE_ITEMS_PERCENTAGE, EPIC_ITEMS_PERCENTAGE);
	//0 for stolen 1 for blackmarket
	$blackMarketItems = Item::getBlackMarketItem( $item_type[0] ,$item_type[1]);
	$bm_id =   $blackMarketItems[0] -> getID();
	$bm_name = $blackMarketItems[0] -> getName();
	$bm_quantity = $blackMarketItems[0] -> getQuanitybyUserId($userID);
	//$fn->printArray($bm_quantity);
	//return;
	$update_items = Item::updateUserBlackmarketItems($_SESSION['userID'], $bm_id ,$bm_quantity );
	
	if( !$user-> updateUserDiamonds($diamonds) )
	{
		header("Location: {$serverRoot}blackmarket.php?error=updationError");
		exit(0);
	}
	
	header("Location: {$serverRoot}blackmarket.php?blackMarketGoods=$item_type&itemName=$bm_name&itemID=$bm_id");
	exit(0);
}
if($updateDiamond)
{
    if( !$user->addUserDiamonds($diamonds) )
    {
            header("Location: {$serverRoot}blackmarket.php?error=updationError");
            exit(0);
    }

    header("Location: {$serverRoot}blackmarket.php?addDiamonds=$diamonds");
    exit(0);
}    
//header("Location: {$serverRoot}battle.php?attack_type=".$attack_type."&cash_lost=$cashLost&winner_demage=$winnerDemage&looser_demage=$looserDemage");
exit;
?>