
function openNav() {
    document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
    document.getElementById("myNav").style.width = "0%";
}

function openSearchBar() {
    document.getElementById("searchBar").style.width = "100%";
}

function closeSearchBar() {
    document.getElementById("searchBar").style.width = "0%";
}

function createGame(){

  if(document.getElementById("SpielName").value != ''){

      window.location.href = "createGame.php";
      //window.alert(document.getElementById("SpielName").value);
    }
}
