<?php
namespace controllers;
use libs\View;
use models\InventarioModel;
use models\ProductoModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;
use controllers\ProductoController;
class ProductosInventarioController extends SessionController
{
  protected $view;
  protected $inventario;
  protected $productos;
  private $productoController;
  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
    $this->inventario = new InventarioModel();
    $this->productos = new ProductoModel();
    $this->productoController = new ProductoController();
  }

  public function render()
  {
    error_log("PRODUCTOSINVENTARIOCONTROLLER::render -> cargar index");
    $this->view->render('producto/productosInventario', [
      'productos' => $this->productos->getAll()
    ]);
  }

}