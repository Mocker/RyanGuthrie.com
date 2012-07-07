/*
ProtoSlider class
A class combining jQueryUI slider and Protovis bar chart in order to create an animate the bar chart

Sample Use: 
    var visSlide;
    var maxBars = 8; var m = 3;
   var data = new Object;
   data[2010] = genData(maxBars, m); data[2011] = genData(maxBars, m); data[2012] = genData(maxBars, m);
   data[2013] = genData(maxBars, m);
   var datakeys = new Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P");
   //generate fake set of data
   function genData(rows, sets){
        return pv.range(rows).map(function() {
    return pv.range(sets).map(function() {
        return Math.random()*90 ;
      });
     });
   }
visSlide = new ProtoSlider(datakeys, data, "#slidercontrol", "#sliderwrap", { 
            minBars: 5, curBars: 6, labelX: "X Axis", labelY: "Y Axis",
            yScaleFormat: '%', yMax: 110, yScaleType: "root",
            w: 400, h: 400, timer: 1000 });
visSlide.groupInit();

Constructor Arguments:
ProtoSlider = function(dataKeys, dataSet, slideControls, slideDiv, opts)
    dateKeys - single Array for x axis labels
    dataSet - object containing 2d arrays for all the data. The slider iterates between dataSet elements. Each dataSet element is a 2d array for grouped bar graph data
    slideControls - div id for where to insert play/stop buttons for slider
    slideDiv - div id for where to create the jquery slider
    opts - optional anonymous object containing configuration data
    
Available options:
    w : width of chart
    h : height of chart
    yMax : maximum value for y Scale
    yScaleType : defaults to linear scale. set to "root" for exponential scale
    yScaleRootMax : only used with root scale. Maximum value for root scale to go up to. Does a range of (0, yMax) to (0, yScaleRootMax). Default is height
    yScaleFormat : optional symbol to prepend to y label (ie "$") . If set to "%" puts at end of label. Default is none
    yScaleTicks: optional number of ticks to make on the y scale
    timer : number of microseconds between sliding. Default 1000
    labelX : text for x axis label
    labelY : text for y axis label
    minBars : minimum number of bars allowed to display
    curBars : number of bars to display with at beginning
        
*/
ProtoSlider = function(dataKeys, dataSet, opts){

	this.data = dataSet;
    this.dataKeys = dataKeys;
/*    this.min = false; this.max = false; this.maxBars = false;
    this.scaleMax = 0; this.sets = 1;
    for(var i in this.data){
        i = parseInt(i);
        if(! this.min){ this.min = parseInt(i); }
        if(! this.max){ this.max = parseInt(i); }
        if(i < this.min){ this.min = i;}
        if(i > this.max){ this.max = i; }
        if(! this.maxBars){ this.maxBars = this.data[i].length; }
        for(var v in this.data[i]){ 
            this.sets = this.data[i][v].length ;
            for(var z in this.data[i][v]){
                if(this.data[i][v][z] > this.scaleMax){ this.scaleMax = this.data[i][v][z]; } }
            }

    }
    this.year = this.min; */
    
 /*   this.scaleMax = opts['scaleMax'] ? opts['scaleMax'] : this.scaleMax;
    this.yScaleType = opts['yScaleType'] ? opts['yScaleType'] : "linear";
    this.yScaleFormat = opts['yScaleFormat'] ? opts['yScaleFormat'] : '' ;
    this.yScaleTicks = opts['yScaleTicks'] ? opts['yScaleTicks'] : '';
    this.slideTimer = undefined; this.timing = opts['slideTimer'] ? opts['slideTimer'] : 1000;
    this.labelX = opts['labelX'] ? opts['labelX'] : "X AXIS";
    this.labelY = opts['labelY'] ? opts['labelY'] : "Y AXIS";
    this.minBars = opts['minBars'] ? opts['minBars'] : this.maxBars - 4;
    this.curBars = opts['curBars'] ? opts['curBars'] : this.minBars  ;
    this.vis = undefined; this.bar = undefined;

	this.slideControls = opts['slideControls'];
	this.slideDiv = opts['slideDiv'];
	this.w = opts['w'] ? opts['w'] : 400;
    this.h = opts['h'] ? opts['h'] : 400;
*/
    //this.x = pv.Scale.ordinal(pv.range(this.curBars)).splitBanded(0, this.w, 4/5);
    //this.y = pv.Scale.linear(0, 1.1).range(0, this.h);
    this.y = pv.Scale.linear(0, opts['scaleMax']).range(0, opts['h']);
    this.x = pv.Scale.linear(0, opts['scaleMax']).range(0, opts['w']);
    //this.x = pv.Scale.ordinal(pv.range(opts['curBars'])).splitBanded(0, opts['w'], 4/5);
   
    
    //allow option 'dataNotation' to be used in place of 'yScaleFormat'
    if(opts.dataNotation && !opts.yScaleFormat){ opts.yScaleFormat = opts.dataNotation; }

    this.year = opts['year']; this.min = opts['min']; this.max = opts['max'];
    this.opts = opts;


    var thisObj = { visSlide: this };
    thisObj['ProtoSlider'] = {};
    //thisObj['protoSlider'][opts['']]
    if(!$.ProtoSlider){ $.extend(thisObj); }
    var thatObj = { ProtoSlider: function(){
        var divID = this[0].id;
        if( $.ProtoSlider && $.ProtoSlider[divID]){
            return $.ProtoSlider[divID] ;
        }
        return undefined;
    }}
    $.fn.extend(thatObj);
   
     //method to add text in the slider of the current value if wanted
    //$(".ui-slider-handle,a").css("text-decoration", "none").css("text-align", "center").text(ui.value); 
}

ProtoSlider.prototype.register = function(visKid){
    this.kidVis = visKid;
    if(!$.ProtoSlider){
        //console.log("Mising protoSlider"); console.log($.ProtoSlider);
        var thisObj = { visSlide: this };
        thisObj['ProtoSlider'] = {};
        $.extend(thisObj);
    }
    var divID;
    if(this.vis.$dom.parentElement){ divID = this.vis.$dom.parentElement.id ; }
    else { divID = this.vis.$dom.parentNode.id ;}
    this.divID = divID;
    $.ProtoSlider[divID] = visKid;
    //console.log(divID); console.log($.ProtoSlider);
    this.initSlider(visKid);
}

ProtoSlider.prototype.initSlider = function(visKid){
    var divID = this.divID;
    this.sliderID = divID+"Slider"; 
    this.amountID = divID+"Amount";
    
    var divSTR = '$.ProtoSlider['+"'"+divID+"'"+'].prototype';
    var htmlControls = '<a onClick="'+divSTR+'.slidePlay();"><img id="imgSlidePlay" src="images/icon32_play.png"></a> <a onClick="'+divSTR+'.slideStop();"><img id="imgSlideStop" src="images/icon32_stop.png"></a>';
    $(visKid.opts['slideControls']).html(htmlControls);
    var divHTML = '<p><label for="'+this.amountID+'">Year:</label>'+
            '<input type="text" class="slider" id="'+this.amountID+'"  /> </p><div id="'+this.sliderID+'"></div>'+
        '<div id="sliderlabels" style="width: 100%;"><span id="sliderMin">'+visKid.min+'</span><div  id="sliderMax" style="float:right;">'+visKid.max+'</div></div>';
    $(visKid.opts['slideDiv']).html(divHTML);

        var amountID = this.amountID;
        var startQ = 1; if(visKid.quarter){ startQ = visKid.quarter; }
        var startV = (visKid.quarter) ? visKid.year+(startQ*0.25)-0.25 : visKid.year;
        var sMin = visKid.min ; 
        if(visKid.minQ){ sMin += (visKid.minQ*.25)-.25; }
        var sMax = visKid.max ; 
        if(visKid.maxQ){ sMax += (visKid.maxQ*.25)-.25; }
        $( "#"+this.sliderID ).slider({
            value: startV,
            quarter: startQ,
            min: sMin,
            max: sMax,
            animate: true, 
            step: 0.25,
            slide: function( event, ui ) {
                var newY = parseInt(ui.value);
                var newQ = Math.round((ui.value - newY)*4)+1;
                //console.log("ui:"+ui.value+" newY:"+newY+" newQ:"+newQ);
                
                if(visKid.quarter){
                    visKid.year = newY; visKid.quarter = newQ;
                    $( "#"+amountID ).val(newY+" Q"+newQ);
                    visKid.render(); }
                else if(visKid.year != newY){
                $( "#"+amountID ).val(newY);
                visKid.year = newY;
                visKid.render();
                }
                
            }
        });

        $( "#"+this.amountID ).val( $( "#"+this.sliderID ).slider( "value" ) );

        //$('#sliderMin').html(visKid.opts['min']);
        //$('#sliderMax').html(visKid.opts['max']);
       
}

ProtoSlider.prototype.render = function(){ 
	this.vis.render(); 
}

ProtoSlider.prototype.groupInit = function(){
    
    
    this.vis = new pv.Panel()
    .width(this.opts['w'])
    .height(this.opts['h'])
    .bottom(40)
    .left(60)
    .right(10)
    .top(5);
    /*
    .bottom(this.opts['bottom'])
    .left(this.opts['left'])
    .right(this.opts['right'])
    .top(this.opts['top']); */
    
    
}


ProtoSlider.prototype.yTicks = function(){

    if(this.yScaleTicks && this.yScaleType != 'root'){ return this.y.ticks(this.yScaleTicks); }
    if(this.yScaleType == 'root'){
        var numTicks = this.yScaleTicks ? this.yScaleTicks : 10 ;
        var max = this.yScaleRootMax ? this.yScaleRootMax : this.h ;
        var step = parseInt(max/numTicks);
        var ticks = Array();
        var count = 0;
        while(count <= max - step){
            ticks.push( parseInt(this.y.invert(count)) );
             count += step;
        }
        count = max; ticks.push( parseInt(this.y.invert(count)) );
        return ticks;

    }
    return this.y.ticks();
}

ProtoSlider.prototype.scaleInitOLD = function(){
    
    
    this.bar2.anchor("top").add(pv.Label)
    .textStyle("white")
    .text(function(d){ return d.toFixed(1) });
 
    this.bar.anchor("bottom").add(pv.Label)
    .textMargin(5)
    .textStyle("white")
    .textBaseline("top")
    .text(function(){ return $.visSlide.dataKeys[this.parent.index] });
 
    this.vis.add(pv.Label)
    .textMargin(5)
    .textStyle("white")
    .textAlign("center")
    .bottom(-35)
    .text(this.labelX);
    this.vis.add(pv.Label)
    .textMargin(5)
    .textStyle("white")
    .textAlign("left")
    .left(-55)
    .text(this.labelY);

    this.yRule = this.vis.add(pv.Rule)
    .data($.visSlide.yTicks())
    .bottom(function(d){ return Math.round($.visSlide.y(d)) - .5 })
    .strokeStyle(function(d){ return d ? "rgba(255,255,255,.3)" : "#fff" })
    .textStyle("white");
    this.yLabels = this.yRule.add(pv.Rule)
    .left(0)
    .width(5)
    .strokeStyle("#fff")
  .anchor("left").add(pv.Label)
  .textStyle("white")
//    .text(function(d){ return d.toFixed(1)+'%' });
    .text(function(d){ return $.visSlide.yScaleText(d.toFixed(1)) });
    

    this.vis.render();
}

//format y labels based on configuration
ProtoSlider.prototype.yScaleText = function(d){
    if(!this.opts.yScaleFormat){ return d; }
    if(this.opts.yScaleFormat == '%'){ return d+'%'; }
    if(this.opts.yScaleFormat.length > 0){ return this.opts.yScaleFormat+d; }
    return d;
}


ProtoSlider.prototype.slideUpdate = function(){

  //y.domain(0, data[year].slice(0,curBars));
  this.kidVis.x = pv.Scale.ordinal(pv.range(this.kidVis.curBars)).splitBanded(0, this.kidVis.opts['w'], 4/5);
  this.kidVis.bar.width( this.kidVis.x.range().band);
  this.render();
}

ProtoSlider.prototype.slidePlay = function (){ 
        //console.log("PLAY");
        //if(this.kidVis.year == $("#"+this.sliderID).slider("option","max")){ 
          if(this.kidVis.year == this.kidVis.max && this.kidVis.quarter && this.kidVis.quarter == this.kidVis.maxQ){
                //this.kidVis.year = $("#"+this.sliderID).slider("option","min");
                this.kidVis.year = this.kidVis.min; this.kidVis.quarter = this.kidVis.minQ;
                var newVal = this.kidVis.year + (this.kidVis.quarter*0.25)-0.25;
                $("#"+this.sliderID).slider("value", newVal);
                $( "#"+this.amountID ).val( this.kidVis.year+"Q"+this.kidVis.quarter );
                if(0 && this.kidVis.quarter){
                    this.kidVis.year = this.kidVis.min; 
                    this.kidVis.quarter = this.kidVis.minQ;
                    var slideVal = this.kidVis.year + (this.kidVis.quarter*.25);
                    $("#"+this.sliderID).slider("value", slideVal);
                    $( "#"+this.amountID ).val( this.kidVis.year+" Q"+this.kidVis.quarter );
                }
                if(this.kidVis.opts.showTooltip){
                    this.kidVis.hideTooltip(); }
                this.render();
            }
        this.slideTimer = setTimeout('$.ProtoSlider["'+this.divID+'"].prototype.slideNext()', this.kidVis.opts['slideTimer']);
    };
ProtoSlider.prototype.slideStop =    function (){ 
	//console.log("slideStop");
	clearTimeout(this.slideTimer);  this.slideTimer = undefined; };
ProtoSlider.prototype.slideNext =    function (){ 
        if(this.slideTimer == undefined){ return; }
        var sliderMax = $("#"+this.sliderID).slider("option","max");
        var curYearQ = this.kidVis.year;
        if(this.kidVis.quarter){ curYearQ += (this.kidVis.quarter*.25)-.25; }
        if( curYearQ < sliderMax ){
            if(this.kidVis.quarter){ this.kidVis.quarter +=1;
                if(this.kidVis.quarter >= 5){ this.kidVis.quarter = 1; this.kidVis.year += 1; }
                var slideVal = this.kidVis.year + (this.kidVis.quarter*.25)-0.25;
                    $("#"+this.sliderID).slider("value", slideVal);
                    $( "#"+this.amountID ).val( this.kidVis.year+" Q"+this.kidVis.quarter );
                    //console.log("slideValue: "+slideVal);
                
            }
            else { this.kidVis.year += 1; 
            $("#"+this.sliderID).slider("value", this.kidVis.year);
            $( "#"+this.amountID ).val( this.kidVis.year );
            }
            //visSlide.render(); 
            if(this.kidVis.opts.showTooltip){
                    this.kidVis.hideTooltip(); }
            this.render();
	   //update the tooltip if it has one
	    //if(this.kidVis.mouseZone){ this.kidVis.showTooltip(this.kidVis.mouseZone); }
            if(this.kidVis.year < sliderMax){ this.slideTimer = setTimeout('$.ProtoSlider["'+this.divID+'"].prototype.slideNext()',this.kidVis.opts['slideTimer']); }
        }
	if(this.kidVis.year == sliderMax){
		$('#play').attr('src',this.kidVis.opts['imgPlay']);
	}
    
}






