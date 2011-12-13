<?php

	include_once("topmenu.php");
        include_once("properties/serverproperties.php");
	include_once 'classes/Comments.php';
	$isAjaxPage = true;
	$jsExec = '';
	$receiverID =$_POST['receiver_id'];
	$senderID = $_POST['sender_id'];
	$content = $_POST['content'];
	$time=strftime('%c');
	if(Comments::postComment($senderID, $receiverID, $content,$time)) {
		 
	}else {
		 
	}
	
?>

<div style="display: none;">
	<div id="inline1" style=""></div>
</div>

<a class="inlinecontent abshide" href="#inline1"> </a>

