
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

$emparray = array();

$sql = "SELECT Spielname, Passwort FROM basis";
$result = $conn->query($sql);
while($row =mysqli_fetch_assoc($result))
{
    $emparray[] = $row;
}

//Create game
if(isset($_POST["SpielName"]) && $_POST["SpielName"] != "basis"){
  //Entry in basis-tab
  $spielName = $_POST["SpielName"];
  $passwort = $_POST["Passwort"];

  $sql = "INSERT INTO Basis (Spielname, Passwort, Oeffentlich)
  VALUES ('$spielName', '$passwort', FALSE)";
  if ($conn->query($sql) === TRUE) {
    //  echo "New record created successfully";
  } else {
     echo "Error: " . $sql . "<br>" . $conn->error;
  }


  $createTab = "CREATE TABLE $spielName (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  Hinweis VARCHAR(150),
  Breitengrad DOUBLE,
  Laengengrad DOUBLE,
  Entdeckt BOOLEAN
  )";
    if ($conn->query($createTab) === TRUE) {
      //  echo "New Tab";
    } else {
        // Lösche letzten Eintrag aus Basis-Tabelle, da dieser falsch war
        $del = "DELETE FROM Basis ORDER BY id desc limit 1";
        if ($conn->query($del) === TRUE) {
          //  echo "Letzter Eintrag wurde gelöscht";
        } else {
           echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

 <!DOCTYPE html>
 <html lang="de">
   <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="description" content="">
     <meta name="author" content="">
     <link rel="icon" href="favicon.ico">

     <title>Paperchase</title>

     <link href="css/bootstrap.min.css" rel="stylesheet">


     <link href="signin.css" rel="stylesheet">


     <script src="js/ie-emulation-modes-warning.js"></script>

    <link rel="stylesheet" type="text/css" href="overlayStyle.css">

    <script src="index.js"></script>
   </head>



<body background="town.jpg">


<!-- The overlay-->
<div id="myNav" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <div class="overlay-content">
    <form action="/index.php" method="post">
       <input type="text" id="SpielName" class="form-control" name="SpielName"  placeholder="Name des Spiels" required autofocus>
       <p></p>
       <input type="password" id="eingabefeldPasswort" class="form-control" name="Passwort" placeholder="Passwort">
       <p></p>
       <button class="btn btn-lg btn-primary btn-block" type="submit" onclick="openCreateGame()">Speichern</button>
     </form>
  </div>
</div>
<!-- End overlay -->

<script>
var allGames = <?php echo json_encode($emparray)?>;

function checkForValue(json, value, suffix) {
    for (var i = 0; i < json.length; i++){
      if(json[i].suffix == value){
        return true;
      }
      else{
        false;
      }
    }
}
function openCreateGame(){
   var checkValue = false;
    var gameName = document.getElementById("SpielName").value;
    //check if name of game already exists
    for (var i = 0; i < allGames.length; i++){
      if(allGames[i].Spielname == gameName){
        checkValue = true;
        break;
      }
      else{
        checkValue = false;
      }
    }

    if(gameName != '' && gameName != "basis" && checkValue ==false){
    var myWin =   window.open('CreateGame.php', gameName);
    }
    else{
      window.alert("Das Spiel kann nicht "+ gameName +" genannt werden");
    }{


    }
}


function openHunter(){

    var gameName = document.getElementById("GesSpiel").value;
    var gamePw = document.getElementById("GesuchtesPasswort").value;

    for(var i = 0; i < allGames.length; i++){

      if(gameName == allGames[i].Spielname && gamePw == allGames[i].Passwort){
          var myWin =   window.open('hunter.php', gameName);
      }
      else{
        //Wrong password or gamename
      }
    }
}
</script>

<!-- Second overlay -->
<div id="searchBar" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick=" closeSearchBar()">&times;</a>
  <div class="overlay-content">
    <form action="/index.php" method="post">
       <input type="text" id="GesSpiel" class="form-control" name="GesSpiel"  placeholder="Spiel suchen" required autofocus>
       <p></p>
       <input type="password" id="GesuchtesPasswort" class="form-control" name="GesuchtesPasswort" placeholder="Passwort">
       <p></p>
       <button class="btn btn-lg btn-primary btn-block" type="submit" onclick="openHunter()">Betreten</button>
     </form>
  </div>
</div>
<!-- End overlay -->

   <div class="container">
        <p></p>
        <button class="btn btn-lg btn-primary btn-block" onclick="openNav()">Spiel erstellen</button>
        <button class="btn btn-lg btn-primary btn-block" onclick="openSearchBar()">Spiel beitreten</button>
  </div>

</body>
</html>
