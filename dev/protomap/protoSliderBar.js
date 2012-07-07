//*********************************************************
//ProtoSliderBar - bar graph inherits from protoSlider base class

ProtoSliderBar = function(dataKeys, dataSet, opts){
    
    this.dSet = dataSet;
    this.dKeys = dataKeys;
    this.tOpts = opts;
    
    //this.init();

    this.setData = function(dataKeys,dataSet,opts){
        this.opts = undefined;
        this.vis = undefined;
        this.dSet = dataSet;
        //this.dKeys = dataKeys;
        this.tOpts = opts;
    }


    this.init = function(){ 
        console.log("init called");
        var dataKeys = this.dKeys; 
        this.dataKeys = this.dKeys;
        var dataSet = this.dSet;
        var opts = this.tOpts;
    this.min = false; this.max = false; this.maxBars = false;
    this.scaleMax = 0; this.sets = 1;
    this.useQuarters = false;

    console.log(dataKeys);
    
    for(var i in dataSet){
        if(dataSet[i][1] && dataSet[i][1][0] && dataSet[i][1][0][0] ){
            this.useQuarters = 1;
        }
        //console.log(i);
        i = parseInt(i);
        if(! this.min){ this.min = i; }
        if(! this.max){ this.max = i; }
        if(i < this.min){ this.min = i;}
        if(i > this.max){ this.max = i; }
        if(! this.maxBars && !this.useQuarters){ this.maxBars = dataSet[i].length; }
        else if(!this.maxBars){ this.maxBars = dataSet[i][1].length ; }

        if(this.useQuarters){
            //parse each quarter first
            for(var q in dataSet[i]){
                if( i == this.min && (!this.minQ || this.minQ > q)){ this.minQ = parseInt(q); }
                if( i == this.max && (!this.maxQ || this.maxQ < q)){ this.maxQ = parseInt(q); }
                for(var v in dataSet[i][q]){ 
                    {
                    this.sets = dataSet[i][q][v].length ;
                    for(var z in dataSet[i][q][v]){
                        if(dataSet[i][q][v][z] > this.scaleMax){ this.scaleMax = dataSet[i][q][v][z]; } }
                    }
                } 
            }
        }
        else {
            for(var v in dataSet[i]){ 
                    {
                    this.sets = dataSet[i][v].length ;
                    for(var z in dataSet[i][v]){
                        if(dataSet[i][v][z] > this.scaleMax){ this.scaleMax = dataSet[i][v][z]; } }
                    }
                }
        }
    } 

    this.year = this.min;
    this.quart = this.minQ;
    this.startYear = this.min; this.endYear = this.max;
    this.quarter = 1;
    

    var defaults = {
        year: this.year,
        imgStop: 'images/stopsm.png',
        imgPlay: 'images/playsm.png',
        quarter: this.quarter,
        min: this.min,
        max: this.max,
        scaleMax: this.scaleMax,
        yScaleType : "linear",
        yScaleFormat: '',
        yScaleTicks : false,
        yTicksColor: "white",
        slideTimer: 1000,
        labelX: "X Axis",
        labelY: "Y Axis",
        labelAxisColor: "white",
        AxisValueColor: "white",
        minBars: this.maxBars - 4,
        maxBars: this.maxBars,
        w: 400,
        h: 400,
        showBarTotals: false,
        showBarTooltip: true
    };
    opts = $.extend({}, defaults, opts);
    this.bar = undefined;
    this.curBars = opts['curBars'] ? opts['curBars'] : opts['minBars'];

    this.prototype = new ProtoSlider(dataKeys, dataSet, opts);
    $.extend(this, this.prototype);




    //calculate tick scale if interval is set
    if(opts['yScaleInterval']){
        if(opts['yScaleFormat']&& opts['yScaleFormat']=='%'){
            this.opts['yScaleTicks'] = parseInt(100/ opts['yScaleInterval'])+2;   
        }
        else {
            this.opts['yScaleTicks'] = parseInt(opts['scaleMax']/opts['yScaleInterval']);
        }
        console.log("Generated "+this.opts['yScaleTicks']+" for interval "+this.opts['yScaleInterval']);

    }

    //setup tooltip if needed
    if(opts['showBarTooltip']){
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

    //Do Bar specific init
    this.y = pv.Scale.linear(0, opts['scaleMax']).range(0, opts['h']);
    if(this.yScaleType == 'root'){ 
        if(opts['yScaleRootMax']){ this.y = pv.Scale.root(0, this.scaleMax).range(0, opts['yScaleRootMax']);}
        else { this.y = pv.Scale.root(0, this.scaleMax).range(0, this.h); }
    }
    this.x = pv.Scale.ordinal(pv.range(this.curBars)).splitBanded(0, opts['w'], 4/5);
    $.visSlide.x = this.x; 
    $.visSlide.data = dataSet; $.visSlide.sets = this.sets;
    console.log(this.y.ticks(this.opts['yScaleTicks']));
    //console.log("Bar specific init done");
    
    }


    this.reloadData = function(newData){
        
        if(newData.keys){ this.dKeys = newData.keys; }
        if(newData.data){ this.dSet =  newData.data;}
        //if(newOpts){ this.tOpts = newOpts; }
         

        this.groupInit();
    }       

    this.reloadGrouped = function(){
        this.vis.children = [];
        this.singleMax = undefined;
        //this.prototype.vis.children = [];
        this.render();
        console.log("Reloading grouped bars"); 
        this.y = pv.Scale.linear(0, this.opts['scaleMax']).range(0, this.opts['h']);
        if(this.yScaleType == 'root'){ 
            if(this.opts['yScaleRootMax']){ this.y = pv.Scale.root(0, this.scaleMax).range(0, this.opts['yScaleRootMax']);}
            else { this.y = pv.Scale.root(0, this.scaleMax).range(0, this.h); }
        }
        this.x = pv.Scale.ordinal(pv.range(this.curBars)).splitBanded(0, this.opts['w'], 4/5);
        this.barInit();
        console.log("group init called");
    }

    


    this.groupInit = function(){
        console.log("group init");
        if(!this.bar){ console.log("first group init"); this.init(); this.prototype.groupInit(); }
        else { console.log("group init- init already called"); }
        

        this.vis = this.prototype.vis;
        this.prototype.register(this);

        
        this.barInit();

    }

    this.barInit = function(){
        console.log("barInit");
        var that = this;
        this.bar = this.vis.add(pv.Panel)
        .data(function(){  
            if(1){ return that.data[that.year][that.quart].slice(0,that.curBars); }
            //return that.data[that.year].slice(0, that.curBars); 
        })
        .left(function(){ return that.x(this.index) })
        .width(this.x.range().band)
        this.bar2 = this.bar.add(pv.Bar)
        .data(function(d){ return d })
        .left(function(){ return this.index * that.x.range().band / that.sets})
        .width(this.x.range().band / this.sets)
        .bottom(0)
        .height(this.y)
        .event("mouseover",function(z,y){
            if(!that.opts['showBarTooltip']){ return; }
            that.mouseZone = y; 
            that.showTooltip(y); })
        .event("click", function(z,y,q){
            console.log("click"); console.log(y);  
            var divLeft = $('#'+that.prototype.divID).position().left + 74;
            var relLeft = that.mouseX-divLeft;
            console.log(that.mouseX+" - "+divLeft+" = "+relLeft);
            var bars = parseInt(relLeft / (that.x.range().band+13))  ;
            console.log(bars); 
            that.redrawSingle(0,bars);
        })
        .event("mouseout",function(){ 
            if(!that.opts['showBarTooltip']){ return; }
            that.mouseZone = undefined;
            that.hideTooltip(); })
        .fillStyle(pv.Colors.category20().by(pv.index));
       
        //console.log("Prototype groupInited"); console.log(this); console.log(this.vis);
        this.vis.render();
        console.log("prototype groupinited");
        //console.log("Prototype groupInit Rendered");
        this.scaleInit();
         


    }

    this.showTooltip = function(y){
        if(!y){ console.log("tooltip failed, no data"); return; }
        if($('#sliderMapTT').length == 0){
        var newdiv = "<div id='sliderMapTT' style='position: absolute;"
        +"left: 1em; top: 2em; z-index: 99; margin-left: 0; width: 120px;"
        +"display: none; padding: 0.8em 1em;"
        +"background: #FFFFAA; border: 1px solid #FFAD33;"
        +"font-size: 0.8em; font-weight: bold;text-align:left;'><ul style='padding:0px;list-style-type:none;foat:left;'>";

        var yCount = 0;
        while(yCount < y.length){
            var col = pv.Colors.category20().range()[yCount].color;
            newdiv += "<li style='width:100px;float: left;'><div style='float:left;width:10px;height:10px;background-color:"
                +col+";'>&nbsp</div> "+y[yCount]+"</li>";
            yCount +=1;
        }
        newdiv += "</ul></div>";
        $('body').append(newdiv);
        
        }
        else {
            var newhtml = "<ul style='padding:0px;list-style-type:none;foat:left;'>";
            var yCount = 0;
            while(yCount < y.length){
                var col = pv.Colors.category20().range()[yCount].color;
                newhtml += "<li style='width:100px;float: left;'><div style='float:left;width:10px;height:10px;background-color:"
                    +col+";'>&nbsp</div> "+y[yCount]+"</li>";
                yCount +=1;
            }
            newhtml += "</ul>";
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
        if(this.opts['yScaleFormat'] == '%'){ 
            if(this.opts['yScaleInterval']){
                return parseInt(d/this.opts['scaleMax']*100)+'%';
            }
            return d+'%'; }
        if(this.opts['yScaleFormat'].length > 0){ return this.opts['yScaleFormat']+d; }
        return d;    
    }

    this.scaleInit = function(){
        console.log("Scale Init called");
        var that = this;
        
        if( this.opts['showBarTotals']){
            
            this.bar2.anchor("top").add(pv.Label)
            .textStyle(this.opts['AxisValueColor'])
            .text(function(d){ return d.toFixed(1) }); 
        }
     
        
        this.bar.anchor("bottom").add(pv.Label)
        .textMargin(5)
        .textStyle(this.opts['AxisValueColor'])
        .textBaseline("top")
        .text(function(){ return that.dataKeys[this.parent.index] });
     
        this.vis.add(pv.Label)
        .textMargin(5)
        .textStyle(this.opts['labelAxisColor'])
        .textAlign("center")
        .bottom(-35)
        .text(this.opts['labelX']);
        this.vis.add(pv.Label)
        .textMargin(5)
        .textStyle(this.opts['labelAxisColor'])
        .textAlign("left")
        .left(-55)
        .text(this.opts['labelY']);

        this.yRule = this.vis.add(pv.Rule)
        .data(this.yTicks())
        .bottom(function(d){ return Math.round(that.y(d)) - .5 })
        .strokeStyle(function(d){ return d ? "rgba(255,255,255,.3)" : "#fff" })
        .textStyle(this.opts['yTicksColor']);


        this.yLabels = this.yRule.add(pv.Rule)
        .left(0)
        .width(5)
        .strokeStyle("#fff")
        .anchor("left").add(pv.Label)
        .textStyle(this.opts['AxisValueColor'])
        .text(function(d){ return that.yScaleText(d.toFixed(1)) });
        
        this.vis.render();    
    }

    this.slideAddBar = function(){
        if(this.curBars < this.opts['maxBars']){ this.curBars +=1; this.prototype.slideUpdate(); }    
    }
    this.slideRemoveBar = function(){
        if(this.curBars > this.opts['minBars']){ this.curBars -= 1; this.prototype.slideUpdate(); }
    }

    
    //clear bar graph and draw single bar graph with line over time
    this.redrawSingle = function(drawState,drawGRP){
        var that = this;
        this.hideTooltip;
        console.log("redrawing bar graph for single column over time");
        this.vis.children = [];
        //this.prototype.vis.children = [];
        this.render();
        this.singleState = drawState;
        this.singleGRP = drawGRP;
        this.singleData = new Array();
        this.singleMax = 0;
        for(y in this.data){
            for(q in this.data[y]){
                    var d = parseFloat(this.data[y][q][drawGRP][drawState]);
                    this.singleData.push({
                        'year': y,
                        'q': q,
                        'd': d
                    });
                    if(d > this.singleMax){ this.singleMax = d; }

            }
        }
        this.y = pv.Scale.linear(0, this.singleMax).range(0, opts['h']);
        this.x = pv.Scale.ordinal(pv.range(this.singleData.length)).splitBanded(0, opts['w'], 4/5);
        console.log(this.singleData);
        
        this.bar = this.vis.add(pv.Bar)
            .data(this.singleData)
            .bottom(0)
            //.width(20)
            .height(function(d){ console.log(d.d); return d.d ; })
            .left(function(){ return that.x(this.index) })
            .width(this.x.range().band) 
            .event("mouseover",function(z){
                 })
            .event("mouseout",function(){ 
                if(!that.opts['showBarTooltip']){ return; }
                that.mouseZone = undefined;
                that.hideTooltip(); }) ;
        
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
        .strokeStyle(function(d){ return d ? "rgba(255,255,255,.3)" : "#fff" })
        .textStyle(this.opts['yTicksColor']);

        this.yLabels = this.yRule.add(pv.Rule)
        .left(0)
        .width(5)
        .strokeStyle("#fff")
        .anchor("left").add(pv.Label)
        .textStyle(this.opts['AxisValueColor'])
        .text(function(d){ return that.yScaleText(d.toFixed(1)) });

        this.render();
    }
}