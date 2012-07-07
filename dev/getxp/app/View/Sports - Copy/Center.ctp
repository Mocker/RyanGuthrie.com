
<h2><?php echo $prof['Center']['name']; ?></h2>

<div style="width: 100%; height: 100px; border-bottom: 2px solid black; border-top: 2px solid black;">
<div style="float: left; width: 100px; height: 100px; background-color: #ccc;">Profile Pic</div>
<div style="float: left; width: 300px;">
<style>
	ul.vertmenu li { width: 100px; float: left; }
</style>
<ul class="vertmenu" style="position: relative; float: left; width: 120px;">
	<li><a href="#">Photo Album</a></li>
	<li><a href="#">Video Album</a></li>
	<li><a href="#">Message</a></li>
	<li><a href="#">Become a Fan</a></li>
	<li><a href="#">Join Membership</a></li>
</ul>

<b>Status Update:</b><br>
Lorem Ipsum

</div>
</div>


<ul class="tabs" data-tabs="tabs">
	<li class="active"><a href="#about">About</a></li>
	<li><a href="#bulletins">Bulletin</a></li>
	<li><a href="#reviews">Reviews</a></li>
	<li><a href="#proshop">Pro Shop</a></li>
	<li><a href="#reserve">Reserve</a></li>
	<li><a href="#halloffame">Hall of Fame</a></li>
	<li><a href="#announcements">announcements</a></li>
	<li><a href="#competitions">Competitions</a></li>
</ul>
<div class="tab-content" id="prof_tab" style="width: 100%; background: #fff; min-height: 200px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; max-height: 650px; overflow-y: auto;">
	<div id="blank" class="tab-pane">PLACEHOLDER TAB</div>
	<div id="about" class="tab-pane active">
		<h2>About <?php print $prof['Center']['name']; ?></h2>
		<blockquote><? echo $prof['Center']['description']; ?></blockquote>
		<b>Location:</b><br>
		<?php 
		print $prof['Center']['street']."<br>".$prof['Center']['city'].", ".$prof['Center']['state'];
		print "<br>".$prof['Center']['zip']."<br><b>Phone: </b>".$prof['Center']['phone'];
		?>
		
		
	</div>

	<div id="bulletins" class="tab-pane">Bulletins</div>

	<div class="tab-pane" id="reviews">
		<script type="text/javascript">
			function sendComment(frmObj){
				alert(frmObj.comment.value);
			}
		</script>
		<br>
		<center>
		<form id="frmComment" onSubmit="return false;">
			<input class="span4" type="text" name="comment" placeholder="Type Comment here">
			<button class="btn primary" onClick="sendComment(this.parentNode);">Submit</button>
		</form>
		</center>
		<ul style="width: 90%; list-style-type: square;">
			<li> 
				<div style="float: right; width: 70%;">
				<blockquote>blahblaha asfs sdfsdfsd afdsf sdfsdfsd fsdfsdfd sdfsdfdsfsd
				sfdsfs sdgdgd fdgdfgfd </blockquote>
				<ul style="list-style-type: none; width: 100%;">
					<li style=" float: left;"><b>Comment</b></li>
					<li style=" float: left;"><b>Like</b></li>
					<li style=" float: left;"><b>Dislike</b></li>
					<li style=" float: left;"><b>Remove</b></li>
				</ul>
				</div>
				<div style="width: 30%;">
					<b>Bob Smith wrote:</b>
				</div>
			</li>

		</ul>

	</div>


	<div class="tab-pane" id="proshop">
		<h4>Pro Shop</h4>

	</div>

	<div class="tab-pane" id="reserve">Reserve a Court</div>

	<div class="tab-pane" id="halloffame">
		<b><?php echo $prof['Center']['name']; ?> Hall of Fame</b>
	 </div>

	<div class="tab-pane" id="announcements">Announcements</div>

	<div class="tab-pane" id="competitions">Competitions</div>

	

</div>







