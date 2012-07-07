
<div id="ChatArea" style='position: relative; left: -322px; background-color: none;'>
	<div class="IMButton" style="background-color: none;">
		<a onClick='toggleChat();'><?php echo $this->Html->image('chat.png'); ?></a></div>
	<?php echo $this->element('chatroom'); ?>
</div>


<h2><?php echo $prof['Network']['name']; ?></h2>



<div style="width: 100%; height: 100px; border-bottom: 2px solid black; border-top: 2px solid black;">
<div style="float: left; width: 100px; height: 100px; background-color: #ccc;">
	<?php if($prof['Network']['profile_pic']){ print "<img src='/getxp/img/profiles/".$prof['Network']['profile_pic']."' width=100 height=100>"; } else { print "&nbsp;"; } ?>
</div>
<div style="float: left; width: 300px;">
<style>
	ul.vertmenu li { width: 100px; float: left; }
</style>
<ul class="vertmenu" style="position: relative; float: left; width: 120px;">
	<li><a href='/getxp/<?php echo $sname; ?>/photos/network/<?php echo $prof['Network']['id']; ?>'>Photo Album</a></li>
	<li><a href='/getxp/<?php echo $sname; ?>/videos/network/<?php echo $prof['Network']['id']; ?>'>Video Album</a></li>
	<li><a href="#">Message</a></li>
	<?php if($isowner && (!isset($isedit) || !$isedit) ){ ?>
		<li><a href="/getxp/<?php echo $sname; ?>/network/edit/<?php echo $prof['Network']['id']; ?>">Edit</a></li>
	<?php } else if(!isset($isedit)){ ?>
	<li><a href="#">Join</a></li>
	<?php } else { ?>
	<li><a href="/getxp/<?php echo $sname; ?>/network/<?php echo $prof['Network']['id']; ?>">View</a></li>
	<?php } ?>
	<script type="text/javascript">
		function joinNetwork(){
			var url = '/getxp/<?php echo $sname; ?>/network/join/<?php echo $prof['Network']['id']; ?>';
			var doIt = confirm('Join <?php echo $prof['Network']['name']; ?>');
			if(doIt){
				//submit join request
				console.log('Join via url '+url);
				/* $.ajax({
					url: url,
					type: 'get',
					dataType: 'text',
					success: function(data){
						
					}
				});
				*/
			}

		}
	</script>
</ul>

<b>Status Update:</b><br>
<?php echo $prof['Network']['status']; ?>

</div>
</div>


<ul class="tabs" data-tabs="tabs">
	<li><a href="#about" class="active">About</a></li>
	<li><a href="#bulletins" onClick="loadBulletins();">Bulletin</a></li>
	<li><a href="#reviews" onClick="loadReviews();">Reviews</a></li>
	<li><a href="#competitions" onClick='loadComps();'>Competitions</a></li>
	<li><a href="#halloffame">Hall of Fame</a></li>
	<li><a href="#announcements">Announcements</a></li>
	<li><a href="#members">Members</a></li>
	
</ul>
<script type="text/javascript">
	function loadComps(){
		//ajax request to load list of competitions for this center
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/league/search',
			dataType: 'text',
			type: 'POST',
			data: 'network_id=<?php echo $prof['Network']['id']; ?>',
			success: function(data){
				console.log("Competitions loaded");
				console.log(data);
				$('#competition_list').html(data);	
			}
		});
	}
</script>
<div class="tab-content" id="prof_tab" style="width: 100%; background: #fff; min-height: 200px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; max-height: 650px; overflow-y: auto;">
	<div id="blank" class="tab-pane">PLACEHOLDER TAB</div>
	<div id="about" class="tab-pane active">
		<?php if(!isset($isedit) ||!$isedit){  ?>
		<h2>About <?php print $prof['Network']['name']; ?></h2>
		<?php if(isset($editmsg)){ print '<h4>'.$editmsg.'</h4>'; } ?>
		<b><?php echo $prof['Network']['description']; ?></b>
		<?php } else { ?>
		<h5>Change Network Picture (100x100): 
			<?php echo $this->Form->create('Network',array('type'=>'file')); ?>
			<?php echo $this->Form->input('profile_pic',array('type'=>'file')); ?>
			<input type='submit' value='Update Picture'></form>
		</h5>
		<h2>Editing Details</h2>
		<?php echo $this->Form->create('Network'); ?>
		<?php echo $this->Form->input('status'); ?>
		<?php echo $this->Form->input('name'); ?>
		<?php echo $this->Form->input('zip'); ?>
		<?php echo $this->Form->input('description'); ?>
		<input type="submit" value="Update">
		</form>

		<?php } ?>
		
		
		
	</div>

	<div id="members" class="tab-pane">Member List:<br>
		<ul>
		</ul>
	</div>

	<div id="bulletins" class="tab-pane">
		<script type="text/javascript">
		function loadBulletins(){
				var cID = '<?php echo $prof['Network']['id']; ?>';
				$.ajax({
					url: '/getxp/<?php echo $sname; ?>/ajax/bulnet'+cID,
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

			<?php if(isset($isedit)){ ?>
				function addBulletin(){
				var cID = '<?php echo $prof['Network']['id']; ?>';
				var datStr = 'data[Bulletin][network_id]='+cID+'&data[Bulletin][title]='+$('#bulletinTitle').val()+'&data[Bulletin][content]='+$('#bulletinText').val();
				$.ajax({
					url: '/getxp/<?php echo $sname; ?>/ajax/bulnet'+cID,
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
			<?php } ?>

		</script>
		<h3>Bulletins</h3>
		<?php if(isset($isedit)){ ?>
		<h4>Add Bulletin:</h4>
		<b>Title: </b><input type='text' id='bulletinTitle'><br>
		<textarea id='bulletinText'></textarea><br>
		<input type='button' class='btn' onClick='addBulletin();' value='Add'><hr>
		<?php } ?>

		<div id='bulletinList'>

		</div>
	</div>

	<div class="tab-pane" id="reviews">
		<script type="text/javascript">
			function sendComment(frmObj){
				//alert(frmObj.comment.value);
				var url = '/getxp/<?php echo $sname; ?>/ajax/comnet<?php echo $prof['Network']['id']; ?>';
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
				url: '/getxp/<?php echo $sname; ?>/ajax/comnet<?php echo $prof['Network']['id']; ?>',
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


	

	<div class="tab-pane" id="reserve">Reserve a Court</div>

	<div class="tab-pane" id="halloffame">
		<b><?php echo $prof['Network']['name']; ?> Hall of Fame</b>
	 </div>

	<div class="tab-pane" id="announcements">Announcements</div>

	<div class="tab-pane" id="competitions">Competitions
		<div id='competition_list'>
		</div>
		<form name='frmCreateLeague' action='/getxp/<?php echo $sname; ?>/league/create' METHOD=POST>
			<input type=hidden name='action' value='register'>
			<input type=hidden name='network_id' value='<?php echo $prof['Network']['id']; ?>'>
			<input type=submit value='Create League'>
		</form>

	</div>

	

</div>







