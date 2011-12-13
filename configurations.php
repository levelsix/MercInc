<?php


?>
<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/backend/update_settings.php' method='POST'>

	Sound :	<br>						<input type="radio" name="sound" value=1 checked="checked" /> ON <br />
										<input type="radio" name="sound" value=0 /> OFF
										<br>
										<br>
		
	Notifications while comment :<br>	<input type="radio" name="comment_notifications" value=1  checked="checked"/> ON <br />
										<input type="radio" name="comment_notifications" value=0 /> OFF
										<br>
										<br>
	Notification on stat full:<br>		<input type="radio" name="stat_notifications" value=1 checked="checked"/> ON <br />
										<input type="radio" name="stat_notifications" value=0 /> OFF
										<br>
										<br>
	<input type= "submit" value="save changes">
			</form>
			
<?php 

?>