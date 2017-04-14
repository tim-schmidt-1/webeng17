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

  }
}

?>
