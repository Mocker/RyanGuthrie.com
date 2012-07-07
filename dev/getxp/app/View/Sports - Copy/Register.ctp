<style>

div.page-logo {
	position: relative; float: left; width: 200px; min-height: 120px; height: 120px;
}
div.page-header { text-align: center; }
</style>
<div class="container">
<div class="page-header span16"><h2><?php echo $sname; ?> Create Profile</h2></div>
<div class="row" style="min-height: 90px; width: 1000px; margin: auto; padding-top: 10px;">

<div class="span-one-third" style="background-color: #ccffcc; height: 90px;">
	<div style="width: 100%; height: 100%; text-align: middle; background-color: #ccc; border: 2px solid black;">
		LOGO
	</div>
</div>


<div class="span-two-thirds" style="  min-height: 90px; background-color: #ffffff;" >
	<div class="topbar-wrapper" style="z-index: 5; width: 620px; position: relative;left:0px;">
	<div class="topbar2">
	<div class="topbar-inner" style="background: none;">
	<div class="container" style="width: 100%; background: none; ">
		
		<ul class="nav" style="float: right; background-color: #ccccff;">
			
			<li><a href="#">Mailbox</a></li>
			<li><a href="#">Waves</a></li>
			<li><a href="#">Photos</a></li>
			<li><a href="#">Videos</a></li>
			<li><a href="#"><span class="label success">!4</span></a></li>
		</ul>

	</div></div></div></div>

	Meet other tennis enthusiasts using the player search
</div>

</div>
<div style=" width: 800px; margin-left: auto; margin-right: auto; min-height: 300px; background-color: #ccffcc; margin-top: 50px;">
	<div style="width: 500px; margin-left: auto; margin-right: auto; text-align: left;">

	<?php echo $this->Form->create('Profile', array(
			'action'=>'register',
			'inputDefaults'=> array(			
				'div'=>false))); 
	?>
		<fieldset>
			<legend>Details</legend>
			<input type=hidden name="sport_name" value="<?php echo $sname; ?>">
			<?php echo $this->Form->input('name'); ?><br>
			<?php echo $this->Form->input('hometown'); ?><br>
			<?php echo $this->Form->input('occupation'); ?><br>
			<?php echo $this->Form->input('education'); ?><br>
			<?php echo $this->Form->input('favorites'); ?><br>
		</fieldset>
		<fieldset>
			<legend>Ability Level</legend>
			<?php echo $this->Form->input('level'); ?><br>
		</fieldset>
		<?php /*
		<fieldset>
			<legend>Looking For</legend>
			<ul class="inputs-list" style="margin-left: auto; margin-right: auto;">
				<li><label> 
					<?php echo $this->Form->checkbox('lf_recreation'); ?>
					<span>Recreation</span>
					</label>
				</li>
				<li><label>
					<?php echo $this->Form->checkbox('lf_socializing'); ?> <span>Socializing</span>
				</label></li>
				<li><label>
					<?php echo $this->Form->checkbox('lf_competition'); ?> <span>Competition</span>
				</label></li>
				<li><label>
					<?php echo $this->Form->checkbox('lf_relationship'); ?> <span>Relationship</span>
				</label></li>
			</ul>
			
		</fieldset>
		*/ ?>

			<input type="submit" name="submit" value="Create Profile">
			
			</form>


	</div>
</div>