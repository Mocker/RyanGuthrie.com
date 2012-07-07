<?php
$this->Html->css(array('jquery.ui.datepicker'),'stylesheet', array('inline' => false ) );
$this->Html->script(array('jquery-ui-1.8.11.custom.min','jquery.ui.core','jquery.ui.widget','jquery.ui.datepicker'),array('inline'=>false));
?>


<h2>Edit <?php echo $prof['Center']['name']; ?></h2>


<div style="width: 100%; height: 100px; border-bottom: 2px solid black; border-top: 2px solid black;">
<div style="float: left; width: 100px; height: 100px; background-color: #ccc;"><?php if($prof['Center']['profile_pic']){ print "<img src='/getxp/img/profiles/".$prof['Center']['profile_pic']."' width=100 height=100>"; } else { print "&nbsp;"; } ?></div>
<div style="float: left; width: 300px;">
<style>
	ul.vertmenu li { width: 100px; float: left; }
</style>
<ul class="vertmenu" style="position: relative; float: left; width: 120px;">
	<li><a href='/getxp/<?php echo $sname; ?>/photos/center/<?php echo $prof['Center']['id']; ?>'>Photo Album</a></li>
	<li><a href='/getxp/<?php echo $sname; ?>/videos/center/<?php echo $prof['Center']['id']; ?>'>Video Album</a></li>
	<li><a href="#">Message</a></li>
	<li><a href="#">Become a Fan</a></li>
	<li><a href="#">Join Membership</a></li>
</ul>

<b>Status Update:</b><br>
<?php echo $prof['Center']['status']; ?>

</div>
</div>


<ul class="tabs" data-tabs="tabs">
	<li class="active"><a href="#about">About</a></li>
	<li><a href="#bulletins" onClick="loadBulletins();">Bulletin</a></li>
	<li><a href="#reviews">Reviews</a></li>
	<li><a href="#proshop">Pro Shop</a></li>
	<li><a href="#reserve" onClick="loadReservations();">Reservations</a></li>
	<li><a href="#halloffame">Hall of Fame</a></li>
	<li><a href="#competitions" onClick="loadComps();">Competitions</a></li>
</ul>
<script type="text/javascript">
	function loadComps(){
		//ajax request to load list of competitions for this center
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/league/search',
			dataType: 'text',
			type: 'POST',
			data: 'center_id=<?php echo $prof['Center']['id']; ?>',
			success: function(data){
				console.log("Competitions loaded");
				console.log(data);
				$('#competition_list').html(data);	
			}
		});
	}
	var rawReserve; var reservLoading = false;
	function loadReservations(){
		//ajax request to load list of courts and reservation requests
		if(reservLoading){ return; }
		reservLoading = true;
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/centers/update',
			dataType: 'json',
			type: 'POST',
			data: 'center_id=<?php echo $prof['Center']['id']; ?>&action=get_reservations',
			success: function(data){
				console.log("Reservations loaded");
				console.log(data);
				rawReserve = data;
				//$('#reserve_requests_list').html(data);
				var rshtml = '<ul id="reservation_ul">';
				for(var i=0;i<data.length;i+=1){
					var reserv = data[i];
					console.log(reserv);
					var sdArray = reserv['Reservation']['start'].split(' '); //convert mysql date to datepicker
					sdArray = sdArray[0].split('-');
					var rDate = sdArray[1]+'/'+sdArray[2]+'/'+sdArray[0];
					rshtml+='<li><a onClick="showCalendar(\''+rDate+'\','+reserv['Reservation']['court_id']+');">'
						+reserv['Reservation']['start']+" to "+reserv['Reservation']['stop']+"</a> <b>Court "+reserv['Reservation']['court_id']+"</b>";
					rshtml+= '<a class="btn" style="margin-left: 15px;" onClick="approveReservation(\''
						+reserv['Reservation']['id']+'\');">Approve</a><a class="btn" onClick="denyReservation(\''+reserv['Reservation']['id']+'\');">Deny</a>';
					rshtml += '</li>';
				}
				rshtml += "</ul>";
				$('#reserve_requests_list').html(rshtml);
				reservLoading = false;	
			}
		});
	}
	var curCourtID = null;
	function showCalendar(dateStr,cID){
		curCourtID = cID;
		console.log("Show calendar for "+dateStr);
		$('#calendar_picker').datepicker("setDate",dateStr);
		loadCalendarDay();
	}
	function approveReservation(rID){
		console.log("Approve reservation #"+rID);
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/ajax/approveReservation',
			type: 'post',
			dataType: 'text',
			data: 'rID='+rID,
			success: function(data){
				console.log(data);
				alert(data);
			},
			error: function(errstr){
				console.log("ERROR approving");
				console.log(errstr);
			}
		})
	}
	function denyReservation(rID){
		console.log("Deny reservation #"+rID);
	}
</script>
<style>
	#reservation_ul > a.btn {
		margin-left: 10px;
	}
</style>
<div class="tab-content" id="prof_tab" style="width: 100%; background: #fff; min-height: 200px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; max-height: 650px; overflow-y: auto;">
	<div id="blank" class="tab-pane">PLACEHOLDER TAB</div>
	<div id="about" class="tab-pane active">
		<h4>Edit Center Details</h4>
		<?php if(isset($editmsg)){ print "<h5>".$editmsg."</h5>"; } ?>
		<h5>Change Center Picture (100x100): 
			<?php echo $this->Form->create('Center',array('type'=>'file')); ?>
			<?php echo $this->Form->input('profile_pic',array('type'=>'file')); ?>
			<input type='submit' value='Update Picture'></form>
		</h5>
		<?php echo $this->Form->create('Center'); ?>
		<?php echo $this->Form->input('name'); ?><br>
		<?php echo $this->Form->input('status'); ?><br>
		<?php echo $this->Form->input('description'); ?><br>
		<?php echo $this->Form->input('street'); ?><br>
		<?php echo $this->Form->input('city'); ?><br>
		<?php echo $this->Form->input('state'); ?><br>
		<?php echo $this->Form->input('zip'); ?><br>
		<?php echo $this->Form->input('phone'); ?><br>
		<input type="submit" value="Update">
		</form>

		
		
	</div>

	<div id="bulletins" class="tab-pane">
		<script type='text/javascript'>
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
			function addBulletin(){
				var cID = '<?php echo $prof['Center']['id']; ?>';
				var datStr = 'data[Bulletin][center_id]='+cID+'&data[Bulletin][title]='+$('#bulletinTitle').val()+'&data[Bulletin][content]='+$('#bulletinText').val();
				$.ajax({
					url: '/getxp/<?php echo $sname; ?>/ajax/bulcen'+cID,
					type: 'post',
					data: datStr,
					dataType: 'text',
					success: function(data){
						alert(data);
					},
					error: function(errstr){
						console.log("Bulletin error!"); console.log(errstr);
					}
				})
			}
		</script>
		<h3>Bulletins</h3>
		<h4>Add Bulletin:</h4>
		<b>Title: </b><input type='text' id='bulletinTitle'><br>
		<textarea id='bulletinText'></textarea><br>
		<input type='button' class='btn' onClick='addBulletin();' value='Add'><hr>

		<div id='bulletinList'>

		</div>


	</div>

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


	<div class="tab-pane" id="proshop">
		<h4>Pro Shop</h4>

	</div>

	<div class="tab-pane" id="reserve">
		<h3>Manage Court Reservations</h3>
		<ul class="tabs" data-tabs="tabs">
		<li class="active"><a href="#reserve_courts">Courts</a></li>
		<li><a href="#reserve_requests">Reservations</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="reserve_courts">
				<h4>Current Courts:</h4>
				<ul>
					<?php
					if(isset($courts)){ 
					foreach($courts as $court){
						print "<li><b>".$court['Court']['name']."</b> <blockquote>".
							$court['Court']['description']."</blockquote> ".
							"<a class='btn' onClick='removeCourt(".$court['Court']['id'].
							");'>[X]</a> </li>";

					} }?>	
				</ul>
				<h4>Add a Court:</h4>
				<form name='frmCourt' id='frmAddCourt' action='' method='POST' onSubmit='addCourt(this); return false;'>
					<b>Name: </b><input type="text" name="court_name" size=50><br>
					<textarea name="court_description" placeholder="Court Description" cols=50>
					</textarea><br>
					<!--<input type="submit" value="Add">-->
					<a class="btn" onClick='addCourt(this.parentNode);'>Add</a>
				</form>
				<div class="modal" id="modalStatus">
					<h4></h4>
					<a class="btn" onClick='$("#modalStatus").modal().hide();'>Close</a>
				</div>
				<script type='text/javascript'>
					function addCourt(frmObj){
						//process form data and send ajax request
						console.log(frmObj);
						var frmDat = $(frmObj).serialize();
						$.ajax({
						url: '/getxp/<?php echo $sname; ?>/centers/update',
						dataType: 'text',
						type: 'POST',
						data: 'center_id=<?php echo $prof['Center']['id']; ?>&action=add_court&'+frmDat,
						success: function(data){
							console.log("Reservations loaded");
							console.log(data);
							showStatus(data);
						}
					});

					}
					function showStatus(stat){
						$('#modalStatus > h4').html(stat);	
						$('#modalStatus').modal().show();
					}
					$(document).ready(function(){
						$('#calendar_picker').bind('change',loadCalendarDay);
						$('#calendar_picker').datepicker();
						//$('#endDate').datepicker();
					});
					function loadCalendarDay(){
				
						var rDate = $('#calendar_picker').val();
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
				</script>
			</div>
			<div class="tab-pane" id="reserve_requests">
			<style>
				.layout-grid td.demos {
					background-color: #ddd;
				}
				#ui-datepicker-div {
					background-color: #ddd;
				}
				tr.odd {
					background-color: #ddffdd;
				}
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
				<div id="reserve_requests_list">
				Loading Reservations..
				</div>
				<center>
					<input type="text" id="calendar_picker"><br>
				</center>
				<div id="courtCalendar">

				</div>
			</div>
 
			
		</div>
	</div>

	<div class="tab-pane" id="halloffame">
		<b><?php echo $prof['Center']['name']; ?> Hall of Fame</b>
	 </div>

	<div class="tab-pane" id="announcements">Announcements</div>

	<div class="tab-pane" id="competitions">Competitions<br>
		<div id="competition_list">

		</div>
		<form name='frmCreateLeague' action='/getxp/<?php echo $sname; ?>/league/create' METHOD=POST>
			<input type=hidden name='action' value='register'>
			<input type=hidden name='center_id' value='<?php echo $prof['Center']['id']; ?>'>
			<input type=submit value='Create League'>
		</form>
	</div>

	

</div>







