<!--<b>LEVEL UP!</b> <br>
Congratulations! You are now level <?php echo $newLevel ?>. <br>
You gained <?php echo $skillPointsGained ?> skill points. <br>

<form action="<?php $_SERVER['DOCUMENT_ROOT'] ?>/skills.php" method="GET">
<input type="submit" value="Spend Skill Points"/>
</form>-->

<div id="notification">
		<div class="successmission">
		<h2 class="congrats">Congratulations!</h2>
			<div class="skillpoints">
				<h4>You leveled up to  <?php echo $newLevel ?>!</h4>
                                <?php if($skillPointsGained > 0) { ?>
                                    <a class="blackbutton skillpointbutton" href="<?php echo $serverRoot.'profile.php?selectedtab=skillpoints'; ?>">Spend Skill Points &#187;</a>
                                <?php } ?>
				<ul  style="padding:15px;">
                                    <?php if($skillPointsGained > 0){ ?>
    					<li><h3>You gained <?php echo $skillPointsGained ?> skill points.</h3></li>
                                    <?php } ?>
					<li><em>+</em> <?php echo $_SESSION['expGained']  ?>  Experience</li>
				</ul>
		</div>
	</div>

