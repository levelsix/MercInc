<?php
include_once("topmenu.php");
include_once 'classes/User.php';
include_once 'classes/Utils.php';
include_once 'properties/constants.php';
include_once("topmenu.php");

if(isset($_SESSION['missionsuccess'])){
    //unset all session variable that use to display mission notification
    unset($_SESSION['newLevel']);
    unset($_SESSION['skillPointsGained']);
    unset($_SESSION['levelUp']);
    unset($_SESSION['gainedLootItemID']);
    unset($_SESSION['itemsLost']);
    unset($_SESSION['baseCashGained']);
    unset($_SESSION['baseExpGained']);
    unset($_SESSION['energyLost']);
    unset($_SESSION['missionsuccess']);
}

if(isset($_GET['firstbattle']))
{ 
     $exp_points ;
	if($user->getLevel()<4 ){
    	$exp_points = $playerNextExperiencePoints - $playerExp;
	}else{
		$exp_points = rand(3, 8);
	}
    $cash = rand(100, 200);
    $looser_demage = 15;
    $winner_demage = 5;
	$_SESSION['expGained'] = $exp_points;
	$user -> updateFirstBattleData($_SESSION['userID'], $cash, $winner_demage*-1, $exp_points,1,REQUIRED_EXPERIENCE_POINTS_FIRST_BATTLE);
	header("location: battle.php?looser_demage={$looser_demage}&winner_demage={$winner_demage}&cash_lost={$cash}&attack_type=normal&first_battle=true");
    exit;
}


?>

<div class="natsays">
<h2>Attack</h2>
<p>The next morning, you wake up to this voicemail from Natalie: "We expect big things out of you, Soldier, here's your first battle."</p>
</div>
	<table class="pvp">
	<tr>
		<th>Opponent</th>
		<th class="armysize">Army Size</th>
		<th></th>
	</tr>
        <tr>
		<td class="oppname">
			<h5><a href="#"><?php echo DUMMY_SOLDIER_NAME_FOR_BATTLE ?></a></h5>
			<p>LVL 3</p>
		</td>
		
		<td class="oppsize"><p>1</p></td>
		<td class="oppattack"><a href="<?php echo $serverRoot; ?>firstbattle.php?firstbattle=true" class="blackbutton">Attack</a></td>
	</tr>
        </table>
    <?php
    include_once 'footer.php';
?>
