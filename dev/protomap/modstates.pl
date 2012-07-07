#!/usr/bin/perl -w

#modify state js files

use JSON;
use Data::Dumper;

require "distance.pl";
my @slist;
my %sobjects;

my $json = JSON->new->allow_nonref;
my $jstr = "var Maps = new Object();\n";


@files = <states/*.js>;
foreach $file (@files) {
	#print $file."\n";
	$file =~ /([A-Z]{2}).js/ ;
	if($1){ $state = $1; push(@slist, $state); }
	else { next; }
	$jstr .= "Maps['$state'] = { code: '$state',\nname: '',\njs: '$file',\n };\n";
	#$scmd = 'sed -i -e "s/var Geo[A-Z][A-Z]/var GeoCounties/" states/'.$state.'.js';
	#print $scmd."\n";
	#$serr = system($scmd);
	#if($serr){
	#	print '!!! '.$serr."\n";
	#}
	if($state ne 'WI' && $state ne 'WV' && $state ne 'WY' ){ next; }
	open FILE, "<states/".$state.".js";
	print "Parsing $state\n";
	undef $/;
	$sjson = <FILE>;
	$/ = "\n";
	close FILE;
	$sjson =~ s/var GeoCounties = /{ "GeoCounties": /;
	$sjson =~ s/\,\];$/\]}/;
	#print $sjson ; exit;
	my $sjstr ;
	if( $sjstr = $json->decode($sjson) ){
		#print "PARSED $state - "
		#.@{$sjstr->{'GeoCounties'}}
		#." Counties\n";
		SimplifyState($state, $sjstr->{'GeoCounties'});  

	}
	else { print $sjson; exit; }
	

}

#print "Found ".@slist." states\n";
#print $jstr;
#open FILE, ">statelist.js";
#print FILE $jstr;
#close FILE;

sub SimplifyState {
my ($state, $counties) = @_;
print "Simplifying ".@{$counties}." counties in $state\n";
my $i = 0;
while($i < scalar @{$counties} ){
	my $county = @{$counties}[$i];
	print "Parsing ".$county->{'properties'}->{'name'}."\n";
	if(my $newbord = SimplifyCounty($county->{'geometry'}->{'coordinates'})){
		@{$counties}[$i]->{'geometry'}->{'coordinates'} = $newbord;
}
	$i+=1;
}
	#my $county = @{$counties}[0];
	#print Dumper($county); exit;
	open FILE, ">states/".$state."_lowres.js";
	#print "OPENED FILED FOR ".$counties[0]->{'properties'}->{'name'};
	my $cstr = $json->encode($counties);
	$cstr =~ s/\}\,/\}\,\n/g ;
	$cstr =~ s/\:\s\{/\:\n\t\{/g ;
	$cstr =~ s/\[\s\[/\[\n\[/g;
	$cstr =~ s/^/var GeoCounties = /;
	$cstr =~ s/$/;/;
	print FILE $cstr;
	close FILE;
	

}

sub SimplifyCounty {
	my $borders = shift @_;
	my $i = 0;
	my $limit = 1.5;
	#print "County BOrders: \n".Dumper(\@{$borders}[0]); exit;
	#print "Country borders is ".scalar(@{@{$borders}[0]})." points\n"; exit;
	foreach $poly (@{$borders}){
		my $isOdd = 0;
		#print "Parsing polygon $i.. ";
		my $numcoord = scalar @{$poly};
		if($numcoord == 1){ 
			$numcoord = scalar @{@{$poly}[0]};
			$isOdd = 1; $poly = @{$poly}[0];
			#print "Poly $i is ODD\n";
 		 }
		#else { print "Poly $i is EVEN\n"; }
		#print "$numcoord points\n";
		my @newpoly;
		my @lp; my @np;
		my $isDone = 0;
		while(!$isDone){
			my @newbord = [];
			#print "next loop! ".scalar(@{$poly})." points\n";
			my $matchedpoints = 0;
			@lp = []; @np = [];
		for(my $z = 0; $z<scalar(@{$poly}); $z+=2){	
		#foreach $loc (@{$poly}){
			my @loc1 = @{@{$poly}[$z]};
			if($z>=scalar(@{$poly})-1){
				push(@newbord, [@loc1]);
				next;
			}
			my @loc2 = @{@{$poly}[$z+1]};
			if(!@loc2){
				print "Failed at loc2\n";
				print "z:$z polycount: ".@{$poly}."\n";
				print Dumper(@{$poly}[$z+1])."\n";
				exit;
			}

			#if(scalar @lp < 1){
			#	@lp = @{$loc}; next; }
			#print "LAST POINT: ".Dumper(\@lp)."\n";
			#print "LOC: ".Dumper($loc)."\n"; exit;
			#my $pdif = distance(@{$loc}[1],@{$loc}[0],$lp[1],$lp[0],"K");
			#print "DISTANCE $pdif\n"; #exit;

			my $pdif = distance($loc1[1],$loc1[0],$loc2[1],$loc2[0],"K");
			if($pdif < $limit){
				$matchedpoints +=1;
				#$lp[0] = ($lp[0]+@{$loc}[0])/2;
				#$lp[1] = ($lp[1]+@{$loc}[1])/2;
				$np[0] = ($loc1[0]+$loc2[0])/2;
				$np[1] = ($loc1[1]+$loc2[1])/2;
				push(@newbord, [@np]);
			}
			else {
				push(@newbord, [@loc1]);
				push(@newbord, [@loc2]);
				#@lp = @{$loc};
			}
		}
		 @newpoly =[]; push(@newpoly, @newbord);
		shift @newpoly; shift @newpoly;
		 $poly = \@newpoly ;
		#print "New Poly!:\n".Dumper($poly)."\n"; exit;
		 $isDone = 1 if($matchedpoints < 1);
		}
		#if(scalar @lp > 0){ push(@newbord, [@lp]); }
		if($isOdd){
			print "IS ODD\n";
			@{@{$borders}[$i]}[0] = @newpoly; 
		} else {
		 #print "$i IS EVEN with ".@{@{$borders}[$i]}." points\n";
		#@{$borders}[$i] = @newpoly ; 
		}
		#print "New points: ".@newpoly."\n";
		#print Dumper(\@newpoly)."\n";
		#print Dumper(\@{@{$borders}[$i]}); 
		#exit;
		$i +=1;
	}
	return $borders;
}

