<?php

require_once 'app/boot.php';

//$view = new View();
//if (!$session->logged_in()) {
//    require_once VIEWS . 'login.html.php';
//} else {
//    $view->make($_SERVER['REQUEST_URI']);
//}

$route  = new RouteController($_SERVER['REQUEST_URI']);
$view   = ViewController::getController($route->getPathInfo());

