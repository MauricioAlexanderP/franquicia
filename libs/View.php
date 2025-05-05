<?php

namespace libs;

use class\ErrorMessages as Errors;
use class\SuccessMessages as Success;
use controllers\ErrorControlle;
/**
 * Clase View
 *
 * Clase encargada de manejar la representación de vistas en la aplicación.
 * Permite renderizar archivos de vista y pasarles datos dinámicos.
 *
 * @package libs
 */
class View
{
  public $d = [];
  function __construct() {}

  function render($nombre, $data = [])
  {
    $this->d = $data;

    $this->handleMessages();


    require 'views/' . $nombre . '.php';
  }

  private function handleMessages()
  {
    if (isset($_GET['success']) && isset($_GET['error'])) {
      // no se muestra nada porque no puede haber un error y success al mismo tiempo
    } else if (isset($_GET['success'])) {

      $this->handleSuccess();
    } else if (isset($_GET['error'])) {
      $this->handleError();
    }
  }

  private function handleError()
  {
    if (isset($_GET['error'])) {
      $hash = $_GET['error'];
      $errors = new Errors();

      if ($errors->existsKey($hash)) {
        error_log('View::handleError() existsKey =>' . $errors->get($hash));
        $this->d['error'] = $errors->get($hash);
      } else {
        $this->d['error'] = NULL;
      }
    }
  }


  private function handleSuccess()
  {
    if (isset($_GET['success'])) {
      $hash = $_GET['success'];
      $success = new Success();

      if ($success->existsKey($hash)) {
        error_log('View::handleError() existsKey =>' . $success->existsKey($hash));
        $this->d['success'] = $success->get($hash);
      } else {
        $this->d['success'] = NULL;
      }
    }
  }

  public function showMessages()
  {
    $this->showError();
    $this->showSuccess();
  }

  public function showError()
  {
    if (array_key_exists('error', $this->d)) {
      echo 
      '
        <script>
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "' . $this->d['error'] . '",
          });
        </script>
      ';
    }
  }

  public function showSuccess()
  {
    if (array_key_exists('success', $this->d)) {
      echo
      '
        <script>
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "' . $this->d['success'] . '",
          });
        </script>
      ';
    }
  }
}
