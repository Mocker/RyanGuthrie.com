<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title_for_layout?></title>
<?php echo $this->Html->script('jquery-1.7.1.min.js'); ?>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-modal.js"></script>
<script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-tabs.js"></script>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-modal.js"></script>

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css">
<?php /*<!-- Include external files and scripts here (See HTML helper for more info.) -->*/?>
<?php echo $scripts_for_layout ?>
</head>
<body>

<?php
//<!-- If you'd like some sort of menu to 
//show up on all of your views, include it here -->
?>


<?php echo $content_for_layout ?>

<?php
//<!-- Add a footer to each displayed page -->
?>

</body>
</html>
