<!DOCTYPE html>
<html>
<head>
      <link rel="stylesheet" type="text/css" href="style.css">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
		<script type="text/javascript" src="js/jquery-1.6.2.js"></script>
		<script type="text/javascript" src="js/jquery.ui.core.js"></script>
		<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="js/jquery.ui.tabs.js"></script>
		<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.js"></script>
		
		<script>
		$(function() {
			$( "#tabs" ).tabs();
		});
		$(document).ready(function() {
			$(".inlinecontent").fancybox({
					'titlePosition'		: 'inside',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none'
					});
					});
		
		$.fx.off = !$.fx.off;
		
		touchMove = function(event) {
		// Prevent scrolling on this element
		event.preventDefault();
		}
		</script>
</head>

<body>

<div id="bar">

	<div id="topbar">
	
	<div id="cash">
		<p class="cashamount">$27,838</p>
		<p class="timeleft"><strong>+1,200</strong> in 24:32</p>
	</div>
	
	<div id="experience">
		<div id="percentbar">
			<img src="img/percentimage3.png"
				 alt="9.5%" 
				 height="15"
	  			 class="percentImage" 
	 			 style="background-position: 30% 0pt;" />
		</div>
		<p class="experiencelabel">Exp: <strong>78</strong>/146</p>
	</div>
	
	<div id="level">
		<a href="#">Lvl<br />
		<em>12</em>
		</a>
	</div>
	
	</div>
		
	<div id="bottombar">

		<div id="health">
			<p id="healthbar"><strong>78</strong>/100<br />
			<em>Health</em>
			</p>
		</div>
		
		<div id="energy">
			<p id="energybar"><strong>22</strong>/30<br />
			<em>Energy</em></p>
		</div>
	
		<div id="stamina">
			<p id="staminabar"><strong>4</strong>/5<br />
			<em>Stamina</em></p>
		</div>
	
	</div>
	
</div>

	<div id="content" class="equipment">
	<div class="realestatetop">
		<h2>Real Estate</h2>
		<p>Income: <span class="income">$97,250</span> / Upkeep: <span class="upkeep">$12,250</span></p>
	</div>
	
	<div class="cashflowinfo">
		<p>Cash flow: <strong>$85,000</strong> every 60 minutes</p>
	</div>
	<p class="unlock2"><span>Unlock more Real Estate at level 13</span></p>
	
	<div class="pieceequipment realestate">
		<div class="equipmentimage">
			<img src="img/supplydepot.png" />
			<h5>Income: <strong>$500</strong></h5>
			
		</div>
		<div class="equipmentinfo">
			<h4>Supply Depot</h4>
			
			<ul>
				<li class="buy">
				$12,000<br />
				<a href="#">Buy</a>
				</li>
				<li class="sell">
				Owned: 1<br />
				<a href="#">Sell</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="pieceequipment realestate">
		<div class="equipmentimage">
			<img src="img/bunker.png" />
			<h5>Income: <strong>$1000</strong></h5>
			
		</div>
		<div class="equipmentinfo">
			<h4>Bunker</h4>
			
			<ul>
				<li class="buy">
				$28,000<br />
				<a href="#">Buy</a>
				</li>
				<li class="sell">
				Owned: 1<br />
				<a href="#">Sell</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="pieceequipment realestate lockedup">
		<div class="equipmentimage">
			<img src="img/biglock.png" />
			
		</div>
		<div class="equipmentinfo">
			<h4>Underground Store</h4>
			<h5>Unlock at Level 15</h5>
				<ul>
				<li class="inactive">
				<p class="inactivebutton">Buy</p>
				</li>
				<li class="inactive">
				<p class="inactivebutton">Sell</p>
				</li>
			</ul>
		</div>
	</div>
	

</div>

</body>