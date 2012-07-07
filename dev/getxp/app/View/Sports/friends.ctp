FRIENDS LIST<br>
<br>
<style>
ul.biglist {
	width: 400px;
	font-size: 1.4em;
	color: black;
}
ul.biglist a {
	color: #330000;
}
ul.biglist li {
	width: 400px; margin-bottom: 10px; background-color: #ddd;
}
</style>
<ul class="biglist">
	<?php foreach($friends as $friend){
		print "<li>".$sportlist[$friend['Friend']['sport_id']]." - ";
		$buddy = array();
		if($friend['Profile']['user_id']==$userinfo['id']){
			$buddy = $friend['ProfileT'];
			print '<a href="/getxp/'.$sportlist[$friend['Friend']['sport_id']].
			'/profile/'.$buddy['id'].'">'.$buddy['name'].'</a>';
		}
		else if($friend['ProfileT']['user_id']==$userinfo['id']){
			$buddy = $friend['Profile'];
			print '<a href="/getxp/'.$sportlist[$friend['Friend']['sport_id']].
			'/profile/'.$buddy['id'].'">'.$buddy['name'].'</a>';
		}
		else {
			print "Error reading friend entry";
		}
		print "</li>";
	}?>
</ul>