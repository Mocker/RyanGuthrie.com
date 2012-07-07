//ProtoSliderMap - Map graph inherits from protoSlider base class
//One class to generate chlorograph maps for both State/Counties and US/States
/** 

To Create a US Map:
var visSlide = new ProtoSliderMap2(dataKeys, dataSet, {
  //object with optional parameters
  slideControls: "#slidercontrol", slideDiv: "#sliderwrap",
   w: w, h: h,
});
visSlide.groupInit(); 

To load a State Map:
visSlide.clearMap();
visSlide.newMap(dataKeys, dataSet, {
	//new options to load. Old ones stay set unless you override them here
    isState: true,
    displayPercentage: true
  });

Options/Default List:
		year: this.year, //year to start at. Default is minimum
        imgStop: 'images/stopsm.png', //img for stop button on slider
        imgPlay: 'images/playsm.png', //img for play button on slider
        isState: false, //set to true when loading a state file
        debug: false, //used to display some debug information in console
		yearsMargin: 70, //margin to position slider controls
		min: this.min, //minimum year to load from the data
		max: this.max, //max year to load from data
		top: null, //map positioning
		bottom: null, //map positioning
		left: null, //map positioning
		right: null, //map positioning
		marginLeft: 0, //set left margin for map graphics so it won't overlap legend
		marginTop: 0, //set so map won't crowd slider
		slideTimer: 900, //milliseconds between slide frames
		numColors: 6, //color in scale
		legendText: '', //text for map legend
		displayPercentage: false, //interprets data as %'s of a total'
		calculatePercentage: false, //totals all data and displays what % of the total each state/county is
		colorScale: ["#0000ff","#0000cc","#0000aa","#000088",
			"#000066","#000033"], //colors to use as rgb values
		//w: width of display area
		//h: height of display area

Data Expected:
	US Map Datakeys:
		Array of states with border coordinates
		[ {	name:'Wyoming',
			code: 'wy',
			borders: [[[-111.137695,45.026951],[-104.029541,45.034710],[-104.040527,40.996483],[-111.115723,40.979897],[-111.137695,45.026951]]]}]
	US Map Data:
		Object organized by years->quarters->state code
		{2008:
			1-> {
				"AL": 0.5,
				"AK": 0.7,..
				},
			2-> {
				"AL": 0.5,
				"AK": 0.7,..
				},
			3-> {
				"AL": 0.5,
				"AK": 0.7,..
				},
			4-> {
				"AL": 0.5,
				"AK": 0.7,..
				},
		 2009: 1->{ "AL": 1.1, "AK": 1.7}
		}

	US State Datakeys:
		Array of GeoJson data organized as the following objects
		{
			properties: {"name": "Kent", name2: "Kent County, RI"},
			geometry: { "type":"Polygon",
				"coordinates": [array of lat/lng coordiantes]}
		}
	US State Data:
		same as US map - Object organized by year ->county name
		{2008: {
				"Kent": 0.5,
				"Lane": 0.7,..
				},
		 2009: { "Kent": 1.1, "Lane": 1.7}
		}
**/

ProtoSliderMap2 = function(opts){

	/*this.fakeVis = new pv.Panel()
	    	.width(opts['w'])
	    	.height(opts['h'])
	    	.top(0); 
	 this.fakeVis.render(); */
		

	this.initProto = function(dataKeys,dataSet,opts){
		this.fakeVis = undefined;
		this.debug = false;
		this.min = false; this.max = false; 
		this.scaleMax = 0; this.sets = 1;
		this.debugCounty = undefined;
		this.min = dataSet.startYear ? dataSet.startYear : 0;
		this.max = dataSet.endYear ? dataSet.endYear : 0;

		console.log(opts);
		this.year = opts['year'] ? opts['year'] : this.min;
		this.quarter = opts['quarter'] ? opts['quarter']: 1;
		this.minQ = 1; this.maxQ = 4; // set default - changed when data is parsed
		//this.parseURL();

		var defaults = {
	        imgStop: 'images/stopsm.png',
	        imgPlay: 'images/playsm.png',
	        isState: false,
	        debug: false,
			yearsMargin: 70,
			min: this.min,
			max: this.max,
			top: null,
			bottom: null,
			left: null,
			right: null,
			marginLeft: 0,
			marginTop: 0,
			slideTimer: 900,
			numColors: 6,
			legendText: '',
			percentRange: [20,30,40],
			scaleRange: undefined,
			displayPercentage: false,
			calculatePercentage: false,
			//colorScaleC: pv.colors("#eeffbb","#8899dd","#0033dd","#220099",
			//"#000066","#000033"),
			colorScale: ["#F1EEF6","#BDC9E1","#74A9CF","#0570B0",
			"",""],
		};
		//defaults['percentColors'] = pv.colors("#0000cc","#000099","#000066","#000033");
		defaults['percentColors'] = pv.colors("#eeffbb","#8899dd","#0033dd","#220099");
		//defaults.percentRange = [20,30,40];
		var tmpW = opts['w'] ? opts['w'] : defaults['w'];
	    var tmpH = opts['h'] ? opts['h'] : defaults['h'];
	    opts = $.extend({}, defaults, opts);
	    this.prototype = new ProtoSlider(dataKeys, dataSet, opts);
	    $.extend(this, this.prototype);
	    if(this.opts.debug){ this.debug = true; }
	    this.min = this.opts.min; this.max = this.opts.max;

	    this.scale = pv.Geo.scale()
	    .domain({lng: -128, lat: 24}, {lng: -64, lat: 50})
	    .range({x: this.opts.marginLeft, y: this.opts.marginTop}, {x: tmpW, y: tmpH});

	    if(this.opts['calculatePercentage']){ 
	    	this.opts['displayPercentage'] = true; 
	    }

	    this.prototype.doSliderInit = this.prototype.initSlider; //overwrite so we can call this after data is parsed
		this.prototype.initSlider = function(visKid){  }

	    //tooltip functions
	    this.TTShown = false; this.mouseX = -600; this.mouseY = 600;
	    var that = this;
	    $('body').mousemove(function(e){
			//console.log("MOVED TO "+e.pageX+","+e.pageY);
			that.mouseX = e.pageX; that.mouseY = e.pageY;
			if(that.TTShown){
				$('sliderMapTT').css('top',e.pageY+20);
				$('sliderMapTT').css('left',e.pageX+10);
			}
		});
	}
	//end initProto function

	this.visInit= function(){
		this.parseURL();
    	if(this.debug || 1){ console.log("visInit started"); }
    	//this.prototype.groupInit();
    	this.prototype.vis = new pv.Panel()
	    	.width(this.opts['w'])
	    	.height(this.opts['h'])
	    	.bottom(this.opts['bottom'])
	    	.left(this.opts['left'])
	    	.right(this.opts['right'])
	    	.top(this.opts['top']); 
	   	console.log("Group init- vis DOM"); console.log(this.prototype.vis.$dom);
	   	if(!this.prototype.vis.$dom){ this.prototype.vis.$dom = $('#'+this.opts['scriptID'])[0]; }
	   	console.log(this.prototype.vis.$dom);
    	this.vis = this.prototype.vis;
    	this.prototype.register(this);
	}

    // Initalize map functions
    this.groupInit = function(){
		this.parseURL();
    	if(this.debug || 1){ console.log("groupInit started"); }
    	//this.prototype.groupInit();
    	this.prototype.vis = new pv.Panel()
	    	.width(this.opts['w'])
	    	.height(this.opts['h'])
	    	.bottom(this.opts['bottom'])
	    	.left(this.opts['left'])
	    	.right(this.opts['right'])
	    	.top(this.opts['top']); 
	   	console.log("Group init- vis DOM"); console.log(this.prototype.vis.$dom);
	   	if(!this.prototype.vis.$dom){ this.prototype.vis.$dom = $('#'+this.opts['scriptID'])[0]; }
	   	console.log(this.prototype.vis.$dom);
    	this.vis = this.prototype.vis;
    	this.prototype.register(this);
    	var that = this;

    	//add panel for each state
    	this.state = this.vis.add(pv.Panel)
    		.data(this.dataKeys);

    	//add a panel for each state landmass
    	this.state.add(pv.Panel)
    		.data(function(c){ return c.borders; })
    		
		  	.add(pv.Line)
		    .data(function(l){ return l; })
		    .left(this.scale.x)
		    .top(this.scale.y)
		    .fillStyle(function(d, l, c){ 
				//return that.col(that.data[c.code].percentage[that.yearIDX(that.year)]); 
				return that.col(that.data[that.year][that.quarter][c.code]);
			})
		    .lineWidth(1)
		    .strokeStyle("black")
		    .antialias(false); 
		  
		this.vis.render();
		this.scaleInit();
    }

	// Initialize scales
    this.scaleInit = function(){
		if(this.debug){ 
			console.log("scaleInit started"); 
		}
    	var that = this;

		this.parseDat()
    	this.yearsScale = pv.Scale.linear()
	    //.domain(this.data.startYear, this.data.endYear)
        .domain(this.min,this.max)
	    .range(this.opts['yearsMargin'] + 2, this.opts['w'] - 10 - this.opts['yearsMargin']);	
	    if(this.debug){
	    	console.log(this.yearsScale); 
	    }
    	// Add the ticks and labels for the year slider
    	/*
		this.vis.add(pv.Rule)
		    //.data(pv.range(this.data.startYear, this.data.endYear + .1))
            .data(pv.range(this.min, this.max+.1))
		    .left(this.yearsScale)
		    .height(4)
		    .top(-15)
		    .anchor("bottom").add(pv.Label);
		    * */

		// Add a label with the state code in the middle of every state
		if(!this.opts.isState)
		{
			this.vis.add(pv.Label)
			    .data(this.dataKeys)
			    .left(function(c){ return that.scale(c.centLatLon).x; })
			    .top(function(c){ return that.scale(c.centLatLon).y; })
			    .text(function(c){ return c.code; })
			    .textAlign("center")
			    .textBaseline("middle");
		}

		// Add the color bars for the color legend
		if(this.opts['legendText'].length > 0){
		   this.vis.add(pv.Label)
				.text(this.opts['legendText'])
				.bottom(70+((that.opts.numColors-6)*5)).left(5);
		}
		
		this.legendBars = this.vis.add(pv.Bar)
		    .data(this.dataScale )
		    .bottom(function(d){ return this.index * 12; })
		    .height(10)
		    .width(10)
		    .left(5)
		    .fillStyle(function(d){ return that.col(d); })
		    .lineWidth(null)
		    .anchor("right");
		if(this.opts.displayPercentage){
			this.legendBars.add(pv.Label)
				.textAlign("left")
				//.text("waah");
				.text(function(d){ return that.percentRangeText(d); });
		}
		else {
		 this.legendBars.add(pv.Label)
			 .textAlign("left")
		     .text(function(d){ return (Math.round(d*100)/100) + " to " + Math.round((d+1)*100)/100 + ""; });
		}
    	this.vis.render();
    } // End scaleInit function

    // parse data to find year range, and min/max values
    // assumes data structure of this.data[year][quarter][location_code] = value for that location/year
    // i.e.. this.data[2009][AK] = 1.59  this.data[2008][AK] = 1.85
    this.parseDat = function(){
    	console.log("starting parse data");
    	this.startYear = null; this.endYear = null;
    	var startQ = null; var endQ = null;
    	this.vMin = null; this.vMax = null; this.vTotal =0;	
		this.vTotals = new Object;
    	for(var obj in this.data){
    		if(obj.length == 2 || parseInt(obj)){
				this.vTotals[obj] = 0;
    			if(this.startYear == null || obj < this.startYear){
    				 this.startYear = parseInt(obj); 
    			}
    			if(this.endYear == null || obj > this.endYear){ 
    				this.endYear = parseInt(obj); 
    			}
    			for(var q in this.data[obj]){
    				if(this.endYear == obj && (!endQ || endQ<q)){ endQ = q; }
    				if(this.startYear == obj && (!startQ || startQ>q)){ startQ = q; }
	    			for(var loc in this.data[obj][q]){
	    				if( !this.data[obj][q][loc] || !parseInt(this.data[obj][q][loc]) ){ 
	    					continue; 
	    				}
	    				if( this.vMin == null || this.data[obj][q][loc] < this.vMin ){ 
	    					this.vMin = this.data[obj][q][loc]; 
	    				}
						if(this.vMax==null||this.data[obj][loc]>this.vMax){ 
							this.vMax = this.data[obj][q][loc]; 
						}
						if(this.opts['calculatePercentage']){
							this.vTotal += this.data[obj][q][loc];
							if(!this.vTotals[obj]){ this.vTotals[obj] = new Array(); }
							this.vTotals[obj][q] += this.data[obj][q][loc];
						} 

					}
				}
    		}
    	}
    	this.minQ = startQ; this.maxQ = endQ;
		if(!this.opts.min){ this.min = this.startYear; }
		if(!this.opts.max){ this.max = this.endYear; }
		if(!this.opts.year){ this.year = this.min;
			this.year += (this.minQ*0.25)-0.25;
		}
		if(!this.prototype.sliderID){
			this.prototype.doSliderInit(this);
		}
		
		//$('#'+this.prototype.sliderID).slider('option','min',this.min);
		//$('#'+this.prototype.sliderID).slider('option','max',this.max);
		// if calculatePercenage is set go through data and set it to % of total
		if(this.vTotal > 0 && this.opts['calculatePercentage'])
		{
			if(this.debug){ 
				console.log("Beginning percent calc"); 
			}
			this.pMax = 0;
			for(var obj in this.data){
				if(parseInt(obj) ){ 
					for(var q in this.data[obj]){
					for(var loc in this.data[obj][q]){
					//console.log(obj+" "+loc+":"+this.data[obj][loc]+" new:"+(this.data[obj][loc]/this.vTotals[obj])*100);
					this.data[obj][q][loc] = (this.data[obj][q][loc]/this.vTotals[obj][q])*100  ;
					if(this.pMax == 0 || this.data[obj][q][loc] > this.pMax){
						this.pMax = this.data[obj][q][loc];
						}
					}
					}

				}
			}
			// create percentage based color scale
			this.dataScale = pv.range(0,this.pMax,(this.pMax/this.opts.numColors));
		}
		else {
			// create color scale
			this.dataScale = pv.range(this.vMin,this.vMax,((this.vMax-this.vMin)/this.opts.numColors));
		}
		if(this.opts.dataScale){ this.dataScale = this.opts.dataScale; }

		if(this.opts.displayPercentage){
			this.dataScale = [0]; 
			this.dataScale.push.apply(this.dataScale,this.opts.percentRange);
		}
			

		//console.log("Done calculating percentages"); console.log(this.data);

    }// End parseDat function

	// return text for legend on what values the range covers
    this.percentRangeText = function(d){
		var i = 0;
		while(i<this.opts.percentRange.length){
			if(d<=this.dataScale[i]){ break; }
			i+=1;
		}
		//return "d: "+d+"i: "+i;	
		if(i==0){ 
			return "0 to "+(this.dataScale[1]-0.1); 
		}
		if(i==this.opts.percentRange.length){ 
			return this.dataScale[i]+" to 100"; 
		}
		return this.dataScale[i]+" to "+(this.dataScale[i+1]-0.1);
		//return "test";
	} // End percentRangeText function

	this.col = function(v) {
		var i = 1;
		if(this.opts.calculatePercentage){
			//i = parseInt(v/this.opts.numColors);
		}
		else if(this.opts.displayPercentage){
			i = 0;
			var cID = 0;
			while(i<this.opts.percentRange.length){
				if(v>=this.opts.percentRange[i]){
					
				}
				else { cID = i; break;}
				i+=1;	
			}
			
			if(v>=this.opts.percentRange[this.opts.percentRange.length-1]){
				cID = this.opts.percentRange.length;
			}
			var cS= this.opts.colorScale[cID]; 	
			return cS;
		}

		while(this.dataScale && i<this.dataScale.length-1){
			if(v<this.dataScale[i]){
				i-=1;
				break;
			}
			i+=1;
		}
		if(i <0 || i >= this.opts.colorScale.length){ 
			return "#cccccc"; 
		}
		color = this.opts.colorScale[i];
		return color;
	} // End col function

    // Year index
	this.yearIDX = function (year) { 
		return this.data.endYear-year; 
	} // End year index

	// Slider init
	
	this.inittSlider = function(visKid){
		var divID = this.divID;
		var divSTR = '$.ProtoSlider['+"'"+divID+"'"+'].prototype';
		visKid.divSTR = divSTR;
    	this.sliderID = divID+"Slider"; 
    	
    	var divHTML = '<div id="yearSliderHeight"><b id="yearLabel">Year:</b>'+
        '<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="'+this.sliderID+'">'+
         '<a style="left: 100%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>'+
        '</div>'+
        '<img id="play" src="'+this.opts['imgPlay']+'" alt="Play" onclick="'+divSTR+'.sPlay();">'+
        '<img id="fast" src="'+this.opts['imgPlay']+'" alt="Fast" onclick="'+divSTR+'.sPlayFast();"></div>';
    	$(visKid.opts['slideDiv']).html(divHTML);
		//console.log("visKid years "+visKid.startYear+" "+visKid.endYear);
		//console.log(this);
        var amountID = this.amountID;
        $( "#"+this.sliderID ).slider({
            value: visKid.year,
            min: visKid.startYear,
            max: visKid.endYear,
            //animate: true, 
            //step: 1,
            slide: function( event, ui ) {
                visKid.year = ui.value; 
                visKid.render();   
            }
        });

		// Play fast
        this.sPlayFast = function(){
        	this.opts['slideTimer'] = 100;	
        	this.slidePlay();
        } // End play fast

		// Play
        this.sPlay = function(){
        	this.opts['slideTimer'] = 900;
        	var iSRC = $('#play').attr('src');
        	if(iSRC == this.opts['imgStop']){
        		
        		$('#play').attr('src',this.opts['imgPlay']);
        		this.slideStop();
        	}
        	else {
        		$('#play').attr('src',this.opts['imgStop']);
        		this.slidePlay();
        	}
        } // End play

        this.renderr = this.render;
        this.render = function(){
        	if(this.kidVis.year >= this.max ){ 
        	 $('#play').attr('src',this.opts['imgPlay']); }
        	this.renderr();
        }

	} // End Slider init

	// Parse URL
    this.parseURL = function(){
		//parse url params into object
		this.urlParams = new Object();
		var searchString = document.location.search;
        searchString = searchString.substring(1);
        var nvPairs = searchString.split("&");
		for(i=0;i<nvPairs.length; i++){
			var nvPair = nvPairs[i].split("=");
			this.urlParams[nvPair[0]] = nvPair[1];
		}
    } // End parseURL

	// Load new map
    this.newMap = function(newMap, newData, newOpts){
        if(this.debug ){
            console.log("Loading new map data..");
            console.log(newMap); console.log(newData); console.log(newOpts);
        }
        if(!this.prototype){
        	//first time drawing map - do initial init
        	console.log("First time map being created");
        	this.initProto(newMap,newData,newOpts);
        	//if(!this.prototype.vis.$dom){ this.prototype.vis.$dom = $('#'+newOpts['scriptID'])[0]; }
        	//console.log(this.vis);
        	this.visInit();
        	//this.clearMap();
        }else {
        	//resize current panel
        	this.opts = $.extend({}, this.opts, newOpts);
        	this.prototype.vis.width(this.opts['w']).height(this.opts['h']);
        	/*this.prototype.vis = new pv.Panel()
	    	.width(this.opts['w'])
	    	.height(this.opts['h'])
	    	.bottom(this.opts['bottom'])
	    	.left(this.opts['left'])
	    	.right(this.opts['right'])
	    	.top(this.opts['top']); */
	    	this.vis = this.prototype.vis;
        }


		this.opts.marginLeft = 100;
		this.opts.marginTop = 20;
		this.opts.h = newOpts.height;
		this.opts.w = newOpts.width;
		this.opts.left = newOpts.left;
		this.opts.top = newOpts.top;
		//console.log(newOpts.left);
		//console.log(newOpts.top);
        $.extend(this.opts, newOpts);
		this.opts['isState']=true;
		if(this.opts['calculatePercentage']){ 
			this.opts['displayPercentage']=true; 
		}
        this.dataKeys = newMap; this.data = newData;
        this.parseState();
        var xOffset = 0;

        this.scale = pv.Geo.scale()
    		.domain({lng: this.cRange.minLng, lat: this.cRange.minLat}, {lng: this.cRange.maxLng, lat: this.cRange.maxLat })
    		.range({x: this.opts.marginLeft, y: this.opts.marginTop}, {x: (this.opts.w*this.cRange.scaleX), y: (this.opts.h*this.cRange.scaleY)});
    		
        //draw map
        var that = this;
        this.state = this.vis.add(pv.Panel).data(this.dataKeys);
        
        this.state.add(pv.Panel)
            .data(function(c){
      	//if(c.geometry.coordinates[0].length==1){
		if(c.code=="BOULDER"){
			//if(!c.geometry.allCoord){ return []; }
		}
		//else { return []; }
		if(c.geometry.allCoord){
		    if(c.code=="BOULDER"){console.log("BOULDER ALLCOORD"); }
                    return [c.geometry.allCoord];
                } else { 
                    return c.geometry.coordinates; }
            })
	    .event("mouseover",function(z,y){
			that.mouseZone = y; 
			that.showTooltip(y); 
		})
	    .event("mouseout",function(){ 
			that.mouseZone = undefined;
			that.hideTooltip(); 
		})
        .add(pv.Line)
        .data(function(l){
                return l;
		})
        .left(this.scale.x)
		//.left(this.cRange.geo.x)
		//.top(this.cRange.geo.y)
            .top(this.scale.y)
            //.fillStyle("aec7e8")
            .fillStyle(function(d,l,c){ 
			this.zone = c;
            return that.col(that.data[that.year][that.quarter][c.code]); })
            .lineWidth(1)
            .strokeStyle("black")
	    //.event("mouseover",function(c){ console.log(this.zone); })
            .antialias(true);
        this.scaleInit();
        this.vis.render(); 
    }

	// chain slideshow play to update tooltip as it plays
	/*
    this.fixPlay = function(){
    	
	}
	* */

	// Clear map
    this.clearMap = function(){
		this.cRange = undefined;
        this.vis.children = [];
        this.vis.render();
    } // End clear map

	// Parse state
    this.parseState = function(){
		if(!Array.isArray){ 
			//if browsing is running old js, create this func
			Array.isArray=function(arg){
				return Object.prototype.toString.call(arg)=='[object Array]';
			};
		}
		
        //parse state/county map to determine scale etc
        var cRange = { minLat: null, maxLat: null, minLng: null, maxLng: null};
        var that = this;
		var countyI = 0; 
		var spliceList = [];
       this.dataKeys.forEach(function(c){
	     /* var nameRE = /david|lou|dick|pick/i ;
	    	if(nameRE.test(c.properties.name)){
				that.debugCounty = jQuery.extend(true,{},c);
				that.debugCountyNew = c;
				console.log("Parsing "+c.properties.name); 
	    	}*/
        	var allCoord = [];
        
		    //a few counties in CO have messed up coordinates
		    if(c.properties.name=="Boulder"&&c.geometry.coordinates[1][1].length > 4){
		    	c.geometry.coordinates = [c.geometry.coordinates[1][0],c.geometry.coordinates[1][1]];
			}
		    else if( c.geometry.coordinates.length > 1 && c.geometry.coordinates[1].length > 1 && c.geometry.coordinates[1][0].length > 1){
				var newCoords = [];
				for(var cI=c.geometry.coordinates[1].length-1;cI>=0;cI-=1){
					if(c.geometry.coordinates[1][cI].length > 2){
						newCoords.push(c.geometry.coordinates[1][cI]);
					}
				}
				if(newCoords.length>1){ 
					c.geometry.coordinates = newCoords;
				}
		    }
	    
		    if(c.geometry.coordinates[0].length ==1 && c.geometry.coordinates[0][0].length <=2){
				//console.log("should splice "+countyI);
				//spliceList.push(countyI);
			}
			
		    var isMulti = 0;
		    if(c.geometry.type=="MultiPolygon"){
				isMulti = 1;
				c.properties['isMulti'] = true;
			}
		
			// Start county loops
	        c.geometry.coordinates.forEach(function(b){
			//if(c.geometry.coordinates[0].length==1){
				if(b.length==1){
					//isMulti = 1;
				    if( !Array.isArray(b) || !Array.isArray(b[0]) || !Array.isArray(b[0][0]) ){
					}
				    else {
						b=b[0];
					}
				}
	
				if(b.length > 4){
		                b.forEach(function(p,i){
							if(1 || c.properties.name != 'Aleutians West'){
								if(p[0] > 160){ 
									p[0] = -180 - (180-p[0]); 
								}
								if(cRange.minLat == null || p[1] < cRange.minLat){ 
									cRange.minLat = p[1];
								}
								if(cRange.maxLat == null || p[1] > cRange.maxLat){
									cRange.maxLat = p[1]; 
								}
								//if(cRange.minLng == null || (p[0] > 0 && p[0] < cRange.minLng) || (p[0] < 0 && p[0] > cRange.minLng)   ){ cRange.minLng = p[0]; }
								if(cRange.minLng == null || (p[0] < cRange.minLng)){ 
									cRange.minLng = p[0]; 
								}
								if(cRange.maxLng == null || p[0] > cRange.maxLng){
									cRange.maxLng = p[0];
								}
							}
	                  
							b[i] = {lat: p[1], lng: p[0]};
							//if(c.properties.name=="Boulder"){b[i].lat*=2;b[i].lng*=2; }
							if(isMulti){
								allCoord.push(b[i]);
								//if(!b[i].lat){ console.log("Invalid coordinate:"); console.log(p); }
							}
	       			 	}); //END b.forEACH
				}//close if b.length<2 if

            }); 
            if(c.geometry.coordinates[0].length==1){
                c.geometry.allCoord = allCoord;
                c.centLatLon = that.centroid(allCoord);
            } else { 
            	c.centLatLon = that.centroid(c.geometry.coordinates[0]);
            }
        	c.code = c.properties.name.toUpperCase();
			countyI += 1;
		}); // done loops through counties
	        
		//remove counties without real data
		if(spliceList.length > 0){
			console.log("splicing!");
			for(var v=0;v<spliceList.length;v+=1){
				var i = spliceList[v];
				var co = that.dataKeys.splice(i-v,1);
			}
		}
		
		//scale based upon whether map is longer/taller
	    cRange.latDif = (cRange.maxLat - cRange.minLat);
		cRange.lngDif = cRange.maxLng - cRange.minLng;
		if(cRange.lngDif>cRange.latDif*1.2){ 
			if(this.debug){ 
				console.log("lattweak orig lng:"+cRange.lngDif+" lat:"+cRange.latDif); 
			}
			cRange.lngDif=cRange.lngDif*0.7;
		}
		if(cRange.lngDif>(cRange.latDif*2.5) )
		{
			if(this.debug){ 
				console.log("Equalizing latDif from :"+cRange.latDif); 
	  		}
	  		cRange.latDif = cRange.latDif*2; 
		}
		else if(cRange.lngDif>(cRange.latDif*1.6))
		{
			if(this.debug){ 
				console.log("lat increased for scaling"); 
	  		}
	  		cRange.latDif=cRange.latDif*1.3;
		}
		cRange.geo = pv.Geo.scale("mercator").range(cRange.latDif,cRange.lngDif);
        //if(cRange.minLng < 0){ cRange.lngDif = cRange.maxLng - 180 - (cRange.minLng*-1); }

    	if( cRange.latDif > cRange.lngDif ){
        	cRange.scaleY = 1; 
        	cRange.scaleX = cRange.lngDif / cRange.latDif;
    	}
    	else {
        	cRange.scaleX = 1; 
        	cRange.scaleY = cRange.latDif / cRange.lngDif;
		}
    
		cRange.geo = pv.Geo.scale("mercator")
			  .domain({lng:cRange.minLng,lat:cRange.minLat},{lng:cRange.maxLng,lat: cRange.maxLat});
		//.range({x:0,y:0},{x:w,y:h});
        this.cRange = cRange;
        this.scale = pv.Geo.scale("mercator") 
        	.domain({lng: cRange.minLng, lat: cRange.minLat}, {lng: cRange.maxLng, lat: cRange.maxLat })
    		.range({x: 0, y: 0}, {x: (w*cRange.scaleX), y: (h*cRange.scaleY)});
    }  // End parseState function
    
    // Centroid function
    this.centroid = function(pts){
       var nPts = pts.length;
       
       var lng=0; var lat=0;
       var f;
       var j=nPts-1;
       var p1; var p2;

       for (var i=0;i<nPts;j=i++) {
          p1=pts[i]; p2=pts[j];
          f=p1.lng*p2.lat-p2.lng*p1.lat;
          lng+=(p1.lng+p2.lng)*f;
          lat+=(p1.lat+p2.lat)*f;
       }

       f=this.area(pts)*6;
       return {lng:lng/f, lat:lat/f}; 
    }
    this.area = function(pts){
        var area=0;
       var nPts = pts.length;
       var j=nPts-1;
       var p1; var p2;

       for (var i=0;i<nPts;j=i++) {
          p1=pts[i]; p2=pts[j];
          area+=p1.lng*p2.lat;
          area-=p1.lat*p2.lng;
       }
       area/=2;
       return area;
    }
    this.reloadUS = function(key, dat,opts){

    	if(!this.prototype){
        	//first time drawing map - do initial init
        	console.log("First time US map being created");
        	this.initProto(key,dat,opts);
        	//if(!this.prototype.vis.$dom){ this.prototype.vis.$dom = $('#'+newOpts['scriptID'])[0]; }
        	this.realgroupInit = this.groupInit ;
        	this.realscaleInit = this.scaleInit;
        	this.visInit();
        	this.realgroupInit();
        	this.realscaleInit();
        }

        this.clearMap();
        this.dataKeys = key; 
        this.data = dat;
        this.scale = pv.Geo.scale()
    .domain({lng: -128, lat: 24}, {lng: -64, lat: 50})
    .range({x: 0, y: 0}, {x: this.opts.w, y: this.opts.h});
        //this.groupInit();
    var that = this;
        //add panel for each state
        this.state = this.vis.add(pv.Panel)
            .data(this.dataKeys);

        //add a panel for each state landmass
        this.state.add(pv.Panel)
            .data(function(c){ return c.borders; })
            
            .add(pv.Line)
            .data(function(l){ return l; })
            .left(this.scale.x)
            .top(this.scale.y)
            .fillStyle(function(d, l, c){ 
                return that.col(that.data[that.year][that.quarter][c.code]); 
            })
            .lineWidth(1)
            .strokeStyle("black")
            .antialias(false); 
        
        this.vis.render();
        this.scaleInit();

    } // End centroid function

   // showTooltip function
   this.showTooltip = function(zone){
	if($('#sliderMapTT').length == 0){
		var newdiv = "<div id='sliderMapTT' style='position: absolute;"
	+"left: 1em; top: 2em; z-index: 99; margin-left: 0; width: 150px;"
	+"display: none; padding: 0.8em 1em;"
	+"background: #FFFFAA; border: 1px solid #FFAD33;"
	+"font-size: 0.8em; font-weight: bold;'></div>";
		$('body').append(newdiv);
	
	}
	var zoneINFO = zone.code+": "+this.data[this.year][this.quarter][zone.code].toFixed(2);
	$('#sliderMapTT').html(zoneINFO);
	$('#sliderMapTT').css('top',this.mouseY+20);
	$('#sliderMapTT').css('left',this.mouseX+10);
	$('#sliderMapTT').css('display','block');
	this.TTShown = true;	

   }
   // End show Tooltip function

   // hideTooltip function
   this.hideTooltip = function(){
	this.TTShown = false;
	$('#sliderMapTT').css('display','none');
	} // hideTooltip function
}