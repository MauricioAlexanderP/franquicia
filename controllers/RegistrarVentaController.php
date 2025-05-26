<?php

namespace controllers;

use libs\View;
use controllers\SessionController;
use models\ProductoModel;
use models\UserModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\VentasController;

class RegistrarVentaController extends SessionController
{
  protected $view;
  protected $productos;
  protected $data = [];

  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
    $this->productos = new ProductoModel();
  }

  public function render()
  {
    error_log("REGISTRARVENTACONTROLLER::render -> cargar index");
    $this->view->render('ventas/registrarVenta', [
      'productos' => $this->getProductos(),
    ]);
  }
  // Method to get the user data
  private function dataUser()
  {
    $usuario = new UserModel();
    $id = $_SESSION['user'];
    $usuario->get($id);
    $item = $usuario->toArray();
    return $item;
  }
  private function getProductos()
  {
    $items = [];
    $this->data = $this->dataUser();
    $producto =  $this->productos->getProductosByTienda($this->data['tienda_id']);
    return $producto;
  }

  public function VentaSession()
  {
    // Verificar si se envió el campo 'productos_seleccionados'
    if (!$this->existPOST('productos_seleccionados')) {
      $this->redirect('registrarVenta', [
        'error' => ErrorMessages::PRODUCTOS_NO_SELECCIONADOS,
        'productos' => $this->productos->getAll()
      ]);
      return;
    }

    // Obtener los datos enviados desde el formulario
    $productosSeleccionadosJSON = $this->getPost('productos_seleccionados');
    $productosSeleccionados = json_decode($productosSeleccionadosJSON, true);
    // Validar que los datos sean válidos
    if (empty($productosSeleccionados) || !is_array($productosSeleccionados)) {
      $this->redirect('registrarVenta', [
        'error' => ErrorMessages::PRODUCTOS_NO_VALIDOS,
      ]);
      return;
    }

    // Crear o actualizar la sesión 'carrito'
    if (!isset($_SESSION['carrito'])) {
      $_SESSION['carrito'] = [];
    }

    foreach ($productosSeleccionados as $producto) {
      // Validar que el producto tenga los campos necesarios
      if (!isset($producto['id'], $producto['imagen'], $producto['nombre'], $producto['precio'])) {
        continue; // Ignorar productos inválidos
      }

      // Verificar si el producto ya está en el carrito
      $existe = false;
      foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] == $producto['id']) {
          // Incrementar la cantidad si ya existe
          $item['cantidad'] += 1;
          $existe = true;
          break;
        }
      }

      // Si no existe, agregarlo al carrito
      if (!$existe) {
        $_SESSION['carrito'][] = [
          'id' => $producto['id'],
          'imagen' => $producto['imagen'],
          'nombre' => $producto['nombre'],
          'precio' => $producto['precio'],
          'cantidad' => 1 // Inicializar con cantidad 1
        ];
      }
    }

    // Redirigir o renderizar la vista con el carrito actualizado
    $this->redirect('registrarVenta', [
      'success' => SuccessMessages::PRODUCTOS_AGREGADOS,
      'carrito' => $_SESSION['carrito'],
    ]);
  }

  public function actualizarCantidad()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Método no permitido']);
      return;
    }

    $productoId = $this->getPost('producto_id');
    $nuevaCantidad = (int)$this->getPost('cantidad');

    // Validaciones
    if (empty($productoId) || $nuevaCantidad < 1) {
      echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
      return;
    }

    if (!isset($_SESSION['carrito'])) {
      echo json_encode(['success' => false, 'message' => 'Carrito no existe']);
      return;
    }

    // Buscar y actualizar el producto
    $encontrado = false;
    foreach ($_SESSION['carrito'] as &$producto) {
      if ($producto['id'] == $productoId) {
        $producto['cantidad'] = $nuevaCantidad;
        $encontrado = true;
        break;
      }
    }

    if (!$encontrado) {
      echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
      return;
    }

    // Calcular nuevos totales
    $subtotal = 0;
    foreach ($_SESSION['carrito'] as $item) {
      $subtotal += $item['precio'] * $item['cantidad'];
    }

    echo json_encode([
      'success' => true,
      'message' => 'Cantidad actualizada',
      'subtotal' => number_format($subtotal, 2),
      'total' => number_format($subtotal, 2), // Asumiendo que no hay impuestos/descuentos
      'cantidad' => $nuevaCantidad,
    ]);
  }

  private function calcularSubtotal()
  {
    $subtotal = 0;
    if (isset($_SESSION['carrito'])) {
      foreach ($_SESSION['carrito'] as $producto) {
        $subtotal += $producto['precio'] * $producto['cantidad'];
      }
    }
    return number_format($subtotal, 2);
  }

  public function deleteProducto()
  {
    // Verificar si se envió el campo 'producto_id'
    if (!$this->existPOST('producto_id')) {
      $this->redirect('registrarVenta', [
        'error' => ErrorMessages::PRODUCTO_NO_SELECCIONADO,
      ]);
      return;
    }

    // Obtener el ID del producto a eliminar
    $productoId = $this->getPost('producto_id');
    error_log("REGISTRARVENTACONTROLLER::deleteProducto -> ID del producto a eliminar: " . $productoId);
    // Verificar si el producto está en el carrito
    if (isset($_SESSION['carrito'])) {
      foreach ($_SESSION['carrito'] as $key => $item) {
        if ($item['id'] == $productoId) {
          // Eliminar el producto del carrito
          unset($_SESSION['carrito'][$key]);
          $this->redirect('registrarVenta', [
            'success' => SuccessMessages::PRODUCTO_ELIMINADO,
            'carrito' => $_SESSION['carrito'],
          ]);
          return;
        }
      }
    }

    // Si llegamos aquí, el producto no estaba en el carrito
    $this->redirect('registrarVenta', [
      'error' => ErrorMessages::PRODUCTO_NO_ENCONTRADO,
    ]);
  }
}
