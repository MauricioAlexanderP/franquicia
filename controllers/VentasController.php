<?php

namespace controllers;

use libs\View;
use models\VentasModel;
use models\UserModel;
use models\ProductoModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;


class VentasController extends SessionController
{
  protected $view;
  protected $data = [];
  protected $productos;
  public function __construct()
  {
    $this->view = new View;
    $this->productos = new ProductoModel();
    parent::__construct();
  }

  public function render()
  {
    error_log("VENTASCONTROLLLER::render -> cargar index");
    $this->view->render('ventas/index');
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
    if (!$this->existPOST('total')) {
      $this->redirect('registrarVenta', [
        'error' => ErrorMessages::PRODUCTOS_NO_SELECCIONADOS,
      ]);
      return;
    }

    $venta = new VentasModel();
    $this->data = $this->dataUser();
    $venta->setTiendaId($this->data['tienda_id']);
    $venta->setFechaVenta(date('Y-m-d H:i:s'));
    $venta->setMontoTotal($this->getPost('total'));
    $venta->save();
    $this->generarTicketPDF();

    $this->redirect('registrarVenta', [
      'success' => SuccessMessages::SUCCESS_VENTA_REGISTRADA,
    ]);
  }

  private function generarTicketPDF()
  {
    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
      $this->redirect('registrarVenta', ['error' => 'No hay productos en el carrito']);
      return;
    }

    $subtotal = 0;
    foreach ($_SESSION['carrito'] as $producto) {
      $subtotal += $producto['precio'] * $producto['cantidad'];
    }
    $total = $subtotal;

    $usuario = $this->dataUser();

    if (ob_get_length()) ob_clean();

    $pdf = new \TCPDF('P', 'mm', array(80, 200), true, 'UTF-8', false);
    $pdf->SetMargins(5, 5, 5);
    $pdf->SetAutoPageBreak(true, 5);
    $pdf->AddPage();

    // Encabezado - Tienda
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 5, 'MI TIENDA', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 4, 'RFC: XAXX010101000', 0, 1, 'C');
    $pdf->Cell(0, 4, 'Calle Falsa 123, SV', 0, 1, 'C');
    $pdf->Cell(0, 4, 'Tel: 555-1234', 0, 1, 'C');
    $pdf->Ln(4);

    // Detalles de ticket
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 4, 'Cajero: ' . $usuario['correo'], 0, 1);
    $pdf->Cell(0, 4, 'Ticket: ' . strtoupper(uniqid('T')), 0, 1);
    $pdf->Cell(0, 4, 'Fecha: ' . date('d/m/Y H:i'), 0, 1);
    $pdf->Ln(3);

    // Línea divisoria
    $pdf->Line(5, $pdf->GetY(), 75, $pdf->GetY());
    $pdf->Ln(2);

    // Tabla de productos
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(35, 4, 'Producto', 0, 0);
    $pdf->Cell(10, 4, 'Cant.', 0, 0, 'C');
    $pdf->Cell(15, 4, 'Precio', 0, 0, 'R');
    $pdf->Cell(15, 4, 'Total', 0, 1, 'R');

    $pdf->SetFont('helvetica', '', 8);

    foreach ($_SESSION['carrito'] as $producto) {
      $nombre = strlen($producto['nombre']) > 25 ? substr($producto['nombre'], 0, 22) . '...' : $producto['nombre'];
      $cantidad = $producto['cantidad'];
      $precio = $producto['precio'];
      $totalProducto = $precio * $cantidad;

      $pdf->Cell(35, 4, strtoupper($nombre), 0, 0);
      $pdf->Cell(10, 4, $cantidad, 0, 0, 'C');
      $pdf->Cell(15, 4, '$' . number_format($precio, 2), 0, 0, 'R');
      $pdf->Cell(15, 4, '$' . number_format($totalProducto, 2), 0, 1, 'R');
    }

    $pdf->Ln(2);
    $pdf->Line(5, $pdf->GetY(), 75, $pdf->GetY());
    $pdf->Ln(3);

    // Totales
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(55, 5, 'TOTAL:', 0, 0, 'R');
    $pdf->Cell(20, 5, '$' . number_format($total, 2), 0, 1, 'R');
    // $pdf->Cell(55, 5, 'Pago en efectivo:', 0, 0, 'R');
    // $pdf->Cell(20, 5, '$' . number_format($total, 2), 0, 1, 'R');
    $pdf->Ln(5);

    // Política y mensaje
    $pdf->SetFont('helvetica', '', 7);
    $pdf->MultiCell(0, 4, "Se aceptan cambios en mercancía intacta dentro de los 15 días siguientes a la compra, presentando el ticket. No hay cambios en ropa interior ni trajes de baño.", 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(0, 4, 'www.mitienda.com', 0, 1, 'C');
    $pdf->Cell(0, 4, '¡GRACIAS POR SU COMPRA!', 0, 1, 'C');

    $pdf->Output('ticket_venta.pdf', 'I');
    exit;
  }
}
