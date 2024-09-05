<?php
require_once(__DIR__ . '/../App/autoload.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$router = new Router();
$router->run();
