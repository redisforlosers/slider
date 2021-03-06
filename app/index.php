<?php
  require_once('../connection.php');		// allows us to connect to the database from any page
  require_once('../helpers.php');		// load helper functions and classes which need to be accessed throughout the application
  date_default_timezone_set('America/Denver');


  if (isset($_GET['controller']) && isset($_GET['action'])) {		// check to see if the controller and action are in the URI
    $controller = strval($_GET['controller']);		// validate and store GET requests
    $action     = strval($_GET['action']);
  }	else {					// redirect everything else to the home page
    $controller = 'slides';
    $action     = 'slideshow';
  }
  
  require_once('views/layout.php');		// call the master view which starts outputting HTML

?>