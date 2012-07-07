<?php
//chat box element - div to hold chats and javascript to handle ajax queries and updating

?>
<!--
	<?php 
	if(isset($SProfile)){ print "SPROFILE<br>".print_r($SProfile,TRUE)."<br>\n"; }
	if(isset($friends)){ print "Friends<br>".print_r($friends,TRUE)."<br>\n"; }
	if(isset($profileUpdated)){ print "Profile Updated<br>".print_r($profileUpdated,TRUE)."<br>\n"; }
	?>
-->
<script type="text/javascript">
	var allIMs = { //hold all information about active chat
		'activeChat': null,
		'newTimer': null, //timer for checking for new ims
		'chatTimer': null, //timer for refreshing current im session
		'isNew': false, //if there are any new ims
		'friendList': new Object(), //list of friend names indexed by ID
		'newChats': new Array(), //list of friends that sent a new msg
		'chats': new Object() //chat logs organized by friend id
	};
	$(document).ready(IMInit);
	function IMInit(){
		//init checking for new im's
		allIMs.newTimer = setTimeout("IMCheckNew()",1000);
	}
	function toggleIM(){
		$('#IMContainer').toggle();
	}
	function sendIMMSG(){

		var msg = document.getElementById('frmIMMSG').IMMSG.value ;
		var friendID = document.getElementById('frmIMMSG').IMFriendID.value;
		if(friendID==0){ console.log('No friend selected'); return; }
		console.log('Sending to '+friendID+'.. '+msg);
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/ims/send',
			data: $('#frmIMMSG').serialize(),
			dataType: 'text',
			type: 'POST',
			success: function(data){
				console.log("IM RESPONSE: "+data);
			}
		});
	}
	function IMShowChat(){
		$('div.IMLog > div').css('display','block');
		$('div.IMList > div').css('display','none');
		
	}
	function IMCheckNew(){
		//console.log("Checking for new IMs");
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/ims/0',
			dataType: 'json',
			type: 'GET',
			success: function(data){
					allIMs.newChats = new Object();
					$('li.IMFriendList').css('background-color','white');
					if(data.length > 0){
						allIMs['isNew'] = true;
						$('.IMButton').css('background-color', '#66dd66');
						//console.log("Found new messages");
						//console.log(data);
						for(i in data){
							allIMs.newChats[data[i]['Message']['profile_id']] = data[i][0]['Num'];
							$('#IMLIFriend'+data[i]['Message']['profile_id']).css('background-color','#006600');
						}
					}
					else {
						$('.IMButton').css('background-color', '#dddddd');
					}
					allIMs.newTimer = setTimeout("IMCheckNew()",10000);
			}
		});
	}
	function IMLoadChat(data){
		
	}
	function IMShowFriends(){
		$('div.IMLog > div').css('display','none');
		$('div.IMList > div').css('display','block');
		document.getElementById('frmIMMSG').IMFriendID.value = 0;
	}
	function IMSelect(friendID){ //show chat for friend
		allIMs.activeChat = friendID ;
		var chatLog = ''; //hold list elements for chat lines
		if(allIMs['chats'][friendID]!=undefined){
			//existing chat log with friend.. populate chat log
			$('div.IMLog > div').html('<ul><li>CHAT WITH FRIEND '+allIMs.friendList[friendID]+'</li></ul>');
		}
		else {
			$('div.IMLog > div').html('<i>No messages</i>');
		}
		$('div.IMLog > h4').html(allIMs.friendList[friendID]);
		document.getElementById('frmIMMSG').IMFriendID.value = friendID;
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/ims/'+friendID,
			dataType: 'json',
			type: 'POST',
			success: function(data){
				console.log("FETCHED CHATS FROM "+allIMs.activeChat);
				console.log(data);
				if(data.length >0 ){
					allIMs['chats'][allIMs.activeChat] = data;
					var lihtml = '<ul>';
					for(i in data){
						var msg = data[i];
						lihtml += "<li><i>(";
						if(msg['Message']['profile_id']==<?php echo $SProfile['Profile']['id']; ?>){
							lihtml += "you";
						}
						else { lihtml += msg['Message']['sent']; }
						lihtml +=")</i> "+msg['Message']['message']+"</li>\n";
					}
					lihtml += "</ul>\n";
					$('div.IMLog > div').html(lihtml);
				}
				IMShowChat();
				allIMs.chatTimer = setTimeout('refreshChat()',5000);
			}
		});
		//IMShowChat();
	}
	function refreshChat(){ //reloads current chat window with any new messages
		//console.log("Refreshing chat with "+allIMs.activeChat);
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/ims/'+allIMs.activeChat,
			dataType: 'json',
			type: 'POST',
			success: function(data){
				console.log("FETCHED CHATS FROM "+allIMs.activeChat);
				console.log(data);
				if(data.length >0 ){
					allIMs['chats'][allIMs.activeChat] = data;
					var lihtml = '<ul>';
					for(i in data){
						var msg = data[i];
						lihtml += "<li><i>(";
						if(msg['Message']['profile_id']==<?php echo $SProfile['Profile']['id']; ?>){
							lihtml += "you";
						}
						else { lihtml += msg['Message']['sent']; }
						lihtml +=")</i> "+msg['Message']['message']+"</li>\n";
					}
					lihtml += "</ul>\n";
					$('div.IMLog > div').html(lihtml);
				}
				if(allIMs.activeChat > 0 && $('div.IMLog > div').css('display')=='block'){
					allIMs.chatTimer = setTimeout('refreshChat()',5000);
				}
				else {
					console.log('Chat closed'); clearTimeout(allIMs.chatTimer);
				}
			}
		});
	}
</script>
<style>
	#IMContainer {
		position: fixed;
		width: 220px; min-height: 300px;
		float: right; 
		right: 30px; top: 46px; 
		z-index: 100;
		background-color: white;
		border: 1px solid black;
		padding: 2px; 
		display: none;
	}
	#IMContainer h4 {
		background-color: #222222;
		color: white;
		border-top: 1px solid gray;
		border-bottom: 1px solid gray;
	}
	#IMLog {
		width: 215px; min-height: 220px;
		margin-left: auto; margin-right: auto;
		
		background: #ddd;
		overflow-y: scroll;
	}
	#IMLog ul { width: 100%; list-style-type: none; }
	#IMLog li { width: 204px; }
	div.IMLog div { max-height: 400px; overflow-y: scroll; }
</style>
<div id='IMContainer'>
	<div style='float: right;'><a class='btn' onClick='toggleIM();'>Hide</a></div>
	<h5>IM!</h5>
	<div class='IMList'>
		<h4 onClick="IMShowFriends();">Online Friends</h4>
		<div>
			<ul>
				<?php foreach($friends as $friend){
					$prof = $friend['Profile']; $updated = $friend['Updated'];
					if($SProfile['Profile']['id']==$friend['Friend']['profile_id']){
						$prof = $friend['ProfileT']; $updated = $friend['UpdatedT'];
					}
					print "<script type='text/javascript'>\n".
						"allIMs.friendList['".$prof['id']."'] = '".$prof['name']."';\n"
						."</script>\n";
					print "<li class='IMFriendList' id='IMLIFriend".$prof['id']."'><b><a onClick='IMSelect(".$prof['id'].");'>".
						$prof['name']."</a></b></li>\n";

				} ?>
			</ul>
		</div>
	</div>
	<div class='IMLog'>
		<h4 onClick="IMShowChat();">Chat</h4>
		<div style='display: none;'>
		<ul>
			<li><b>12:17</b> Here is message!</li>
		</ul>
		</div>
	</div>
	<div class='IMMSG'>
		<form name='frmIMMSG' id='frmIMMSG'>
			<input type="text" size=60 name='IMMSG'><br>
			<input type='hidden' name='IMFriendID' value='0'>
			<a class='btn' onClick='sendIMMSG();'>Send</a>
		</form>
	</div>


</div>