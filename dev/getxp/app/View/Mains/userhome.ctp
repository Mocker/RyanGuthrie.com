<?php /* <!-- <style>
div.show-grid div {
	background-color: #ddd;
	margin-bottom: 20px;
	text-align: center;
	border: 2px outset #aaa;
}
ul.footer li { width: 100px; }
ul.footer { width: 500px; margin-left: auto; margin-right: auto; }

</style> --> */ ?>
<script type="text/javascript">
	$.ready(function(){
		$('.tabs').tabs();	
	});
</script>
<!--
DUMP USERINFO
<?php print_r($userinfo); ?>

DUMP SPORTS:
<?php print_r($sports); ?>

DUMP DATA:
<?php print_r($dumped); ?>

DUMP PROFILES:
<?php print_r($profiles); ?>

DUMP PROFLIST:
<?php print_r($plist); ?>

DUMP CENTERS OWNED:
<?php print_r($centers_owned); ?>
DUMP CENTER_MEMBERS:
<?php print_r($centers_member); ?>

<?php if($userinfo['admin']){
	print "DUMP PENDING NETWORKS\n";
	print_r($networks_pending);	
}
?>
-->


<!-- MODALS -->
<style>
.modal {
	display: none;
	position: fixed;
	width: 500px;
	margin-left: auto;
	margin-right: auto;
	top: 300px;
	border: 2px solid black;
	padding: 5px;
}

</style>
<div id="network_modal" style="" class="modal">
	<h3>Request a New Network</h3>
	<span id='requestnet_status'></span>
	<?php echo $this->Form->create('Network', array(
			'action'=>'',
			'inputDefaults'=> array(			
				'div'=>false))); 
	?>
	<label for="NetworkSportID">Sport</label>
	<select name="data[Network][sport_id]">
		<option value="1">Tennis</option>
	</select><br>

	<?php echo $this->Form->input('name'); ?><br>
	<?php echo $this->Form->input('zip'); ?><br>

	<input type="button" id="requestnet_submit" onClick="requestNetwork();" value="Create">
	<input type="button" onClick="$('#network_modal').modal().hide();" value="Cancel">
	<script type="text/javascript">
		function requestNetwork(){
			//submit network form via ajax
			$('#requestnet_status').html('Submitting Network Request<br>');
			$('#requestnet_submit').attr('disabled','disabled');
			$.post('/getxp/request_network',$('#NetworkForm').serialize(),function(data){
				if(data=="Success"){
					alert("Network request succesful");
					$('#network_modal').modal().hide();
				}
				else {
					console.log("error with network request: ");
					console.log(data);
					$('#requestnet_status').html("<b>Error: Failed Request Network</b>");
					$('#requestnet_submit').attr('disabled','enabled');
				}
			});
		}
	</script>
</div>

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

	<!--
	<div id="innerNav">	
    <a href="/getxp/users/messages">Mailbox (
    <?php echo count($messages); ?>)</a> | <a href="#">Waves (
    <?php echo count($waves); ?>)</a> | <a href="#">Photos</a> | <a href="#">Videos</a>
  </div> -->
  
<div id="rightBlock">
  
	<?php //check for admin- provide admin menu
		if($userinfo['admin']){
			?>
	  <script type="text/javascript">
	  	function adminToggle(){
	  		if($('#adminExpandable').css('display')=='block'){
	  			$('#adminExpandable').css('display','none');
	  			$('#adminTabContent').css('height','0px');
	  			$('#adminToggleSpan').html('+');
	  		}
	  		else {
	  			$('#adminTabContent').css('min-height','200px');
	  			$('#adminExpandable').css('display','block');
	  			$('#adminToggleSpan').html('-');
	  		}
	  	}
	  </script>
      <div class="profileList" id="adminTabContent" style="height: 20px;">
			<br><h2><a href='#' class='btn' onClick='adminToggle();'><span id='adminToggleSpan'>+</span> Admin Menu</a></h2>
			<div id="adminExpandable" style='display: none;'>
			<ul class="xpTabs" data-tabs="tabs">
				<li class="active"><a href="#adminGeneral">General</a></li>
				<li><a href="#adminNetworks">Networks</a></li>
				<li><a href="#adminMedia">Media Center</a></li>
				<li><a href="#adminGames">Games</a></li>
			</ul>
			<div class="row show-grid tab-content" style="margin-left: 10px;">
			
			<div class="tab-pane active" id="adminGeneral">
				General Admin Functions
			</div>

			<div class="tabPane" id="adminNetworks">
				<h3>Manage Networks</h3>
				<b>Pending Network Requests:</b><br>

				<ul>
				<?php
					foreach($networks_pending as $net){
						print "<li>".$net['Network']['name']." - zipcode: ".$net['Network']['zip']." <a class='btn' href='/getxp/admin/approve_network/".$net['Network']['id']."'>Approve</a>".
						"<a class='btn' href='/getxp/admin/deny_network/".$net['Network']['id']."'>Deny</a></li>";
					}
				?>
				</ul>

			</div>
      
			<div class="tab-pane" id="adminMedia">
				<h3>Media Center</h3>
				<a class="btn" onClick="adminShowMedia();">Add Content</a><br>
				<b>Manage Content:</b>
				<ul>

				</ul>
			</div>
			<div class="tab-pane" id="adminGames">
				<h3>Media Center</h3>
				
				<form name="adminAddGame" id="adminAddGame" onSubmit="return false;">
					<textarea name="adminGameCode">Copy embed code for game here</textarea><br>
					<input type=button value="+Add Game"><br>
				</form>
				<b>Manage Content:</b>
				<ul>

				</ul>
			</div>

			</div>
        <!--<div class="rightFloatBox"><a href="#">See All</a></div>-->
        </div><!--adminExpandable-->
      </div>
			<?php
		}
	?>

	<br>
  <div class="profileList" style="min-height: 250px;">
	<h2>Choose your Profile</h2>
	<ul>
		<?php
		for($i=1;$i<7;$i+=1){
			if(isset($plist[$i])){
				print '<li><a href="/getxp/'.strtolower($sports[$i]).'/">';
				print $this->Html->image(strtolower($sports[$i])."_banner_500_off.jpg");
				print "</a></li>";
			}
			else { print ''; }

		}
		?>
	</ul>

  </div>
  
  <div class="profileList">
	<h2>Create New Profile</h2>
	<div class="tab-pane active" id="create_user">
		<?php
		for($i=1;$i<7;$i+=1){
			print '<div class="span3';
			if(isset($sports[$i]) && !isset($plist[$i]) ){
				print ' span-out"><a href="/getxp/'.strtolower($sports[$i]).'/register">'.
					$sports[$i].'</a>';
			}
			else { print '"><a href="#">&nbsp</a>'; }
			print '</div>';
		}
		?>
	</div>
  </div>
  
</div>
</div>