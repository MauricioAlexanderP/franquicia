<?php
namespace controllers;

use models\UserModel;
use controllers\SessionController;  // namespace con "C" mayúscula
use libs\View;                       // importa la clase View

class PerfilController extends SessionController
{
  public function __construct()
  {
    parent::__construct();           // llama al constructor de SessionController
    $this->view = new View();        // instancia el view helper
  }

  public function render()
  {
    error_log("PerfilController::render -> cargar index");
    $this->view->render('usuarios/editarPerfil');
  }
}