<h4>Tournament: <?php echo $tourn['Tournament']['name']; ?></h4>
<?php if(isset($tourn_message)){
	print "<b>** ".$tourn_message." **</b>"; 
}?>
<hr>
<?php if(isset($location)){ ?><b>Located at: <?php echo $location['name']; ?></b><br><?php } ?>
<b>Starts on </b><?php echo $tourn['Tournament']['starts']; ?><br>
<b>Registration is <?php echo $tourn['Tournament']['registration']; ?></b><br>
<blockquote><?php echo $tourn['Tournament']['description']; ?></blockquote>
<hr>
<div class="modal" id="modalStatus">
					<h4></h4>
					<a class="btn" onClick='$("#modalStatus").modal().hide();'>Close</a>
				</div>
<ul class="tabs" data-tabs="tabs">
	<li class="active"><a href='#tourn_teams'>Competitors</a></li>
	<li><a href='#tourn_matches'>Matches</a></li>
	<?php if($userinfo['id']== $tourn['Tournament']['host_id']){ ?>
	<li><a href='#tourn_manage'>Manage</a></li>
	<?php } ?>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tourn_teams">
		<?php if($tourn['Tournament']['registration']=='open' 
			&& count($competitors_approved)< $tourn['Tournament']['max_teams'] ){ ?>
		<a class='btn' onClick='joinTourn();'>Register for this tournament</a><br>
		<?php } ?>
		<h4>Registered Competitors</h4><hr>
		<ul>
			<?php if(isset($competitors_approved)){
				foreach($competitors_approved as $comp){
				print "<li>";
				if($comp['Competitor']['profile_id']){
					print "<a href='/getxp/$sname/profile/".$comp['Competitor']['profile_id'].
					"'>".$comp['Competitor']['player_name']."</a>";
				} else { print $comp['Competitor']['player_name'] ; }
				print "</li>\n";

			} }?>
		</ul>

	</div>

	<div class="tab-pane" id="tourn_matches">
		<h4>Matches</h4><hr>
		<?php if($tourn["Tournament"]["registration"]!="closed"){ ?>
		This tournament is still open for registration. Match brackets will be generated 
		upon closing registration
		<?php } else {  ?>
			<?php if(isset($matches) && count($matches)>0){ ?>
			<b>Round 1:</b>
			<ul>
			<?php 
				$curRound = 1;

				$curPool = $matches[0]['Match']['pool'];
				foreach($matches as $match){
					if($match['Match']['round']>$curRound){
						$curRound = $match['Match']['round'];
						print "</ul><br><b>Round $curRound</b>\n<ul>";
					}
					if($match['Match']['pool'] && $match['Match']['pool']!=$curPool){
						print "<li><b>Pool ".$match['Match']['pool'].":</b></li>";
						$curPool = $match['Match']['pool'];
					}
					print "<li>".$match["Match"]['p1_name']." vs ".$match['Match']['p2_name'].
						" -  ";
					if(!$match['Match']['winner']){ 
						if(!$match['Match']['time']){
							print "Not scheduled yet";
						}
						else { print "Starts on ".$match['Match']['time']; }
					} else {
						if($match['Match']['winner']==1){
							print "(<b>Winner: ".$match['Match']['p1_name']."</b> ".
								$match['Match']['p1_score']." to ".$match['Match']['p2_score'].
								" )";
						} else {
							print "(<b>Winner: ".$match['Match']['p2_name']."</b> ".
								$match['Match']['p1_score']." to ".$match['Match']['p2_score'].
								" )";
						}
					}
					print "</li>";
				}

			} else { print "<b>NO MATCHES FOUND</b>"; } ?>
		<?php } ?>
	</div>

	<?php if($userinfo['id']== $tourn['Tournament']['host_id']){ ?>

	<div class="tab-pane" id="tourn_manage">
		<h4>Manage Tournament</h4>
		<b>[Registration] [Match Results] [Manage Bracket]</b>
		<hr>
		<?php if($tourn['Tournament']['registration'] != 'closed'){ ?>
		<b>Invite competitor (for invitation only): <input type=text></b><br>
		<b>Pending Competitor Requests:</b><br>
		<ul>
			<li></li>
			<?php 
				if(isset($competitors_pending)){
				foreach($competitors_pending as $comp){
				print "<li>".$comp['Competitor']['player_name']." <a class='btn' onClick='approveComp(".
					$comp['Competitor']['id'].");'>Approve</a></li>\n";

			} }?>
		</ul>
		<b>Manually Add Competitor: </b><form method=POST onSubmit='tournAddComp(this); return false;'><input type=text size=25 name='addCompetitorName' id='addCompetitorName'><input type=submit value='Add'></form>
		<?php } ?>
		<br>
		<b>Approved Competitors: <?php echo count($competitors_approved); ?>/<?php echo $tourn['Tournament']['max_teams']; ?> slots filled</b><br>
			<ul>
				<li></li>
				<?php if(isset($competitors_approved)){
				foreach($competitors_approved as $comp){
				print "<li>".$comp['Competitor']['player_name']."</li>\n";

			} }?>
			</ul>
		<br>
		<?php if($tourn["Tournament"]['registration'] != 'closed'){ ?>
		<form name='frmStartTourn' id='frmStartTourn' method=POST action='/getxp/<?php echo $sname; ?>/tournament/<?php echo $tourn['Tournament']['id']; ?>'>
			<input type='hidden' name='action' value='startTournament'>
			<input type=submit value='Close Registration and Generate Matches'>
		</form>
		
		<?php } ?>
		<hr>
		
		<!--
			MATCH DUMP:
			<?php print_r($matches); ?>
		-->

		<hr>
		<b>Brackets:</b><br>
		<?php if(isset($matches)&&count($matches)>0){ ?>
			<div class="modal" id="modalMatchScore">
				<h4>Set Match Score:</h4>
				<b>Select Winner:</b><br>
				<form id="frmMatchScore">
					<input type=hidden name="MatchScoreID" name="MatchScoreID" id="MatchScoreID">
					<input type=radio name="MatchScoreWinner" id="MatchScoreWinner" value="1">
					<span id="spanMatchScoreP1">Player 1</span> - score: 
					<input type=text name="MatchScoreP1Score"><br> 
					<input type=radio name="MatchScoreWinner" id="MatchScoreWinner" value="2">
					<span id="spanMatchScoreP2">Player 2</span> - score: 
					<input type=text name="MatchScoreP2Score"><br> 
					<input type=button value="Submit Scores" onClick="setMatchScore(this.parentNode);"> <input type=button value="Cancel" onClick="$('#modalMatchScore').modal().hide();">
				</form>
			</div>
			<script type="text/javascript">
				function showModalScore(mID,p1Name,p2Name){
					$('#spanMatchScoreP1').html(p1Name);
					$('#spanMatchScoreP2').html(p2Name);
					$('#MatchScoreID').val(mID);
					$('#modalMatchScore').modal().show();
				}
				function approveComp(compID){
					var datStr = 'action=approveCompetitor&competitor_id='+compID;
					$.ajax({
					url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
					dataType: 'text',
					type: 'POST',
					data: datStr,
					success: function(data){
						console.log("Competitor Approved");
						console.log(data);
						$('#modalStatus > h4').html(data);	
						$('#modalStatus').modal().show();
						if(data=='Competitor Approved'){
							window.location.reload();
						}
					}
					});
				}
				function joinTourn(){
					//send request to join tournament
					var datStr = 'action=joinTourn&tourn_id=<?php echo $tourn['Tournament']['id']; ?>';
					$.ajax({
					url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
					dataType: 'text',
					type: 'POST',
					data: datStr,
					success: function(data){
						console.log("Tournament joined");
						console.log(data);
						$('#modalStatus > h4').html(data);	
						$('#modalStatus').modal().show();
						if(data=='Score updated'){
							window.location.reload();
						}
					}
					});
					
				}
				function setMatchScore(frmObj){
					//submit new score via ajax and reload page
					//console.log("Match score ID "+$(frmObj.MatchScoreID).val());
					var datStr='action=setMatchScore&match_id='+$(frmObj.MatchScoreID).val() ;
					datStr += '&winner='+$(frmObj.MatchScoreWinner).val();
					datStr += '&score1='+$(frmObj.MatchScoreP1Score).val();
					datStr += '&score2='+$(frmObj.MatchScoreP2Score).val();
					//console.log(datStr);
					$.ajax({
					url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
					dataType: 'text',
					type: 'POST',
					data: datStr,
					success: function(data){
						console.log("Score has been set");
						console.log(data);
						$('#modalStatus > h4').html(data);	
						$('#modalStatus').modal().show();
						if(data=='Score updated'){
							window.location.reload();
						}
					}
					});
					$('#modalMatchScore').modal().hide();
				}

				function matchNewRound(rID){
					var datStr = 'action=newRound&tourn_id=<?php echo $tourn['Tournament']['id']; ?>&round_id='+rID;
					$.ajax({
					url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
					dataType: 'text',
					type: 'POST',
					data: datStr,
					success: function(data){
						console.log("Round generated");
						console.log(data);
						$('#modalStatus > h4').html(data);	
						$('#modalStatus').modal().show();
						if(data=='Score updated'){
							//window.location.reload();
						}
					}
					});
					//$('#spGenerateRound').html('<b>Generating matches for Round '+rID+'</b><br>');
					
				}

			</script>
			<b>Round 1:</b>
			<ul>
			<?php 
				$curRound = 1;
				$matchesDone = 1;
				$matchesInRound = 0; //how many matches in current round - used to determine winner or next round
				if(count($matches)> 0){ $curPool = $matches[0]['Match']['pool'];}
				foreach($matches as $match){
					if($match['Match']['round']>$curRound){
						$curRound = $match['Match']['round'];
						print "</ul><br>";
						$matchesInRound = 0;
						print "<b>Round $curRound</b>\n<ul>";
					}
					if($match['Match']['pool'] && $match['Match']['pool']!=$curPool){
						print "<li>Pool ".$match['Match']['pool']."</li>";
						$curPool = $match['Match']['pool'];
					}
					print "<li>".$match["Match"]['p1_name']." vs ".$match['Match']['p2_name'].
						" -  ";
					if(!isset($match['Match']['winner']) || !$match['Match']['winner']){ 
						$matchesDone = 0;
						if(!isset($match['Match']['time']) || !$match['Match']['time']){
							print "Not scheduled yet <a onClick='' class='btn'>set time</a>";
						}
						else { print "Starts on ".$match['Match']['time']; }
						print " <a onClick='showModalScore(".$match['Match']['id'].
						",\"".$match['Match']['p1_name']."\",\"".$match['Match']['p2_name'].
						"\");' class='btn'>Set Score</a> ";
					} else {
						if($match['Match']['winner']==1){
							print "(<b>Winner: ".$match['Match']['p1_name']."</b> ".
								$match['Match']['p1_score']." to ".$match['Match']['p2_score'].
								" )";
						} else {
							print "(<b>Winner: ".$match['Match']['p2_name']."</b> ".
								$match['Match']['p1_score']." to ".$match['Match']['p2_score'].
								" )";
						}
					}
					print "</li>";
					$matchesInRound += 1;
				}
				if($matchesDone){ //if all matches in previous round are complete, generate new round
						if($matchesInRound==1){ print "<br>Tournament Complete!<br>\n"; }
						else {
							print "<br><span id='spGenerateRound'><b>Round ".$curRound." complete - <a class='btn' onClick='matchNewRound(".
							($curRound+1).");'>Generate Next Round</a></span><br>\n";
						}
				}

			} else { ?>
		<i>Registration still ongoing - no matches have been generated</i><br>
		<?php } ?>
	<script type='text/javascript'>
		//tournament management functions
		var urlTourn = '/getxp/<?php echo $sname; ?>/tournament/ajax';
		var tournID = <?php echo $tourn["Tournament"]["id"]; ?> ;
		 
		function tournAddComp(frmObj){
			//manually add competitor with specified name
			var compName = frmObj.addCompetitorName.value ;
			var datStr = 'action=addCompetitor&tournament_id='+tournID+'&'+$(frmObj).serialize();
			$.ajax({
			url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
			dataType: 'text',
			type: 'POST',
			data: datStr,
			success: function(data){
				console.log("Competitor has been added");
				console.log(data);
				$('#modalStatus > h4').html(data);	
				$('#modalStatus').modal().show();
			}
		});

		}
		function tournApproveComp(compID){
			//approve competitor
			var datStr = 'action=approveCompetitor&tournament_id='+tournID+'&competitor_id='+compID;
			$.ajax({
			url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
			dataType: 'text',
			type: 'POST',
			data: datStr,
			success: function(data){
				console.log("Competitor approved");
				console.log(data);
				$('#modalStatus > h4').html(data);	
				$('#modalStatus').modal().show();
			}
		});
		}
		function tournDenyComp(compID){
			//deny competitor request
			var datStr = 'action=denyCompetitor&tournament_id='+tournID+'&competitor_id='+compID;
			$.ajax({
			url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
			dataType: 'text',
			type: 'POST',
			data: datStr,
			success: function(data){
				console.log("Competitor Denied");
				console.log(data);
				$('#modalStatus > h4').html(data);	
				$('#modalStatus').modal().show();	
			}
		});
		}
		function tournStart(){
			//start tournament- close registration, generate brackets etc
			//actually this needs to redirect so no ajax
		}

	
	</script>
	</div>
	<?php } ?>


</div>
