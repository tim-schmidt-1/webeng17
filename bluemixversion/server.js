
var express = require('express');
var app     = express();
var http    = require('http');
var path = require('path');

app.set('port', process.env.PORT || 3000);
app.set('view engine', 'ejs');
app.engine('html', require('ejs').renderFile);
app.use( express.static( __dirname + '/views' ));

//var exec = require("child_process").exec;
//app.get('/', function(req, res){exec("php index.php", function (error, stdout, stderr) {res.send(stdout);});});



app.get('/', function (req, res) {
    res.sendFile( path.join( __dirname, 'views', 'hunter.html' ));
  //  res.sendFile( "C:/Users/IBM_ADMIN/Desktop/DH/4. Semester/Webengineering/GitCode/webeng17/bluemixversion/views/hunter.html" );
  });


http.createServer(app).listen(app.get('port'), '0.0.0.0', function() {
	console.log('Express server listening on port ' + app.get('port'));
});
