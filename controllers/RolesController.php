<?php

namespace controllers;

use libs\View;
use models\RolesModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;

class RolesController extends SessionController
{
  protected $view;
  protected $rolesModel;

  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
    $this->rolesModel = new RolesModel();
    
  }

  public function render()
  {
    error_log("ROLESCONTROLLER::render -> cargar index");
    error_log("ROLESCONTROLLER::render -> " . print_r($this->getRoles(), true));
    $this->view->render('roles/index', [
      'roles' => $this->getRoles()
    ]);
  }

  private function getRoles()
  {
    $dataRoles = $this->rolesModel->getAll();
    $items = [];
    foreach ($dataRoles as $rol) {
      array_push($items, [
        'role_id' => $rol->getRoleId(),
        'nombre_rol' => $rol->getNombreRol(),
      ]);
    }
    return $items;
  }

  public function newRol()
  {
    error_log("ROLESCONTROLLER::newRol - Método llamado");
    if (!$this->existPOST(['role_name'])) {
      $this->redirect('roles', ['error' => ErrorMessages::ERROR_ROLES_NEWROL_DATOSFALTANTES]);
      return;
    }

    $rol = new RolesModel();
    $rol->setNombreRol($this->getPost('role_name'));

    $rol->save();
    $this->redirect('roles', ['success' => SuccessMessages::SUCCESS_ROLES_NEWROL_GUARDADO]);
  }

  public function getRolById(){
    error_log("ROLESCONTROLLER::getRolById - Método llamado");
    if (!$this->existPOST(['role_id'])) {
      error_log("ROLESCONTROLLER::getRolById - Datos faltantes");
      return;
    }

    $rol = new RolesModel();
    $id = $this->getPost('role_id');
    $items = $rol->get($id);
    if ($items) {
      echo json_encode($items->toArray());
    }else {
      echo json_encode(['error' => ErrorMessages::ERROR_ROLES_NEWROL_DATOSFALTANTES]);
      return;
    }
  }

  public function updateRol(){
    if (!$this->existPOST(['role_id', 'role_name'])) {
      $this->redirect('roles', ['error' => ErrorMessages::ERROR_ROLES_NEWROL_DATOSFALTANTES]);
      return;
    }

    $rol = new RolesModel();
    $rol->setRoleId($this->getPost('role_id'));
    $rol->setNombreRol($this->getPost('role_name'));

    $rol->update();
    $this->redirect('roles', ['success' => SuccessMessages::SUCCESS_ROLES_NEWROL_ACTUALIZADO]);
  }

  public function deleteRol()
  {
    error_log("ROLESCONTROLLER::deleteRol - Método llamado");
    if (!$this->existPOST(['role_id'])) {
      $this->redirect('roles', ['error' => ErrorMessages::ERROR_ROLES_NEWROL_DATOSFALTANTES]);
      return;
    }

    $rol = new RolesModel();
    $id = $this->getPost('role_id');
    $rol->delete($id);
    $this->redirect('roles', ['success' => SuccessMessages::SUCCESS_ROLES_NEWROL_ELIMINADO]);
  }

}
