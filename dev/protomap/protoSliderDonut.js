//*********************************************************
//ProtoSliderBar - bar graph inherits from protoSlider base class

ProtoSliderDonut = function(opts){
    this.opts = opts;

    this.reloadData = function(newData){
        
        if(newData.keys){ this.dKeys = newData.keys; }
        if(newData.data){ this.dSet =  newData.data;}
        //if(newOpts){ this.tOpts = newOpts; }
        console.log("Reloading donut data");
        this.groupInit();
    }      

    this.init = function (){
        var opts = this.opts;
        var dataKeys = this.dKeys;
        var dataSet = this.dSet;
        this.min = false; this.max = false; this.maxBars = false;
        this.scaleMax = 0; this.sets = 1;
        this.useQuarters = 1;
        
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
            min: this.min,
            imgStop: 'images/stopsm.png',
            imgPlay: 'images/playsm.png',
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
            largeIRadius : 51,
            largeORadius : 60,
            smallIRadius : 20,
            smallORadius : 39
        };
        var tmpW = opts['w'] ? opts['w'] : defaults['w'];
        var tmpH = opts['h'] ? opts['h'] : defaults['h'];
        defaults['left'] = tmpW / 2; defaults['bottom'] = tmpH / 2;
        defaults['largeORadius'] = tmpW/2-20 ; defaults['largeIRadius'] = tmpW/4 ; 
        defaults['smallORadius'] = tmpW/5 ; defaults['smallIRadius'] = tmpW/8 ; 
        opts = $.extend({}, defaults, opts);
        //console.log(opts);
        //this.bar = undefined;
        //this.curBars = opts['curBars'] ? opts['curBars'] : opts['minBars'];

        this.prototype = new ProtoSlider(dataKeys, dataSet, opts);
        $.extend(this, this.prototype);                                         

    } //end init function

    
    //console.log("Bar specific init done");
    
    this.dataSlice = function(isOutter, i){
        //return single array with all i index elements 
        var arr = [];
        var aMap = [];
        if(isOutter){ this.oMap = {}; } 
        else{ this.iMap = {}; }
        var count = 0;
        //for(var q in this.data[this.year]){
            for(var v in this.data[this.year][this.quart]){
            arr.push(parseFloat(this.data[this.year][this.quart][v][i]));
            //console.log("y:"+this.year+" q:"+this.quart+" v:"+v+" i:"+i);
            //console.log(this.data[this.year][this.quart][v][i]);
            }

        //}
        //var arrSum = pv.sum(arr);
        //console.log("Sum: "+arrSum);
        arr = pv.normalize(arr);
        //console.log(arr);
        for(var v in arr){
            //console.log("pushing value "+v+" into aMap");
        aMap.push({a: arr[v],
            value: this.data[this.year][this.quart][v][i],
            label: this.dataKeys[v] 
        });
        //if(isOutter){ this.oMap[arr[v]] = count; }
        //else {  this.oMap[arr[v]] = count; }
        //count += 1;
        }
        //console.log(aMap);
        return aMap;
    }   
    
    this.groupInit = function(){
        this.init();
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
        .angle(function(d){
            //console.log(d.a);
            //console.log(d.a*2*Math.PI);  
            return d.a * 2 * Math.PI }) ;


      this.wedgeI = this.wedge.add(pv.Wedge)
        .data(function(){ return  that.dataSlice(false,1) })
        .innerRadius(this.opts['smallIRadius'])
        .outerRadius(this.opts['smallORadius']) ;

       this.vis.render();
        this.scaleInit();

    }


    this.scaleInit = function(){
        var that = this;
        this.labelV = this.wedge.anchor("center").add(pv.Label)
      .textAngle(0)
      .text(function(d){  
          //console.log(d);
          return parseFloat(d.value).toFixed(2) });
      

      this.wedgeLabels = this.vis.add(pv.Wedge)
        .data(function(){ return that.dataSlice(true,0) })
        .left(this.opts['left'])
        .bottom(this.opts['bottom'])
        .fillStyle("rgba(230, 130, 110, .0)")
        .innerRadius(this.opts['largeORadius']+35)
        .outerRadius(this.opts['largeORadius'])
        .angle(function(d){  return d.a * 2 * Math.PI }) ;
     this.labelT = this.wedgeLabels.anchor("center").add(pv.Label)
      .textAngle(0)
      .textAlign("center")
      .textStyle("#fff")
      .textShadow("0.1em 0.1em 0.1em rgba(0,0,0,.5)")
      .font("15px sans-serif")
      .text(function(d){  return ""+d.label+"" });

     

      this.labelI = this.wedgeI.anchor("center").add(pv.Label)
      .textAngle(0)
      .text(function(d){  return parseFloat(d.value).toFixed(2) });

        this.vis.render();    
    }

 

    //return this;
}