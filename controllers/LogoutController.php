<?php

namespace controllers;

use controllers\SessionController;

class LogoutController extends SessionController
{
  function __construct()
  {
    parent::__construct();
  }
  public function render()
  {
    error_log("LOGOUTCONTROLLER::render ->  cerrar sesion");
    $this->logout();
    $this->redirect('');
  }
}
