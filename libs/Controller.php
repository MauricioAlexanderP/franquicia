<?php

namespace libs;

use libs\View;

/**
 * Clase Controller
 *
 * Clase base para los controladores de la aplicación. Proporciona métodos
 * comunes para manejar vistas, modelos y solicitudes HTTP (GET y POST).
 *
 * @package libs
 */
class Controller
{
  /**
   * @var View Instancia de la clase View para manejar las vistas.
   */
  protected $view;

  /**
   * @var mixed Instancia del modelo asociado al controlador.
   */
  protected $model;

  /**
   * Constructor de la clase Controller.
   *
   * Inicializa la instancia de la vista.
   */
  function __construct()
  {
    $this->view = new View();
  }

  /**
   * Carga un modelo asociado al controlador.
   *
   * @param string $model Nombre del modelo a cargar.
   * @return void
   */
  public function loadModel($model)
  {
    $url = 'models/' . $model . '.php';

    if (file_exists($url)) {
      require $url;

      $modelName = $model . 'Model';
      $this->model = new $modelName();
    }
  }

  /**
   * Verifica si existen los parámetros especificados en la solicitud POST.
   *
   * @param array $parametros Lista de parámetros a verificar.
   * @return bool True si todos los parámetros existen, False en caso contrario.
   */
  public function existPOST($parametros)
  {
    foreach ($parametros as $parametro) {
      if (!isset($_POST[$parametro])) {
        error_log("CONTROLLER::existPOST => No existe el parametro $parametro en POST");
        return false;
      }
    }
    return true;
  }

  /**
   * Verifica si existen los parámetros especificados en la solicitud GET.
   *
   * @param array $parametros Lista de parámetros a verificar.
   * @return bool True si todos los parámetros existen, False en caso contrario.
   */
  public function existGET($parametros)
  {
    foreach ($parametros as $parametro) {
      if (!isset($_GET[$parametro])) {
        error_log("CONTROLLER::existGET => No existe el parametro $parametro en GET");
        return false;
      }
    }
    return true;
  }

  /**
   * Obtiene el valor de un parámetro de la solicitud GET.
   *
   * @param string $nombre Nombre del parámetro.
   * @return mixed Valor del parámetro.
   */
  public function getGet($nombre)
  {
    return $_GET[$nombre];
  }

  /**
   * Obtiene el valor de un parámetro de la solicitud POST.
   *
   * @param string $nombre Nombre del parámetro.
   * @return mixed Valor del parámetro.
   */
  public function getPost($nombre)
  {
    return $_POST[$nombre];
  }

  /**
   * Redirige a una ruta específica con parámetros opcionales.
   *
   * @param string $ruta Ruta a la que se redirigirá.
   * @param array $mensajes Lista de mensajes o parámetros a incluir en la URL.
   * @return void
   */
  public function redirect($url, $mensajes = [])
  {
    $data = [];
    $params = '';

    foreach ($mensajes as $key => $value) {
      array_push($data, $key . '=' . $value);
    }
    $params = join('&', $data);

    if ($params != '') {
      $params = '?' . $params;
    }
    header('location: ' . constant('URL') . $url . $params);
  }
}
