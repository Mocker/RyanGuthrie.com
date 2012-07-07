
<h2>LEAGUE <?php echo $comp['name']; ?></h2>

<!-- COMP INFO
	<?php print_r($comp); ?>

-->

<div style="width: 100%; height: 100px; border-bottom: 2px solid black; border-top: 2px solid black;">
<div style="float: left; width: 50%; height: 100px; background-color: #ccc;">
	<b>League Host:</b>  <?php echo $comp['host_name']; ?><br>
	<b># of Competitors:</b> <?php echo $comp['max_competitors']; ?><br>
	<b>League Type: </b><?php echo $comp['type']; ?><br>
	<b>League Format: </b><?php echo $comp['format']; ?><br>

</div>
<div style="float: left; width: 50%;">
<style>
	ul.vertmenu li { width: 100%; float: left; }
</style>
<ul class="vertmenu" style="position: relative; float: left; width: 200px;">
	<li>Championship ON</li>
	<li>Point System: </li>
	<li>Match Type: <?php echo $comp['match_type']; ?></li>
	<li>
	<?php if(isset($entry)){ 
		if(!$entry['Competitor']['approved']){
			print "<b>Your entry is waiting for approval</b>";
		}
		else { print "<b>You have joined this League</b>"; }
	} else { ?>
	<a href="#" onClick='$("#modalJoinLeague").modal().show();'>Join</a>
	<?php } ?>
	</li>
	<script type="text/javascript">
		function joinLeague(){
			var url = '/getxp/<?php echo $sname; ?>/league/join/<?php echo $comp['id']; ?>';
			//var doIt = confirm('Join <?php echo $comp['name']; ?>');
			var doIt = true;
			if(doIt){
				//submit join request
				$('span.status_msg').html('<b>Submitting Request..</b><br>');
				console.log('Join via url '+url);
				 $.ajax({
					url: url,
					type: 'get',
					dataType: 'text',
					success: function(data){
							$('span.status_msg').html(data+'<br>');
					}
				});
				
			}

		}
	</script>
</ul>
<div id='modalJoinLeague' class='modal'>
	<h4>Compete in <?php echo $comp['name']; ?></h4>
	<?php if($comp['fee']){
		print '<i>Entry fee is $'.$comp['fee'].'</i><br>';
	}
	else { print '<i>No entry fee</i><br>'; }
	?>
	<span class='status_msg'>
	<a class='btn' onClick='joinLeague();'>Register</a></span>
	<a class='btn' onClick='$("#modalJoinLeague").modal().hide();'>Cancel</a>
</div>


</div>
</div>


<ul class="tabs" data-tabs="tabs">
	<li><a href="#details" class="active">Details</a></li>
	<li><a href="#bulletins">Bulletin</a></li>
	<li><a href="#comments">Comments</a></li>
	<li><a href="#competitors">Competitors</a></li>
	<li><a href="#standings">Standings</a></li>
	<li><a href="#upcoming">Upcoming</a></li>
	<li><a href="#matches">Matches</a></li>
	<li><a href="#sponsors">Sponsors</a></li>
	
</ul>
<div class="tab-content" id="prof_tab" style="width: 100%; background: #fff; min-height: 200px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; max-height: 650px; overflow-y: auto;">
	<div id="blank" class="tab-pane">PLACEHOLDER TAB</div>
	<div id="details" class="tab-pane active">
		<h2>About <?php print $comp['name']; ?></h2>
		
		<b>Location:</b><br>
		<?php if(isset($comp['center'])){ 
			print "<b>Venue: <a href='/getxp/$sname/centers/".
				$comp['center']['id']."'>".$comp['center']['name']." </b><br>\n"; }
			if(isset($comp['network_id'])){ 
			print "<b>Network: <a href='/getxp/$sname/network/".
				$comp['net']['id']."'>".$comp['net']['name']." </b><br>\n"; }
		?>

		<h4>Details</h4>
		<b>Registration:</b> <?php echo $comp['registration']; ?><br>
		<b>Type:</b> <?php echo $comp['type']; ?><br>
		<b>Max Competitors:</b> <?php echo $comp['max_competitors']; ?><br>
		<b>Format:</b> <?php echo $comp['format']; ?><br>
		<b>Starts:</b> <?php echo $comp['start']; ?><br>
		<b>Ends:</b> <?php echo $comp['end']; ?><br>
		
		
		
	</div>

	<div id="members" class="tab-pane">Member List:<br>
		<ul>
		</ul>
	</div>

	<div id="bulletins" class="tab-pane">Bulletins</div>

	<div class="tab-pane" id="reviews">
		<script type="text/javascript">
			function sendComment(frmObj){
				alert(frmObj.comment.value);
			}
		</script>
		<br>
		<center>
		<form id="frmComment" onSubmit="return false;">
			<input class="span4" type="text" name="comment" placeholder="Type Comment here">
			<button class="btn primary" onClick="sendComment(this.parentNode);">Submit</button>
		</form>
		</center>
		<ul style="width: 90%; list-style-type: square;">
			<li> 
				<div style="float: right; width: 70%;">
				<blockquote>blahblaha asfs sdfsdfsd afdsf sdfsdfsd fsdfsdfd sdfsdfdsfsd
				sfdsfs sdgdgd fdgdfgfd </blockquote>
				<ul style="list-style-type: none; width: 100%;">
					<li style=" float: left;"><b>Comment</b></li>
					<li style=" float: left;"><b>Like</b></li>
					<li style=" float: left;"><b>Dislike</b></li>
					<li style=" float: left;"><b>Remove</b></li>
				</ul>
				</div>
				<div style="width: 30%;">
					<b>Bob Smith wrote:</b>
				</div>
			</li>

		</ul>

	</div>


	

	<div class="tab-pane" id="reserve">Reserve a Court</div>

	<div class="tab-pane" id="halloffame">
		<b><?php echo $prof['Network']['name']; ?> Hall of Fame</b>
	 </div>

	<div class="tab-pane" id="announcements">Announcements</div>

	<div class="tab-pane" id="competitions">Competitions</div>

	

</div>







