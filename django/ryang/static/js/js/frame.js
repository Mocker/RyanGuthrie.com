/***** disabled YUI menu cause it blows or something
YAHOO.util.Event.onContentReady("DV_MENU", function() {
	var oMenu = new YAHOO.widget.MenuBar("DV_MENU", 
								{ autosubmenudisplay: true,
								hidedelay: 500,
								lazyload: true});
	oMenu.render();
	} );
*****/

var buzzDoc; //will hold buzz xml feed
var isIE = 0;

$(document).ready(function(){

		loadBuzz();

	$("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)

	$("ul.topnav li").mouseover(function() { //When trigger is clicked...

		//Following events are applied to the subnav itself (moving subnav up and down)
		$(this).find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click

		$(this).hover(function() {
		}, function(){
			$(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
		});

		//Following events are applied to the trigger (Hover events for the trigger)
		}).hover(function() {
			$(this).addClass("subhover"); //On hover over, add class "subhover"
		}, function(){	//On Hover Out
			$(this).removeClass("subhover"); //On hover out, remove class "subhover"
	});
	
	//right sliding menu
	
	//$("ul.subnav_left").css("display","none");
	$("ul.subnav_right").hide();
	$("ul.subnav_right").parent().mouseover(function() {
		//$("ul.subnav_left").css("display","block");
		$("ul.subnav_right").show("fast"); });
	$("ul.subnav_right").parent().mouseout(function() {		
		$("ul.subnav_right").hide(); 
		//$("ul.subnav_left").css("display","none"); 
		});
	
	

});

function ajaxRequest(){
 var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
 if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
  for (var i=0; i<activexmodes.length; i++){
   try{
    return new ActiveXObject(activexmodes[i])
   }
   catch(e){
    //suppress error
   }
  }
 }
 else if (window.XMLHttpRequest) // if Mozilla, Safari etc
  return new XMLHttpRequest()
 else
  return false
}

//load buzz feed
function loadBuzz(){
	var browser = navigator.appName ;
	var xhttp = ajaxRequest();
	/*if(browser == "Microsoft Internet Explorer"){
		isIE = 1;
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
		//buzzDoc = new ActiveXObject("Microsoft.XMLDOM");
		//buzzDoc.async="false";
		//buzzDoc.onreadystatechange = parseBuzz ;
		//buzzDoc.load("http://buzz.googleapis.com/feeds/SokerCap/public/posted");
	}
	else {
		isIE = 0;
		xhttp = new XMLHttpRequest() ;
		//var parser = new DOMParser();
		//buzzDoc = document.implementation.createDocument("","",null);
		//buzzDoc.load("http://buzz.googleapis.com/feeds/SokerCap/public/posted");
		//buzzDoc.onload = parseBuzz ;
	}*/
	xhttp.open("GET","http://ryanguthrie.com:3000/static/misc/buzz.xml",false);
	xhttp.onerror = parseBuzzFail ;
	xhttp.send("");
	buzzDoc = xhttp.responseXML;
	if(xhttp.status == 200) { parseBuzz(); }
	else { 
		alert("Jiminy no doc- status: "+xhttp.status+" \ncontentType: "+xhttp.statusText);
	}
} 
function parseBuzz(){
	if(!buzzDoc) return;
	if(isIE && buzzDoc.readystate != 4) return;
	
	//debug crap
	//alert("XML Root Tag Name: " + buzzDoc.documentElement.tagName);
	//alert("First Entry: "+buzzDoc.getElementsByTagName("published")[0].text );
	//alert("Entries: "+ buzzDoc.getElementsByTagName("entry").length);
	
	var entries = buzzDoc.getElementsByTagName("entry") ;
	var html = "<ul><li><b>Latest Buzz:</b></li>\n";
	for(var i=0; i<entries.length;i++)
	{
		html =html + "<li>";
		if( entries[i].getElementsByTagName("updated").length > 0) html += "<span class='buzzbox'><i>"+entries[i].getElementsByTagName("updated")[0].childNodes[0].nodeValue+"</i></span><br>";
		if( entries[i].getElementsByTagName("link").length > 0) html += "<a href='"+entries[i].getElementsByTagName("link")[0].getAttribute("href")+"'>";
		else html += "<a target='#'>";
		html =html + entries[i].getElementsByTagName("summary")[0].childNodes[0].nodeValue+"</a></li><li></li>\n";
	}
	html += "</ul>";
	
	$("#DV_BUZZBOX").html(html);
	
}
function parseBuzzFail()
{
	alert("Buzz FAILED ");
}


//display menu labels
function frame_help_show() {
	return;
	var aDivs = document.getElementsByName("DV_HELPTXT");
	var i = 0;
	for (i=0;i<aDivs.length;i++ )
	{
		aDivs[i].style.left = '0px';
		aDivs[i].style.top = (i+1.5)+'em';
		aDivs[i].style.display = 'block';
	}
}


// change help css to hide menu labels
function frame_help_hide() {
	return; 
	var aDivs = document.getElementsByName("DV_HELPTXT");
	var i = 0;
	for (i=0;i<aDivs.length;i++ )
	{
		//aDivs[i].style.display = 'none';
	}
}
