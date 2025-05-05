<?php
//TIME ZONE CONFIGURATION
date_default_timezone_set('America/El_Salvador');

//ROOT PATH CONFIGURATION
define('URL', 'http://localhost/franquicia/');

//DATABASE CONFIGURATION
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'franquicia');

//ERROR CONFIGURATION
// Activar el registro de errores
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '..\\error.log');

// Opcional: Desactivar mostrar errores en producción
//	ini_set('display_errors', 0);
//	ini_set('display_startup_errors', 0);
