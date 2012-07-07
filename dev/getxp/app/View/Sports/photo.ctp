<?php $this->Html->script('galleria-1.2.6.js',array('inline'=> false)); ?>
<script>
    Galleria.loadTheme('/getxp/css/themes/classic/galleria.classic.min.js');
    $(document).ready(function(){
    	$('#gallery').galleria({
    		width: 450,
    		height: 350
    	});
    });
</script>
<h3>Photo Album for <?php echo $aname; ?></h3>

<div id="galleryWrap" style='width: 450px; border: 2px inset black; margin-left: auto; margin-right: auto;'>
<center>
<?php if($editmsg){ print "<h3>$editmsg</h3>"; } ?>
<?php if($isowner){ ?>
	<b>You own this album. <a onClick="$('#photoUpload').toggle();">Upload a new picture? </a></b>
	<div id='photoUpload' style='display: none;'>
	<?php echo $this->Form->create('Photo',array('type'=>'file')); ?>
	<?php echo $this->Form->input('caption'); ?><br>
	<?php echo $this->Form->input('file',array('type'=>'file')); ?>
	<?php echo $this->Form->input('url'); ?><br>
	<input type='submit' value='Add Photo'>
	</form>
	</div>
<?php } ?>
 <div id="gallery" >
 <?php
 	foreach($photos as $photo){
 		print "<img src='/getxp/img/user_photos/".$photo['Photo']['file']."'>";
 	}
 ?>
 </div>
</center>
</div>