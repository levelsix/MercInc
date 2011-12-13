<?php
function getPlayerTypeFromTypeID($type) {
	$playertype1="Heavy Arms";
	$playertype2="Specialist";
	$playertype3="Marine";
	
	switch ($type)
	{
		case 1:
			return $playertype1;
		case 2:
			return $playertype2;
		case 3:
			return $playertype3;
		default:;
	}
}
function getPlayerTypeFromTypeImageURL($type) {
	
	switch ($type)
	{
		case 1:
			return "img/heavyarms.png";
		case 2:
			return "img/specialist.png";
		case 3:
			return "img/marine.png";
		default:
            return "img/marineicon2.png";
	}
}
function getPlayerTypeFromTypeCSS($type) {
	
	switch ($type)
	{
		case 1:
			return "heavyarms";
		case 2:
			return "specialist";
		case 3:
			return "marine";
		default:
            return "marine";
	}
}
?>