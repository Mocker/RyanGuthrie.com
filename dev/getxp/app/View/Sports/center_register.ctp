
	<div style="width: 400px; margin-left: auto; margin-right: auto; text-align: left;">

	<?php echo $this->Form->create('Center', array(
			'action'=>'docreate',
			'inputDefaults'=> array(			
				'div'=>false))); 
	?>
		<fieldset>
			<legend>Register New Center</legend>
			<input type=hidden name="sport_name" value="<?php echo $sname; ?>">
			<?php echo $this->Form->input('name'); ?><br>
			<?php echo $this->Form->input('description'); ?><br>
			<?php echo $this->Form->input('city'); ?><br>
			<?php echo $this->Form->input('state'); ?><br>
			<?php echo $this->Form->input('zip'); ?><br>
			<?php echo $this->Form->input('phone'); ?><br>
		</fieldset>
		<fieldset>
			<legend>Features</legend>
			<?php echo $this->Form->input('courts'); ?><br>

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

			<input type="submit" name="submit" value="Create Center">
			
			</form>


	</div>
