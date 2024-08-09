<?php
$folderPath = dirname($_SERVER['SCRIPT_NAME']);
$urlPath = $_SERVER['REQUEST_URI'];
$url = substr($urlPath, strlen($folderPath));

define('URL', $url);
define('URL_PATH', $folderPath);

//constantes sesion
define('LOG', 'usuarioLog');
define('NO_LOG', 'usuario');
define('ADMIN', 'admin');
//constantes de fallo de sesion
//constantes de estados de oferta
define('PUBLICADO', 'publicado');
define('ESPERA', 'espera');
//constantes de estado de renta
define('ACEPTADO', 'Aceptado');
define('RECHAZADO', 'Rechazado');
define('ESPERA_RENTA', 'Espera');
//constantes reserva
define('ENCURSO', 'en curso');
define('FINALIZADA', 'finalizada');
