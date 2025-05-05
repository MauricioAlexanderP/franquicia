<?php

namespace controllers;

use libs\Controller;

/**
 * Class ErrorController
 *
 * Controlador que gestiona los errores en la aplicación.
 * Este controlador se utiliza para mostrar una vista personalizada de error 404.
 */
class ErrorController extends Controller
{

  /**
   * Constructor de la clase ErrorController.
   *
   * Llama al constructor de la clase base `Controller` y renderiza la vista de error 404.
   */
  function __construct()
  {
    parent::__construct();
    $this->view->render('404_view');
  }
}
