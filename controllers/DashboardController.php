<?php

namespace controllers;

use libs\View;
use models\VentasModel;
use models\InventarioModel;
use models\EvaluacionesModel;
use models\ProductoModel;
use models\UserModel;
use models\TiendaModel;
use controllers\SessionController;

class DashboardController extends SessionController
{
  protected $view;
  protected $ventas;
  protected $inventario;
  protected $evaluaciones;
  protected $productos;
  protected $tiendas;
  protected $data = [];

  public function __construct()
  {
    $this->view = new View;
    $this->ventas = new VentasModel();
    $this->inventario = new InventarioModel();
    $this->evaluaciones = new EvaluacionesModel();
    $this->productos = new ProductoModel();
    $this->tiendas = new TiendaModel();
    parent::__construct();
  }

  public function render()
  {
    error_log("DASHBOARDCONTROLLER::render");
    $this->data = $this->dataUser();
    $tienda_id = $this->data['tienda_id'];

    // Obtener datos para el dashboard
    $dashboardData = [
      'ventas' => $this->getVentasData($tienda_id),
      'inventario' => $this->getInventarioData($tienda_id),
      'evaluaciones' => $this->getEvaluacionesData($tienda_id),
      'top_productos' => $this->getTopProductos($tienda_id),
      'metricas_generales' => $this->getMetricasGenerales()
    ];

    $this->view->render('dashboard/index', [
      'dashboard' => $dashboardData,
    ]);
  }

  private function getVentasData($tienda_id)
  {
    return [
      'hoy' => $this->ventas->getVentasHoy($tienda_id),
      'semana' => $this->ventas->getVentasSemana($tienda_id),
      'mes' => $this->ventas->getVentasMes($tienda_id),
      'tendencia' => $this->ventas->getTendenciaVentas($tienda_id)
    ];
  }

  private function getInventarioData($tienda_id)
  {
    return [
      'total_productos' => $this->inventario->getTotalProductos($tienda_id),
      'bajo_stock' => $this->inventario->getProductosBajoStock($tienda_id),
      'stock_critico' => $this->inventario->getProductosStockCritico($tienda_id)
    ];
  }

  private function getEvaluacionesData($tienda_id)
  {
    return [
      'promedio' => $this->evaluaciones->getCalificacionPromedio($tienda_id),
      'recientes' => $this->evaluaciones->getEvaluacionesRecientes($tienda_id, 5)
    ];
  }

  private function getTopProductos($tienda_id)
  {
    return $this->productos->getTopProductos($tienda_id, 5);
  }

  private function getMetricasGenerales()
  {
    return [
      'total_tiendas' => $this->tiendas->getTotalTiendas(),
      'total_productos' => $this->productos->getTotalProductos(),
      'total_ventas' => $this->ventas->getTotalVentas()
    ];
  }

  private function dataUser()
  {
    $usuario = new UserModel();
    $id = $_SESSION['user'];
    $usuario->get($id);
    return $usuario->toArray();
  }
}
