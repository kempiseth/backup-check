<?php

 if ( file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '.local') ) {
     error_reporting(E_ALL);
    ini_set('display_errors', 1);
 }
 
// --- REQUIRE --- //
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/_config/_config.php';

$_route = require_once __DIR__ . '/_route/_route.php';
// --------------- //

use PKEM\Model\Security;
use PKEM\Controller\Route;

$security = new Security();

$route = new Route($_route);
$route->renderPage();
