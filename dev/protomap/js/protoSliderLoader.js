/****************************
/** jQuery based ajax loader and parser
/*  Used for loading external xml data and converting it to a format
/*  that can be used with the protoSlider classes
*  returned data should be format year.quarter = [array of data] for bar/donut
*********************************/

/* constructor takes url of xml document, and a callback function for when */
/* it is done parsing */

ProtoSliderLoader = function(opts){
	this.opentag = 'data';
	if(opts['opentag']){ this.opentag = opts['opentag']; }
	this.type = 'bar'; if(opts['type']){ this.type = opts['type']; }
	this.opts = opts;
	this.callback = opts['callback'];
	this.url = opts['url'];	
	this.url2 = false; this.url1ready = false; this.url2ready = false;
	if(opts['url2']){ this.url2 = opts['url2']; }
	var that = this;
	this.geoMap = false; //check to see if parsed geo codes into counties
	that.tag = false; if(opts['tag']){ that.tag = opts['tag']; }


	this.data = new Object();

	if(this.type != 'map' || this.geoLoaded){
		//that.requestData();
	}
	else {
		if(!opts['geoURL']){ console.log('missing url to get geo codes'); return; }
		this.geoURL = opts['geoURL'];
		$.ajax({
			type: "GET",
			url: this.geoURL,
			dataType: "text",
			success: function(txt){
				console.log("Retrieved geo codes text. Parsing.. ");
				that.geoMap = new Object();
				var lines = txt.split("\n");
				for(i in lines){
					var segs = lines[i].split(',');
					that.geoMap[segs[0]] = segs[1];
				}
				//console.log(lines);
				console.log(that.geoMap);
				that.requestData();
			}
		});	
	}


	this.requestData = function(){
		var that = this;
		$.ajax({
	        type: "GET",
			url: this.url,
			dataType: "xml",
		success: function(xml) {
	 		that.url1done(xml);
			}
		});
		if(this.url2){
			$.ajax({
	        type: "GET",
			url: this.url2,
			dataType: "xml",
			success: function(xml) {
		 		that.url2done(xml);
				}
			});
		}
	}

	this.url1done = function(xml){
		this.url1ready = true;
		this.data['url1data'] = this.parseData(xml);
		if(this.type != 'bar' || !this.url2 || this.url2ready){
			if(this.url2 && this.callback){  this.callback( this.mergeData() ); }
			else if(this.callback){ this.callback(this.data);}
		}
	}
	this.url2done = function(xml){
		this.url2ready = true;
		this.data['url2data'] = this.parseData(xml);
		if( this.url1ready){
			//this.mergeData();
			if(this.callback){ this.callback( this.mergeData() ); }
		}
	}

	//merge ulr2data and ulr1 data into single data structure and return it
	this.mergeData = function(){
		console.log("Merging both loaded docs");
		var dat = this.data['url1data']; 
		var that = this;
		console.log(dat);
		/*
		$(this.data['url1data']).each(function(){
			//loop through each year
			console.log('next year');
			var yearO = this;
			$.each(this,function(i,v){
			//loop through each quarter
				var counter = 0;
				console.log(yearO); console.log(i);
				while(counter < yearO['url1data'][i].length() ){
					//if(this.data['url2data'][this.])
					dat[yearO][i][counter] = 0;
					counter += 1;
				}
				
					
			});
		});*/
		$.each(this.data['url1data'],function(year,yeardat){
			//console.log("year "+year);
			$.each(that.data['url1data'][year],function(quart,quartdat){
				var counter = 0;
				//dat[year][quart] = new Array();
				//console.log(quartdat);
				/*
				while(counter < quartdat.length() ){
					dat[year][quart][counter] = new Array(
						that.data['url1data'][year][quart][counter],
						that.data['url2data'][year][quart][counter]);
					counter +=1 ;
				}*/
				$.each(quartdat,function(i,v){
					dat[year][quart][i] = new Array(v,
						that.data['url2data'][year][quart][i]);
					counter += 1;
				});
			});
		});
		this.data['data'] = dat;
		return this.data;
	}

	this.parseData = function(xml){
		var that = this;
		console.log("Received xml document");
		//console.log(xml);
		$xml = $( xml );
		$data = $xml.find(this.opentag)[0];
		this.makeKeys = false;
		if(!this.data['keys']){ console.log("making keys"); this.data['keys'] = new Object(); this.makeKeys = true; }
		//console.log($data);
		var parsed = new Object();
		var countlist = 0;
		$($data).children().each(function(){
			countlist +=1 ;
			var quart = $(this).find('quarter').text();
			var quartA = quart.split('Q');
			var year = quartA[0]; var quart = quartA[1];
			var yearq = parseInt(year)+ (0.1*parseInt(quart))
			if(!parsed[year]){ parsed[year] = new Object(); }
			//if(!parsed[year]){ parsed[year] = new Object(); }
			//if(!parsed[year][quart]){ parsed[year][quart] = new Object(); }
			var geo = $(this).find('qwi_geo').text();
			if(that.geoMap&& that.geoMap[geo]){geo = that.geoMap[geo].toUpperCase(); }
			
			var agecount = 0;
			if(that.type != 'map'){ 
				if(!parsed[year][quart]){ parsed[year][quart] = new Array(); }
				var vList = new Array(); }
			else { parsed[year][geo] = new Object(); }
			$(this).children().each(function(){
				var nN = $(this)[0].nodeName;
				if(that.makeKeys && nN != 'quarter' && nN != 'qwi_geo' && nN != 'naics'){ that.data['keys'][nN] = true; }
				if(that.type == 'map'){
					if(that.tag && that.tag == nN){ parsed[year][geo] = parseInt($(this).text());}
					else if(!that.tag && nN != 'quarter' && nN != 'qwi_geo' && nN != 'naics'){
						parsed[year][geo][nN] = $(this).text();
					}
				}
				else { 
					if( nN != 'quarter' && nN != 'qwi_geo' && nN != 'naics'){
					parsed[year][quart].push($(this).text());
					}
				}
			});
			

		});
		if(this.makeKeys){ 
			var newKeys = new Array();
			$.each(this.data['keys'],function(i,v){
				newKeys.push(i);
			});
			this.data['keys'] = newKeys;
			//console.log(this.data['keys']);
		}
		this.makeKeys = false;
		console.log("# of XML Entities: "+countlist);
		//console.log(parsed);
		return parsed;
	//if(this.callback){ this.callback(this.data); }
	}

	this.parseSingle = function(xml){
		var that = this;
		console.log("Received single xml document");
		console.log(xml);
		$xml = $(xml);
		var makeKeys = false;
		if(!this.data['keys']){ this.data['keys'] = new Array(); makeKeys = true; }
		var parsed = new Object();
		var countlist = 0;
	}
}