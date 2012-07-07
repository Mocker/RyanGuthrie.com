<style>
#flashMessage { font-weight: bold; font-size: 1.2em; color: #cc6666;}
</style>
<script type="text/javascript">
document.body.onload = function(){
	//console.log("done loading");
	document.getElementById('UserBirthMonth').style.width="120px";
	document.getElementById('UserBirthDay').style.width="50px";
	document.getElementById('UserBirthYear').style.width="120px";
	$('.tabs').tabs();
	//console.log(document.getElementById('UserBirthMonth').style);
}
</script>
<div class="container">
<div class="page-header"><h2>Login Page</h2></div>
<div class="topbar-wrapper">
	<div class="topbar">
		<div class="topbar-inner">
			<div class="container">
				<h3><a href="#">GetXP</a></h3>
				<!--<form class="pull-left">-->
				<?php echo $this->Form->create('User', array('action' => 'login')); ?>
					<input type="text" name="data[User][username]" id="UserUsername" placeholder="Email">
					<input type="password" name="data[User][password]" id="UserPassword" placeholder="Password">
					<input type="submit" name="submit" value="Login">
				
				<?php
				/*
				echo $this->Form->inputs(array(
					'legend' => __('Log In', true),	
					'username',
					'password'
				));*/
		?>
				</form>
			</div>
		</div>
	</div>
</div>
<?php print $this->Session->flash(); ?>
<div class="row">
	<div class="span8" style='background-color: #ffcccc; min-height: 400px; padding-top: 150px;'>
		<br>
		<center>
		<div style='width: 80%; background-color: #cccccc; border: 2px solid black;min-height: 200px;'><center><h2>Logo</h2></center></div>
		<div style='width: 80%;'><center>Space for Motto/Phrase</center></div>
		</center>
	</div>
	<div class="span8" style='background-color: #ccffcc; min-height: 400px; padding-top: 150px;'>
		<!--<form action="/getxp/users/login">-->
		
		<?php echo $this->Form->create('User', array(
			'action'=>'register',
			'inputDefaults'=> array(
				
				'div'=>false))); 
		?>
		<fieldset>
			<legend>Not a member already?<br> Sign up for free!</legend>
			<?php echo $this->Form->input('first_name'); ?>
			<?php echo $this->Form->input('middle_name'); ?>
			<?php echo $this->Form->input('last_name'); ?>
			<?php echo $this->Form->input('username',array('label'=>'Email')); ?>
			<label for='confirm_email'></label>
			<input type="text" name="confirm_email" placeholder="Confirm Email">
			
			<?php echo $this->Form->input('birth'); ?>
			<?php echo $this->Form->input('password'); ?>
			<label for='c_password'></label>
			<input type="password" size="30" name="c_password" placeholder="Confirm Password">
			<!--
			<input type="text" name="first_name" placeholder="First Name">
			<input type="text" name="middle_name" placeholder="Middle Name(optional)">
			<input type="text" name="last_name" placeholder="Last Name">
			<input type="text" name="email" placeholder="Email">
			<input type="text" name="c_email" placeholder="Confirm Email">
			<input type="text" name="birth" placeholder="Date of Birth">
			<input type="text" name="password" placeholder="Password"> -->
			
			<input type="submit" name="submit" value="Register">
		</fieldset>
		</form>
		
	</div>
</div>

</div>