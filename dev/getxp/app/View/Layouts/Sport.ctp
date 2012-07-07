<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title_for_layout?></title>
<?php echo $this->Html->script('jquery-1.7.1.min.js'); ?>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-modal.js"></script>

<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-dropdown.js"></script>

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css">
<!--<link rel="stylesheet" href="http://devgetxp.ryanguthrie.com/getxp/css/mainStyle.css">-->
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
<!-- include bootstrap tabs afterwards to overwrite jquery ui tabs -->
<script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-tabs.js"></script>
</head>
<body>

<div class="page">

<?php
//<!-- If you'd like some sort of menu to 
//show up on all of your views, include it here -->
?>

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
				<li><a href="#">Change Profile</a></li>
				<li><a href="#">Site Features</a></li>
				<li><a href="#">Account Settings</a></li>
				<li><a href="#">Advertisement</a></li>
				<li><a href="/getxp/users/logout">Logout</a></li>
				</ul>
		</li>
		<li style='width: 120px;'>&nbsp;</li>
		<li>
			<form name='frmTopSearch' method='POST' action='/getxp/search' onSubmit='return false;'>
			<input type=hidden name='sport_name' value='<?php echo $sname; ?>'>
			<input type=hidden name='searchType' value='all'>
			<input type="text" name="playerName" id='topSearchText' placeHolder="Search"></li>
		<li><input type="submit" value="Go"></form>
		</li>
		<li><a href="/getxp/users/messages">Mailbox (<?php echo count($messages); ?>)</a></li>
	    <li><a href="#">Waves (<?php echo count($waves); ?>)</a>  <li>
	    <li><a href="#">Photos</a></li>
	    <li><a href="#">Videos</a></li>
		



	</ul>
	<!--<div class="chatButton"><a onClick='toggleChat();'>Chat</a></div>-->
	<div class="IMButton"><a onClick='toggleIM();'>IM</a></div>
	<!--
	<div id="innerNav" style=" position: relative; float: right; top: 25px; margin-right: 160px; ">	 
    
 </div> -->
	</div>
	
<?php echo $this->element('im'); ?>

<div id="content">

<div id="stage">
<style>

div.page-logo {
	position: relative; float: left; width: 200px; min-height: 120px; height: 120px;
}
div.page-header { text-align: center; }
#content_container {
	color: black;
}
#content_container h2 {
	color: black;
}
#content_container a{
	color: black;
}
a { color: black;}
a:link { color: black; }
a:visited { color: black; }
</style>
<div class="container">

<!-- Chat and IM Windows -->
	
	


<div class="row" style="min-height: 90px; width: 1000px; margin: auto; padding-top: 10px;">
	<div style='background-color: #004400; width: 950px;'>
	<center>
	
	<?php echo $this->Html->image('tennis_banner_500_on.jpg'); ?></center></div>
	<br>
	<center>

<!--
<div class="span-one-third" style="background-color: #ccffcc; height: 110px;">
	<div style="width: 100%; height: 100%; text-align: middle; background-color: #ccc; border: 2px solid black;">
		<?php echo $this->Html->image('GetXP_300x110.png'); ?>
	</div>
	<br>
-->
<style>
ul.tabs li { width: 25%;}
ul.tabs { width: 100%; }
a.dropdown-toggle{ color: #ccccff;}
</style>
<!--	old nav bar


</div>



<div class="span-two-thirds" style="  min-height: 90px; " >
<style>
ul.pills li{
	height: 30px; max-height: 30px; overflow: hidden;
	
	z-index: 2000;
}
ul.pills li a {
	margin: none;
	z-index: 3000;
	background-color: #ffdddd;
}
#vertmenu {
	position: relative;
	float: left; height: 200px; width: 120px; z-index: 120;
	color: black;
}
#vertmenu a { color: #ddd; }
#vertmenu ul { list-style-type: arrow; }
.nav a { color: #fff; }
.nav a:visited{ color: #fff; }
</style>

	<div id="vertmenu" >
		
		<ul style="width: 120px; z-index: 150; ">
			
		</ul>
	</div>

	<div id="nearby" style="width: 400px; position: relative; float: right; margin-right: 100px;">
	<ul style="list-style-type: none; float: right;">
		<li style="width: 100px; height: 100px; float: left; background-color: #bbb;"><h3>NEARBY MEMBERS</h3></li>
		<li style="width: 100px; height: 100px; float: left; background-color: #bbb; margin-left: 50px;"><h3>NEARBY CENTERS</h3></li>
	</ul>
	</div>

	

</div>
-->
<!--
</div>

<div class="row" style="position: relative; top: -70px;">
	<center>
	<div class=""> -->
			<ul class="nav" data-dropdown="dropdown" style="background-color: #333; z-index: 100; width: 500px; float: none; margin-left: auto; margin-right: auto; clear: both;">
		<li><a href="/getxp/">Home</a></li>
		<li class="dropdown" ><a href="#" class="dropdown-toggle">Profile</a>
			<ul class="dropdown-menu">
				<li><a href="/getxp/<?php echo $sname; ?>/profile">View</a></li>
				<li><a href="/getxp/<?php echo $sname; ?>/profile/edit">Edit</a></li>
			</ul>
		</li>
		<li class="dropdown" ><a href="#" class="dropdown-toggle">Friends</a>
			<ul class="dropdown-menu">
				<li><a href="/getxp/<?php echo $sname; ?>/friends">All</a></li>
				<li><a href="/getxp/<?php echo $sname; ?>/friends/edit">Edit</a></li>
				<li><a href="/getxp/<?php echo $sname; ?>/friends/invite">Invite</a></li>
			</ul>
		</li>
		<li class="dropdown" ><a href="#" class="dropdown-toggle">Find</a>
			<ul class="dropdown-menu">
				<li><a href="/getxp/<?php echo $sname;?>/search/players">
					Players</a></li>
				<li><a href="/getxp/<?php echo $sname;?>/search/centers">
					Centers</a></li>
				<li><a href="/getxp/<?php echo $sname;?>/search/networks">
					Networks</a></li>
			</ul>
		</li>
		<li class="dropdown" ><a href="#" class="dropdown-toggle">Media</a>
			<ul class="dropdown-menu">
				<li>News</li>
				<li>Blogs</li>
				<li>Forum</li>
				<li>Polls</li>
				<li>Videos</li>
				<li>Photos</li>
			</ul>
		</li>
		<li><a href="#">Pro Shop</a></li>
		<li><a href="#">Competition</a></li>
	</ul>
<!--
	</div>
	</center>
</div>
--></div>
<div class="row" style="position: relative; top: 0px;">

<div class="span3" style="background-color: #eeeeee; min-height: 400px; border: 2px inset black;">
<div style="width: 100px; height: 100px; margin: 10px; background-color:#ccc; border: 1px solid black;">

Profile Pic</div><br><br>
Current Centers<br>
<?php
	if(isset($centersOwned) && count($centersOwned)> 0){
		print "<b>Owned:</b><br>"; //print_r($centersOwned); print "<br>";
		foreach($centersOwned as $center){
			print "<a href='/getxp/$sname/centers/".$center['Center']['id'].
			"'><b>".$center['Center']['name']."</b></a> ".
			$center['Center']['city'].", ".$center['Center']['state']."<br>";
		}

	}
	if(isset($centersMember) && count($centersMember) > 0){
		//print_r($centersMember); print"<br>";
		print "<b>Member:</b><br>";
		foreach($centersMember as $center){
			print "<b>".$center['Center']['name']."</b> ".
			$center['Center']['city'].", ".$center['Center']['state']."<br>";
		}
	}
?>

<a href='/getxp/<?php echo $sname; ?>/centers/create' class='btn'>+Add new Center</a><br>
Current Networks<br>
<?php
if(isset($networksOwned) && count($networksOwned) > 0){
		//print_r($networksOwned); print "<br>";
		foreach($networksOwned as $net){
			print "<a href='/getxp/$sname/network/".$net['Network']['id'].
			"'><b>".$net['Network']['name']."</b></a> ".$net['Network']['zip']."<br>";
		}

	}
?>
<a href='/getxp/<?php echo $sname; ?>/networks/create' class='btn'>+Request new network</a>
<br>
Ad Space
</div>

<div class="span9" style="min-height: 400px; background-color: #eeeeee; margin-left: 50px; border: 2px outset black;" id="content_container">
	

<?php echo $content_for_layout ?>

</div>

<div class="span3" style="min-height: 400px; background-color: #eeeeee; border: 2px inset black; float: right;">
AD SPACE
</div>


</div>



</div>

</div>

<div id="botBar">
&copy <a href="#">GetXP</a> | <a href="#">About Us</a> | <a href="#">FAQ</a> | <a href="#">Copyright</a> | <a href="#">Help</a> | <a href="#">Terms</a>
</div>

</div>

</body>
</html>