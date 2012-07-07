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
<!-- TOP BAR -->
<style>
		div.chatButton {
			position: relative;
			float: left; width: 50px; background-color: #ddd; height: 30px;
			left: 150px;
			z-index: 300;
		}
		div.IMButton {
			position: relative;
			float: right; width: 50px; background-color: #ddd; height: 30px;
			right: 200px;
			
			z-index: 300;
		}
		div.fixedTop {
			position: fixed;
			z-index: 200;
			top: 0px;
			width: 100%;
			/*background-color: black;*/
			  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background: #2F2F2F; /* Old browsers */
  background: -moz-linear-gradient(top, #565656 0%, #1c1c1c 87%, #131313 100%); /* FF3.6+ */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#565656), color-stop(87%,#1c1c1c), color-stop(100%,#131313)); /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top, #565656 0%,#1c1c1c 87%,#131313 100%); /* Chrome10+,Safari5.1+ */
  background: -o-linear-gradient(top, #565656 0%,#1c1c1c 87%,#131313 100%); /* Opera 11.10+ */
  background: -ms-linear-gradient(top, #565656 0%,#1c1c1c 87%,#131313 100%); /* IE10+ */
  background: linear-gradient(top, #565656 0%,#1c1c1c 87%,#131313 100%); /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#565656', endColorstr='#131313',GradientType=0 ); /* IE6-9 */
			
			height: 46px;
		}
	ul.navtop {
		margin-left: 100px;
	}
	ul.navtop li {
		margin-left: 10px; 
	}
	ul.navtop li a { color: white; }
	#innerNav { color: white; }
	#innerNav a { color: white; }
	</style>
	<div class="fixedTop">
	<ul class="nav navtop" data-dropdown="dropdown" style="background-color: #222; z-index: 100; ">
		<li><div style="width: 40px; height: 40px; background-color: red;">GETXP</div></li>
		<li><div style="width: 40px; height: 40px; background-color: red;">PIC</div></li>
		<li class="dropdown" ><a href="#" class="dropdown-toggle">
			<?php echo $userinfo['first_name']." ".$userinfo['last_name']; ?></a>
			<ul class="dropdown-menu">
				<li><a href="#">Site Features</a></li>
				<li><a href="#">Account Settings</a></li>
				<li><a href="#">Advertisement</a></li>
				<li><a href="/getxp/users/logout">Logout</a></li>
				</ul>
		</li>
		<li style='width: 120px;'>&nbsp;</li>
		<li>
			<form name='frmTopSearch' method='POST' action='/getxp/search' onSubmit='return false;'>
			<input type=hidden name='sport_name' value='none'>
			<input type=hidden name='searchType' value='all'>
			<input type="text" name="playerName" id='topSearchText' placeHolder="Search"></li>
		<li><input type="submit" value="Go"></form>
		</li>
		<li><a href="/getxp/users/messages">Mailbox (<?php echo count($messages); ?>)</a></li>
	    <li><a href="#">Waves (<?php echo count($waves); ?>)</a>  <li>
	    <li><a href="#">Photos</a></li>
	    <li><a href="#">Videos</a></li>
		



	</ul>
	<!--
	<div class="IMButton"><a onClick='toggleIM();'>IM</a></div>
    
	 -->
</div>


<div id="content" >

<div id="stage" >


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
