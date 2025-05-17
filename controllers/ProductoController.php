<?php

namespace controllers;

use libs\View;
use models\ProductoModel;
use models\TipoProductoModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;

class ProductoController extends SessionController
{
  protected $view;
  protected $tipoProducto;
  protected $producto;

  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
    $this->tipoProducto = new TipoProductoModel();
    $this->producto = new ProductoModel();
  }

  public function render()
  {
    error_log("PRODUCTOCONTROLLER::render -> cargar index");
    //error_log("PRODUCTOCONTROLLER::render -> tipoProducto: " . print_r($this->getTipoProducto(), true));
    $this->view->render('producto/index', [
      'tipoProducto' => $this->getTipoProducto(),
      'productos' => $this->producto->getAll()
    ]);
  }

  public function newProducto()
  {
    error_log("PRODUCTOCONTROLLER::newProducto");


    if (!$this->existPOST(['tipoProducto', 'nombre', 'descripcion', 'precio','stock'])) {
      $this->redirect('producto', ['error' => ErrorMessages::ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES]);
      return;
    }
    //error_log("PRODUCTOCONTROLLER::newProducto -> Datos recibidos: " . print_r($this->getPost('imagen'), true));

    $producto = new ProductoModel();
    $producto->setTipoProductoId($this->getPost('tipoProducto'));
    $producto->setNombre($this->getPost('nombre'));
    $producto->setDescripcion($this->getPost('descripcion'));
    $producto->setImagen($this->images());
    $producto->setPrecio($this->getPost('precio'));
    $producto->setStock($this->getPost('stock'));
    $producto->save();
    $this->redirect('producto', ['success' => SuccessMessages::SUCCESS_PRODUCTO_NEWPRODUCTO]);
  }

  public function updateProducto()
  {
    error_log("PRODUCTOCONTROLLER::updateProducto");

    if (!$this->existPOST(['producto_id', 'tipoProducto', 'nombre', 'descripcion', 'precio', 'stock'])) {
      $this->redirect('producto', ['error' => ErrorMessages::ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES]);
      return;
    }

    $producto = new ProductoModel();
    $producto->setProductoId($this->getPost('producto_id'));
    $producto->setTipoProductoId($this->getPost('tipoProducto'));
    $producto->setNombre($this->getPost('nombre'));
    $producto->setDescripcion($this->getPost('descripcion'));
    // $producto->setImagen($this->images());
    $producto->setPrecio($this->getPost('precio'));
    $producto->setStock($this->getPost('stock'));
    $producto->update();
    $this->redirect('producto', ['success' => SuccessMessages::SUCCESS_PRODUCTO_UPDATEPRODUCTO]);
  }

  public function deleteProducto()
  {
    if(!$this->existPOST(['producto_id'])){
      $this->redirect('producto', ['error' => ErrorMessages::ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES]);
      return;
    }
    $producto = new ProductoModel();
    $id = $this->getPost('producto_id');
    $producto->delete($id);
    $this->redirect('producto', ['success' => SuccessMessages::SUCCESS_PRODUCTO_DELETEPRODUCTO]);
  }

  public function getProductoById() 
  {
    error_log("PRODUCTOCONTROLLER::getProductoById");

    if (!$this->existPOST(['producto_id'])) {
      $this->redirect('producto', ['error' => ErrorMessages::ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES]);
      return;
    }

    $producto = new ProductoModel();
    $id = $this->getPost('producto_id');
    $producto->setProductoId($id);

    $items = $producto->get($id);
    error_log("PRODUCTOCONTROLLER::getProductoById -> producto: " . print_r($items, true));
    if ($items) {
      echo json_encode($items->toArray());
      error_log("PRODUCTOCONTROLLER::getProductoById -> array: " . print_r($items->toArray(), true));
    } else {
      echo json_encode(['error' => 'No se encontró el producto']);
    }
  }

  private function getTipoProducto()
  {
    $items = [];
    $tipos = $this->tipoProducto->getAll();
    foreach ($tipos as $tipo) {
      array_push($items, [
        'tipo_producto_id' => $tipo->getTipoProductoId(),
        'catalogo' => $tipo->getCatalogo(),
        'descripcion' => $tipo->getDescripcion()
      ]);
    }
    return $items;
  }

  private function images()
  {
    $name = 'default.png'; // Valor por defecto

    if (isset($_FILES["imagen"]) && is_uploaded_file($_FILES['imagen']['tmp_name'])) {
      // Validación de tipo de imagen
      $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mimeType = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
      finfo_close($finfo);

      if (!in_array($mimeType, $allowedTypes)) {
        error_log("PRODUCTOCONTROLLER::imagen -> Tipo de archivo no permitido: " . $mimeType);
        $this->redirect('producto', ['error' => ErrorMessages::ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES]);
        return $name;
      }

      $tmp_name = $_FILES["imagen"]["tmp_name"];
      $originalName = $_FILES["imagen"]["name"];
      $uploadDir = 'public/imgs/';
      $name = $originalName; // Este nombre se guarda en la base de datos

      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $filePath = $uploadDir . $name;

      // Si el archivo ya existe, agrega un identificador único
      if (is_file($filePath)) {
        $idUnico = time();
        $name = $idUnico . "-" . $originalName;
        $filePath = $uploadDir . $name;
      }

      if (!move_uploaded_file($tmp_name, $filePath)) {
        error_log("PRODUCTOCONTROLLER::imagen -> Error al mover la imagen: " . $_FILES['imagen']['error']);
        $this->redirect('producto', ['error' => ErrorMessages::ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES]);
        $name = 'default.png';
      }
    }
    return $name;
  }
}
