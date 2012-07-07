<h3>Video Album for <?php echo $aname; ?></h3>

<div id="galleryWrap" style='width: 450px; border: 2px inset black; margin-left: auto; margin-right: auto;'>
<center>
 <?php if($editmsg){ print "<h3>$editmsg</h3>"; } ?>
<?php if($isowner){ ?>
	<b>You own this album. <a onClick="$('#photoUpload').toggle();">Upload a new picture? </a></b>
	<div id='photoUpload' style='display: none;'>
	<?php echo $this->Form->create('Video'); ?>
	<?php echo $this->Form->input('caption'); ?><br>
	<?php echo $this->Form->input('embed'); ?>
	<?php echo $this->Form->input('url'); ?><br>
	<input type='submit' value='Add Video'>
	</form>
	</div>
<?php } ?>
<div id="gallery">
 	<ul style='list-style-position: outside; position: relative; left:-27px;'>
 	<?php foreach($videos as $video){
 		print "<li><h4><a href='".$video['Video']['url']."'>".$video['Video']['caption']."</a></h4><br>".
 			$video['Video']['embed']."</li>";
 	} ?>
 	</ul>
</div>
</center>
</div>