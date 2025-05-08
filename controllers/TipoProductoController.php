<?php

namespace controllers;

use libs\View;
use models\TipoProductoModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;

class TipoProductoController extends SessionController
{
  protected $view;
  protected $data;

  public function __construct()
  {
    $this->view = new View;
    parent::__construct();
  }

  public function render()
  {
    error_log("TIPOPRODUCTOCONTROLLER::render -> cargar index");
    //error_log("TIPOPRODUCTOCONTROLLER::render -> data: " . print_r($dataTipoProducto, true));
    $this->view->render('tipoProductos/index', ['tipoProducto' => $this->getTipoProductos()]);
  }

  private function getTipoProductos()
  {
    error_log("TIPOPRODUCTOCONTROLLER::getTipoProductos -> cargar tipo productos");
    $tipoProducto = new TipoProductoModel();
    $dataTipoProducto = $tipoProducto->getAll();
    $items = [];
    foreach ($dataTipoProducto as $tipo) {
      array_push($items, [
        'tipo_producto_id' => $tipo->getTipoProductoId(),
        'catalogo' => $tipo->getCatalogo(),
        'descripcion' => $tipo->getDescripcion()
      ]);
    }
    return $items;
  }

  public function newTipoProducto()
  {
    error_log("TIPOPRODUCTOCONTROLLER::newTipoProducto - Método llamado");
    if (!$this->existPOST(['catalogo', 'descripcion'])) {
      $this->redirect('tipoProducto', ['error' => ErrorMessages::ERROR_TIPOPRODUCTO_NEWTIPOPRODUCTO_DATOSFALTANTES]);
      return;
    }

    $tipoProducto = new TipoProductoModel();
    $tipoProducto->setCatalogo($this->getPost('catalogo'));
    $tipoProducto->setDescripcion($this->getPost('descripcion'));

    $tipoProducto->save();
    $this->redirect('tipoProducto', ['success' => SuccessMessages::SUCCESS_TIPOPRODUCTO_NEWTIPOPRODUCTO]);
  }

  public function getTipoProductoById()
  {
    error_log("TIPOPRODUCTOCONTROLLER::getTipoProductoById - Método llamado");
    if (!$this->existPOST(['tipo_producto_id'])) {
      $this->redirect('tipoProducto', ['error' => ErrorMessages::ERROR_TIPOPRODUCTO_GETTIPOPRODUCTO_DATOSFALTANTES]);
      return;
    }

    $tipoProducto = new TipoProductoModel();
    $id = $this->getPost('tipo_producto_id');
    $items = $tipoProducto->get($id);
    if($items) {
      echo json_encode($items->toArray());
      error_log("TIPOPRODUCTOCONTROLLER::getTipoProductoById - Método llamado -> " . print_r($items->toArray(), true));
    } else {
      echo json_encode(['error' => "No se encontró el tipo de producto."]);
    }
  }

  public function updateTipoProducto()
  {
    error_log("TIPOPRODUCTOCONTROLLER::updateTipoProducto - Método llamado");
    if (!$this->existPOST(['tipo_producto_id', 'catalogo', 'descripcion'])) {
      $this->redirect('tipoProducto', ['error' => ErrorMessages::ERROR_TIPOPRODUCTO_UPDATETIPOPRODUCTO_DATOSFALTANTES]);
      return;
    }

    $tipoProducto = new TipoProductoModel();
    $tipoProducto->setTipoProductoId($this->getPost('tipo_producto_id'));
    $tipoProducto->setCatalogo($this->getPost('catalogo'));
    $tipoProducto->setDescripcion($this->getPost('descripcion'));

    $tipoProducto->update();
    $this->redirect('tipoProducto', ['success' => SuccessMessages::SUCCESS_TIPOPRODUCTO_UPDATETIPOPRODUCTO]);
  }

  public function deleteTipoProducto()
  {
    error_log("TIPOPRODUCTOCONTROLLER::deleteTipoProducto - Método llamado");
    if (!$this->existPOST(['tipo_producto_id', 'catalogo', 'descripcion'])) {
      $this->redirect('tipoProducto', ['error' => ErrorMessages::ERROR_TIPOPRODUCTO_GETTIPOPRODUCTO_DATOSFALTANTES]);
      return;
    }

    $tipoProducto = new TipoProductoModel();
    $tipoProducto->setTipoProductoId($this->getPost('tipo_producto_id'));
    $tipoProducto->setCatalogo($this->getPost('catalogo'));
    $tipoProducto->setDescripcion($this->getPost('descripcion'));
    $tipoProducto->setEstado(0);
    $tipoProducto->update(); 
    $this->redirect('tipoProducto', ['success' => SuccessMessages::SUCCESS_TIPOPRODUCTO_DELETE_ELIMINADO]);
  }


}
