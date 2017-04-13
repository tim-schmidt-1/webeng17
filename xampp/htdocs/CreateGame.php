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

    <title>WER</title>

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
   $SpName =  $data[0];

   //entry in db when u want to set point on map
   if(isset($_POST["btSetPoint"])){

     $hinweis = $_POST["btSetPoint"];
     $breite = $_POST["latitude"];
     $laenge = $_POST["longitude"];

     $insertData =  "INSERT INTO $SpName (Hinweis, Breitengrad, Laengengrad, Entdeckt)
     VALUES ('$hinweis', '$breite', '$laenge', FALSE)";

     if ($conn->query($insertData) === TRUE) {
        echo "Good";
     } else {
         echo "Error: " . $insertData . "<br>" . $conn->error;
     }
   }
    if(isset($_POST["btPublish"])){
      $sql ="UPDATE basis SET Oeffentlich=TRUE WHERE Spielname = '$SpName'";

      if ($conn->query($sql) === TRUE) {
         echo "Good";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }
?>
  <body>

    <!--Overlay -->
    <div id="dia" class="overlay">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      <div class="overlay-content">
        <form action="/CreateGame.php" method="post">
             <p></p>
             <input type="hidden" id="longitude" name="longitude" />
             <input type="hidden" id="latitude" name="latitude" />
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
             <p style="color:white; font-size:160%; text-align:center">Wollen Sie das Spiel veröffentlichen?</p>
            <p></p>
           <button name="btPublish" class="btn btn-lg btn-primary btn-block" type="submit">Speichern</button>
         </form>
      </div>
    </div>


    <div class="container">
      <h1 align="center"><?= $SpName ?></h1>
            <button name="btnPosition" class="btn btn-success btn-circle btn-xl" type="submit" onclick="openDia(); getLocation()">+</button>
            <p></p>

              <button name="btSave" type="submit" class="btn btn-danger btn-circle btn-xl" onClick="openSaveOv()">
              <span class="glyphicon glyphicon-floppy-disk"></span>
              </button>


      <script>

        function showPosition(position) {
             document.getElementById("latitude").value = position.coords.latitude;
             document.getElementById("longitude").value =  position.coords.longitude;
         }
        function getLocation() {
         if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(showPosition);
          } else {
              x.innerHTML = "Geolocation is not supported by this browser.";
          }
      }
      </script>

  </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10-Anzeigefenster-Hack für Fehler auf Surface und Desktop-Windows-8 -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
