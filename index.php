<?php
session_start();

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use libs\app;
use libs\Conexion;
use libs\Controller;
use libs\Model;
use libs\View;
use class\ErrorMessages;
use class\SuccessMessages;


$app = new App();

?>