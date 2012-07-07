//*********************************************************
//ProtoSliderBar - bar graph inherits from protoSlider base class

ProtoSliderBarTime = function(dataKeys, dataSet, opts){
    




    this.min = false; this.max = false; this.maxBars = false;
    this.scaleMax = 0; this.sets = 1;
    this.minQ = false; this.maxQ = false; this.quarter = false;
    
    for(var i in dataSet){
        //console.log(i);
        i = parseInt(i);
        if(! this.min){ this.min = i; }
        if(! this.max){ this.max = i; }
        if(i < this.min){ this.min = i;}
        if(i > this.max){ this.max = i; }
        //if(! this.maxBars){ this.maxBars = dataSet[i].length; }
        
        for(var q in dataSet[i]){
                if( i == this.min && (!this.minQ || this.minQ > q)){ this.minQ = parseInt(q); }
                if( i == this.max && (!this.maxQ || this.maxQ < q)){ this.maxQ = parseInt(q); }
                for(var v in dataSet[i][q]){ 
                    {
                    if(!this.maxBars){ this.maxBars = dataSet[i][q].length; }
                    this.sets = dataSet[i][q][v].length ;
                    for(var z in dataSet[i][q][v]){
                        if(dataSet[i][q][v][z] > this.scaleMax){ this.scaleMax = dataSet[i][q][v][z]; } }
                    }
                } 
            }

        /*
        for(var v in dataSet[i]){ 
            this.sets = dataSet[i][v].length ;
            for(var z in dataSet[i][v]){
                if(dataSet[i][v][z] > this.scaleMax){ this.scaleMax = dataSet[i][v][z]; } }
            }*/
        
    } 
    this.year = this.min; if(opts['year']){ this.year = opts['year']; }
    this.quarter = this.minQ; if(opts['quarter']){ this.quarter = opts['quarter']; }
    this.scaleMax = 100;

    var defaults = {
        year: this.year,
        quarter: this.quarter,
        min: this.min,
        max: this.max,
        scaleMax: this.scaleMax,
        yScaleType : "linear",
        yScaleFormat: '%',
        yScaleTicks : false,
        slideTimer: 1000,
        labelX: "X Axis",
        labelY: "Y Axis",
        labelAxisColor: 'black',
        AxisValueColor: 'black',
        yTicksColor: "black",
        minBars: this.maxBars - 4,
        maxBars: this.maxBars,
        showTooltip: true,
        hideData: true,
        w: 400,
        h: 400
    };
    opts = $.extend({}, defaults, opts);
    this.bar = undefined;
    this.curBars = opts['curBars'] ? opts['curBars'] : opts['minBars'];

    this.prototype = new ProtoSlider(dataKeys, dataSet, opts);
    $.extend(this, this.prototype);


    //Do Bar specific init
    this.y = pv.Scale.linear(0, opts['scaleMax']).range(0, opts['h']);
   /*
    if(this.yScaleType == 'root'){ 
        if(opts['yScaleRootMax']){ this.y = pv.Scale.root(0, this.scaleMax).range(0, opts['yScaleRootMax']);}
        else { this.y = pv.Scale.root(0, this.scaleMax).range(0, this.h); }
    }
*/
    this.x = pv.Scale.ordinal(pv.range(this.curBars)).splitBanded(0, opts['w'], 4/5);
    $.visSlide.x = this.x; 
    $.visSlide.data = dataSet; 
    $.visSlide.sets = this.sets;
    //console.log("Bar specific init done");

    //setup tooltip if needed
    if(this.opts['showTooltip']){
        this.TTShown = false; this.mouseX = -600; this.mouseY = 600;
        var that = this;
        $('body').mousemove(function(e){
        that.mouseX = e.pageX; that.mouseY = e.pageY;
        if(that.TTShown){
            $('sliderMapTT').css('top',e.pageY+20);
            $('sliderMapTT').css('left',e.pageX+10);
        }
        });
    }
    
    this.prototype.initSlider = function(visKid){
        //dummy function - do not use a slider for this visualization
    }   

    this.parseData = function(){
        //run through data and create a single array for the bar graph
        this.singleData = Array();
        this.lineData = Array();
        this.singleMax = 0;
        var counter = 0;
        for(y in this.data){
            for(q in this.data[y]){
                    //var d = parseFloat(this.data[y][q]);
                    var cPerc = (this.data[y][q][0]/this.data[y][q][1]).toFixed(2);
                    this.singleData.push({
                        'year': y,
                        'q': q,
                        'stateTotal': this.data[y][q][1],
                        'countyTotal': this.data[y][q][0],
                        'countyPercent': cPerc
                    });
                    if(cPerc > this.singleMax){ this.singleMax = cPerc; }
                    counter +=1 ;

            }
        }
        this.singleMax = this.singleMax * 100;
        if(this.singleMax < 50){ this.singleMax = 50; }
         this.scaleMax = this.singleMax;
        this.opts.scaleMax = this.scaleMax;
        this.x = pv.Scale.ordinal(pv.range(this.singleData.length)).splitBanded(0, opts['w'], 4/5);
        this.y = pv.Scale.linear(0, this.singleMax).range(0, opts['h']);
        counter = 0;
        for(var v in this.singleData){
            this.lineData.push({
                       'x': this.x(counter),
                       'y': this.y(this.singleData[v].countyPercent*100) 
                    });
            counter +=1 ;
        }
        console.log(this.singleData);
    }


    this.groupInit = function(){
        console.log("Running group init");
        this.parseData();
        this.prototype.groupInit();
        this.vis = this.prototype.vis;
        this.prototype.register(this);
        var that = this;

        this.lineStacked = this.vis.add(pv.Area)
            .data(this.lineData)
            .bottom(1)
            .left(function(d){return (d.x);})
            .height(function(d){ return d.y; })
            .fillStyle("rgb(210,210,210)")
            .anchor("top").add(pv.Line)
            .lineWidth(3);

        this.bar = this.vis.add(pv.Bar)
            .data(this.singleData)
            .bottom(0)
            //.width(20)
            .height(function(d){ return that.y(d.countyPercent*100); })
            .left(function(){ return that.x(this.index) })
            .width(this.x.range().band) 
            .event("mouseover",function(y){
                 if(!that.opts['showTooltip']){ return; }
                that.mouseZone = y; 
                that.showTooltip(y); })
            .event("mouseout",function(){ 
                if(!that.opts['showTooltip']){ return; }
                that.mouseZone = undefined;
                that.hideTooltip(); }) ;
        this.line = this.vis.add(pv.Line)
            .data(this.lineData)
            .left(function(d){ return (d.x) })
            .bottom(function(d){ return (d.y) })
            .strokeStyle("#000")
            .lineWidth(3);

        this.vis.render();
        this.scaleInit();

    }

    this.yTicks = function(){
        if(this.opts['yScaleTicks']&& this.opts['yScaleType']!='root' && this.opts['yScaleInterval'] && this.opts['yScaleFormat']!='%'){
            var yCount = 0; var yTicks= Array();
            var yMax = this.opts['scaleMax'] ? this.opts['scaleMax'] : this.opts['h'] ;
            if(this.singleMax){ yMax = this.singleMax; }
            while(yCount <= yMax-this.opts['yScaleInterval']){
                yTicks.push(parseInt(this.opts['yScaleInterval']+yCount));
                yCount += this.opts['yScaleInterval'];
            }
            return yTicks;
        }
        if(this.opts['yScaleTicks'] && this.opts['yScaleType'] != 'root'){ return this.y.ticks(this.opts['yScaleTicks']); }
        if(this.opts['yScaleType'] == 'root'){
        
        var numTicks = this.opts['yScaleTicks'] ? this.opts['yScaleTicks'] : 10 ;
        var max = this.opts['yScaleRootMax'] ? this.opts['yScaleRootMax'] : this.opts['h'] ;
        var step = this.opts['yScaleInterval'] ? this.opts['yScaleInterval'] : parseInt(max/numTicks);
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

    
    
    this.yScaleText = function(d){
        if(this.opts['yScaleFormat'] == '%'){ return d+'%'; }
        if(this.opts['yScaleFormat'].length > 0){ return this.opts['yScaleFormat']+d; }
        return d;    
    }

    this.scaleInit = function(){
        var that = this;
        if(!this.opts.hideData){
            this.bar.anchor("top").add(pv.Label)
            .textStyle("black")
            .text(function(d){ return d.countyPercent.toFixed(1) });
        }
        this.bar.anchor("bottom").add(pv.Label)
        .textMargin(5)
        .textStyle(this.opts['AxisValueColor'])
        .textBaseline("top")
        .text(function(d){ return d.year+"Q"+d.q; });
         

        this.vis.add(pv.Label)
        .textMargin(5)
        .textStyle(this.opts['labelAxisColor'])
        .textAlign("center")
        .bottom(-35)
        .text('Quarter');
        this.vis.add(pv.Label)
        .textMargin(5)
        .textStyle(this.opts['labelAxisColor'])
        .textAlign("left")
        .left(-55)
        .text(this.opts['labelY']); 

        this.yRule = this.vis.add(pv.Rule)
        .data(this.yTicks())
        .bottom(function(d){ return Math.round(that.y(d)) - .5 })
        .strokeStyle(function(d){ return d ? "rgba(0,0,0,.3)" : "#fff" })
        .textStyle(this.opts['yTicksColor']);

        this.yLabels = this.yRule.add(pv.Rule)
        .left(0)
        .width(5)
        .strokeStyle("#000")
        .anchor("left").add(pv.Label)
        .textStyle(this.opts['AxisValueColor'])
        .text(function(d){ return that.yScaleText(d.toFixed(1)) });

     /*
        this.bar.anchor("bottom").add(pv.Label)
        .textMargin(5)
        .textStyle("black")
        .textBaseline("top")
	.font("bold 11px/11px sans-serif")
        .text(function(){ return $.visSlide.dataKeys[this.parent.index] });
     
        this.vis.add(pv.Label)
        .textMargin(5)
        .textStyle("black")
        .textAlign("center")
	.font("bold 12px/12px sans-serif")
        .bottom(-35)
        .text(this.opts['labelX']);
        this.vis.add(pv.Label)
        .textMargin(5)
        .textStyle("black")
        .textAlign("left")
	.font("bold 12.5px/12.5px sans-serif")
	.textAngle(-Math.PI/2)
        .left(-55)
	.bottom(140)
        .text(this.opts['labelY']);

        this.yRule = this.vis.add(pv.Rule)
        .data(this.yTicks())
        .bottom(function(d){ return Math.round(that.y(d)) - .5 })
        .strokeStyle(function(d){ return d ? "rgba(0,0,0,.3)" : "#000" })
        .textStyle("white");

        this.yLabels = this.yRule.add(pv.Rule)
        .left(0)
        .width(5)
        .strokeStyle("#000")
        .anchor("left").add(pv.Label)
        .textStyle("black")
	.font("bold 12.5px/12.5px sans-serif")
        .text(function(d){ return that.yScaleText(d.toFixed(1)) });
        */
        //add optional leged
        if(this.opts.dataLegend){
            this.legendBars = this.vis.add(pv.Bar)
            .data(this.opts.dataLegend )
            .bottom(function(d){ return -30; return this.index * 12; })
            .height(10)
            .width(10)
            .left(function(d){ return this.index* 90+30; })
            .fillStyle(pv.Colors.category20().by(pv.index))
            .lineWidth(null)
            .anchor("right");
             this.legendBars.add(pv.Label)
                 .textAlign("left")
                 .text(function(d){ return d; });
            
        }
        if(this.opts.cornerText){
            this.vis.add(pv.Label).textAlign("right")
            .right(10).top(20)
            .font("bold 12px/12px sans-serif")
            .textStyle("black")
            .text( this.opts.cornerText);
        }

        this.vis.render();    
    }

    this.slideAddBar = function(){
        if(this.curBars < this.opts['maxBars']){ this.curBars +=1; this.prototype.slideUpdate(); }    
    }
    this.slideRemoveBar = function(){
        if(this.curBars > this.opts['minBars']){ this.curBars -= 1; this.prototype.slideUpdate(); }
    }

    
    //Tooltip functions
    this.showTooltip = function(y){
        if(!y){ console.log("tooltip failed, no data"); return; }
        //console.log(y);
        if($('#sliderMapTT').length == 0){
        var newdiv = "<div id='sliderMapTT' style='position: absolute;"
        +"left: 1em; top: 2em; z-index: 99; margin-left: 0; width: 120px;"
        +"display: none; padding: 0.8em 1em;"
        +"background: #FFFFAA; border: 1px solid #FFAD33;"
        +"font-size: 0.8em; font-weight: bold;text-align:left;'><ul style='padding:0px;list-style-type:none;foat:left;'>";

        
        newdiv += "<b>"+y.year+"Q"+y.q+"</b><br>County: "+y.countyTotal+" ("+(y.countyPercent*100)+"%)</div>";
        $('body').append(newdiv);
        
        }
        else {
            var newhtml = "<b>"+y.year+"Q"+y.q+"<br>County: "+y.countyTotal+" ("+(y.countyPercent*100)+")";
            $('#sliderMapTT').html(newhtml);
        }
        $('#sliderMapTT').css('top',this.mouseY+20);
        $('#sliderMapTT').css('left',this.mouseX+10);
        $('#sliderMapTT').css('display','block');

        this.TTShown = true;
    }
    this.hideTooltip = function(){
        this.TTShown = false;
        $('#sliderMapTT').css('display','none');
    }
}