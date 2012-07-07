<?php echo $this->Form->create('League', array(
			//'url'=>array('controller'=>'Sport','action'=>$sname.'/league/create'),
			'url'=>'/'.$sname.'/league/create',
			//'action'=>'/getxp/'.$sname.'/leagues/create',
			'inputDefaults'=> array(			
				'div'=>false))); 
?>
<fieldset>
			<legend>Create a New League</legend>
			<?php if(isset($center_id)){ ?>
			Center: <?php echo $center_id; ?><br>
			<input type=hidden name="data[League][center_id]" value="<?php echo $center_id; ?>">
			<?php } if(isset($network_id)){ ?>
			Network: <?php echo $network_id; ?><br>
			<input type=hidden name="data[League][network_id]" value="<?php echo $network_id; ?>">
			<?php } ?>
			<?php echo $this->Form->input('name'); ?><br>
</fieldset>
<fieldset>
			<legend>Details</legend>
			<?php echo $this->Form->input('max_competitors'); ?><br>
			<?php echo $this->Form->input('type',array('options'=>array(
				"auto_rr"=>"Automatic Round Robin",
				"man_rr"=>"Manual Round Robin",
				"man_fix"=>"Manual Fixtures",
				"auto_ladder_classic"=>"Automatic Ladder (Classic)",
				"auto_ladder_advanced"=>"Automatic Ladder (Advanced)",
				"man_ladder"=>"Manual Ladder"

				))); ?><br>
			<?php echo $this->Form->input('format',array('options'=>array(
				'single'=>'Single',
				'double'=>'Double'))); ?><br>
			<?php echo $this->Form->input('match_type',array('options'=>array(
				'best_3_6_sets'=>'Best of 3 - 6 Game Sets',
				'best_3_tiebreaker'=>'Best of 3- 3rd Set Tiebreaker',
				'best_5_6_sets'=>'Best of 5 - 6 Game Sets',
				'best_5_tiebreaker'=>'Best of 5 - 5th Set Tiebreaker',
				'8_superset'=>'Eight-game Superset',
				'10_superset'=>'10-game Superset',
				'normal_6'=>'Normal 6 Game Set'
				))); ?><br>
			<?php echo $this->Form->input('championship',array('options'=>array(
				'auto_group'=>'Automatic Group Stage',
				'man_group'=>'Manual Group Stage',
				'auto_bracket'=>'Automatic Bracket',
				'man_bracket'=>'Manual Bracket'))); ?><br>
			<?php echo $this->Form->input('fee'); ?><br>
			<?php echo $this->Form->input('start'); ?><br>
			<?php echo $this->Form->input('end'); ?><br>
			<?php echo $this->Form->input('age_min'); ?><br>
			<?php echo $this->Form->input('age_max'); ?><br>
			<br>
			<input type=submit value='Create League'>

</fieldset>
</form>