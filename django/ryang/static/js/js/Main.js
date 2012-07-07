var canvas;

//var stats = new Stats();

var delta = [0,0];
//var stage = [window.screenX,window.screenY,window.innerWidth,window.innerHeight];
var stage = [window.ScreenX,window.screenY,100,100];
getBrowserDimensions();

var theme;
var themes = 	[
					["#10222B","#95AB63","#BDD684","#E2F0D6","#F6FFE0"],
					["#362C2A","#732420","#BF734C","#FAD9A0","#736859"],
					["#0D1114","#102C2E","#695F4C","#EBBC5E","#FFFBB8"],
					["#2E2F38","#FFD63E","#FFB54B","#E88638","#8A221C"],
					["#121212","#E6F2DA","#C9F24B","#4D7B85","#23383D"],
					["#343F40","#736751","#F2D7B6","#BFAC95","#8C3F3F"],
					["#000000","#2D2B2A","#561812","#B81111","#FFFFFF"],
					["#333B3A","#B4BD51","#543B38","#61594D","#B8925A"]
				];

var worldAABB;
var world;
var iterations = 1;
var timeStep = 1 / 20;

var walls = new Array();
var wall_thickness = 200;
var wallsSetted = false;

var text;

var bodies;
var elements;

var createMode = false;
var destroyMode = false;

var isMouseDown = false;
var mouseJoint;
var mouseX = 0;
var mouseY = 0;
var PI2 = Math.PI * 2;

init();
play();

function init()
{
	canvas = document.getElementById('dvCanvas');
	
	document.onmousedown = onDocumentMouseDown;
	document.onmouseup = onDocumentMouseUp;
	document.onmousemove = onDocumentMouseMove;
	document.ondblclick = onDocumentDoubleClick;
	//document.onselectstart = function() {return false;} // ie
	
	// init box2d
	
	worldAABB = new b2AABB();
	worldAABB.minVertex.Set(-200, -200);
	worldAABB.maxVertex.Set( screen.width + 200, screen.height + 200);

	world = new b2World(worldAABB, new b2Vec2(0, 0), true);
		
	setWalls();
	reset();
}


function play()
{
	setInterval(loop, 25);	
}

function reset()
{	
	// color theme
	theme = themes[ Math.random() * themes.length >> 0 ];
	if(canvas){
	canvas.backgroundColor = theme[0]; }
	
	bodies = new Array();
	elements = new Array();	

	createInstructions();
	
	for(var i = 0; i < 10; i++)
		createBall();
}

// .. ACTIONS

function onDocumentMouseDown()
{
	isMouseDown = true;
	return false;
}

function onDocumentMouseUp()
{
	isMouseDown = false;
	return false;
}

function onDocumentMouseMove(e)
{
	var ev = (!e) ? window.event : e;
	mouseX = ev.clientX;
	mouseY = ev.clientY;
}

function onDocumentDoubleClick()
{
	for (i = 0; i < bodies.length; i++)
	{
		var body = bodies[i]
		$('#dvCanvas').removeChild( body.GetUserData().element );
		world.DestroyBody(body);
		body = null;
	}

	reset();
}

function onElementMouseDown()
{
	return false;
}

function onElementMouseUp()
{
	return false;
}

function onElementClick()
{
	return false;
}

//
function createInstructions()
{
	var size = 250;
	
	var element = document.createElement("div");
	element.width = size;
	element.height = size;	
	element.style['position'] = 'absolute';
	element.style['left'] = -200 + 'px';
	element.style['top'] = -200 + 'px';
	element.style.cursor = "default";
	
	if(! canvas ){ canvas = document.getElementById('dvCanvas'); }
	if(canvas){
		canvas.appendChild(element);}
	elements.push( element );	
	
	var circle = document.createElement("canvas");	
	circle.width = size;
	circle.height = size;
	
	var graphics = circle.getContext("2d");
	
	graphics.fillStyle = theme[3];
	graphics.beginPath();
	graphics.arc(size * .5, size * .5, size * .5, 0, PI2, true); 
	graphics.closePath();
	graphics.fill();
	
	element.appendChild(circle);
	
	text = document.createElement("div");
	text.onSelectStart = null;
	text.innerHTML = '<span style="color:' + theme[0] + ';font-size:40px;">404!</span><br /><br /><span style="font-size:15px;"><strong>This is how it works:</strong><br /><br />1. Drag a ball.<br />2.&nbsp;Click&nbsp;on&nbsp;the&nbsp;background.<br />3. Shake your browser.<br />4. Double click.<br />5. Play!</span>';
	text.style['color'] = theme[1];
	text.style['position'] = 'absolute';
	text.style['left'] = '0px';
	text.style['top'] = '0px';	
	text.style['font-family'] = 'Georgia';
	//text.style['text-align'] = 'center';
	text.style.textAlign = 'center';
	element.appendChild(text);	
	
	text.style['left'] = ((250 - text.clientWidth) / 2) +'px';
	text.style['top'] = ((250 - text.clientHeight) / 2) +'px';	

	var b2body = new b2BodyDef();	
	
	var circle = new b2CircleDef();
	circle.radius = size >> 1;
	circle.density = 1;
	circle.friction = 0.3;
	circle.restitution = 0.3;
	b2body.AddShape(circle);
	b2body.userData = {element: element};

	b2body.position.Set( Math.random() * stage[2], Math.random() * -200 );
	b2body.linearVelocity.Set( Math.random() * 400 - 200, Math.random() * 400 - 200 );
	bodies.push( world.CreateBody(b2body) );	
}

function createBall(x,y)
{
	var x = (x != null) ? x : Math.random() * stage[2];
	var y = (y != null) ? y : Math.random() * -200;

	var size = (Math.random() * 100 >> 0) + 20;

	var element = document.createElement("canvas");
	element.width = size;
	element.height = size;
	element.style['position'] = 'absolute';
	element.style['left'] = -200 + 'px';
	element.style['top'] = -200 + 'px';	
	
	var graphics = element.getContext("2d");

	var num_circles = Math.random() * 10 >> 0;
	
	for (var i = size; i > 0; i-= (size/num_circles))
	{
		graphics.fillStyle = theme[ (Math.random() * 4 >> 0) + 1];
		graphics.beginPath();
		graphics.arc(size * .5, size * .5, i * .5, 0, PI2, true); 
		graphics.closePath();
		graphics.fill();
	}
	
	if(canvas){ canvas.appendChild(element); }
	
	elements.push( element );	

	var b2body = new b2BodyDef();	
	
	var circle = new b2CircleDef();
	circle.radius = size >> 1;
	circle.density = 1;
	circle.friction = 0.3;
	circle.restitution = 0.3;
	b2body.AddShape(circle);
	b2body.userData = {element: element};

	b2body.position.Set( x, y );
	b2body.linearVelocity.Set( Math.random() * 400 - 200, Math.random() * 400 - 200 );
	bodies.push( world.CreateBody(b2body) );
}

//

function loop()
{
	if (getBrowserDimensions())
		setWalls();

	delta[0] += (0 - delta[0]) * .5;
	delta[1] += (0 - delta[1]) * .5;
	
	world.m_gravity.x = 0 + delta[0];
	world.m_gravity.y = 350 + delta[1];
		
	mouseDrag();
	world.Step(timeStep, iterations);	
	
	for (i = 0; i < bodies.length; i++)
	{
		var body = bodies[i];
		var element = elements[i];
		
		element.style['left'] = (body.m_position0.x - (element.width >> 1)) + 'px';
		element.style['top'] = (body.m_position0.y - (element.height >> 1)) + 'px';
		
		if (element.tagName == "DIV")
		{
			// webkit
			text.style['-webkit-transform'] = 'rotate(' + (bodies[i].m_rotation0 * 57.2957795) + 'deg)';
			
			// gecko
			text.style['MozTransform'] = 'rotate(' + (bodies[i].m_rotation0 * 57.2957795) + 'deg)';
		}
	}
}


// .. BOX2D UTILS

function createBox(world, x, y, width, height, fixed)
{
	if (typeof(fixed) == 'undefined') fixed = true;
	var boxSd = new b2BoxDef();
	if (!fixed) boxSd.density = 1.0;
	boxSd.extents.Set(width, height);
	var boxBd = new b2BodyDef();
	boxBd.AddShape(boxSd);
	boxBd.position.Set(x,y);
	return world.CreateBody(boxBd)
}

function mouseDrag()
{
	// mouse press
	if (createMode)
	{
		createBall( mouseX, mouseY );
	}
	else if (isMouseDown && !mouseJoint)
	{
		var body = getBodyAtMouse();
		
		if (body)
		{
			var md = new b2MouseJointDef();
			md.body1 = world.m_groundBody;
			md.body2 = body;
			md.target.Set(mouseX, mouseY);
			md.maxForce = 30000 * body.m_mass;
			md.timeStep = timeStep;
			mouseJoint = world.CreateJoint(md);
			body.WakeUp();
		}
		else
		{
			createMode = true;
		}
	}
	
	// mouse release
	if (!isMouseDown)
	{
		createMode = false;
		destroyMode = false;
	
		if (mouseJoint)
		{
			world.DestroyJoint(mouseJoint);
			mouseJoint = null;
		}
	}
	
	// mouse move
	if (mouseJoint)
	{
		var p2 = new b2Vec2(mouseX, mouseY);
		mouseJoint.SetTarget(p2);
	}
}

function getBodyAtMouse()
{
	// Make a small box.
	var mousePVec = new b2Vec2();
	mousePVec.Set(mouseX, mouseY);
	
	var aabb = new b2AABB();
	aabb.minVertex.Set(mouseX - 1, mouseY - 1);
	aabb.maxVertex.Set(mouseX + 1, mouseY + 1);

	// Query the world for overlapping shapes.
	var k_maxCount = 10;
	var shapes = new Array();
	var count = world.Query(aabb, shapes, k_maxCount);
	var body = null;
	
	for (var i = 0; i < count; ++i)
	{
		if (shapes[i].m_body.IsStatic() == false)
		{
			if ( shapes[i].TestPoint(mousePVec) )
			{
				body = shapes[i].m_body;
				break;
			}
		}
	}
	return body;
}

function setWalls()
{
	if (wallsSetted)
	{
		world.DestroyBody(walls[0]);
		world.DestroyBody(walls[1]);
		world.DestroyBody(walls[2]);
		world.DestroyBody(walls[3]);
		
		walls[0] = null; 
		walls[1] = null;
		walls[2] = null;
		walls[3] = null;
	}
	
	walls[0] = createBox(world, stage[2] / 2, - wall_thickness, stage[2], wall_thickness);
	walls[1] = createBox(world, stage[2] / 2, stage[3] + wall_thickness, stage[2], wall_thickness);
	walls[2] = createBox(world, - wall_thickness, stage[3] / 2, wall_thickness, stage[3]);
	walls[3] = createBox(world, stage[2] + wall_thickness, stage[3] / 2, wall_thickness, stage[3]);	
	
	wallsSetted = true;
}

// BROWSER DIMENSIONS

function getBrowserDimensions()
{
	var changed = false;
		
	if (stage[0] != window.screenX)
	{
		delta[0] = (window.screenX - stage[0]) * 50;
		stage[0] = window.screenX;
		changed = true;
	}
	
	if (stage[1] != window.screenY)
	{
		delta[1] = (window.screenY - stage[1]) * 50;
		stage[1] = window.screenY;
		changed = true;
	}
	
	if (stage[2] != window.innerWidth)
	{
		//stage[2] = window.innerWidth;
		changed = true;
	}
	
	if (stage[3] != window.innerHeight)
	{
		//stage[3] = window.innerHeight;
		changed = true;
	}
	
	return changed;
}	
