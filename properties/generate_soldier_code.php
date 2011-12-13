<?php
include_once '../classes/SoldierCode.php';
include_once '../classes/SoldierCodeBackup.php';
save_soldier_code();
// Generate any 6 digit Random Code using 0-9 and A-Z 
function generate_random(){
	$code = null;
	$codeLenght = 6;
	for ($i=0; $i<$codeLenght; $i++) {
		// Just to make more random effect.
		$chr =rand(1,30)%2;
		// Select character from 0-9 and A-Z
		$code .= $chr ? chr(rand(65,90)) : chr(rand(48,57));
	  
	}
	return $code;
}
// generate soldier codes and save them in Database. 
function save_soldier_code()
{
	$soldierCode = new SoldierCode();
	$db_enteries_limit = 1000; 
	$unique_enteries = 0;
	$code;
	while ($unique_enteries < $db_enteries_limit){
		$code = generate_random(); 
		if(!$soldierCode->codeExist($code)){
			if($soldierCode->insertCode($code))
			{
				$unique_enteries++;
			}
		}				
	}	
}



?>