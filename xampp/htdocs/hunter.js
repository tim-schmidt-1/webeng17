
// Global variable
var img = null,
	needle = null,
	ctx = null,
	s=0, //aktuelle pos des zeigers
  z=0, //ziel des zeigers
	os=0, //entfernung s von 0 grad
	oz=0,//entfernung z von 0 grad
	posx=0.0, //Eigene Position latitude
	posy=0.0,  //Eigene Position longitude
	zielx=0, //Ziel Position latitude
	ziely=0,  //Ziel Position longitude
	entf=0, //Entfernung zum Ziel
	richtung = 1,
	deviceorientation=0,
	antideviceorientation=0,
  mode="m",
  currentcomment="Startkommentar";


  var xdb=new Array();
  var ydb=new Array();
  var cdb=new Array();

  var standort;
  var info;
  var ziel;

function endgame(){
  console.log("No more Points, games has ended.");
	//endgame message back to index?
}

function getNextPoint(){
	//change the new ziel to the next point in db
	// ziel = {lat: NEWVALUE, lng: NEWVALUE}
  var currentx = xdb.pop();
  var currenty = ydb.pop();
  currentcomment = cdb.pop();

	if (currentx==null){
		endgame();
		return false;
	}
	console.log("Neues Ziel geladen: " + currentx + ", " + currenty);

//  ziel = {lat: currentx, lng: currenty};
	ziel = new google.maps.LatLng(currentx, currenty);
	return true;
  /**
  if (x && y && c){
    ziel = "{lat: "+x+", lng: "+y+"}";
    return true;
  } else {
	 // if there is no point left in db
   endgame();
   return false;
 }
 **/

 }

function loadData(){

	var datapoints = JSON.parse('[{"Hinweis":"h1","Breitengrad":"49.414878599999994","Laengengrad":"8.6724602"},{"Hinweis":"h2","Breitengrad":"49.4147299","Laengengrad":"8.672346"}]');

	for (i in datapoints) {
  	xdb.unshift(parseFloat(datapoints[i].Breitengrad));
	  ydb.unshift(parseFloat(datapoints[i].Laengengrad));
	  cdb.unshift(parseFloat(datapoints[i].Hinweis));
	  console.log(datapoints[i].Breitengrad);
	  console.log(datapoints[i].Laengengrad);
	  console.log(datapoints[i].Hinweis);
	}
  //xdb.unshift(49.4743855);
//  cdb.unshift("Comment 1");

  //xdb.unshift(49.4763855);
  //ydb.unshift(8.4879386);
  //cdb.unshift("Comment 2");

  //xdb.unshift(49.4763855);
  //ydb.unshift(8.4909386);
  //cdb.unshift("Comment 3");

  //xdb.unshift(49.4743855);
  //ydb.unshift(8.4909386);
  //cdb.unshift("Comment 4");
}

function switchmode(){
	console.log("mode: " + mode);
  if (mode=="m"){
    mode="c";
    setModeCompass();
  } else if (mode=="c"){
    mode="m";
    setModeMap();
  } else {
		console.log("Mode is not properly set");
	}

}

function setTitle(){
	document.getElementById("place").value =  window.name;
}


function initCompass() {
	zielx=ziel.lat; //Ziel Position latitude
	ziely=ziel.lng;  //Ziel Position longitude

  activateOrientationListener();

	// Grab the navigation element
	var canvas = document.getElementById('navigation');
	info = document.getElementById('info');


	// Canvas supported?
	if (canvas.getContext('2d')) {
		ctx = canvas.getContext('2d');

		// Load the needle image
		needle = new Image();
		needle.src = 'needle.png';

		// Load the compass image
		img = new Image();
		img.src = 'compass.png';
		img.onload = imgLoaded;
	} else {
		console.log("Canvas not supported!");
	}
}


function setModeCompass(){

		console.log("setmodecompass");
		if(getNextPoint()){
		document.getElementById('navigation').style.visibility='visible';
		document.getElementById('navigation').style.height='50%';
		document.getElementById('navigation').style.width='50%';
		document.getElementById('googlemap').style.visibility='hidden';
		document.getElementById('googlemap').style.height='0';
		document.getElementById('googlemap').style.width='0';
	}
}

function clearCanvas() {
	 // clear canvas
	ctx.clearRect(0, 0, 200, 200);
}

function updateCompass() {
  //auf 3 nachkommastellen muss es passen der rest ist egal

		console.log(ziel.lat + " - " + posx + " , " + ziel.lng + " - " + posy);
		console.log("div: " + Math.abs(ziel.lat-posx) + ", "+ Math.abs(ziel.lng-posy));

  if (Math.abs(ziel.lat-posx)<0.001 && Math.abs(ziel.lng-posy)<0.001){

    console.log("Ziel Kompass erreicht");
    switchmode();
  }

	//document.getElementById("info").innerHTML = "Aktuelle Position:" + posx + " - " + posy;




  if (Math.abs(s-(z%360))>3){

				if(s>180) os=360-s;
				else os=s;

				if(z>180) oz=360-z;
				else oz=z;

				if (Math.abs(z-s)>os+oz){
					if(z-s>0) richtung=-1;
					else richtung=1;
				} else {
					if(z-s>0) richtung=1;
					else richtung=-1;
				}

			// nadel um 5 Grad in richtige richtung
			s = (s+richtung*5)%360;
		  if(s<=0) s = 360-s;

	clearCanvas();

	// neuer Hintergrund
	ctx.drawImage(img, 0, 0);

	// state speichern
	ctx.save();


	// in die Mitte des Bilds wechseln
	ctx.translate(100, 100);

	// an diesem Punkt das Zeichenframework so drehen wie die nadel steht
	ctx.rotate(s * (Math.PI / 180));

	// nadel hinmalen
	ctx.drawImage(needle, -100, -100);

	// zum gespeicherten state zurück
	ctx.restore();

	}

}

function caculateDirection(deviceorientation){

	var zielwinkel=0;
	var coordwinkel=calccoordwinkel();
	if (coordwinkel===(-1)){
		coordwinkel=0;
		console.log('The goal is at the location');
		return -1;
	}

	if(coordwinkel-deviceorientation>0) zielwinkel=coordwinkel-deviceorientation; //wenn
	else zielwinkel= 360-(deviceorientation-coordwinkel)

	return zielwinkel;
}

function calccoordwinkel(){

	var xdif = zielx-posx;
	var ydif = ziely-posy;
	console.log('xdif = ' + xdif + ' , ydif = ' + ydif);

	//quardanten im uhrzeigersinn von oben rechts=1 bis oben links=4
	if(ydif>0.0){ // ziel ist in oberen quardanten
		if(xdif>0.0){ // ziel ist in rechten quardanten - Q1
			console.log('Quadrant 1');
			var x = Math.atan(xdif/ydif)*(180/Math.PI); // winkel von x Achse zu geraden die auf Ziel Zeitg in rad , *(180/Math.PI) wandelt das in Grad um
			return 90-x;
		} else if(xdif<0.0){ //ziel ist in linken Quadranten - Q4
			console.log('Quadrant 4');
			var x = Math.atan(ydif/(-xdif))*(180/Math.PI);;
			return 270.0+x;
		} else { //ziel liegt auf gleicher breite
			return 0;
		}
	} else if(ydif<0.0){ //ziel ist in unterem Quadranten
		if(xdif>0.0){ // ziel ist in rechtem quardanten - Q2
			console.log('Quadrant 2');
			var x = Math.atan((-ydif)/xdif)*(180/Math.PI);;
			return 90.0+x;
		} else if(xdif<0.0){ //ziel ist in linken Quadranten - Q3
			console.log('Quadrant 3');
			var x = Math.atan((-xdif)/(-ydif))*(180.0/Math.PI);;
			return 270.0-x;
		} else { //ziel liegt auf gleicher breite
			return 180.0;
		}
	} else { //ziel liegt auf gleicher höhe
		console.log('same height');
		if(xdif>0){ // ziel ist in rechten quardanten
			return 90;
		} else if(xdif<0){ //ziel ist in linken Quadranten
			return 270;
		} else { //ziel liegt auf gleicher breite
			console.log('same widht');
			console.log('at the goal');
			return -1;
		}
	}
	// -1 heiß das Ziel liegt genau drauf
}

function imgLoaded() {
		ctx.drawImage(img, 0, 0);
		ctx.save();
		ctx.translate(100, 100);
		ctx.drawImage(needle, -100, -100);
		ctx.restore();

//	setInterval(updateCompass, 100);
}

function activateOrientationListener(){
  if(window.DeviceOrientationEvent) {
      console.log('Congratulations! Device orientation IS supported - You can not play on the Equator, tho. Don\'t be ridiculus. ');

    window.addEventListener('deviceorientation', function(event) {
      console.log('Orientation of the device has changed.');
            if(event.alpha!=null){

							// als Orientation wird vom Gerät ein Wert übergeben. Der wird von 0 gegen den Uhrzeigersinn gerechnet: antideviceorientation
							// der Wert wird normalisiert. Zwischen 0 und 360 grad
							antideviceorientation=event.alpha%360;
							if(antideviceorientation<0) antideviceorientation=360+antideviceorientation;

							// Wir wollen mit Winkeln rechen, die mit dem Uhrzeigersinn gerechnet werden
							deviceorientation=360-antideviceorientation;

							var zielwinkel=caculateDirection(deviceorientation);

							console.log('The goal of the compass needle is ' + zielwinkel);
							z=zielwinkel;
							updateCompass();
						}
          }, false);

  } else {
      console.log('Device orientation is not supported');
	}
}

function initMap() {
	console.log("initmap wurde aufgerufen");
	if (navigator && navigator.geolocation) {
//	document.getElementById("info").innerHTML = "Geolocation is supported";
	var watchId = navigator.geolocation.watchPosition(successCallbackMap,errorCallbackMap,{enableHighAccuracy:true,timeout:60000,maximumAge:0});
	} else {
		console.log('Geolocation is not supported');
//		document.getElementById("info").innerHTML = "Geolocation is not supported";
	}
}

function setModeMap(){

			console.log("setmodemap");
			if(getNextPoint()){
			document.getElementById('navigation').style.visibility='hidden';
			document.getElementById('navigation').style.height='0';
			document.getElementById('navigation').style.width='0';
			document.getElementById('googlemap').style.visibility='visible';
			document.getElementById('googlemap').style.height='200';
			document.getElementById('googlemap').style.width='200';
		}
}

function errorCallbackMap() {
	console.log("ERRORCALLBACK");
	console.log("ERRORCALLBACK");
}

function successCallbackMap(position) {
	posx=position.coords.latitude;
	posy=position.coords.longitude;

         var directionsService = new google.maps.DirectionsService();
         var directionsDisplay = new google.maps.DirectionsRenderer();

         var map = new google.maps.Map(document.getElementById('googlemap'), {
           zoom:10,
           mapTypeId: google.maps.MapTypeId.SATELLITE
         });

         directionsDisplay.setMap(map);
         directionsDisplay.setPanel(document.getElementById('googlemap'));
				 var origincoords = new google.maps.LatLng(posx, posy);

         var request = {
           origin: origincoords,
           destination: ziel,
           travelMode: google.maps.DirectionsTravelMode.WALKING
         };

         directionsService.route(request, function(response, status) {
           if (status == google.maps.DirectionsStatus.OK) {
             directionsDisplay.setDirections(response);
           }
         });

	/*
		console.log("sucesscallback for map was called");
		posx=position.coords.latitude;
		posy=position.coords.longitude;

		console.log("Aktueller Standort: " + posx + ", " +posy);
		var markerArray = [];

		// Instantiate a directions service.
		var directionsService = new google.maps.DirectionsService;

		// Create a map and center it on Manhattan.
		var map = new google.maps.Map(document.getElementById('googlemap'), {
			zoom: 13,
			center: {lat: posx, lng: posy}
		});

		// Create a renderer for directions and bind it to the map.
		var directionsDisplay = new google.maps.DirectionsRenderer({map: map});

		// Instantiate an info window to hold step text.
		var stepDisplay = new google.maps.InfoWindow;

		// Display the route between the initial start and end selections.
		calculateAndDisplayRoute(directionsDisplay, directionsService, markerArray, stepDisplay, map);
		// Listen to change events from the start and end lists.
		var onChangeHandler = function() {
			calculateAndDisplayRoute(directionsDisplay, directionsService, markerArray, stepDisplay, map);
		};
*/
}

function calculateAndDisplayRoute(directionsDisplay, directionsService, markerArray, stepDisplay, map) {
	// First, remove any existing markers from the map.

	for (var i = 0; i < markerArray.length; i++) {
		markerArray[i].setMap(null);
	}

	// Retrieve the start and end locations and create a DirectionsRequest using
	// WALKING directions.
	directionsService.route({
		origin: {lat: posx, lng: posy},
		destination: ziel,
		travelMode: 'WALKING'
	}, function(response, status) {
		// Route the directions and pass the response to a function to create
		// markers for each step.
	if (status === 'OK') {
		directionsDisplay.setDirections(response);
	} else {
		console.log('Directions request failed due to ' + status);
	}
	});
}
