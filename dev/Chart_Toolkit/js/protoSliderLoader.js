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
		this.requestData();
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
			if(this.callback){ this.callback(this.data);}
		}
	}
	this.url2done = function(xml){
		this.url2ready = true;
		this.data['url2data'] = this.parseData(xml);
		if( this.url1ready){
			if(this.callback){ this.callback(this.data); }
		}
	}

	this.parseData = function(xml){
		var that = this;
		console.log("Received xml document");
		console.log(xml);
		$xml = $( xml );
		$data = $xml.find(this.opentag)[0];
		console.log($data);
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
			parsed[year][geo] = new Object();
			var agecount = 0;
			$(this).children().each(function(){
				var nN = $(this)[0].nodeName;
				if(that.tag && that.tag == nN){ parsed[year][geo] = parseInt($(this).text());}
				else if(!that.tag && nN != 'quarter' && nN != 'qwi_geo' && nN != 'naics'){
					parsed[year][geo][nN] = $(this).text();
				}
			});
		});
		console.log("# of XML Entities: "+countlist);
		//console.log(parsed);
		return parsed;
		//if(this.callback){ this.callback(this.data); }
	}
}