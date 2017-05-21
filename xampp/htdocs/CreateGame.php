<!DOCTYPE html>

<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="favicon.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="starter-template.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="button.css">
    <link rel="stylesheet" type="text/css" href="overlayStyle.css">

    <script src="js/ie-emulation-modes-warning.js"></script>
    <script src="CreateGame.js"></script>
  </head>


<?php
  $servername = "localhost";
  $username = "user1";
  $password = "Test123";
  $dbName = "mydbflorian";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbName );

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  //Get name of game
  $sql =  "SELECT Spielname FROM basis ORDER BY id DESC LIMIT 1";
  $result = $conn->query($sql);
  $data=array();

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
            $data[] = $row['Spielname'];
    }
  }

   //entry in db when u want to set point on map
   if(isset($_POST["btSetPoint"])){

     $hinweis = $_POST["btSetPoint"];
     $breite = $_POST["latitude"];
     $laenge = $_POST["longitude"];
     $SpName = $_POST["gameTitle"];

     $insertData =  "INSERT INTO $SpName (Hinweis, Breitengrad, Laengengrad, Entdeckt)
     VALUES ('$hinweis', '$breite', '$laenge', FALSE)";

     if ($conn->query($insertData) === TRUE) {
        echo "Der Punkt wurde gesetzt";
     } else {
         echo "Error: Bitte setzten Sie den Punkt erneut";
     }
   }
    if(isset($_POST["btPublish"])){
      $SpName = $_POST["gameTitleSec"];

      $sql ="UPDATE basis SET Oeffentlich=TRUE WHERE Spielname = '$SpName'";

      if ($conn->query($sql) === TRUE) {
         echo "Good";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }

?>
  <body onload = "getLocationM()" background = "map.jpg">
    <!--Overlay -->
    <div id="dia" class="overlay">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      <div class="overlay-content">
        <form action="/CreateGame.php" method="post">
             <p></p>
             <input type="hidden" id="longitude" name="longitude" />
             <input type="hidden" id="latitude" name="latitude" />
             <input type="hidden" id="gameTitle" name="gameTitle" />
             <input type="text" id="btSetPoint" class="form-control" name="btSetPoint"  placeholder="Geben Sie einen Hinweis" required autofocus>
            <p></p>
           <button class="btn btn-lg btn-primary btn-block" type="submit">Punkt setzen</button>
         </form>
      </div>
    </div>

    <!--Secondoverlay -->
    <div id="saveOv" class="overlay">
      <a href="javascript:void(0)" class="closebtn" onclick="closeSaveOv()">&times;</a>
      <div class="overlay-content">
        <form action="/CreateGame.php" method="post">

          <input type="hidden" id="gameTitleSec" name="gameTitleSec" />
             <p style="color:white; font-size:160%; text-align:center">Wollen Sie das Spiel veröffentlichen?</p>
            <p></p>
           <button name="btPublish" class="btn btn-lg btn-primary btn-block" type="submit" onClick="publishGame()">Veröffentlichen</button>
         </form>
      </div>
    </div>


    <div class="container">
      <h1 align="center" id="myHeader" style="background-color:white"></h1>
        <!--  <picture>
            <source srcset="platzhalter.jpg" media="(min-width: 300px)">
              <img src="platzhalter.jpg" alt ="Platzhalter" style="width:auto;">
            </picture>
          -->
            <div id="mapholder" style = "position: absolute; float: left; margin-left: 25%; width:24%; "></div>
            <p></p>
    <p id="demo"></p>
    <p id="demo1"></p>
    <p id="demo2"></p>
            <span style="Display:inline-block; width:20%;"></span>
            <button name="btnPosition" class="btn btn-success btn-circle btn-xl" type="submit" onclick=" getLocation(); openDia();">+</button>
            <span style="Display:inline-block; width:20%;"></span>
                <button name="btSave" type="submit" class="btn btn-danger btn-circle btn-xl" onClick="openSaveOv(); postTitle();">
              <span class="glyphicon glyphicon-floppy-disk"></span>
              </button>
      <p id="demo"></p>
    </div>

      <script>
      var x = document.getElementById("demo");
      var y = document.getElementById("demo1");
      var z = document.getElementById("demo2");
      var p = 1;
      function getLocationM() {

          if (navigator.geolocation) {
              navigator.geolocation.watchPosition(showPositionMap, showError);


          } else {
              x.innerHTML = "Geolocation is not supported by this browser.";
          }
      }

      function showPositionMap(position) {

          var latlon = position.coords.latitude + "," + position.coords.longitude;

      	x.innerHTML = p++;
      	y.innerHTML = position.coords.longitude; //Länge
      	z.innerHTML = position.coords.latitude; //Breite

    var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=17&size=400x300&sensor=false&key=AIzaSyAnZvLQzskFbI58r6nvWDZZPbv8QuEuqlo";
    document.getElementById("mapholder").innerHTML = "<img alt='Karte' src='"+img_url+"'/>";
      }
      //To use this code on your website, get a free API key from Google.
      //Read more at: https://www.w3schools.com/graphics/google_maps_basic.asp

      function showError(error) {
          switch(error.code) {
              case error.PERMISSION_DENIED:
                  x.innerHTML = "User denied the request for Geolocation."
                  break;
              case error.POSITION_UNAVAILABLE:
                  x.innerHTML = "Location information is unavailable."
                  break;
              case error.TIMEOUT:
                  x.innerHTML = "The request to get user location timed out."
                  break;
              case error.UNKNOWN_ERROR:
                  x.innerHTML = "An unknown error occurred."
                  break;
          }
      }

        window.document.title = window.name;
        myHeader.innerText = window.name;

        function postTitle(){
          document.getElementById("gameTitleSec").value =  window.name;
        }
        function showPosition(position) {
             document.getElementById("latitude").value = position.coords.latitude;
             document.getElementById("longitude").value =  position.coords.longitude;
             document.getElementById("gameTitle").value =  window.name;
         }
        function getLocation() {
         if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(showPosition);
          } else {
              x.innerHTML = "Geolocation is not supported by this browser.";
          }
      }
      function publishGame(){
        window.alert("Das Spiel wurde erfolgreich veröffentlicht");
        window.close('CreateGame.php');
      }

      </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10-Anzeigefenster-Hack für Fehler auf Surface und Desktop-Windows-8 -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
