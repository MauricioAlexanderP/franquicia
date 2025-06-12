<?php

namespace controllers;

use libs\View;
use models\ReportesModel;
use models\TiendaModel;
use models\UserModel;
use controllers\SessionController;

class ReportesController extends SessionController
{
  protected $view;
  protected $reportesModel;
  protected $tiendaModel;
  protected $data = [];

  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
    $this->reportesModel = new ReportesModel();
    $this->tiendaModel = new TiendaModel();
  }

  public function render()
  {
    $this->data = $this->dataUser();
    $tienda_id = $this->data['tienda_id'];

    $tiendas = $this->tiendaModel->getAll();

    $this->view->render('reportes/index', [
      'tiendas' => $tiendas,
      'tienda_id' => $tienda_id
    ]);
  }

  public function generarReporteVentas()
  {
    $fechaInicio = $this->getPost('fecha_inicio');
    $fechaFin = $this->getPost('fecha_fin');
    $tiendaId = $this->getPost('tienda_id');
    $tipoReporte = $this->getPost('tipo_reporte');

    // Validar fechas
    if (empty($fechaInicio) || empty($fechaFin)) {
      $this->redirect('reportes', [
        'error' => 'Debe seleccionar un rango de fechas válido'
      ]);
      return;
    }

    // Obtener reporte según el tipo
    switch ($tipoReporte) {
      case 'ventas_detalladas':
        $reporte = $this->reportesModel->getVentasDetalladas($fechaInicio, $fechaFin, $tiendaId);
        $vista = 'reportes/ventas_detalladas';
        break;

      case 'ventas_resumen':
        $reporte = $this->reportesModel->getVentasResumen($fechaInicio, $fechaFin, $tiendaId);
        $vista = 'reportes/ventas_resumen';
        break;

      case 'productos_mas_vendidos':
        $reporte = $this->reportesModel->getProductosMasVendidos($fechaInicio, $fechaFin, $tiendaId);
        $vista = 'reportes/productos_mas_vendidos';
        break;

      case 'evaluaciones':
        $reporte = $this->reportesModel->getEvaluaciones($fechaInicio, $fechaFin, $tiendaId);
        $vista = 'reportes/evaluaciones';
        // calcular promedio de calificaciones
        if (!empty($reporte)) {
          $suma      = array_sum(array_column($reporte, 'calificacion'));
          $promedio  = $suma / count($reporte);
        }
        break;

      default:
        $this->redirect('reportes', [
          'error' => 'Tipo de reporte inválido'
        ]);
        return;
    }

    $this->view->render($vista, [
      'reporte' => $reporte,
      'fechaInicio' => $fechaInicio,
      'fechaFin' => $fechaFin,
      'tiendaId' => $tiendaId,
      'tipoReporte' => $tipoReporte,
      'promedio' => $promedio ?? 0,
    ]);
  }

  private function dataUser()
  {
    $usuario = new UserModel();
    $id = $_SESSION['user'];
    $usuario->get($id);
    return $usuario->toArray();
  }
}
