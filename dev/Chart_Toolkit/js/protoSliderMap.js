//ProtoSliderMap - Map graph inherits from protoSlider base class

ProtoSliderMap = function(dataKeys, dataSet, opts){
	this.debug = false;
	this.min = false; this.max = false; 
	this.scaleMax = 0; this.sets = 1;

	this.min = dataSet.startYear;
	this.max = dataSet.endYear;

	this.year = opts['year'] ? opts['year'] : this.min;

	var defaults = {
		year: this.year,
		yearsMargin: 100,
		min: this.min,
		max: this.max,
		top: null,
		bottom: null,
		left: null,
		right: null,
		slideTimer: 100,
	};
	var tmpW = opts['w'] ? opts['w'] : defaults['w'];
    var tmpH = opts['h'] ? opts['h'] : defaults['h'];
    opts = $.extend({}, defaults, opts);
    this.prototype = new ProtoSlider(dataKeys, dataSet, opts);
    $.extend(this, this.prototype);

    this.scale = pv.Geo.scale()
    .domain({lng: -128, lat: 24}, {lng: -64, lat: 50})
    .range({x: 0, y: 0}, {x: tmpW, y: tmpH});

    this.groupInit = function(){
    	if(this.debug){ console.log("groupInit started"); }
    	//this.prototype.groupInit();
    	this.prototype.vis = new pv.Panel()
    	.width(this.opts['w'])
    	.height(this.opts['h'])
    	.bottom(this.opts['bottom'])
    	.left(this.opts['left'])
    	.right(this.opts['right'])
    	.top(this.opts['top']); 
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
			    return that.col(that.data[c.code].percentage[that.yearIDX(that.year)]); 
			})
		    .lineWidth(1)
		    .strokeStyle("white")
		    .antialias(false); 
		  

    	this.vis.render();
    	this.scaleInit();
    }

    this.scaleInit = function(){
    	var that = this;

    	this.yearsScale = pv.Scale.linear()
	    .domain(this.data.startYear, this.data.endYear)
	    .range(this.opts['yearsMargin'] + 2, this.opts['w'] - this.opts['yearsMargin']);	
	    if(this.debug){console.log(this.yearsScale); }
    	// Add the ticks and labels for the year slider
		this.vis.add(pv.Rule)
		    .data(pv.range(this.data.startYear, this.data.endYear + .1))
		    .left(this.yearsScale)
		    .height(4)
		    .top(-15)
		  .anchor("bottom").add(pv.Label);

		 // Add a label with the state code in the middle of every state
		this.vis.add(pv.Label)
		    .data(this.dataKeys)
		    .left(function(c){ return that.scale(c.centLatLon).x; })
		    .top(function(c){ return that.scale(c.centLatLon).y; })
		    .text(function(c){ return c.code; })
		    .textAlign("center")
		    .textBaseline("middle")
		    .textStyle("#fff");

		// Add the color bars for the color legend
		this.vis.add(pv.Bar)
		    .data(pv.range(-4.5, 4.5, 1))
		    .bottom(function(d){ return this.index * 12; })
		    .height(10)
		    .width(10)
		    .left(5)
		    .fillStyle(function(d){ return that.col( this.index-4.5); })
		    .lineWidth(null)
		  .anchor("right").add(pv.Label)
		    .textAlign("left")
		    .text(function(d){ return (d) + " - " + (d+1) + "%"; });

    	this.vis.render();
    }

    //parse data to find year range, and min/max values
    //assumes data structure of this.data[year][location_code] = value for that location/year
    // i.e.. this.data[2009][AK] = 1.59  this.data[2008][AK] = 1.85
    this.parseDat = function(){
    	this.startYear = null; this.endYear = null;
    	this.vMin = null; this.vMax = null;	
    	for(var obj in this.data){
    		if(parseInt(obj)){
    			if(this.startYear == null || obj < this.startYear){ this.startYear = obj; }
    			if(this.endYear == null || obj > this.endYear){ this.endYear = obj; }
    			for(var loc in this.data[obj]){
    				if(!this.data[obj][loc] || !parseInt(this.data[obj][loc]) ){ continue; }
    				if(this.vMin == null || this.data[obj][loc] < this.vMin){ this.vMin = this.data[obj][loc]; }
    			}
    		}
    	}
    }

    //generate color scale based upon min/max data
    this.genCols = function(){
    	
    }

    this.col = function(v) {
		  if (v < -3.5) return "#226080";
		  if (v < -2.5) return "#338080";
		  if (v < -1.5) return "#44A080";
		  if (v < -0.5) return "#55C080";
		  if (v <  0.5) return "#DDDDDD";
		  if (v <  1.5) return "#C05580";
		  if (v <  2.5) return "#A04480";
		  if (v <  3.5) return "#803380";
		                return "#602280";
		};

	this.yearIDX = function (year) { return this.data.endYear-year; }

	this.prototype.initSlider = function(visKid){
		var divID = this.divID;
		var divSTR = '$.ProtoSlider['+"'"+divID+"'"+'].prototype';
		visKid.divSTR = divSTR;
    	this.sliderID = divID+"Slider"; 
    	
    	var divHTML = '<div id="yearSliderHeight"><b id="yearLabel">Year:</b>'+
        '<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="'+this.sliderID+'">'+
         '<a style="left: 100%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>'+
        '</div>'+
        '<img id="play" src="images/play.png" alt="Play" onclick="'+divSTR+'.sPlay();">'+
        '<img id="fast" src="images/play.png" alt="Fast" onclick="'+divSTR+'.sPlayFast();"></div>';
    	$(visKid.opts['slideDiv']).html(divHTML);
        var amountID = this.amountID;
        $( "#"+this.sliderID ).slider({
            value: visKid.year,
            min: visKid.data.startYear,
            max: visKid.data.endYear,
            //animate: true, 
            //step: 1,
            slide: function( event, ui ) {
                visKid.year = ui.value; 
                visKid.render();
            }
        });

        this.sPlayFast = function(){
        	this.opts['slideTimer'] = 100;	
        	this.slidePlay();
        }

        this.sPlay = function(){
        	this.opts['slideTimer'] = 900;
        	var iSRC = $('#play').attr('src');
        	if(iSRC == 'images/stop.png'){
        		
        		$('#play').attr('src','images/play.png');
        		this.slideStop();
        	}
        	else {
        		$('#play').attr('src','images/stop.png');
        		this.slidePlay();
        	}
        }

        this.renderr = this.render;
        this.render = function(){
        	if(this.kidVis.year >= this.max ){ 
        	 $('#play').attr('src','images/play.png'); }
        	this.renderr();
        }

	}

}