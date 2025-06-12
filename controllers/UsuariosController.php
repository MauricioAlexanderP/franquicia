<?php

namespace controllers;

use libs\View;
use models\UserModel;
use models\TiendaModel;
use models\RolesModel;;

use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;


class UsuariosController extends SessionController
{
  protected $view;
  protected $user;
  protected $tienda;
  protected $rol;

  public function __construct()
  {
    parent::__construct();
    error_log("USUARIOSCONTROLLER::construct");
    $this->view = new View();
    $this->user = new UserModel();
    $this->tienda = new TiendaModel();
    $this->rol = new RolesModel();
  }

  public function render()
  {
    error_log("USUARIOSCONTROLLER::render -> cargar index");
    error_log("USUARIOSCONTROLLER::render -> tipoProducto: " . print_r($this->getTienda(), true));
    $this->view->render('usuarios/index', [
      'tiendas' => $this->getTienda(),
      'roles' => $this->getRol(),
      'usuarios' => $this->getUsuario()
    ]);
  }
  private function getUsuario()
  {
    $Usuario = new UserModel();
    $dataUsuario = $Usuario->getUserAndTiendaRol();
    $items = [];
    foreach ($dataUsuario as $usuario) {
      array_push($items, [
        'usuario_id' => $usuario->getId(),
        'tienda_id' => $usuario->getTiendaId(),
        'rol_id' => $usuario->getRolId(),
        'nombre_usuario' => $usuario->getNombreUsuario(),
        'correo' => $usuario->getCorreo(),
        'telefono' => $usuario->getTelefono(),
        'estado' => $usuario->getEstado()
      ]);
    }
    error_log("USUARIOSCONTROLLER::getUsuario -> cargar usuarios" . print_r($items, true));
    return $items;
  }
  private function getTienda()
  {
    $items = [];
    $tiendas = $this->tienda->getAll();
    error_log("USUARIOSCONTROLLER::getTienda -> cargar tiendas" . print_r($tiendas, true));
    foreach ($tiendas as $tienda) {
      array_push($items, [
        'tienda_id' => $tienda->getTiendaId(),
        'nombre' => $tienda->getNombreTienda(),
      ]);
    }
    return $items;
  }
  private function getRol()
  {
    $items = [];

    $roles = $this->rol->getAll();
    foreach ($roles as $rol) {
      array_push($items, [
        'rol_id' => $rol->getRoleId(),
        'nombre_rol' => $rol->getNombreRol(),
      ]);
    }
    return $items;
  }

  public function newUsuario()
  {
    if (!$this->existPOST(['nombre_usuario', 'correo', 'telefono', 'tienda_id', 'rol_id'])) {
      $this->redirect('usuarios', ['error' => ErrorMessages::ERROR_USUARIO_USUARIO_DATOSFALTANTES]);
      return;
    }
    $password = $this->user->encriptar_desencriptar('encriptar', '1234');
    $usuario = new UserModel();
    $usuario->setTiendaId($this->getPost('tienda_id'));
    $usuario->setRolId($this->getPost('rol_id'));
    $usuario->setNombreUsuario($this->getPost('nombre_usuario'));
    $usuario->setCorreo($this->getPost('correo'));
    $usuario->setPassword($password);
    $usuario->setTelefono($this->getPost('telefono'));
    $usuario->save();
    $this->redirect('usuarios', ['success' => SuccessMessages::SUCCESS_USUARIO_NEWUSUARIO]);
  }

  public function getUsuarioById()
  {
    if (!$this->existPOST(['usuario_id'])) {
      $this->redirect('usuarios', ['error' => ErrorMessages::ERROR_USUARIO_USUARIO_DATOSFALTANTES]);
      return;
    }
    error_log("USUARIOSCONTROLLER::getUsuarioById" . $this->getPost('usuario_id'));
    $usuario = new UserModel();
    $id = $this->getPost('usuario_id');
    $items = $usuario->get($id);
    if ($items) {
      echo json_encode($items->toArray());
    } else {
      echo json_encode(['error' => 'No se encontró el usuario']);
    }
  }

  public function updateUsuario()
  {
    error_log("USUARIOSCONTROLLER::updateUsuario");
    if (!$this->existPOST(['usuario_id', 'nombre_usuario', 'correo', 'telefono', 'tienda_id', 'rol_id'])) {
      $this->redirect('usuarios', ['error' => ErrorMessages::ERROR_USUARIO_USUARIO_DATOSFALTANTES]);
      return;
    }
    $usuario = new UserModel();
    $usuario->setId($this->getPost('usuario_id'));
    $usuario->setNombreUsuario($this->getPost('nombre_usuario'));
    $usuario->setCorreo($this->getPost('correo'));
    $usuario->setTelefono($this->getPost('telefono'));
    $usuario->setTiendaId($this->getPost('tienda_id'));
    $usuario->setRolId($this->getPost('rol_id'));

    $usuario->update();
    $this->redirect('usuarios', ['success' => SuccessMessages::SUCCESS_USUARIO_UPDATE_USUARIO]);
  }

  public function deleteUsuario()
  {
    if (!$this->existPOST(['usuario_id'])) {
      $this->redirect('usuarios', ['error' => ErrorMessages::ERROR_USUARIO_USUARIO_DATOSFALTANTES]);
      return;
    }
    $usuario = new UserModel();
    $id = $this->getPost('usuario_id');
    $usuario->delete($id);
    $this->redirect('usuarios', ['success' => SuccessMessages::SUCCESS_USUARIO_DELETE_USUARIO]);
  }

  /**
   * Cambiar contraseña del usuario
   */
  public function changePassword()
  {
    if (!$this->existPOST(['current_password', 'new_password', 'confirm_password'])) {
      $this->redirect('tienda', ['error' => ErrorMessages::ERROR_USUARIO_CAMBIARPASSWORD_DATOSFALTANTES]);
      return;
    }

    $currentPassword = $this->getPost('current_password');
    $newPassword = $this->getPost('new_password');
    $confirmPassword = $this->getPost('confirm_password');

    // Validar coincidencia de nuevas contraseñas
    if ($newPassword !== $confirmPassword) {
      $this->redirect('tienda', ['error' => ErrorMessages::ERROR_USUARIO_CAMBIARPASSWORD_NOMATCH]);
      return;
    }

    try {
      // Obtener usuario actual desde sesión
      $userId = $_SESSION['user'];

      // Verificar contraseña actual
      if (!$this->user->verifyCurrentPassword($userId, $currentPassword)) {
        $this->redirect('tienda', ['error' => ErrorMessages::ERROR_USUARIO_CAMBIARPASSWORD_PASSWORDINCORRECTO]);
        return;
      }

      // Actualizar contraseña
      if ($this->user->updatePassword($userId, $newPassword)) {
        $this->redirect('tienda', ['success' => SuccessMessages::SUCCESS_USUARIO_CAMBIARPASSWORD]);
      } else {
        $this->redirect('tienda', ['error' => ErrorMessages::ERROR_USUARIO_CAMBIARPASSWORD_UPDATE]);
      }
    } catch (\Exception $e) {
      error_log("UsuariosController::changePassword " . $e->getMessage());
      $this->redirect('tienda', ['error' => ErrorMessages::ERROR_USUARIO_CAMBIARPASSWORD_GENERAL]);
    }
  }
}
