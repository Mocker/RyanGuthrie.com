<h2><?php echo $prof['Profile']['name']; ?></h2>

<div style="width: 100%; height: 100px; border-bottom: 2px solid black; border-top: 2px solid black;">
<div style="float: left; width: 100px; height: 100px; background-color: #ccc;">
	<?php if($prof['Profile']['profile_pic']){ print "<img src='/getxp/img/profiles/".$prof['Profile']['profile_pic']."' width=100 height=100>"; } else { print "&nbsp;"; } ?>
</div>
<div style="float: left; width: 300px;">
<style>
	ul.vertmenu li { width: 100px; float: left; }
</style>
<ul class="vertmenu" style="position: relative; float: left; width: 120px;">
	<li><a href='/getxp/<?php echo $sname; ?>/photos/profile/<?php echo $prof['Profile']['id']; ?>'>Photo Album</a></li>
	<li><a href='/getxp/<?php echo $sname; ?>/videos/profile/<?php echo $prof['Profile']['id']; ?>'>Video Album</a></li>
	<?php if(!$isself){ ?>
	<li><a href="#" onClick='$("#modalFriendMSG").modal().show();return false;'>Send Message</a></li>
	<li><a href="#">Wave</a></li>
	<li><a href="#" onClick="addFriend(?php echo $prof['Profile']['id']; ?>);">Befriend</a></li>
	<?php } else {
		if(!isset($isedit)){
			print '<li><a href="/getxp/'.$sname.'/profile/edit">Edit Profile</a></li>';
		} else {
			print '<li><a href="/getxp/'.$sname.'/profile/">View Profile</a></li>';
		}
	} ?>
</ul>
<div id="modalFriendRequest" class="modal" style="">
	<h3>Add <?php echo $prof['Profile']['name']; ?> as a Friend:</h3>
	<h4></h4>
	<form name="frmFriendRequest" id="frmFriendRequest">
		Include a message with your request:<br>
		<textarea name="friendRequestMSG"></textarea><br>
		<input type="hidden" name="friendRequestID" id="friendRequestID" value="<?php echo $prof['Profile']['id']; ?>">
		<input type="button" value="Send Request" onClick="sendFriendReq(this.parentNode);"><input type="button" value="Cancel" onClick="$('#modalFriendRequest').modal().hide();">
	</form>
</div>
<div id="modalFriendMSG" class="modal" style="">
	<h3>Send <?php echo $prof['Profile']['name']; ?> a message:</h3>
	<h4></h4>
	<form name="frmFriendMSG" id="frmFriendMSG">
		<textarea name="friendMSG"></textarea><br>
		<input type="hidden" name="friendID" id="friendID" value="<?php echo $prof['Profile']['id']; ?>">
		<input type="button" value="Send Message" onClick="sendFriendMSG(this.parentNode);"><input type="button" value="Cancel" onClick="$('#modalFriendMSG').modal().hide();">
	</form>
</div>
<div id="modalMessage" class="modal">
	<h3></h3>
	<input type="button" onClick="$(this.parentNode).modal().hide();" value="Close">
</div>
<script type="text/javascript">
	function addFriend(fID){
		$('#modalFriendRequest').modal().show();
		return false;
		//send friend request for profile ID fID
	}
	function sendFriendReq(frmNode){
		$('#modalFriendRequest h4').html('SENDING FRIEND REQUEST');
		$.ajax('/getxp/<?php echo $sname; ?>/friends/request',{
			data: $('#frmFriendRequest').serialize(),
			dataType: 'text',
			type: 'post',
			success: function(data){
				console.log("FriendRequest success"); console.log(data);
				$('#modalFriendRequest h4').html('');
				$('#modalFriendRequest').modal().hide();
				$('#modalMessage h3').html(data);
				$('#modalMessage').modal().show();
			},
			error: function(jqh, txt){
				alert('Error with friend request: '+txt);
			}
		});
	}
	function sendFriendMSG(frmNode){
		$('#modalFriendMSG h4').html('SENDING MESSAGE');
		console.log("Sending mesage");
		$.ajax('/getxp/<?php echo $sname; ?>/friends/sendmsg',{
			data: $('#frmFriendMSG').serialize(),
			dataType: 'text',
			type: 'post',
			success: function(data){
				console.log("FriendMSG success"); console.log(data);
				$('#modalFriendMSG h4').html('');
				$('#modalFriendMSG').modal().hide();
				$('#modalMessage h3').html(data);
				$('#modalMessage').modal().show();
			},
			error: function(jqh, txt){
				alert('Error sending message: '+txt);
			}
		});
	}


</script>

<b>Status Update:</b> <?php if($isself){ ?>
	<div class="modal" id="modalChangeStatus" >
		<h4>Enter new status:</h4>
		<form name="frmChangeStatus" id="frmChangeStatus" >
			<textarea name="newStatus" placeholder="Enter new status"></textarea><br>
			<input type="button" value="Update" onClick="doStatus(this.parentNode);">
			<input type="button" value="Cancel" onClick="$('#modalChangeStatus').modal().hide();">
		</form>
	</div>
	<script type="text/javascript">
		function doStatus(frmNode){
			//do ajax status update then refresh
			$('#modalChangeStatus').modal().hide();
			var newStat = $(frmNode.newStatus).val();
			console.log("New status: "+newStat);
			$.ajax({
				url: '/getxp/<?php echo $sname; ?>/profile/changeStatus',
				data: $(frmNode).serialize(),
				type: 'post',
				success: function(data){
					$('#modalMessage h3').html(data);
					$('#modalMessage').modal().show();
					$('#profileStatus').html(newStat);

				}	
			});
			return false;
		}

	</script>
	<a class='btn' onClick='$("#modalChangeStatus").modal().show();'>Update</a>
<?php } ?>
<br>
<span id='profileStatus' style='font-size: 1.0em; color: black; z-index: 900;'>
<?php echo $prof['Profile']['status']; ?>
</span>

</div>
</div>
<script type="text/javascript">
		var rawCom ;
		function loadComments(){
			$('#allcomments').html('Loading Comments..');
			$.ajax({
				url: '/getxp/<?php echo $sname; ?>/ajax/compro<?php echo $prof['Profile']['id']; ?>',
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


				}
			});
		}
</script>

<ul class="tabs" data-tabs="tabs">
	<li class="active"><a href="#bio">Bio</a></li>
	<li><a href="#bulletins">Bulletin</a></li>
	<li><a href="#comments" onClick="loadComments()";>Comments</a></li>
	<li><a href="#networks">Networks</a></li>
	<li><a href="#trophies">Trophies</a></li>
	<li><a href="#instructor">Instructor</a></li>
	<li><a href="#affiliations">Affiliations</a></li>
	<li><a href="#competitions">Competitions</a></li>
</ul>
<div class="tab-content" id="prof_tab" style="width: 100%; background: #fff; min-height: 200px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; max-height: 650px; overflow-y: auto;">
	<div id="blank" class="tab-pane">PLACEHOLDER TAB</div>
	<div id="bio" class="tab-pane active">
		<?php if(!isset($isedit)){ ?>
		<div style="width:50%; float: right; ">
			<h4><?php echo $prof['Profile']['name']."'s friends"; ?></h4>
			<ul>
				<?php
				if(isset($friends)){
				foreach($friends as $friend){
					$fProf = $friend['Profile'];
					if($fProf['id']==$prof['Profile']['id']){ $fProf = $friend['ProfileT']; }
					print "<li><img src='".$fProf['profile_pic']."'> ".$fProf['name']."</li>";
				}
				}	
				?>

			</ul>
		</div>
		<?php } ?>

		<div style="width: 50%;">
		<?php if(!isset($isedit)||!$isedit){ ?>
		<b>Age:</b> <?php echo $prof['Profile']['age']; ?><br>
		<b>Level:</b> <?php echo $prof['Profile']['level']; ?> <br>
		<b>Hometown:</b> <?php echo $prof['Profile']['hometown']; ?> <br>
		<b>Looking For:</b> <br><ul>
		<?php
			if($prof['Profile']['lf_recreation']){ print "<li>Recreation</li>"; }
			if($prof['Profile']['lf_socializing']){ print "<li>Socializing</li>"; }
			if($prof['Profile']['lf_competition']){ print "<li>Competition</li>"; }
			if($prof['Profile']['lf_relationship']){ print "<li>Relationship</li>"; }
		?>
		</ul>
		<b><?php echo $sname; ?> Experience:</b><br>
		<blockquote>
			<ul style="list-style-type: none;">
				<li>Level: <?php echo $prof['Profile']['level']; ?></li>
				<li>Favorite Pro: </li>
			</ul>
		</blockquote>
		<b>Occupation:</b> <?php echo $prof['Profile']['occupation']; ?> <br>
		<b>Education:</b> <?php echo $prof['Profile']['education']; ?><br>
		<b>Favorites:</b> <p><?php echo $prof['Profile']['favorites']; ?></p><br>
		<?php } else { ?>
		
		<h4>Edit Profile:</h4>
			<?php if(isset($editmsg)){ print "<h5>".$editmsg."</h5>"; } ?>
			<h5>Change Profile Picture (100x100): 
			<?php echo $this->Form->create('Profile',array('type'=>'file')); ?>
			<?php echo $this->Form->input('profile_pic',array('type'=>'file')); ?>
			<input type='submit' value='Update Picture'></form>
			</h5>
		  	<!-- REQUEST DATA:
		  	<?php print_r($this->request->data); ?>
		  	-->
		  	<h5>Bio</h5>
			<?php echo $this->Form->create('Profile'); ?>
			<?php echo $this->Form->input('age'); ?><br>
			<?php echo $this->Form->input('name'); ?><br>
			<?php echo $this->Form->input('hometown'); ?><br>
			<?php echo $this->Form->input('zip'); ?><br>
			<?php echo $this->Form->input('occupation'); ?><br>
			<?php echo $this->Form->input('education'); ?><br>
			<?php echo $this->Form->input('favorites'); ?><br>
			<b>Looking For:</b>
			<ul>
				<li><?php echo $this->Form->checkbox('lf_recreation'); ?>Recreation</li>
				<li><?php echo $this->Form->checkbox('lf_socializing'); ?>Socializing</li>
				<li><?php echo $this->Form->checkbox('lf_competition'); ?>Competition</li>
				<li><?php echo $this->Form->checkbox('lf_relationship'); ?> Relationship</li>
			</ul>
			<input type=submit value="Update Profile">


			</form>

		<?php } ?>
		
		</div>
		
	</div>

	<div id="bulletins" class="tab-pane">Bulletins</div>

	<div class="tab-pane" id="comments">
		
		<script type="text/javascript">
			function sendComment(frmObj){
				//alert(frmObj.comment.value);
				var url = '/getxp/<?php echo $sname; ?>/ajax/compro<?php echo $prof['Profile']['id']; ?>';
				var datstr = "data[Comment][comment]="+$('#commentBox').val();
				$('#commentBox').prop('disabled',true);
				console.log("Sending comment to "+url+" data:"+datstr);

				$.ajax({
				url: url,
				data: datstr,
				type: 'POST',
				success: function(data){
					console.log("Sent comment");
					console.log(data);
					loadComments();
					alert(data);
					$('#commentBox').prop('disabled',false);
					$('#commentBox').val('');	
				},
				error: function(err){
					console.log("error: "); console.log(err);
					alert('Error sending comment');
					$('#commentBox').prop('disabled',false);
					$('#commentBox').val('');	
				}
			});
			}
		</script>
		<br>
		<center>
		<?php if(!isset($isself) || !$isself){ ?>
		<form id="frmComment" onSubmit="return false;">
			<input class="span4" type="text"  id="commentBox" name="commentBox" placeholder="Type Comment here">
			<button class="btn primary" onClick="sendComment(this.parentNode);">Submit</button>
		</form>
		<?php } ?>
		</center>
		<!--
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
		</ul> -->
		<div id="allcomments">
			loading comments..
		</div>

	</div>


	<div class="tab-pane" id="networks">
		<h4>Tri State Area:</h4>
		<ul style="list-style-type: circle;">
			<li><b>Westchester County</b></li>
			<li><b>Manhattan</b></li>
		</ul>

	</div>

	<div class="tab-pane" id="trophies">Trophy Room </div>

	<div class="tab-pane" id="instructor">Instructor </div>

	<div class="tab-pane" id="affiliations">Affiliations</div>

	<div class="tab-pane" id="competitions">Competitions</div>

	

</div>
