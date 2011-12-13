<?php
	include_once("../properties/serverproperties.php");
	include_once '../classes/Comments.php';
	$action = $_GET['action'];
	
	
	if($action == "delete" && isset($_GET['id'])) {
		$id = $_GET['id'];
		$receiverID = $_GET['receiver_id'];
		Comments::deleteComment($id);
		
		if(isset($_GET['screen']) && $_GET['screen'] == "home" ) {
			header("location:{$serverRoot}charhome.php#three");
			exit;	
		}else if(isset($_GET['screen']) && $_GET['screen'] == "externalProfile" ) {
			header("location:{$serverRoot}externalplayerprofile.php?userID=$receiverID#two");
			exit;
			
		}else{
			header("location:{$serverRoot}profile.php#four");
			exit;
		}
	}
	else if($action == "post" ) {
		$receiverID =$_POST['receiver_id'];
		$senderID = $_POST['sender_id'];
		$content = $_POST['content'];

		$time=strftime('%c');
		if(Comments::postComment($senderID, $receiverID, $content,$time)) {
			
			if(isset($_GET['screen']) && $_GET['screen'] == "externalProfile" ) {
				header("location:{$serverRoot}externalplayerprofile.php?userID=$receiverID#two");
				exit;
			}
			else
			{
				header("location:{$serverRoot}profile.php#four");
				exit;
			}
		}
	}

?>