<?php

namespace libs;

use controllers\LoginController;
use controllers\ErrorController as Error;

class App
{
  function __construct()
  {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = explode('/', $url);

    // Cuando se ingresa sin definir controlador
    if (empty($url[0])) {
      $archivoController = 'controllers/LoginController.php';
      require_once $archivoController;
      $controller = new LoginController();
      $controller->loadModel('login');
      $controller->render();
      return false;
    }

    $controllerName = ucfirst($url[0]) . 'Controller';
		$methodName = isset($url[1]) ? $url[1] : 'render';
		$params = array_slice($url, 2);

		if (class_exists("controllers\\" . $controllerName)) {
			$controller = "controllers\\" . $controllerName;
			$controllerInstance = new $controller();

			if (method_exists($controllerInstance, $methodName)) {
				$result = call_user_func_array([$controllerInstance, $methodName], $params);
				if (is_array($result)) {
					echo json_encode($result); // Convierte el array a JSON si es necesario
				} else {
					echo $result; // Imprime directamente si es una cadena
				}
			} else {
				echo "Metodo no encotrado: $methodName";
				$controller = new Error();
			}
		} else {
			$controller = new Error();
		}
  }


}
