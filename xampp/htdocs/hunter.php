<?php
/*
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

$gameValid = False;

if(isset($_POST["GesSpiel"])){
  //$getGames = "SELECT Spielname FROM basis";
  $actGame = $_POST["GesSpiel"];
  $actPW = $_POST["GesuchtesPasswort"];
  $sql = "SELECT Spielname, Passwort FROM basis";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      if($row["Spielname"] == $actGame and $row["Passwort"] == $actPW ){
             $gameValid = True;
      }
    }
  }

  if($gameValid == True){
    $gameName = $_POST["GesSpiel"];
      $sql = "SELECT Breitengrad, Laengengrad From SecGame";
      //Alle Breiten und Laengen sind hier gespeichert
    $result = $conn->query($sql);
    //result irgendwie in html ablegen damit es von js wieder aufgegriffen werdne kann
  } else {
    // alert("Falsches pas oder Spielname bla bla")
    //zurück auf Index leiten
  }
}
*/
?>


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

     <title>neuer Titel</title>

     <!-- Bootstrap-CSS -->
     <link href="css/bootstrap.min.css" rel="stylesheet">


     <!-- Unterstützung für Media Queries und HTML5-Elemente in IE8 über HTML5 shim und Respond.js -->
     <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]    "-->

    <script src="hunter.js"></script>

   </head>

<body onload="loadData();setModeMap();initCompass();initMap();">

	<canvas class="navigation" id="navigation" width="200" height="200"></canvas>
	<div class="navigation" id="navigation2" width="200" height="200"></div>

<p id="info">---</p>
<p><a onclick="setModeMap()" href="#">Map</a></p>
<p><a onclick="setModeCompass()" href="#">Compass</a></p>
</body>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZx_ECIITPEmiXQa_JtMCfagSyyYRE7XA">
</script>

</html>
