//*********************************************************
//ProtoSliderBar - bar graph inherits from protoSlider base class
/*
  Options:
    year - current year to start graph at
    quarter - current quarter to start graph at
    dataNotation - formatting for data values. '%' is fixed to the end, any other symbol prepended
    innerLabel - text above inner wedge
    outerLabel- text above outer wedge
    showTooltips - display tooltip when hovering over slice
    hideData - do not display data inside slices


*/



ProtoSliderDonut = function(dataKeys, dataSet, opts){
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
        if(! this.maxBars){ this.maxBars = dataSet[i].length; }
        
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
    this.year = this.min;
    this.quarter = this.minQ; if(opts['quarter']){ this.quarter = opts['quarter']; }

    var defaults = {
        year: this.year,
        min: this.min,
        max: this.max,
        scaleMax: this.scaleMax,
        yScaleType : "linear",
        yScaleFormat: '',
        yScaleTicks : false,
        slideTimer: 1000,
        labelX: "X Axis",
        labelY: "Y Axis",
        minBars: this.maxBars - 4,
        maxBars: this.maxBars,
        w: 400,
        h: 400,
        left: 75,
        bottom: 75,
        largeIRadius : 55,
        largeORadius : 65,
        smallIRadius : 20,
        smallORadius : 39,
        showTooltip: true,
        hideData: true
    };
    var tmpW = opts['w'] ? opts['w'] : defaults['w'];
    var tmpH = opts['h'] ? opts['h'] : defaults['h'];
    defaults['left'] = tmpW / 2; defaults['bottom'] = tmpH / 2;
    defaults['largeORadius'] = tmpW/2-35 ; defaults['largeIRadius'] = tmpW/4 ; 
    defaults['smallORadius'] = tmpW/5 ; defaults['smallIRadius'] = tmpW/8 ; 
    opts = $.extend({}, defaults, opts);
    //console.log(opts);
    //this.bar = undefined;
    //this.curBars = opts['curBars'] ? opts['curBars'] : opts['minBars'];

    this.prototype = new ProtoSlider(dataKeys, dataSet, opts);
    $.extend(this, this.prototype);
    

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

    
    
    this.dataSlice = function(isOutter, i){
        //return single array with all i index elements 
        var arr = [];
        var aMap = [];
        if(isOutter){ this.oMap = {}; } 
        else{ this.iMap = {}; }
        var count = 0;
        for(var v in this.data[this.year][this.quarter]){
            arr.push(this.data[this.year][this.quarter][v][i]);
           

        }
        arr = pv.normalize(arr);
        for(var v in arr){
        aMap.push({a: arr[v],
            value: this.data[this.year][this.quarter][v][i],
            label: this.dataKeys[v] 
        });
        //if(isOutter){ this.oMap[arr[v]] = count; }
        //else {  this.oMap[arr[v]] = count; }
        //count += 1;
        }
        return aMap;
    }   
    
    this.groupInit = function(){
        this.prototype.groupInit();
        this.vis = this.prototype.vis;
        this.prototype.register(this);
        var that = this;

        //var testArr = this.dataSlice(0); console.log(testArr);

       this.wedge = this.vis.add(pv.Wedge)
        //.data(pv.normalize([1, 1.2, 1.7, 1.5, .7]))
        .data(function(){ return that.dataSlice(true,0) })
        .left(this.opts['left'])
        .bottom(this.opts['bottom'])
        .innerRadius(this.opts['largeIRadius'])
        .outerRadius(this.opts['largeORadius'])
        .angle(function(d){  return d.a * 2 * Math.PI }) 
        .event("mouseover",function(z,y){
            if(!that.opts['showTooltip']){ return; }
            
            that.mouseZone = z; 
            that.showTooltip(z); })
        .event("mouseout",function(){ 
            if(!that.opts['showTooltip']){ return; }
            that.mouseZone = undefined;
            that.hideTooltip(); });


      this.wedgeI = this.wedge.add(pv.Wedge)
        .data(function(){ return  that.dataSlice(false,1) })
        .innerRadius(this.opts['smallIRadius'])
        .outerRadius(this.opts['smallORadius']) 
        .event("mouseover",function(z,y){
            if(!that.opts['showTooltip']){ return; }
            
            that.mouseZone = z; 
            that.showTooltip(z); })
        .event("mouseout",function(){ 
            if(!that.opts['showTooltip']){ return; }
            that.mouseZone = undefined;
            that.hideTooltip(); });

       this.vis.render();
        this.scaleInit();

    }

    this.scaleInit = function(){
        var that = this;
       	this.labelV = this.wedge.anchor("center").add(pv.Label)
      .textAngle(0)
      .font("bold 11px/9px sans-serif")
      .text(function(d){  
          return that.yScaleText(d.value.toFixed(0));
        })
      .textStyle("#fff"); 

      this.wedgeLabels = this.vis.add(pv.Wedge)
        .data(function(){ return that.dataSlice(true,0) })
        .left(this.opts['left'])
        .bottom(this.opts['bottom'])
        .fillStyle("rgba(230, 130, 110, .0)")
        .innerRadius(this.opts['largeORadius']-2)
        .outerRadius(this.opts['largeORadius']+45)
        //.outerRadius(this.opts.w)
        .angle(function(d){  return d.a * 2 * Math.PI });
     this.labelT = this.wedgeLabels.anchor("center").add(pv.Label)
      .textAngle(0)
      .textAlign("center")
      .textStyle("#000")
      .font("bold 14px/12.5px sans-serif")
      .text(function(d){  
        return d.label+"";
      });


      
      this.labelI = this.wedgeI.anchor("center").add(pv.Label)
      .textAngle(0)
      .font("bold 11px/9px sans-serif")
      .text(function(d){  return that.yScaleText(d.value.toFixed(0)); })
      .textStyle("#fff");
        

      
      if(this.opts.innerLabel){
          this.wedgeI.add(pv.Label)
          .top(120)
          .left(170)
          .textAngle(0).text(this.opts.innerLabel);
      }
      if(this.opts.outerLabel){
          this.wedge.add(pv.Label)
          .top(10)
          .left(5)
          .font("bold 14px/9px sans-serif")
          .textAngle(0).text(this.opts.outerLabel);
      }

      this.vis.render();    
    }

 

    //Tooltip functions
    this.showTooltip = function(y){
        if(!y){ console.log("tooltip failed, no data"); return; }
        if($('#sliderMapTT').length == 0){
        var newdiv = "<div id='sliderMapTT' style='position: absolute;"
        +"left: 1em; top: 2em; z-index: 99; margin-left: 0; width: 120px;"
        +"display: none; padding: 0.8em 1em;"
        +"background: #FFFFAA; border: 1px solid #FFAD33;"
        +"font-size: 0.8em; font-weight: bold;text-align:left;'><b>"
        +y.label+":</b> "+this.yScaleText(y.value.toFixed(2));

        newdiv += "</div>";
        $('body').append(newdiv);
        
        }
        else {
            var newhtml = "<b>"+y.label+":</b> "+this.yScaleText(y.value.toFixed(2));
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