<?php

namespace controllers;

use libs\View;
use models\ProductoModel;
use models\TiendaModel;
use models\InventarioModel;
use models\UserModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;

class InventarioController extends SessionController
{
  protected $view;
  protected $data = [];
  protected $inventario;
  protected $tienda;
  protected $producto;
  protected $user;

  public function __construct()
  {
    $this->view = new View;
    $this->inventario = new InventarioModel();
    $this->tienda = new TiendaModel();
    $this->producto = new ProductoModel();
    $this->user = new UserModel();
    parent::__construct();
  }


  public function render()
  {
    error_log("INVENTARIOCONTROLLER::render ->  ");
    //error_log("INVENTARIOCONTROLLER::render -> inventario: " . print_r($this->inventario->getInventarioByTienda($this->data['tienda_id']), true));
    $this->view->render('inventario/index', [
      'inventario' => $this->getInventario(),
    ]);
  }

  private function dataUser()
  {
    $usuario = new UserModel();
    $id = $_SESSION['user'];
    $usuario->get($id);
    $item = $usuario->toArray();
    return $item;
  }

  private function getInventario()
  {
    $items = [];
    $this->data = $this->dataUser();
    $inventario =  $this->inventario->getInventarioByTienda($this->data['tienda_id']);
    foreach ($inventario as $item) {
      array_push($items, [
        // 'inventario_id' => $item->getId(),
        'tienda_id' => $item->getTiendaId(),
        'producto_id' => $item->getProductoId(),
        'stock' => $item->getStock(),
        'imagen' => $item->getImagen(),
        'inventario_id' => $item->getInventarioId(),
      ]);
    }
    return $items;
  }

  public function newProducto()
  {
    error_log("INVENTARIOCONTROLLER::newProducto");
    if (!$this->existPOST(['productos_seleccionados'])) {
      $this->redirect('inventario', ['error' => ErrorMessages::ERROR_INVENTARIO_NEWPRODUCTO_DATOSFALTANTES]);
      return;
    }
    $inventario = new InventarioModel();
    $this->data = $this->dataUser();

    foreach ($this->getPost('productos_seleccionados') as $productoId) {
      // Verificar si el producto ya existe en la tienda
      error_log("INVENTARIOCONTROLLER::newProducto -> Verificando producto: " . $productoId);
      $productoExistente = $inventario->getByTiendaAndProducto($this->data['tienda_id'], $productoId);

      if ($productoExistente) {
        if ($productoExistente->getEstado() == 0) {
          // Si el producto existe pero está eliminado, cambiar su estado a 1
          $productoExistente->setTiendaId($this->data['tienda_id']);
          $productoExistente->setEstado(1);
          $productoExistente->setUltimaActualizacion(date('Y-m-d'));
          $productoExistente->update();
          error_log("INVENTARIOCONTROLLER::newProducto -> Producto restaurado en el inventario.");
        } else {
          // Si el producto ya está activo, mostrar un error
          error_log("INVENTARIOCONTROLLER::newProducto -> El producto ya está registrado en la tienda.");
          $this->redirect('inventario', ['error' => ErrorMessages::ERROR_INVENTARIO_PRODUCTO_YA_REGISTRADO]);
          return;
        }
      } else {
        // Si el producto no existe, agregarlo al inventario
        $inventario->setTiendaId($this->data['tienda_id']);
        $inventario->setProductoId($productoId);
        $inventario->setStock(0);
        $inventario->setUltimaActualizacion(date('Y-m-d'));
        $inventario->setEstado(1);
        $inventario->save();
        error_log("INVENTARIOCONTROLLER::newProducto -> Producto registrado en el inventario.");
      }
    }

    $this->redirect('inventario', ['success' => SuccessMessages::SUCCESS_INVENTARIO_NEWPRODUCTO_GUARDADDA]);
  }

  public function deleteProducto()
  {
    error_log("INVENTARIOCONTROLLER::deleteProducto");
    if (!$this->existPOST(['inventario_id'])) {
      $this->redirect('inventario', ['error' => ErrorMessages::ERROR_INVENTARIO_PRODUCTO_YA_REGISTRADO]);
      return;
    }
    $inventario = new InventarioModel();
    $id = $this->getPost('inventario_id');
    $inventario->delete($id);
    $this->redirect('inventario', ['success' => SuccessMessages::SUCCESS_INVENTARIO_PRODUCTO_ELIMINADO]);
  }
}
