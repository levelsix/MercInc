<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<script language="javascript">
function playTutorial()
{    
    <?php if(isset($_GET['android'])){ ?>
             window.Level6android.startGame();
    <?php } else { ?>
        window.location = "level6://develop.com?call=gonext:&param=1";
    <?php } ?>
	//window.location.href = url;
}
		
</script>

<style type="text/css">
        *{
            -webkit-touch-callout: none;
            // Disable selection/Copy of UIWebView
            -webkit-user-select: none;
        }
    </style>
</head>

<body>
	<div class="tutorialheader">
		<h1>Mercenaries, Inc.</h1>
	</div>
	<div class="natsays">
	<h2>How to Play</h2>
	<p class="tutorial">You wake up. You don't know where you are or how you got there. You see a voicemail left on your phone and you listen to it.</p>
	
	<p class="tutorial">"Hey it's Natalie. Look, I don't have any time to explain who I am, but you have to listen to me. Get to the training room ASAP. I left the address in your pocket."</p>
	</div>
	<dl class="tutorial">
	  <dt>Missions</dt>
	    <dd>Complete for cash and experience.</dd>
	  <dt>Equipment</dt>
	    <dd>Buy to become stronger.</dd>
	   <dt>Attack</dt>
	     <dd>Crush your rivals to become the top agency.</dd>
	   <dt>Real Estate</dt>
	     <dd>Purchase to generate income.</dd>
	</dl>
	<div class="continue">
		<a class="blackyellowbutton continuebtn" href="#" onclick="playTutorial()">Continue to game &#187;</a>
	</div>
	
	
</body>