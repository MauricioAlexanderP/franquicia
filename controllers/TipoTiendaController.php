<?php
namespace controllers;

use class\ErrorMessages;
use class\SuccessMessages;
use models\TipoTiendaModel;
use libs\View;
use controllers\SessionController;
use Error;

class TipoTiendaController extends SessionController
{
  protected $view;

  public function __construct()
  {
    $this->view = new View();
    parent::__construct();
  }

  public function render()
  {
    error_log("TIPOTIENDACONTROLLER::render -> cargar index");
    
    $this->view->render('tipoTienda/index', ['tipoTienda' => $this->getTipoTienda()]);
    error_log("TIPOTIENDACONTROLLER::render -> cargar index -> " . print_r($this->getTipoTienda(), true));
  }

  private function getTipoTienda()
  {
    $items =[];
    $tipoTienda = new TipoTiendaModel();
    $tipoTienda =$tipoTienda->getAll();
    
    foreach ($tipoTienda as $tipo){
      array_push($items, [
        'tipo_tienda_id' => $tipo->getTipoTiendaId(),
        'tipo' => $tipo->getTipo(),
        'descripcion' => $tipo->getDescripcion()
      ]);
    }
    return $items;
  }

  public function newTipoTienda(){
    error_log("TIPOTIENDACONTROLLER::newTipoTienda - Método llamado");
    if(!$this->existPOST(['tipo', 'descripcion'])){
      $this->redirect('tipoTienda', ['error' => ErrorMessages::ERROR_TIPOTIENDA_NEWTIPOTIENDA_DATOSFALTANTES]);
      return;
    }

    $tipoTienda = new TipoTiendaModel();
    $tipoTienda->setTipo($this->getPost('tipo'));
    $tipoTienda->setDescripcion($this->getPost('descripcion'));

    $tipoTienda->save();
    $this->redirect('tipoTienda', ['success' => SuccessMessages::SUCCESS_TIPOTIENDA_NEWTIPOTIENDA]);
  }

  public function editTipoTienda(){
    error_log("TIPOTIENDACONTROLLER::editTipoTienda - Método llamado");
    if(!$this->existPOST(['tipo_tienda_id', 'tipo', 'descripcion'])){
      $this->redirect('tipoTienda', ['error' => ErrorMessages::ERROR_TIPOTIENDA_DELETETIPOTIENDA_DATOSFALTANTES]);
      return;
    }

    $tipoTienda = new TipoTiendaModel();
    $tipoTienda->setTipoTiendaId($this->getPost('tipo_tienda_id'));
    $tipoTienda->setTipo($this->getPost('tipo'));
    $tipoTienda->setDescripcion($this->getPost('descripcion'));
    
    $tipoTienda->update();
    $this->redirect('tipoTienda', ['success' => SuccessMessages::SUCCESS_TIPOTIENDA_EDIT_EDITADO]);
    
  }

  public function deleteTipoTienda(){
    error_log("TIPOTIENDACONTROLLER::deleteTipoTienda - Método llamado");
    if(!$this->existPOST(['tipo_tienda_id', 'tipo', 'descripcion'])){
      $this->redirect('tipoTienda', ['error' => ErrorMessages::ERROR_TIPOTIENDA_DELETETIPOTIENDA_DATOSFALTANTES]);
      return;
    }

    $tipoTienda = new TipoTiendaModel();
    $tipoTienda->setTipoTiendaId($this->getPost('tipo_tienda_id'));
    $tipoTienda->setTipo($this->getPost('tipo'));
    $tipoTienda->setDescripcion($this->getPost('descripcion'));
    $tipoTienda->setEstado(0);
    $tipoTienda->update();
    $this->redirect('tipoTienda', ['success' => SuccessMessages::SUCCESS_TIPOTIENDA_DELETE_ELIMINADO]);
  }

  /** 
   * Método para obtener los datos de una tienda por su ID.
   * 
   * @return void
   * 
   */
  public function getTipoTiendaById(){
    error_log("TIPOTIENDACONTROLLER::getTiendaById - Método llamado");

    if(!$this->existPOST(['tipo_tienda_id'])){
      $this->redirect('tipoTienda', ['error' => ErrorMessages::ERROR_TIPOTIENDA_DELETETIPOTIENDA_DATOSFALTANTES]);
      return;
    }
    $tipoTienda = new TipoTiendaModel();
    $id = $this->getPost('tipo_tienda_id');
    $tipoTienda->setTipoTiendaId($id);

    $items = $tipoTienda->get($id);

    if($items){
      echo json_encode($items->toArray());
      error_log("TIPOTIENDACONTROLLER::getTiendaById - Método llamado -> " . print_r($items->toArray(), true));
    }else{
      echo json_encode(['error' => 'No se encontró el tipo de tienda']);
    }
  }
}