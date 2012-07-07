 <div id="leftBlock">
	<h2>Welcome, <?php echo $userinfo['first_name'].' '.$userinfo['last_name'];?></h2>
	<i><a href="users/logout">Logout</a></i>
	<br />
  <br />
  <div id="logoo" style="position: relative;">
<?php echo $this->Html->image('GetXP_sm.png'); ?>
</div>

	<div id="socialNotifications">
	<ul>
	<li>You have <span class="label">
	<?php echo count($messages); ?> new messages</span></li>
	<li id="message_invites">You have <span class="label success">
	<?php echo count($requests); ?> friend invite</span></li>
	</div>
	

</div>

	<div id="innerNav">	
    <a href="/getxp/">Home
    </a> | <a href="#">Waves (
    <?php echo count($waves); ?>)</a> | <a href="#">Photos</a> | <a href="#">Videos</a>
  </div>
  
<div id="rightBlock">



 <style>
 div.modal {
  top: 350px; padding: 10px; display: none;
  z-index:11000;
}
 </style>
 <script type="text/javascript">
 	function sendFriendMSG(frmNode){
		$('#modalFriendMSG h4').html('SENDING MESSAGE');
		console.log("Sending mesage");
		var whaturl = '/getxp/'+$('#sportName').val()+ '/friends/sendmsg'; //insert sport of user
		//alert('sending msg to '+whaturl); return;
		$.ajax({
			url: whaturl,
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
	function approveRequest(reqID){
		$.ajax({
			url: '/getxp/users/friends/approve/'+reqID,
			dataType: 'text',
			success: function(data){
				$('#modalMessage h3').html(data);
				$('#modalMessage').modal().show();
			}
		})
	}
	function denyRequest(reqID){
		$.ajax({
			url: '/getxp/users/friends/deny/'+reqID,
			dataType: 'text',
			success: function(data){
				$('#modalMessage h3').html(data);
				$('#modalMessage').modal().show();
			}
		})
	}
	function showSendMSG(fID,sName){
		console.log($('#friendID')[0]);
		//$('#frmFriendMSG').friendID.value(fID);
		$('#friendID').val(fID);
		$('#sportName').val(sName);
		$('#modalFriendMSG').modal().show();
	}

	function showFullMessage(fName,fMSG){
		$('#modalFullMessageS').html('<h3>From: <b>'+fName+'</b></h3><p>'+fMSG+'</p>');
		$('#modalFullMessage').modal().show();
	}

 </script>

 <h3>Notices:</h3>
 <ul class="tabs" data-tabs="tabs">
	<li class="active"><a href="#messages">Messages (
	<?php echo count($messages); ?>)</a></li>
	<li><a href="#requests">Friend Requests (
	<?php echo count($requests); ?>)</a></li>
	<li><a href="#waves">Waves (
	<?php echo count($waves); ?>)</a></li>
</ul>
<div id="modalFriendMSG" class="modal" style="">
	<div id="modalFriendMSGContent"></div>
	<h3>Send <span id='FriendMSGName'></span> a message:</h3>
	<h4></h4>

	<form name="frmFriendMSG" id="frmFriendMSG">
		<textarea name="friendMSG"></textarea><br>
		<input type="hidden" name="friendID" id="friendID" value="">
		<input type="hidden" name="sportName" id="sportName" value="">
		<input type="button" value="Send Message" onClick="sendFriendMSG(this.parentNode);"><input type="button" value="Cancel" onClick="$('#modalFriendMSG').modal().hide();">
	</form>
</div>
<div id="modalMessage" class="modal">
	<h3></h3>
	<input type="button" onClick="$(this.parentNode).modal().hide();" value="Close">
</div>
<div id="modalFullMessage" class="modal">
	<span id="modalFullMessageS"></span>
	<input type="button" onClick="$(this.parentNode).modal().hide();" value="Close">
</div>

<div class="tab-content">
	<div id="messages" class="tab-pane active">
		Mail:<br>
		<table>
		<tr><th>Sport</th><th>Sent</th><th>From</th><th>Message</th></tr>
		<?php foreach($messages as $msg){
			$msgText = str_replace("\n","<br>",$msg['Message']['message']);
			$msgText = str_replace("\r","",$msgText);
			$sportID = (isset($msg['Profile'])) ? $msg['Profile']['sport_id'] : 'System';
			//$profURL = (isset($msg['Profile'])) ? "<a href='/getxp/".$sportlist[$msg['Profile']['sport_id']]."/profile/".$msg['Profile']['id']."'>".$msg['Profile']['name']."</a>" : "<b>System</b>";
			$sportname = "system";
			$profURL = "<b>System</b>";
			if(isset($msg['Profile'])&& $msg['Profile']['id']){
				$profURL = "<a href='/getxp/".$sportlist[$msg['Profile']['sport_id']]."/profile/".$msg['Profile']['id']."'>".$msg['Profile']['name']."</a>";
				$sportname = $sportlist[$msg['Profile']['sport_id']];
			}
			print "<tr><td>".
			$sportname.
			"</td><td>".
			$msg['Message']['sent']."</td><td>".$profURL."</td><td onClick='showFullMessage(\"".$msg['Profile']['name']."\",\"".
			$msgText."\");'>"
			.substr($msg['Message']['message'],0,15)."..</td></tr>";

			print "<tr><td colspan=3>-</td><td>";
			if($sportname != 'system'){ print "
			<a href='#' class='btn' onClick='showSendMSG(".
			$msg['Profile']['id'].',"'.$sportname.
			'"); return false;'."'>Reply</a></td></tr>"; }
		}?>

		</table>
	</div>
	<div id="requests" class="tab-pane">
		Friend Requests:<br>
		<ol>
		<?php foreach($requests as $req){
			print "<li id='request".$req['FriendRequest']['id']."'><a href='/getxp/tennis/profile/".
			$req['Profile']['id']."'><b>".$req['Profile']['name'].
			"</b></a> has sent you a friend request.<br><blockquote>".
			$req['FriendRequest']['message']."</blockquote><br>
			<a href='#' class='btn' onClick='approveRequest(".'"'.
			$req['FriendRequest']['id'].'"'."); return false;'>Approve</a>
			<a href='#' class='btn' onClick='denyRequest(".'"'.
			$req['FriendRequest']['id'].'"'."); return false;'>Deny</a>
			</li>";

		}
		?>
		</ol>
	</div>
	<div id="waves" class="tab-pane">
		Waves:<br>
		
		<ol>

		</ol>
	</div>

</div>

</div>