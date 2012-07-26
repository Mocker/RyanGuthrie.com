// forked from cezinha's "3D Geometry Experience (Spiral)" http://jsdo.it/cezinha/nVUM
// modified by Ryan Guthrie to form interactive gallery with textures mapped onto spiral and clickable actions to display them
var spiralGL = function(canvasID, projs) {
    /**
     * Following Aerotwist Tutorial
     * http://www.aerotwist.com/lab/getting-started-with-three-js/
     */
     // globals variables
     var mouseX = 0, mouseY = 0,
     cID = canvasID, fishyPlane,
     windowHalfX = window.innerWidth / 2,
     windowHalfY = window.innerHeight / 2,
     ray, mouse3D, brush, projector,
     projects = projs, conW=0,conH=0,
     camera, scene, renderer, assets = new Object();
    console.log(projects);
    console.log(windowHalfX+" by "+windowHalfY);

     var main = function() {
        if ( ! Detector.webgl ) Detector.addGetWebGLMessage();
              
        init();
        animate();
    };
    
    var init = function() {  
        //var container = $('#'+cID);
        var container = document.getElementById(cID);
        console.log(container);
        var win = window;
        conW = container.offsetWidth;
        conH = container.offsetHeight;
        //container = document.createElement('div');
        //document.body.appendChild(container);
        // cam y win.innerWidth / win.innerHeight
        camera = new THREE.Camera( 45, conW / conH, 0.1, 10000 );
        camera.position.z = 400;
        
        scene = new THREE.Scene();
                
        renderer = new THREE.WebGLRenderer();
        //window.innerWidth innerHeight
        renderer.setSize( container.offsetWidth, container.offsetHeight );
        container.appendChild( renderer.domElement );
        projector = new THREE.Projector();
        ray = new THREE.Ray( camera.position, null );
        loadMaterials();
        
        fishyPlane = new THREE.Mesh(new THREE.PlaneGeometry(3000, 3000), assets.fishMaterial);
        fishyPlane.overdraw = true;
        fishyPlane.position.z = -900;
        scene.addChild(fishyPlane);

        var startHex = 0xFFFF00;
        var endHex = 0xFF00FF;
        
        var objects = [];
        
        var centerX = 0;
        var centerZ = 0;
        var posY = 240;
        var count = 0;
        var objsQty = 30;
        var radius = 30;
        var loops = 4;
        var degree = loops * 360 / (objsQty*1);
        var startI = 10;
        var projKeys = Object.keys(projects);
        
        for (var i = startI; i < objsQty; i++) {
            var posX = centerX + ((radius * i * 0.3) * Math.cos(degToRad(degree * i)));
            var posZ = centerZ - ((radius * i * 0.3) * Math.sin(degToRad(degree * i)));  
            var newPosY = posY -  ((radius * i * 0.35) );
            //var newPosY = posY;
            var ratio = i / objsQty; 
            color = fadeHex(startHex, endHex, ratio);
            
            var mat = false;
            if(i < projKeys.length + startI){
                mat = assets.projMaterials[projKeys[i-10]];
                console.log("loading project mat for "+projKeys[i-10]);
                console.log(mat);
            }
            var sphere = drawSphere(color, posX, newPosY, posZ,mat);                   
            //posY -= 1;
            
            // add the sphere to the scene
            scene.addChild(sphere);
        }       
    
        // create a point light
        var pointLight = new THREE.PointLight( 0xcccccc );
        
        // set its position
        pointLight.position.x = 10;
        pointLight.position.y = 50;
        pointLight.position.z = 130;
        
        // add to the scene
        scene.addLight( pointLight );
        
        var directionalLight = new THREE.DirectionalLight( 0xff0000, 1 );
				directionalLight.position.set( 50, 80, 10 ).normalize();
				scene.addLight( directionalLight );
        var directionalLight2 = new THREE.DirectionalLight( 0xffffff, 1 );
				directionalLight2.position.set( -50, -80, 100 ).normalize();
				scene.addLight( directionalLight2 );

        container.addEventListener( 'click', onClick, false );
        document.addEventListener( 'mousemove', onDocumentMouseMove, false );
        document.addEventListener( 'touchstart', onDocumentTouchStart, false );
        document.addEventListener( 'touchmove', onDocumentTouchMove, false );  
        //window.addEventListener( 'DOMMouseScroll', onWindowMouseWheel, false);
        window.onmousewheel = onWindowMouseWheel;
    };
    
    var degToRad = function(degree) {
        radians = degree * (Math.PI/180);
        return radians;
    };
    
    var fadeHex = function(hex, hex2, ratio){
        var r = hex >> 16;
        var g = hex >> 8 & 0xFF;
        var b = hex & 0xFF;
        r += ((hex2 >> 16)-r)*ratio;
        g += ((hex2 >> 8 & 0xFF)-g)*ratio;
        b += ((hex2 & 0xFF)-b)*ratio;
        return(r<<16 | g<<8 | b);
    };
    
    var loadMaterials = function() {
        var imgURL = '/site_media/images/icons/stepfinal3.png';
      	var imgTexture = THREE.ImageUtils.loadTexture( imgURL );
		if(imgTexture){ 
            imgTexture.repeat.set( 4, 2 );
            imgTexture.wrapS = imgTexture.wrapT = THREE.RepeatWrapping;  
            assets.imgTexture = imgTexture;
            console.log("loaded image texture"); console.log(assets.imgTexture);
            var shininess = 15, shading = THREE.SmoothShading;
            //assets.imgMaterial = new THREE.MeshPhongMaterial( { map: assets.imgTexture, color: 0x000000, ambient: 0x000000, specular: 0xffaa00, shininess: shininess, metal: true, shading: shading } );
            assets.imgMaterial  = new THREE.MeshLambertMaterial({
            map: THREE.ImageUtils.loadTexture(imgURL)
       		 });
            console.log(assets.imgMaterial);
        }
        var fishURL = '/site_media/images/fishy_leftbar.jpg';
        assets.fishMaterial  = new THREE.MeshLambertMaterial({
            map: THREE.ImageUtils.loadTexture(fishURL)
             });
        assets.projMaterials = new Object();
        var projKeys = Object.keys(projects);
        for(var i=0;i<Object.keys(projects).length;i++){
            var projID = projKeys[i];
            assets.projMaterials[projID] = new THREE.MeshLambertMaterial({
            map: THREE.ImageUtils.loadTexture(projects[projID].thumbSrc)
             });
        }
        console.log("finsihed load mat"); console.log(assets);
       
    };
    
    var drawSphere = function(color, x, y, z,mat) {
        // create the sphere's material
        var sphereMaterial = new THREE.MeshLambertMaterial(
        {
          // a gorgeous red.
          color: color
        });
    
        // set up the sphere vars
        var radius = 8, segments = 8, rings = 8;
        var cubesize = 30;
        
        // create a new mesh with sphere geometry -
        // we will cover the sphereMaterial next!
        //var sphere = new THREE.Mesh(
        //   new THREE.SphereGeometry(radius, segments, rings),
        //    assets.imgMaterial);
        //var sphere = new THREE.Mesh( new THREE.CubeGeometry( cubesize,cubesize,cubesize ), sphereMaterial );
        //new THREE.MeshNormalMaterial()
        sphereMaterial = assets.imgMaterial;
        if(mat) sphereMaterial = mat;
        var sphere = new THREE.Mesh( new THREE.CubeGeometry( cubesize, cubesize, cubesize ),  sphereMaterial);
       
        sphere.position.x = x;
        sphere.position.y = y;
        sphere.position.z = z;
        
        return sphere;
    };
    
    var handle = function(delta) {
        if (delta > 0) {
            camera.position.z += ( delta - camera.position.z ) * 0.05;
        } else {
            camera.position.z += ( camera.position.z - delta ) * 0.05;
        }
    };
    
    var onWindowMouseWheel = function(event) {
        event = event ? event : window.event;
        var delta = event.detail ? event.detail * -1 : event.wheelDelta / 40;
        
        if (delta)
            handle(delta);
        /** Prevent default actions caused by mouse wheel.
         * That might be ugly, but we handle scrolls somehow
         * anyway, so don't bother here..
         */
        if (event.preventDefault)
                event.preventDefault();
        event.returnValue = false;
    };
    
    var onDocumentMouseMove = function(event) {
        mouseX = event.clientX - windowHalfX;
        mouseY = event.clientY - windowHalfY;
        //mouseX = event.clientX - (conW/2);
        //mouseY = event.clientY - (conH/2);

        
    };
    
    var onDocumentTouchStart = function( event ) {
        console.log("onDocumentTouchStart"); console.log(event);
        if ( event.touches.length > 1 ) {
            
            event.preventDefault();
            
            mouseX = event.touches[ 0 ].pageX - windowHalfX;
            mouseY = event.touches[ 0 ].pageY - windowHalfY;
        }
    };
    
    var onDocumentTouchMove = function( event ) {
        if ( event.touches.length == 1 ) {
        
            event.preventDefault();
            
            mouseX = event.touches[ 0 ].pageX - windowHalfX;
            mouseY = event.touches[ 0 ].pageY - windowHalfY;
        }
    };

    var onClick = function( event ) {

                    event.preventDefault();

                    mouse3D = projector.unprojectVector( new THREE.Vector3( ( event.clientX / renderer.domElement.width ) * 2 - 1, - ( event.clientY / renderer.domElement.height ) * 2 + 1, 0.5 ), camera );
                    ray.direction = mouse3D.subSelf( camera.position ).normalize(); 
                    console.log("click"); console.log(ray);

                    var intersect, intersects = ray.intersectScene( scene );
                    console.log(intersect); console.log(intersects);
                    if ( intersects.length > 0 ) {
                        intersect = intersects[ 0 ].object ==  intersects[ 0 ];
                        if ( intersect &&  intersect.object != plane ) {
                            console.log("Interect!"); console.log(intersect.object);
                        }
                    }
    };

    
    var animate = function() {
        requestAnimationFrame( animate );
        
        render();
    };
    
    var render = function() {
        camera.position.x += ( mouseX - camera.position.x ) * 0.05;
        camera.position.y += ( - mouseY + 200 - camera.position.y ) * 0.05;

        camera.updateMatrix();
     
        renderer.render( scene, camera );
    };
    
    main();
};
