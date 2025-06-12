<?php

namespace controllers;

use libs\View;
use models\VentasModel;
use models\UserModel;
use libs\Model;
use models\ProductoModel;
use models\DetalleVentaModel;
use models\TiendaModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;


class VentasController extends SessionController
{
  protected $view;
  protected $data = [];
  protected $productos;
  protected $db;
  public function __construct()
  {
    $this->view = new View;
    $this->productos = new ProductoModel();
    $this->db = new Model();
    parent::__construct();
  }

  public function render()
  {
    error_log("VENTASCONTROLLLER::render -> cargar index");
    error_log("VENTASCONTROLLLER::render -> ventas: " . print_r($this->ventasByTienda(), true));
    $this->view->render('ventas/index', [
      'ventas' => $this->ventasByTienda(),
    ]);
  }


  private function ventasByTienda()
  {
    $ventasModel = new VentasModel();
    $this->data = $this->dataUser();
    $tienda_id = $this->data['tienda_id'];

    // Obtén las ventas desde el modelo
    $ventas = $ventasModel->getByTienda($tienda_id);

    // Valida que las ventas no estén vacías
    if (empty($ventas)) {
      error_log("VENTASCONTROLLLER::ventasByTienda -> No se encontraron ventas para la tienda ID: $tienda_id");
      return [];
    }

    return $ventas; // Devuelve el array de objetos
  }

  private function dataUser()
  {
    $usuario = new UserModel();
    $id = $_SESSION['user'];
    $usuario->get($id);
    $item = $usuario->toArray();
    return $item;
  }

  public function newVenta()
  {
    error_log("VENTASCONTROLLER::newVenta -> cargar registrar venta");

    // Validar que existan productos en el carrito
    if (!$this->existPOST('Total') || !isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
      $this->redirect('registrarVenta', [
        'error' => ErrorMessages::PRODUCTOS_NO_SELECCIONADOS,
      ]);
      return;
    }

    $this->data = $this->dataUser();
    $tienda_id = $this->data['tienda_id'];
    $productoModel = new ProductoModel();

    // 1. Primero validar que haya suficiente stock para todos los productos
    foreach ($_SESSION['carrito'] as $producto) {
      if (!$productoModel->verificarStock($tienda_id, $producto['id'], $producto['cantidad'])) {
        $this->redirect('registrarVenta', [
          'error' => ErrorMessages::STOCK_INSUFICIENTE,
        ]);
        return;
      }
    }

    // Iniciar transacción para asegurar integridad de datos
    $this->db->beginTransaction();

    try {
      $total = 0;
      print_r($_SESSION['carrito']);
      foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
      }

      // Calcular regalias según porcentaje definido en la tienda
      $tiendaModel = new TiendaModel();
      $tienda = $tiendaModel->get($tienda_id);
      $porcentajeRegalias = $tienda->getRegalias(); // Convertir porcentaje a decimal
      $regalias = $total * $porcentajeRegalias;

      // 2. Guardar la venta principal
      $venta = new VentasModel();
      $venta->setTiendaId($tienda_id);
      $venta->setFechaVenta(date('Y-m-d H:i:s'));
      $venta->setMontoTotal($total);
      $venta->setRegalias($regalias / 100); // Guardar regalias como decimal

      if (!$venta->save()) {
        throw new \Exception("Error al guardar la venta principal");
      }

      // Obtener el ID de la venta recién creada
      $venta_id = $venta->getLastInsertId();

      // 3. Guardar los detalles de venta y actualizar stock
      $detalleVentaModel = new DetalleVentaModel();

      foreach ($_SESSION['carrito'] as $producto) {
        // Guardar detalle de venta
        $detalleVentaModel->setVentaId($venta_id);
        $detalleVentaModel->setProductoId($producto['id']);
        $detalleVentaModel->setCantidad($producto['cantidad']);
        $detalleVentaModel->setPrecioUnitario($producto['precio']);

        //descomentar
        if (!$detalleVentaModel->save()) {
          throw new \Exception("Error al guardar detalle de venta");
        }

        // Actualizar stock descomentar
        error_log("VENTASCONTROLLER::newVenta -> Actualizando stock: tienda " . $tienda_id . ", producto " . $producto['id'] . ", cantidad " . $producto['cantidad']);
        if (!$productoModel->actualizarStock($tienda_id, $producto['id'], $producto['cantidad'])) {
          throw new \Exception("Error al actualizar stock");
        }

        // Reiniciar el modelo para el próximo detalle
        $detalleVentaModel = new DetalleVentaModel();
      }

      // Confirmar la transacción si todo salió bien
      $this->db->commit();

      // Generar factura PDF
      $this->generarFacturaPDF();

      $this->redirect('registrarVenta', [
        'success' => SuccessMessages::SUCCESS_VENTA_REGISTRADA,
      ]);
    } catch (\Exception $e) {
      // Revertir la transacción en caso de error
      $this->db->rollBack();
      error_log("VENTASCONTROLLER::newVenta -> Error: " . $e->getMessage());

      $this->redirect('registrarVenta', [
        'error' => ErrorMessages::ERROR_REGISTRAR_VENTA,
      ]);
    }
  }

  private function generarFacturaPDF()
  {
    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
      $this->redirect('registrarVenta', ['error' => 'No hay productos en el carrito']);
      return;
    }

    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
      $total += $producto['precio'] * $producto['cantidad'];
    }

    if (ob_get_length()) ob_clean();

    $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetAutoPageBreak(true, 25);
    $pdf->AddPage();

    // Encabezado minimalista
    $pdf->SetFont('helvetica', 'B', 24);
    $pdf->Cell(0, 10, 'MI TIENDA', 0, 1, 'L');

    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'Calle Falsa 123, Ciudad', 0, 1, 'L');
    $pdf->Cell(0, 5, 'Tel: 555-1234 | contacto@mitienda.com', 0, 1, 'L');
    $pdf->Cell(0, 5, 'CIF/NIF: XAXX010101000', 0, 1, 'L');

    // Línea divisoria fina
    $pdf->SetLineWidth(0.1);
    $pdf->Line(20, $pdf->GetY() + 5, 190, $pdf->GetY() + 5);
    $pdf->Ln(10);

    // Título de factura con número y fecha
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(0, 10, 'FACTURA #' . str_pad(rand(1000, 9999), 6, '0', STR_PAD_LEFT), 0, 1, 'R');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, date('d/m/Y H:i'), 0, 1, 'R');
    $pdf->Ln(15);

    // Tabla de productos con diseño limpio
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(110, 8, 'DESCRIPCIÓN', 'B', 0);
    $pdf->Cell(25, 8, 'CANTIDAD', 'B', 0, 'R');
    $pdf->Cell(25, 8, 'PRECIO', 'B', 0, 'R');
    $pdf->Cell(25, 8, 'TOTAL', 'B', 1, 'R');

    $pdf->SetFont('helvetica', '', 10);
    foreach ($_SESSION['carrito'] as $producto) {
      $nombre = $producto['nombre'];
      $cantidad = $producto['cantidad'];
      $precio = $producto['precio'];
      $totalProducto = $precio * $cantidad;

      $pdf->Cell(110, 8, $nombre, 0, 0);
      $pdf->Cell(25, 8, $cantidad, 0, 0, 'R');
      $pdf->Cell(25, 8, '$' . number_format($precio, 2, ',', '.'), 0, 0, 'R');
      $pdf->Cell(25, 8, '$' . number_format($totalProducto, 2, ',', '.'), 0, 1, 'R');
    }

    // Línea divisoria antes del total
    $pdf->SetLineWidth(0.2);
    $pdf->Line(150, $pdf->GetY() + 5, 190, $pdf->GetY() + 5);
    $pdf->Ln(8);

    // Total destacado
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(160, 10, 'TOTAL:', 0, 0, 'R');
    $pdf->Cell(25, 10, '$' . number_format($total, 2, ',', '.'), 0, 1, 'R');
    $pdf->Ln(20);

    // Mensaje de agradecimiento minimalista
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->Cell(0, 5, 'Gracias por su compra', 0, 1, 'C');
    $pdf->Cell(0, 5, 'www.mitienda.com', 0, 1, 'C');

    $pdf->Output('factura.pdf', 'I');
    unset($_SESSION['carrito']);
    exit;
  }
}
