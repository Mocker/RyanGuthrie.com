<?php
//chat box element - div to hold chats and javascript to handle ajax queries and updating

?>

<script type="text/javascript">
	var allChats = { //hold all information about active chat
		<?php
			if(isset($prof['Center'])){ ?>
				'roomName': '<?php echo $prof['Center']['name'];?> Chat',
				'roomType': 'center',
			<?php }
			else if(isset($prof['Network'])){ ?>
				'roomName': '<?php echo $prof['Network']['name'];?> Chat',
				'roomType': 'network',
			<?php } ?>
		'roomID': 0,
		'lastChatID':null,
		'chatlog': new Array(),
		'newTimer': null
	};
	$(document).ready(ChatInit);
	function toggleChat(){
		$('#chatContainer > h4').html(allChats.roomName);
		$('#chatContainer').toggle();
	}
	function sendChatMSG(){

		var msg = document.getElementById('frmChatMSG').chatMSG.value ;
		var url = '/getxp/<?php echo $sname; ?>/chat/send';
		<?php 
		if(isset($prof['Center'])){ 
			echo "var ctype = 'center';\n var cid='".$prof['Center']['id']."';\n"; 
		}
		else {
			echo "var ctype = 'network';\n var cid='".$prof['Network']['id']."';\n"; 
		}

		?>

		console.log('url:'+url+' type:'+ctype+' msg:'+msg);
		$.ajax({
			url: url,
			dataType: 'text',
			type: 'POST',
			data: 'type='+ctype+'&cid='+cid+'&msg='+msg,
			success: function(data){
				console.log('send success'); console.log(data);
				allChats.newTimer = setTimeout("ChatCheckNew()",100);
			},
			error: function(err){
				console.log('send error'); console.log(err);
			}
		})
	}
	function ChatInit(){
		//init checking for new im's
		allChats.newTimer = setTimeout("ChatCheckNew()",1000);
	}
	function ChatCheckNew(){
		$.ajax({
			url: '/getxp/<?php echo $sname; ?>/chat/<?php 
				if(isset($prof['Center'])){ 
				echo 'cen'.$prof['Center']['id']; }
				else { echo 'net'.$prof['Network']['id']; }
				?>',
			dataType: 'json',
			type: 'GET',
			success: function(data){
					allChats.chatlog = data;
					ChatDisplay(data);
					allChats.newTimer = setTimeout("ChatCheckNew()",10000);
			},
			error: function(err){
				console.log("error checking chat"); console.log(err);
			}
		});
	}

	function ChatDisplay(chatData){
		if(!chatData){ return; }
		var liStr = "<ul>\n";
		for(var i=0; i<chatData.length;i+=1){
			var chat = chatData[i];
			liStr += "<li><b>"+chat['Chat']['from_name']+'</b> '+chat['Chat']['message']+'</li>';
		}
		liStr += '</ul>';
		$('div.chatLog').html(liStr);
	}

</script>
<style>
	#chatContainer {
		position: absolute;
		width: 220px; min-height: 300px;
		float: left; 
		left: 100px; top: 30px; 
		z-index: 100;
		background-color: white;
		border: 1px solid black;
		padding: 2px; 
		display: none;
	}
	div.chatLog {
		width: 215px; min-height: 120px;
		max-height: 300px;
		margin-left: auto; margin-right: auto;
		
		background: #ddd;
		overflow-y: scroll;
	}
	#chatLog ul { width: 100%; list-style-type: none; }
	#chatLog li { width: 204px; }
</style>
<div id='chatContainer'>
	<div style='float: right;'><a class='btn' onClick='toggleChat();'>Hide</a></div>
	<h4>Chat!</h4>
	<div class='chatLog'>
		<ul>
			<li><b>Loading chat messages..</b></li>
		</ul>
	</div>
	<div class='chatMSG'>
		<form name='frmChatMSG' id='frmChatMSG'>
			<input type="text" size=60 name='chatMSG'><br>
			<a class='btn' onClick='sendChatMSG();'>Send</a>
		</form>
	</div>


</div>