<?php
include_once("topmenu.php");
$user = User::getUser($_SESSION['userID']);
//$fn-> printArray($user);
$sound ='';
$vibration ='';
$comment_notification ='';
$other_notification ='';
$splash = '';

if($user -> getSoundSettings() == 1)
	$sound = 'checked="checked"';
if($user -> getVibrationSettings() == 1)	 
	$vibration = 'checked="checked"';

if($user -> getCommentSettings() == 1)	 
	$comment_notification = 'checked="checked"';

if($user -> getNotificationSettings() == 1)	 
	$other_notification = 'checked="checked"';

if($user -> getSplashSettings() == 1)
	$splash = 'checked="checked"';

?>

<script type="text/javascript" src="js/iphone-style-checkboxes.js"></script>
<script type="text/javascript" charset="utf-8">
 $(window).load(function() {
    $('.on_off :checkbox').iphoneStyle();
    $('.disabled :checkbox').iphoneStyle();
    $('.css_sized_container :checkbox').iphoneStyle({ resizeContainer: false, resizeHandle: false });
    $('.long_tiny :checkbox').iphoneStyle({ checkedLabel: 'Very Long Text', uncheckedLabel: 'Tiny' });
	$('.on_off :checkbox').iphoneStyle({labelOnClass: function(){ alert('test change function again');}});
    
    var onchange_checkbox = ($('.onchange :checkbox')).iphoneStyle({
      onChange: function(elem, value) { 
	  	//alert('change'); 
        $('span#status').html(value.toString());
      }
    });
	
	
	
	
  });
  
	$(document).ready(function() { 
		/*$(".on_off").click(function () { 
		   alert('click');
		   var frmData = 'sound_settings='+$("#sound").is(':checked')+'&vibration_settings='+$("#vibration").is(':checked')+'&comment_settings='+$("#comment_notification").is(':checked')+'&notification_settings='+$("#other_notification").is(':checked');
			contentsAjaxObj = callAjax('settings.php', frmData, $('.settings_overlay').show(), $('.settings_overlay').hide(), 'GET');
		}); */
		
		
		
	});
   
</script>	

<div class="abs settings_overlay" style="display:none;">
	<div class="abs settings_loading" id="settingDescLoading" style="display:block;"><div class="abs settings_text">Loading...</div></div>
</div>
<div id="content">

	<div id="tabs">

	<div class="container missionsmenu">
		<ul id="menu2">
			<li class="tab"><a href="#one">General</a></li>
			<!--<li class="tab"><a href="#two">Blocked List</a></li>-->
		</ul>
	</div>	
	
	<div id="one">
    	<form>
		<table class="settings">
		  <tr class="on_off">
		    <th><label for="sound">Sound</label></th>
		    <td>
		      <input type="checkbox" class="checkbox" name="chkBox"  <?php echo $sound; ?>  id="sound" />
		    </td>
		  </tr>
		  <tr class="on_off">
		    <th><label for="on_off_on">Vibration</label></th>
		    <td>
		      <input type="checkbox" class="checkbox" name="chkBox"  <?php echo $vibration; ?>   id="vibration" />
		    </td>
		  </tr>
                  <tr class="on_off">
		    <th><label for="on_off_on">Splash Screen</label></th>
		    <td>
		      <input type="checkbox" class="checkbox" name="chkBox" <?php echo $splash; ?>  id="splash"/>
		    </td>
		  </tr>
		  <tr class="on_off">
		    <th><label for="on_off_on">Notification on Comment</label></th>
		    <td>
		      <input type="checkbox" class="checkbox" name="chkBox" <?php echo $comment_notification; ?>  id="comment_notification"/>
		    </td>
		  </tr>
		  <tr class="on_off">
		    <th><label for="on_off_on">Notification on full energy and Stamina</label></th>
		    <td>
		      <input type="checkbox" class="checkbox" name="chkBox" <?php echo $other_notification; ?>  id="other_notification"/>
		    </td>
		  </tr>
                  
		</table>
        </form>
	</div>	
	
	<!--<div id="two">
	<p class="blockedlist">You don't have anyone in your blocked list.</p>
	</div>-->
	
</div>

<?php
include_once 'footer.php';
?>