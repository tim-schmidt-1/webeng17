<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Die 3 Meta-Tags oben *müssen* zuerst im head stehen; jeglicher sonstiger head-Inhalt muss *nach* diesen Tags kommen -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>WER</title>

    <!-- Bootstrap-CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Besondere Stile für diese Vorlage -->
    <link href="starter-template.css" rel="stylesheet">

    <!-- Nur für Testzwecke. Kopiere diese Zeilen nicht in echte Projekte! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- Unterstützung für Media Queries und HTML5-Elemente in IE8 über HTML5 shim und Respond.js -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="button.css">
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
  $sql =  "SELECT Spielname FROM basis ORDER BY id DESC LIMIT 1";
  $result = $conn->query($sql);

  $data=array();

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
            //var SpName= $row["Spielname"]. "<br>";
            $data[] = $row['Spielname'];
    }
  }
   $SpName =  $data[0];

   if (isset($_POST["btnPosition"])) {
     $breite =  123;
     $laenge = 3;
     $insertData =  "INSERT INTO $SpName (Hinweis, Breitengrad, Laengengrad, Entdeckt)
     VALUES ('A', '$breite', '$laenge', 'FALSE')";
   }

?>




  <body>


    <div class="container">
      <h1><?= $SpName ?></h1>
      <form action="CreateGame.php" method="POST">
      <button type="button" name="btnPosition" class="btn btn-success btn-circle btn-xl" type="submit" onclick="getLocation()">+</button>
      </form>
      <p id="demo"></p>

      <script>
      var x = document.getElementById("demo");

      function getLocation() {
          if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(showPosition);
          } else {
              x.innerHTML = "Geolocation is not supported by this browser.";
          }
      }

      function showPosition(position) {
          x.innerHTML = "Latitude: " + position.coords.latitude +
          "<br>Longitude: " + position.coords.longitude;
      }
      </script>

      <script language="JavaScript">
  function processForm()
  {
    var parameters = location.search.substring(1).split("&");
    var temp = parameters[0].split("=");
    l = unescape(temp[1]);
    alert(l); //Dialog with the text you put on the textbox
  }
  processForm();
</script>
    </div><!-- /.container -->


    <!-- Bootstrap-JavaScript
    ================================================== -->
    <!-- Am Ende des Dokuments platziert, damit Seiten schneller laden -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- IE10-Anzeigefenster-Hack für Fehler auf Surface und Desktop-Windows-8 -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
