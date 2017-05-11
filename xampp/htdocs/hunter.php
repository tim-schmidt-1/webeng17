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

  if(isset($_POST["place"])){

    $aktuellesSpiel = $_POST["place"];
    $emparray = array();
    $sql = "SELECT Hinweis, Breitengrad, Laengengrad FROM $aktuellesSpiel";

    $result = $conn->query($sql);
    while($row =mysqli_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    $fp = fopen('empdata.json', 'w');
    fwrite($fp, json_encode($emparray));
    fclose($fp);
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
     <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="/hunter.js"></script>
   </head>

   <body onload="loadData();setModeMap();initMap();initCompass();">

     <div><h1 align="center" id="myHeader"></h1>


       <script>
       window.document.title = window.name;
       myHeader.innerText = window.name;
       </script>

     </div>

	<canvas class="navigation" id="navigation" width="200" height="200"></canvas>
	<div class="navigation" id="googlemap" width="200" height="200"></div>



<p id="info">---</p>
<p><a onclick="setModeMap()" href="#">Map</a></p>
<p><a onclick="setModeCompass()" href="#">Compass</a></p>

<!-- hier alle Hinweise auslesen + Koordinaten

  <form action="/hunter.php" method="post">
   <button class="btn btn-lg btn-primary btn-block" type="submit" onClick="setTitle()">Spiel starten</button>
   <input type="hidden" id="place" name="place" />
 </form>
 -->
</body>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnZvLQzskFbI58r6nvWDZZPbv8QuEuqlo">
</script>

</html>
