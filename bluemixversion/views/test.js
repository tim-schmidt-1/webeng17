// Global variable
var img = null,
	needle = null,
	ctx = null,
	s=0, //aktuelle pos des zeigers
  z=0, //ziel des zeigers
	os=0, //entfernung s von 0 grad
	oz=0,//entfernung z von 0 grad
	posx=0, //Eigene Position latitude
	posy=0,  //Eigene Position longitude
	zielx=49.4751087, //Ziel Position latitude
	ziely=8.494213499999999,  //Ziel Position longitude
	entf=0, //Entfernung zum Ziel
	richtung = 1,
	deviceorientation=0,
	antideviceorientation=0;

var info;

function clearCanvas() {
	 // clear canvas
	ctx.clearRect(0, 0, 200, 200);
}

function updateCompass() {
// todo
// funktioniert um 0 noch nicht so gut bzw mindestes um 0 nuícht

	if(z===(-1)){
		console.log('still at the goal.');
		z=0;
	}


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

function imgLoaded() {
		ctx.drawImage(img, 0, 0);
		ctx.save();
		ctx.translate(100, 100);
		ctx.drawImage(needle, -100, -100);
		ctx.restore();

	setInterval(updateCompass, 100);
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
						}
          }, false);

  } else {
      console.log('Device orientation is not supported');
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
if(ydif>0){ // ziel ist in oberen quardanten
	if(xdif>0){ // ziel ist in rechten quardanten - Q1
		console.log('Quadrant 1');
		var x = Math.atan(xdif/ydif)*(180/Math.PI); // winkel von x Achse zu geraden die auf Ziel Zeitg in rad , *(180/Math.PI) wandelt das in Grad um
		return 90-x;
	} else if(xdif<0){ //ziel ist in linken Quadranten - Q4
		console.log('Quadrant 4');
		var x = Math.atan(ydif/(-xdif))*(180/Math.PI);;
		return 270+x;
	} else { //ziel liegt auf gleicher breite
		return 0;
	}
} else if(ydif<0){ //ziel ist in unterem Quadranten
	if(xdif>0){ // ziel ist in rechtem quardanten - Q2
		console.log('Quadrant 2');
		var x = Math.atan((-ydif)/xdif)*(180/Math.PI);;
		return 90+x;
	} else if(xdif<0){ //ziel ist in linken Quadranten - Q3
		console.log('Quadrant 3');
		var x = Math.atan((-xdif)/(-ydif))*(180/Math.PI);;
		return 270-x;
	} else { //ziel liegt auf gleicher breite
		return 180;
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
	 coordwinkel;
}


 function errorCallback(error) {

	 info.innerHTML = 'error: ' + error.message ;
 }

 function successCallback(position) {
	 posy=position.coords.longitude;
	 posx=position.coords.latitude;
	 info.innerHTML = 'x= ' + posx + '  y= ' + posy ;
}



function init() {
  activateOrientationListener();

	if (navigator && navigator.geolocation) {
	var watchId = navigator.geolocation.watchPosition(successCallback,
																										errorCallback,
																										{enableHighAccuracy:true,timeout:60000,maximumAge:0});

	} else {
		console.log('Geolocation is not supported');
	}

	// Grab the compass element
	var canvas = document.getElementById('compass');
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
		alert("Canvas not supported!");
	}
}
