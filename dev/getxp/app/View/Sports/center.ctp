
<?php
$this->Html->css(array('jquery.ui.datepicker'),'stylesheet', array('inline' => false ) );
//'jquery-ui-1.8.11.custom.min',
$this->Html->script(array('jquery-ui-1.8.11.custom.min','jquery.ui.core','jquery.ui.widget','jquery.ui.datepicker'),array('inline'=>false));
?>

<div id="ChatArea" style='position: relative; left: -322px; background-color: none;'>
	<div class="IMButton" style="background-color: none;">
		<a onClick='toggleChat();'><?php echo $this->Html->image('chat.png'); ?></a></div>
	<?php echo $this->element('chatroom'); ?>
</div>

<style>
	#ui-datepicker-div { background-color: white; }
</style>

<h2><?php echo $prof['Center']['name']; ?></h2>

<div style="width: 100%; height: 100px; border-bottom: 2px solid black; border-top: 2px solid black;">
<div style="float: left; width: 100px; height: 100px; background-color: #ccc;">
	<?php if($prof['Center']['profile_pic']){ print "<img src='/getxp/img/profiles/".$prof['Center']['profile_pic']."' width=100 height=100>"; } else { print "&nbsp;"; } ?>
</div>
<div style="float: left; width: 300px;">
<style>
	ul.vertmenu li { width: 100px; float: left; }
</style>
<ul class="vertmenu" style="position: relative; float: left; width: 120px;">
	<li><a href='/getxp/<?php echo $sname; ?>/photos/center/<?php echo $prof['Center']['id']; ?>'>Photo Album</a></li>
	<li><a href='/getxp/<?php echo $sname; ?>/videos/center/<?php echo $prof['Center']['id']; ?>'>Video Album</a></li>
	<li><a href="#">Message</a></li>
	<li><a href="#">Become a Fan</a></li>
	<?php if($prof['Center']['owner']==$userinfo['id']){ ?>
		<li><a href='/getxp/<?php echo $sname; ?>/centers/edit/<?php echo $prof['Center']['id']; ?>'><b>Manage Center</b></a></li>
	<?php } else { ?>
	<li><a href="#">Join Membership</a></li>
	<?php } ?>
</ul>

<b>Status Update:</b><br>
<?php echo $prof['Center']['status']; ?>

</div>
</div>


<ul class="tabs" data-tabs="tabs">
	<li class="active"><a href="#about">About</a></li>
	<li><a href="#bulletins" onClick="loadBulletins();">Bulletin</a></li>
	<li><a href="#reviews" onClick="loadReviews();">Reviews</a></li>
	<li><a href="#proshop">Pro Shop</a></li>
	<li><a href="#reserve">Reserve</a></li>
	<li><a href="#halloffame">Hall of Fame</a></li>
	<li><a href="#competitions" onClick="loadComps();">Competitions</a></li>
</ul>
<script type="text/javascript">
	function loadComps(){
		//ajax request to load list of leagues for this center
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/league/search',
			dataType: 'text',
			type: 'POST',
			data: 'center_id=<?php echo $prof['Center']['id']; ?>',
			success: function(data){
				console.log("leagues loaded");
				console.log(data);
				$('#league_list').html(data);	
			}
		});
		//ajax request to load list of tournaments for this center
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/tournament/ajax',
			dataType: 'text',
			type: 'POST',
			data: 'action=list&return_type=html&center_id=<?php echo $prof['Center']['id']; ?>',
			success: function(data){
				console.log("tournaments loaded");
				console.log(data);
				$('#tournament_list').html(data);	
			}
		});
	}
</script>
<div class="tab-content" id="prof_tab" style="width: 100%; background: #fff; min-height: 200px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; max-height: 650px; overflow-y: auto;">
	<div id="blank" class="tab-pane">PLACEHOLDER TAB</div>
	<div id="about" class="tab-pane active">
		<h2>About <?php print $prof['Center']['name']; ?></h2>
		<blockquote><? echo $prof['Center']['description']; ?></blockquote>
		<b>Location:</b><br>
		<?php 
		print $prof['Center']['street']."<br>".$prof['Center']['city'].", ".$prof['Center']['state'];
		print "<br>".$prof['Center']['zip']."<br><b>Phone: </b>".$prof['Center']['phone'];
		?>
		
		
	</div>

	<div id="bulletins" class="tab-pane">
		<script type="text/javascript">
		function loadBulletins(){
				var cID = '<?php echo $prof['Center']['id']; ?>';
				$.ajax({
					url: '/getxp/<?php echo $sname; ?>/ajax/bulcen'+cID,
					type: 'get',
					dataType: 'json',
					success: function(data){
						console.log("Bulletins retrieved"); console.log(data);
						var bulStr = '<ul>';
						for(var i=0;i<data.length;i+=1){
							var bul = data[i];
							bul['Bulletin']['content'].replace("\n","<br>");
							bulStr += '<li><i>'+bul['Bulletin']['posted']+'</i><b> '
								+bul['Bulletin']['title']+'</b><br><blockquote>'
								+bul['Bulletin']['content']+'</blockquote></li>';
						}
						bulStr+='</ul>';
						$('#bulletinList').html(bulStr);
					}
				})
			}
		</script>
		<h3>Bulletins</h3>
		<div id='bulletinList'>

		</div>

	</div>

	<div class="tab-pane" id="reviews">
		<script type="text/javascript">
			function sendComment(frmObj){
				//alert(frmObj.comment.value);
				var url = '/getxp/<?php echo $sname; ?>/ajax/comcen<?php echo $prof['Center']['id']; ?>';
				var datstr = "data[Comment][comment]="+$('#commentBox').val();
				$('#commentBox').prop('disabled',true);
				console.log("Sending comment to "+url+" data:"+datstr);

				$.ajax({
				url: url,
				data: datstr,
				type: 'POST',
				success: function(data){
					console.log("Sent review");
					console.log(data);
					loadReviews();
					alert(data);
					$('#commentBox').prop('disabled',false);
					$('#commentBox').val('');	
				},
				error: function(err){
					console.log("error: "); console.log(err);
					alert('Error sending review');
					$('#commentBox').prop('disabled',false);
					$('#commentBox').val('');	
				}
			});
			}
			function loadReviews(){
			$('#allcomments').html('Loading Reviews..');
			$.ajax({
				url: '/getxp/<?php echo $sname; ?>/ajax/comcen<?php echo $prof['Center']['id']; ?>',
				type: 'get',
				dataType: 'json',
				success: function(data){
					rawCom = data;
					console.log(data[0]);
					
					var htmlStr = '<ul style="width:90%; list-style-type: square;">';
					//arrange comments into array by reply level
					var comTree = new Object();
					comTree['top'] = new Array();
					for(var i=0;i<rawCom.length;i+=1){
						var com = rawCom[i];
						var rID = com['Comment']['reply_id'];
						if(rID){
							if(!comTree[rID]){ comTree[rID] = new Array(); }
							comTree[rID].push(com);
						}
						else {
							comTree['top'].push(com);
						}
					}
					//generate display html starting with 'top' level comments
					for(var i=0;i<comTree['top'].length;i+=1){
						var com = comTree['top'][i];
						htmlStr += "\n<li style='margin-bottom: 10px;'>\n<div style='float: right; width: 70%;'>\n";
						htmlStr += "<blockquote>"+com['Comment']['comment']+"</blockquote>\n";
						htmlStr += "</div><div style='width:30%;'><b>"+com['Comment']['poster_name']+" wrote <i>("+com['Comment']['date']+")</i>:</b></div></li>\n";
					}
					htmlStr += "</ul>";
					$('#allcomments').html(htmlStr);	


				},
				error: function(errstr){
					console.log("ERROR FETCHING REVIEWS"); console.log(errstr);
				}
			});
		}
		</script>
		<br>
		<center>
		<?php if(!isset($isself) || !$isself){ ?>
		<form id="frmComment" onSubmit="return false;">
			<input class="span4" type="text"  id="commentBox" name="commentBox" placeholder="Type Review here">
			<button class="btn primary" onClick="sendComment(this.parentNode);">Submit</button>
		</form>
		<?php } ?>;
		</center>
		<div id="allcomments">
			loading reviews..
		</div>

	</div>


	<div class="tab-pane" id="proshop">
		<h4>Pro Shop</h4>

	</div>

	<div class="tab-pane" id="reserve">
		<style>
			#dvCourtTimes {
				width: 95%; margin-left: auto; margin-right: auto;
				min-height: 200px;
				border: 1px solid gray;
				background-color: #ddddff; 
			}
		</style>
		<script type="text/javascript">
			var courts = new Array('');
			<?php if(isset($courts)){
				foreach($courts as $court){
					print "courts[".$court['Court']['id']."] = \"".
					$court['Court']['description']."\";\n";
				}
			} ?>
			var curCourtID = null;
			function loadCourtTimes(slObj){
				//load calendar for selected court
				if(slObj.selectedIndex == 0){ 
					$('#dvCourtDescription').html(' ');
					$('#dvCourtTimes').fadeOut();
					return; }
				var centerID = <?php echo $prof['Center']['id'] ?>;

				console.log("Court selected: "+slObj.options[slObj.selectedIndex].value);
				console.log(slObj);
				curCourtID = slObj.options[slObj.selectedIndex].value;
				loadCourtCal(slObj.options[slObj.selectedIndex].value);
			}
			function loadCourtCal(cID){
				loadCalendarDay();
				$('#dvCourtDescription').html(courts[cID]);
				$('#dvCourtTimes').fadeIn();

			}
			$(document).ready(function(){
				$('#startDate').bind('change',loadCalendarDay);
				$('#startDate').datepicker();
				//$('#endDate').datepicker();
			});
			var testDate;
			var reservations = null;
			function loadCalendarDay(){
				
				var rDate = $('#startDate').val();
				if(!rDate || rDate.length < 1){ return; }
				var calDate = new Date(rDate);
				testDate = calDate;
				console.log(calDate);
				console.log("Load calendar for "+rDate);
				//here I'd load via ajax the schedule for the specified day
				var mDateA = rDate.split('/');
				var mDate = mDateA[2]+'-'+mDateA[0]+'-'+mDateA[1];
				$.ajax({
					url: '/getxp/<?php echo $sname; ?>/ajax/fetchReservations',
					type: 'post',
					dataType: 'json',
					data: 'approved=1&center_id=<?php echo $prof['Center']['id']; ?>&court_id='+curCourtID+'&date='+mDate,
					success: function(data){
						reservations = data;
						$('#courtCalendar').html('');

						var calHTML = '<h3>'+calDate.toDateString()+'</h3><table width="400px">'
							+'<tr><th width="60px"></th><th>Activity</th></tr>';
						
						var apts = convertTimes(data);
						for(var curHour=6; curHour<24; curHour+=1){
							var aTimes = checkTimes(curHour,apts);
							var classStr = (curHour%2!=0) ? 'class="odd"' : 'class="even"';
							calHTML += '<tr '+classStr+'><td><b>'+curHour+':00</b></td><td>';

							calHTML += aTimes[0]+'</td></tr>';
							calHTML += '<tr '+classStr+'><td><b>'+curHour+':30</b></td><td>'+aTimes[1]+'</td></tr>';
						}
						calHTML += '</table>';
						$("#courtCalendar").html(calHTML);
					},
					error: function(errstr){
						console.log("ERROR!"); console.log(errstr);
					}
				});	
			}
			function checkTimes(hr,times){
				//check fetched appoints to see if it falls between the current hour and half hour blocks
				var rString = ["Vacant","Vacant"];
				for(var i=0;i<times.length;i+=1){
					if(times[i].start <= hr && times[i].stop > hr){ 
						rString[0] = "<b>Occupied</b>"; }
					if(times[i].start <= hr+0.5 && times[i].stop > hr+0.5){
						rString[1] = "<b>Occupied</b>";
					}
				};
				return rString;

			}
			function convertTimes(times){
				for(var i=0;i<times.length;i+=1){
					var start = times[i]['reservations']['start'];
					var stop = times[i]['reservations']['stop'];
					//console.log(start.substring(10,13));
					var newStart = parseInt(start.substring(11,13))+ ( parseInt(start.substring(14,16))/60); 
					var newStop = parseInt(stop.substring(11,13))+ ( parseInt(stop.substring(14,16))/60);
					times[i]['start'] = newStart;
					times[i]['stop'] = newStop;
				}
				return times;
			}
			function displayMessage(datStr){
				console.log(datStr);
			}
			function sendReservation(frmObj){
				var slVal = $('#selectCourt').val();
				var datStr = 'action=addReservation&center_id=<?php echo $prof['Center']['id']; ?>&court_id='+slVal;
				var frmStr = $(frmObj).serialize();
				datStr += '&'+frmStr;
				datStr += '&date='+escape( $('#startDate').val() );
				//console.log(frmObj);
				console.log(datStr);
				$.ajax({
					url: '/getxp/<?php echo $sname; ?>/ajax/addReservation',
					dataType: 'text',
					type: 'POST',
					data: datStr,
					success: function(data){
						console.log("Reservation sent");
						console.log(data);
						$('#modalStatus > h4').html(data);
						$('#modalStatus').modal().show();	
					}
				});	
			}

		</script>
		<div class="modal" id="modalStatus">
					<h4></h4>
					<a class="btn" onClick='$("#modalStatus").modal().hide();'>Close</a>
				</div>
		<h3>Reserve a Court</h3>
		<form name='frmReserveCourt' id='frmReserveCourt' method=POST onSubmit='return false;'>
		<select name='selectCourt' id='selectCourt' onChange='loadCourtTimes(this);'>
			<option value='0'>No Court Selected</option>
			<?php
			if(isset($courts)){
				foreach($courts as $court){
					print "<option value='".$court['Court']['id']."'>".$court['Court']['name']
					."</option>\n";
				}
			}
			?>
		</select>
		</form>
		<style>
			.layout-grid td.demos {
				background-color: #ddd;
			}
			input.hour {
				width: 40px;
			}
			#dvCourtTimes select {
				width: 60px;
			}
			tr.odd {
				background-color: #ddffdd;
			}
		</style>
		<div id="dvCourtDescription"></div>
		<div id="dvCourtTimes" style='display: none;'>
			<center>
			<?php 
			echo $this->Form->create('Reservation', array(
			'action'=>'docreate',
			'onsubmit'=>'return false;',
			'inputDefaults'=> array(			
				'div'=>false)));
			?>
			Day<input type="text" id="startDate"><br>
			Start: <input class="hour" type=input size=2 placeholder='hr' name='startHour'>:<select name='startHalf'>
			<option value='0'>00</option><option value='30'>30</option></select><select name='startAM'>
			<option value='am'>AM</option><option value='pm'>PM</option></select> 
			-- End: <input class="hour"  type=input size=2 placeholder='hr' name='endHour'>:<select name='endHalf'>
			<option value='0'>00</option><option value='30'>30</option></select><select name='endAM'>
			<option value='am'>AM</option><option value='pm'>PM</option></select>
			<br>
			Comment<br><textarea name="reservationComment" cols=20 rows=4></textarea><br>
			<a id="sendReservBTN" class='btn' onClick='sendReservation(this.parentNode);'>Reserve</a>
			</form>
			</center>
			<!--<input type="text" id="endDate"><br>-->
			</form>
			<style>
				#courtCalendar {
					width: 450px;
					margin: 0 auto;
				}
			</style>
			<h3>Court Schedule:</h3>
			<div id="courtCalendar">

			</div>
		</div>
		<style>
			#courtCalendar tr {
				margin: 0 0;
				padding: 0 0;

			}
			#courtCalendar td {
				height: 12px;
				margin: 0 0;
				padding: 0 0;
				border-bottom: 1px solid black;
			}
		</style>

	</div>

	<div class="tab-pane" id="halloffame">
		<b><?php echo $prof['Center']['name']; ?> Hall of Fame</b>
	 </div>

	<div class="tab-pane" id="announcements">Announcements</div>

	<div class="tab-pane" id="competitions">Competitions<br>
		<h4>Leagues:</h4>
		<div id="league_list">

		</div>
		<form name='frmCreateLeague' action='/getxp/<?php echo $sname; ?>/league/create' METHOD=POST>
			<input type=hidden name='action' value='register'>
			<input type=hidden name='center_id' value='<?php echo $prof['Center']['id']; ?>'>
			<input type=submit value='Create League'>
		</form>
		<hr><h4>Tournaments</h4>
		<div id="tournament_list">

		</div>
		<form name='frmCreateTourn' action='/getxp/<?php echo $sname; ?>/tournament/create' METHOD=POST>
			<input type=hidden name='action' value='register'>
			<input type=hidden name='center_id' value='<?php echo $prof['Center']['id']; ?>'>
			<input type=submit value='Create Tournament'>
		</form>
	</div>

	

</div>







