# functions for calculating distance between lat/long points

#use Math::Trig 'acos';

$pi = atan2(1,1) * 4;

sub distance { 
	my ($lat1, $lon1, $lat2, $lon2, $unit) = @_;
	#print "LAST: $lat1 LON1: $lon1 LAT2: $lat2 LON2: $lon2\n";
	my $theta = $lon1 - $lon2;
	#print "THETA: $theta  deg2radLAT1: ".sin(deg2rad($lat1))."\n";
	my $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) 
	+ cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	#print "DIST: $dist ";
	$dist = acos($dist); #print "ACOS: $dist "; 
	#$fakecos = acoss($dist); print " ACOSS: $fakecos\n"; exit;
	$dist = rad2deg($dist); #print " rad2deg: $dist ";
	$dist = $dist*60*1.1515; #print " $dist ";
	if($unit eq "K"){
		$dist = $dist*1.609344;
	} elsif ($unit eq "N"){
		$dist = $dist *0.8684;
	}
	return ($dist);
}

sub acos {
	my ($rad) = @_;
	my $ret;
	eval {
		
		$ret = atan2(sqrt(1- $rad*$rad), $rad);
	};
	if($@){
		$ret = 0;
		#print $@."\n";
		#die "atan is ".atan2(0, $rad)."\n";
		#die "Cannot take square root .. \nrad: ".($rad+1)."\n".($rad-1)."\n".(1-$rad)."\n";
	}
	#die "ACOS of $rad is $ret\n";
	return $ret;
}

sub deg2rad {
	my ($deg) = @_;
	return ($deg * $pi / 180);
}

sub rad2deg {
	my ($rad) = @_;
	return ($rad * 180/$pi);
}

#print distance(32.9697, -96.80322, 29.46786, -98.53506, "K")." Kilos\n";
#print distance(-71.330402, 41.892528, -71.339498, 41.892499, "K")." KILOS\n";
