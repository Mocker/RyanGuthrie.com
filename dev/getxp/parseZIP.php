<?php

?>

<h2>Parsing ZIP Codes for Lat/Long</h2>

<?php

$fh = fopen("ZIP_CODES.txt",'r');
if(!$fh){ print "UNABLE TO OPEN ZIP FILES"; exit; }
print "<ol>\n";
while($line = <$fh>){
	print "<li>".$line."</li>\n";
}
print "</ol>";

?>

<h2>DONE</h2>