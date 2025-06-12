<?php
$reporte = $this->d['reporte'];
$fechaInicio = $this->d['fechaInicio'];
$fechaFin = $this->d['fechaFin'];
$tiendaId = $this->d['tiendaId'];
$tipoReporte = $this->d['tipoReporte'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte de Ventas Detalladas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .report-header {
      background-color: #4361ee;
      color: white;
      padding: 20px;
      border-radius: 10px 10px 0 0;
    }

    .report-title {
      margin: 0;
    }

    .report-container {
      border: 1px solid #dee2e6;
      border-radius: 10px;
      margin-bottom: 30px;
    }

    .report-body {
      padding: 20px;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .total-row {
      font-weight: bold;
      background-color: #f8f9fa;
    }

    .print-section {
      display: none;
    }

    @media print {
      .no-print {
        display: none;
      }

      .print-section {
        display: block;
      }

      .report-container {
        border: none;
        box-shadow: none;
      }

      body {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="report-container">
      <div class="report-header">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="report-title">Reporte de Ventas Detalladas</h1>
            <p class="mb-0">
              <?= date('d M Y', strtotime($fechaInicio)) ?> - <?= date('d M Y', strtotime($fechaFin)) ?>
            </p>
          </div>
          <div class="no-print">
            <button class="btn btn-light" onclick="window.print()">
              <i class="bi bi-printer"></i> Imprimir
            </button>
            <a href="<?= constant('URL') ?>reportes" class="btn btn-light">
              <i class="bi bi-arrow-left"></i> Volver
            </a>
          </div>
        </div>
      </div>

      <div class="report-body">
        <div class="print-section mb-4">
          <h4>Reporte de Ventas Detalladas</h4>
          <p>Fecha: <?= date('d M Y H:i') ?></p>
          <p>Periodo: <?= date('d M Y', strtotime($fechaInicio)) ?> - <?= date('d M Y', strtotime($fechaFin)) ?></p>
        </div>

        <?php if (empty($reporte)): ?>
          <div class="alert alert-info">No hay ventas en el rango de fechas seleccionado</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID Venta</th>
                  <th>Tienda</th>
                  <th>Fecha</th>
                  <th>Cajero</th>
                  <th>Monto Total</th>
                  <th>Regalías</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totalVentas = 0;
                $totalRegalias = 0;
                ?>
                <?php foreach ($reporte as $venta): ?>
                  <tr>
                    <td><?= $venta['venta_id'] ?></td>
                    <td><?= $venta['nombre_tienda'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></td>
                    <td><?= $venta['cajero'] ?></td>
                    <td>$<?= number_format($venta['monto_total'], 2) ?></td>
                    <td>$<?= number_format($venta['regalias'], 2) ?></td>
                  </tr>
                  <?php
                  $totalVentas += $venta['monto_total'];
                  $totalRegalias += $venta['regalias'];
                  ?>
                <?php endforeach; ?>
                <tr class="total-row">
                  <td colspan="4" class="text-end">Total:</td>
                  <td>$<?= number_format($totalVentas, 2) ?></td>
                  <td>$<?= number_format($totalRegalias, 2) ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>

</html>