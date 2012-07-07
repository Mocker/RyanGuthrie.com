<h2><?php echo $prof['Profile']['name']; ?></h2>

<div style="width: 100%; height: 100px; border-bottom: 2px solid black; border-top: 2px solid black;">
<div style="float: left; width: 100px; height: 100px; background-color: #ccc;">Profile Pic</div>
<div style="float: left; width: 300px;">
<style>
	ul.vertmenu li { width: 100px; float: left; }
</style>
<ul class="vertmenu" style="position: relative; float: left; width: 120px;">
	<li><a href="#">Photo Album</a></li>
	<li><a href="#">Video Album</a></li>
	<?php if(!$isself){ ?>
	<li><a href="#" onClick='$("#modalFriendMSG").modal().show();return false;'>Send Message</a></li>
	<li><a href="#">Wave</a></li>
	<li><a href="#" onClick="addFriend(<?php echo $prof['Profile']['id']; ?>);">Befriend</a></li>
	<?php } else {
		print '<li><a href="#">View Inbox</a></li>';
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

<b>Status Update:</b><br>
Played tennis this morning with Bob

</div>
</div>


<ul class="tabs" data-tabs="tabs">
	<li class="active"><a href="#bio">Bio</a></li>
	<li><a href="#bulletins">Bulletin</a></li>
	<li><a href="#comments">Comments</a></li>
	<li><a href="#networks">Networks</a></li>
	<li><a href="#trophies">Trophies</a></li>
	<li><a href="#instructor">Instructor</a></li>
	<li><a href="#affiliations">Affiliations</a></li>
	<li><a href="#competitions">Competitions</a></li>
</ul>
<div class="tab-content" id="prof_tab" style="width: 100%; background: #fff; min-height: 200px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; max-height: 650px; overflow-y: auto;">
	<div id="blank" class="tab-pane">PLACEHOLDER TAB</div>
	<div id="bio" class="tab-pane active">
		<div style="width:50%; float: right; ">
			<h4><?php echo $prof['Profile']['name']."'s friends"; ?></h4>


		</div>
		<div style="width: 50%;">
	<b>Age:</b> ??<br>
	<b>Level:</b> <?php echo $prof['Profile']['level']; ?> <br>
	<b>Location:</b> <?php echo $prof['Profile']['hometown']; ?> <br>
	<b>Looking For:</b> ?? <br>
	<b><?php echo $sname; ?> Experience:</b><br>
	<blockquote>
		<ul style="list-style-type: none;">
			<li>Been playing for: </li>
			<li>Favorite Pro: </li>
		</ul>
	</blockquote>
	<b>Occupation:</b> ?? <br>
	<b>Education:</b> ?? <br>
	<b>Favorites:</b> ?? <br>
		</div>
		
	</div>

	<div id="bulletins" class="tab-pane">Bulletins</div>

	<div class="tab-pane" id="comments">
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


	<div class="tab-pane" id="networks">
		<h4>Tri State Area:</h4>
		<ul style="list-style-type: circle;">
			<li><b>Westchester County</b></li>
			<li><b>Manhattan</b></li>
		</ul>

	</div>

	<div class="tab-pane" id="trophies">Trophy Room </div>

	<div class="tab-pane" id="instructors">Instructors </div>

	<div class="tab-pane" id="affiliations">Affiliations</div>

	<div class="tab-pane" id="competitions">Competitions</div>

	

</div>