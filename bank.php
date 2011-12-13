<?php
include_once("topmenu.php");
$result_html = '';
// Session checks
if (isset($_SESSION['notValid'])) {
	$result_html .= '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>The value you entered was not a valid amount. Note: no decimal values allowed.</h3>
			</div>
		</div>
	</div>';
	//print "The value you entered was not a valid amount. Note: no decimal values allowed. <br>";	
	unset($_SESSION['notValid']);
}
if (isset($_SESSION['notEnoughCash'])) {
	$result_html .= '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>You think you have that much cash?</h3>
			</div>
		</div>
	</div>';
	//print "You think you have that much cash? <br>";
	unset($_SESSION['notEnoughCash']);
}
if (isset($_SESSION['deposited'])) {
	
	$result_html .= '<div id="notification">
		<div class="notificationbox successbox">
			<h3>You successfully deposited $'.$_SESSION['deposited'].' cash.</h3>
			</div>
		</div>
	</div>';
	//print "You successfully deposited " . $_SESSION['deposited'] . " cash. <br>";
	unset($_SESSION['deposited']);
}
if (isset($_SESSION['notEnoughBalance'])) {
	$result_html .= '<div id="notification">
		<div class="notificationbox notifybox wounded">
			<h3>You think your bank balance is that big? </h3>
			</div>
		</div>
	</div>';
	//print "You think your bank balance is that big? <br>";
	unset($_SESSION['notEnoughBalance']);
}
if (isset($_SESSION['withdrew'])) {
	//print "You successfully withdrew " . $_SESSION['withdrew'] . " cash. <br>";
	$result_html .= '<div id="notification">
		<div class="notificationbox successbox">
			<h3>You successfully withdrew $'.$_SESSION['withdrew'].' cash. </h3>
			</div>
		</div>
	</div>';
	
	unset($_SESSION['withdrew']);
}

$userID = $_SESSION['userID'];
$user = User::getUser($userID);
$bankBalance = $user->getBankBalance();
$cash = $user->getCash();
?>

<script>
	$(document).ready(function() {
		$(".inlinecontent").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
				});
			$('#dAmount').numeric( { negative : false, decimal : false });
			$('#wAmount').numeric({ negative : false , decimal : false});
		});
	
	$.fx.off = !$.fx.off;
	
	touchMove = function(event) {
	// Prevent scrolling on this element
	event.preventDefault();
	}
</script>






<?= $result_html;?>
<div id="topcode">
	<h2 class="cash">Bank Balance: <em><?php echo '$'.$bankBalance; ?></em></h2>
</div>
	<!--  Deposit-->
	<form  id="deposit"  action='<?php echo $serverRoot; ?>backend/bankdeposit.php' onsubmit='return validateDeposit();' method='POST'>
        <h3 class="miniheader">Deposit amount:</h3>
        <fieldset class="top">
       		<input type="text"  maxlength ="11" id='dAmount' name="dAmount" value="<?php echo $cash ?>" class="textbox" />
            <input type="button" id='depositAmount' class="bankbutton" value="Deposit" />
          </fieldset>
        
        <p class="note">Note: There is a 10% fee for any deposit.</p>
    </form>
    <!--  Withdrawal-->
	<form id="withdraw" action='<?php echo $serverRoot; ?>backend/bankwithdrawal.php' onsubmit='return validateWithdraw();' method='POST'>
        <h3 class="miniheader">Withdrawl amount:</h3>
        <fieldset class="top">
        	<input type="text"  maxlength="11" id='wAmount' name="wAmount"  value="<?php echo $bankBalance?>" class="textbox" />
        	<input type="button" class="bankbutton" value="Withdraw" name='amount' id='withdrawAmount'/>
        </fieldset>
	</form>
<!--  Deposit-->
<!--<form action='<?php echo $serverRoot; ?>backend/bankdeposit.php' onsubmit='return validateDeposit();' method='GET'>
<input type='text' name='amount' id='depositAmount'/>
<input type='submit' value='Deposit'/>
</form>
Note that there is a 10% fee on every deposit.-->

<!--  Withdrawal
<form action='<?php $_SERVER['DOCUMENT_ROOT'] ?>/backend/bankwithdrawal.php' onsubmit='return validateWithdraw();' method='GET'>
<input type='text' name='amount' id='withdrawAmount'/>
<input type='submit' value='Withdraw'/>
</form>
-->

<script>


	$('#withdrawAmount').click(function()
	{
		//alert('i am withdraw');
		$('#withdraw').submit();
		//return false;
	});
	
	$('#depositAmount').click(function()
	{
		//alert('i am deposit');
		$('#deposit').submit();
		//return false;
	});
 function closePpoup(){
	$('#fancybox-close').click();
 }
 function validateDeposit() {
   var depositAmount = $('#dAmount').val();
	$('#notification').html(' ');
	if (depositAmount.trim() == '') 
	{
    	$('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> Please enter deposit amount again.</p><div class="popupbuy"  style="margin:10px auto 0 auto;"> <a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
		//alert('You did not enter a deposit amount.');
		$('.abshide').click();
    	return false;
	}
	if (depositAmount.trim() <= 0) 
	{
		   $('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> You need to deposit a positive number.</p><div class="popupbuy"  style="margin:10px auto 0 auto;"><a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
		   //alert('You need to deposit a positive number.');
		   $('.abshide').click();
			return false;
	}

   return true;
 }
 function validateWithdraw() {
	  var withdrawAmount = $('#wAmount').val();
	  $('#notification').html(' ');		
	  if (withdrawAmount.trim() == '') {
		  $('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> Please enter withdraw amount again.</p><div class="popupbuy"  style="margin:10px auto 0 auto;"><a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
		//alert('You did not enter a deposit amount.');
		$('.abshide').click();
	    //alert('You did not enter a withdraw amount.');
	    return false;
	   }

	  if (isNaN(withdrawAmount.trim())) {
		   $('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> That withdraw amount is not a number.</p><div class="popupbuy"  style="margin:10px auto 0 auto;"><a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
		   $('.abshide').click();
		   // alert('That withdraw amount is not a number.');
		    return false;
		  }
	  if (withdrawAmount.trim() < 0) {
		   $('#inline1').html('<h5 class="popupweaponheader">Error</h5><br/><p> You need to withdraw a positive number.</p><div class="popupbuy"  style="margin:10px auto 0 auto;"><a href="javascript:" onClick="closePpoup()" class="blackbutton popupbuybutton">OK</a></div>');
		   $('.abshide').click();
			//alert('You need to withdraw a positive number.');
		    return false;
		  }

	   return true;
	 }
 </script>
 
 
 <?php
include_once 'footer.php';
?>