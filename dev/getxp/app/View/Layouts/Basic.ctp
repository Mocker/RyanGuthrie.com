<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title_for_layout?></title>
<?php echo $this->Html->script('jquery-1.7.1.min.js'); ?>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-modal.js"></script>
<script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-tabs.js"></script>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-dropdown.js"></script>

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css">

<script type="text/javascript">
	//create fake console so debugging on older browsers doesnt break it
	if(!console){
		var console = new Object();
		console.log = function(str){
			return;
		}
	}
</script>

<?php echo $this->Html->css('mainStyle.css'); ?>
<?php /*<!-- Include external files and scripts here (See HTML helper for more info.) -->*/?>
<?php echo $scripts_for_layout ?>
</head>
<body>

<div class="page">

<?php
//<!-- If you'd like some sort of menu to 
//show up on all of your views, include it here -->
?>



<div id="content">

<div id="stage">


<?php echo $content_for_layout ?>

<?php
//<!-- Add a footer to each displayed page -->
?>

</div>

</div>

<div id="botBar">
&copy <a href="#">GetXP</a> | <a href="#">About Us</a> | <a href="#">FAQ</a> | <a href="#">Copyright</a> | <a href="#">Help</a> | <a href="#">Terms</a>
</div>

</div>

</body>
</html>
