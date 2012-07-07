<h3>Create a New Tournament</h3>
<hr>
<?php echo $this->Form->create('Tournament', array(
			'url'=>'/'.$sname.'/tournament/create',
			'inputDefaults'=> array(			
				'div'=>false)));
				
	/*
	array(
		'single elim'=>'Single Elimination',
		'double elim'=>'Double Elimination',
		'round robin'=>'Round Robin')
	*/ 
?>
<fieldset>
	<legend></legend>
	<?php echo $this->Form->input('name'); ?><br>
	<?php echo $this->Form->input('description'); ?><br>
	<?php if(isset($center_id)){ print "<b>Located at Center $center_id</b><br>\n"; 
		print "<input type=hidden name='center_id' value='$center_id'>"; }?>
	<?php if(isset($network_id)){ print "<b>Located at Network $network_id</b><br>\n"; 
		print "<input type=hidden name='network_id' value='$network_id'>";}?>
	<br>
	<?php echo $this->Form->input('registration',array('options'=>array(
		'open'=>'Open',
		'invite'=>'Invite Only'))); ?><br>
	<?php echo $this->Form->input('starts'); ?><br>
	<?php echo $this->Form->input('format',array('options'=>array(
		'single elim'=>'Single Elimination',
		'double elim'=>'Double Elimination',
		'round robin'=>'Round Robin'))); ?><br>
	<?php echo $this->Form->input('max_teams',array('label'=>'Max Competitors')); ?><br>
	<?php echo $this->Form->input('pools'); ?><br>
	
	<input type="submit" value="Create">
</fieldset>
</form>