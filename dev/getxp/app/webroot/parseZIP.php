<?php

?>

<h2>Parsing ZIP Codes for Lat/Long</h2>

<?php

$sql = mysql_connect('localhost','root','');
if(!$sql){ print "<h2>ERROR: NO MYSQL CONNECTION</h2>"; exit; }
mysql_select_db('getxp',$sql);
print "<h4>MySQL connected..</h4>";


$fh = fopen("ZIP_CODES.txt",'r');
if(!$fh){ print "UNABLE TO OPEN ZIP FILES"; exit; }
print "<ol>\n";
while($line = fgets($fh)){
	//print "<li>".$line."</li>\n";
	$parts = preg_split('/,/',$line);
	print "<li>".print_r($line,true)."</li>";

}
print "</ol>";

?>

<h2>DONE</h2>