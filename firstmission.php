<?php
include_once("topmenu.php");
include_once ('classes/Mission.php');

$mission = Mission::getMission(1);
?>

<div class="natsays">
<h2>Missions</h2>
<p>The next morning, you wake up to this voicemail from Natalie: "We expect big things out of you, Soldier, here's your first mission."</p>
</div>
	<div class="mission">
        <table class="topmission">
            <tr>
                <td>
                <h4><?php echo $mission->getName(); ?></h4>
                </td>
                <td>
                <div class="missionrank">
                        <img src="img/percentimage4.png"
                                 alt="9.5%"
                                 height="9"
                                 class="percentImageMissions"
                                 style="background-position: 100% 0pt;" />
                        <p>0% Rank 1</p>
                </div>
                </td>
            </tr>
        </table>
	<div class="missioninfo">
	<ul>
		<li><strong>+ $<?php echo $mission->getMinCashGained().' - $'.$mission->getMaxCashGained(); ?> </strong></li>
		<li>+ <?php echo $mission->getExpGained(); ?> Experience</li>
	</ul>
	<div class="domission">
		<a href="<?php echo $serverRoot; ?>backend/domission.php?missionID=1&currentMissionCity=1&cityID=1&firstmission=true">Do Mission</a>

	</div>
	</div>

	<div class="missionreq">
		<h5>Required <em><?php echo $mission->getEnergyCost(); ?> Energy</em></h5>
	</div>
	</div>
    <?php
    include_once 'footer.php';
?>
