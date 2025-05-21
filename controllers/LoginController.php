<?php

namespace controllers;

use class\ErrorMessages;
use libs\Controller;
use libs\View;
use models\UserModel;
use models\LoginModel;
use controllers\SessionController;

/**
 * Class LoginController
 *
 * Controlador que gestiona el proceso de inicio de sesión en la aplicación.
 * Hereda de `SessionController` para manejar la lógica de sesiones.
 */
class LoginController extends SessionController
{

  /**
   * @var LoginModel $LoginModel
   * Modelo utilizado para manejar la lógica de autenticación de usuarios.
   */
  public $LoginModel;
  protected $UserModel;

  /**
   * @var View $view
   * Instancia de la clase View para renderizar vistas.
   */
  protected $view;

  /**
   * Constructor de la clase LoginController.
   *
   * Inicializa el modelo de login y la vista, y llama al constructor de la clase base `SessionController`.
   */
  public function __construct()
  {
    $this->view = new View;
    $this->LoginModel = new LoginModel();
    $this->UserModel = new UserModel();
    parent::__construct();
  }

  /**
   * Renderiza la vista principal de login.
   *
   * @return void
   */
  public function render()
  {
    $this->view->render('login/index');
  }

  /**
   * Autentica a un usuario basado en los datos enviados por POST.
   *
   * Verifica si los campos requeridos están presentes y no están vacíos.
   * Si las credenciales son correctas, inicializa la sesión del usuario y redirige a la tienda.
   * Si las credenciales son incorrectas o hay errores, redirige con un mensaje de error.
   *
   * @return void
   */
  function authenticate()
  {
    if ($this->existPOST(['nombre_usuario', 'contraseña'])) {
      $username = $this->getPost('nombre_usuario');
      $password = $this->getPost('contraseña');
      error_log("LOGINCONTROLLER::authenticate => username: $username, password: $password");

      if ($username == '' || empty($username) || $password == '' || empty($password)) {
        $this->redirect('', ['error' => 'Por favor, completa todos los campos.']);
      }
      $password = $this->UserModel->encriptar_desencriptar('encriptar', $password);
      error_log("LOGINCONTROLLER::authenticate => password: $password");
      $user =  $this->LoginModel->login($username, $password);

      if ($user != null) {
        $this->initialize($user);
        $this->redirect('tienda');
      } else {
        $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_LOGIN_LOGIN]);
      }
    } else {
      $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_CAMPO_VACIO]);
    }
  }
}
