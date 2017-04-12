
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
/*$sql = "CREATE TABLE Basis (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
Spielname VARCHAR(30) NOT NULL,
Passwort VARCHAR(30)
)";
$createTab = "CREATE TABLE E (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
Hinweis VARCHAR,
Breitengrad VARCHAR,
Laengengrad VARCHAR,
Entdeckt BOOLEAN
)";
if ($conn->query($sql) === TRUE) {
    echo "Table Basis created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}*/
//Create game
if(isset($_POST["SpielName"])){
  //Entry in basis-tab
  $spielName = $_POST["SpielName"];
  $passwort = $_POST["Passwort"];
  $sql = "INSERT INTO Basis (Spielname, Passwort)
  VALUES ('$spielName', '$passwort')";
  if ($conn->query($sql) === TRUE) {
    //  echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $createTab = "CREATE TABLE $spielName (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  Hinweis VARCHAR(100),
  Breitengrad DOUBLE,
  Laengengrad DOUBLE,
  Entdeckt BOOLEAN
  )";
    if ($conn->query($createTab) === TRUE) {
      //  echo "New Tab";
    } else {
        echo "Error: " . $createTab . "<br>" . $conn->error;
    }
//  "parent.location='CreateGame.html'";
}
if(isset($_GET["GesSpiel"])){
  //$getGames = "SELECT Spielname FROM basis";
  $actGame = $_GET["GesSpiel"];
  $actPW = $_GET["GesuchtesPasswort"];
  $sql = "SELECT Spielname, Passwort FROM basis";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      if($row["Spielname"] == $actGame and $row["Passwort"] == $actPW ){
             echo $row["Spielname"]. "<br>";
      }
    }
  }
}
//echo "Connected successfully";
// Create database
//$sql = "CREATE DATABASE myDBFlorian";
//    echo "Database created successfully";
//} else {
//    echo "Error creating database: " . $conn->error;
//}
//background="town.jpg"
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

     <title>WER</title>

     <!-- Bootstrap-CSS -->
     <link href="css/bootstrap.min.css" rel="stylesheet">

     <!-- Besondere Stile für diese Vorlage -->
     <link href="signin.css" rel="stylesheet">

     <!-- Nur für Testzwecke. Kopiere diese Zeilen nicht in echte Projekte! -->
     <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
     <script src="js/ie-emulation-modes-warning.js"></script>

     <!-- Unterstützung für Media Queries und HTML5-Elemente in IE8 über HTML5 shim und Respond.js -->
     <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]-->

    <link rel="stylesheet" type="text/css" href="overlayStyle.css">

    <script src="index.js"></script>
   </head>




<body background="town.jpg">


<!-- The overlay -->
<div id="myNav" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <div class="overlay-content">
    <form action="/index.php" method="post">
       <input type="text" id="SpielName" class="form-control" name="SpielName"  placeholder="Name des Spiels" required autofocus>
       <p></p>
       <input type="password" id="eingabefeldPasswort" class="form-control" name="Passwort" placeholder="Passwort">
       <p></p>
       <button class="btn btn-lg btn-primary btn-block" type="submit" onclick="createGame()">Speichern</button>
     </form>
  </div>
</div>
<!-- End overlay -->


<!-- Second overlay -->
<div id="searchBar" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick=" closeSearchBar()">&times;</a>
  <div class="overlay-content">
    <form action="/index.php" method="post">
       <input type="text" id="GesSpiel" class="form-control" name="GesSpiel"  placeholder="Spiel suchen" required autofocus>
       <p></p>
       <input type="password" id="GesuchtesPasswort" class="form-control" name="GesuchtesPasswort" placeholder="Passwort">
       <p></p>
       <button class="btn btn-lg btn-primary btn-block" type="submit">Betreten</button>
     </form>
  </div>
</div>
<!-- End overlay -->

   <div class="container">
        <p></p>
        <button class="btn btn-lg btn-primary btn-block" onclick="openNav()">Spiel erstellen</button>
        <button class="btn btn-lg btn-primary btn-block" onclick="openSearchBar()">Spiel beitreten</button>

       </div> <!-- /container -->

</body>
</html>
