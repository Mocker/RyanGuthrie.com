
	<h4>Create Profile</h4>
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


			<input type="submit" name="submit" value="Create Profile">
			
			</form>


