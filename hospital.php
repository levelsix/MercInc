<?php
include_once("topmenu.php");
include_once("./classes/Utils.php");
$hospitalMinLevel = 5;
if(isset($_SESSION['levelError']))
{
    echo $_SESSION['levelError'];
    unset($_SESSION['levelError']);
    exit;
}
else if ($playerLevel<$hospitalMinLevel)
{
    $tooLowLevelString = '<div class="natsays">
	<h2>Hospital</h2>
	<p><strong>Natalie says:</strong></p>
	<p>"You need more experience before you can heal from Hospital."</p>
	<p>"Come back at LVL '.$hospitalMinLevel.'"</p>
	<a href="'.$serverRoot.'choosemission.php" class="blackyellowbutton notready">Missions</a>
    </div>';
    echo $tooLowLevelString;
    exit;
} 
$user = User::getUser($_SESSION['userID']);
$hospital_html = '';
$healCost = 0;
if(isset ($_SESSION['heal_cost']) )
{
  $healCost = $_SESSION['heal_cost'];
}

if(isset($_GET['heal']))
 {
    if($_GET['heal']=='true')
    {
         $hospital_html = '<div class="hospitaltop"><h2>Hospital</h2><p>Your Health is successfully filled.<p></div>';
       
    }
    else if($_GET['heal']=='false')
    {
        $hospital_html = '<div class="notificationbox notifybox needcash">
					<h3>You need $'.$healCost.' cash in the bank !</h3>
					<p>You can only pay the doctor with money in the bank.<br> You only have <strong>$'.$user->getBankBalance().' </strong> in the bank.</p>
					<a class="blackbutton visitbank" href="#">Go to the Bank</a>
					</div>';
        
    }
}    
else
{
    if ($user->getHealth() >= $user->getHealthMax()) 
    {

		$hospital_html = '<div class="hospitaltop">
                <h2>Hospital</h2>
                <p>You are already at full health<p>
               </div>';
    }
    else {
	$healCost =calculateHealCost($user -> getHealth() ,$user -> getHealthMax() , $user ->getLevel() );
            if( $user->getBankBalance() > $healCost )
            {
            
            $hospital_html = "<div class='hospitaltop'>
                <h2>Hospital</h2>
                <p>Pay the doctor to regain full health. Doctors must be paid with money from the bank.<p>
                <p>You currently have <strong>$".$user->getBankBalance()." </strong> in the bank.</p></div><a href='backend/healuser.php?healCost=".$healCost."' class='healinhospital'><span class='healer'>Heal for $".$healCost."</span></a>";
            
            }
			else
            {
	          $hospital_html = '<div class="notificationbox notifybox needcash">
					<h3>You need $'.$healCost.' cash in the bank!</h3>
					<p>You can only pay the doctor with money in the bank.<br> You only have <strong>'.$user->getBankBalance().' $</strong> in the bank.</p>
					<a class="blackbutton visitbank" href="'.$serverRoot.'bank.php">Go to the Bank</a>
					</div>';
            }
    }
}
unset ($_SESSION['healCost']);
?>

<?php echo $hospital_html; ?>

<?php
    include_once 'footer.php';
?>
            