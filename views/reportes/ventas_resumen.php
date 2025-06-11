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
  <title>Resumen de Ventas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .report-header {
      background-color: #3a0ca3;
      color: white;
      padding: 20px;
      border-radius: 10px 10px 0 0;
    }

    .chart-container {
      height: 300px;
      margin-bottom: 30px;
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

      .chart-container {
        display: none;
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
            <h1 class="report-title">Resumen de Ventas</h1>
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
          <h4>Resumen de Ventas</h4>
          <p>Fecha: <?= date('d M Y H:i') ?></p>
          <p>Periodo: <?= date('d M Y', strtotime($fechaInicio)) ?> - <?= date('d M Y', strtotime($fechaFin)) ?></p>
        </div>

        <div class="chart-container no-print">
          <canvas id="ventasChart"></canvas>
        </div>

        <?php if (empty($reporte)): ?>
          <div class="alert alert-info">No hay ventas en el rango de fechas seleccionado</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Total Ventas</th>
                  <th>Total Monto</th>
                  <th>Regalías</th>
                  <th>Promedio por Venta</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totalVentas = 0;
                $totalMonto = 0;
                $totalRegalias = 0;
                ?>
                <?php foreach ($reporte as $resumen): ?>
                  <tr>
                    <td><?= date('d/m/Y', strtotime($resumen['fecha'])) ?></td>
                    <td><?= $resumen['total_ventas'] ?></td>
                    <td>$<?= number_format($resumen['total_monto'], 2) ?></td>
                    <td>$<?= number_format($resumen['total_regalias'], 2) ?></td>
                    <td>$<?= number_format($resumen['total_monto'] / $resumen['total_ventas'], 2) ?></td>
                  </tr>
                  <?php
                  $totalVentas += $resumen['total_ventas'];
                  $totalMonto += $resumen['total_monto'];
                  $totalRegalias += $resumen['total_regalias'];
                  ?>
                <?php endforeach; ?>
                <tr class="total-row">
                  <td>Total:</td>
                  <td><?= $totalVentas ?></td>
                  <td>$<?= number_format($totalMonto, 2) ?></td>
                  <td>$<?= number_format($totalRegalias, 2) ?></td>
                  <td>$<?= $totalVentas > 0 ? number_format($totalMonto / $totalVentas, 2) : '0.00' ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    // Datos para el gráfico
    const ventasData = {
      labels: [<?php
                $dates = [];
                $amounts = [];
                foreach ($reporte as $resumen) {
                  $dates[] = "'" . date('d M', strtotime($resumen['fecha'])) . "'";
                  $amounts[] = $resumen['total_monto'];
                }
                echo implode(',', $dates);
                ?>],
      datasets: [{
        label: 'Ventas Diarias',
        data: [<?= implode(',', $amounts) ?>],
        backgroundColor: 'rgba(58, 12, 163, 0.2)',
        borderColor: 'rgba(58, 12, 163, 1)',
        borderWidth: 2,
        tension: 0.4,
        fill: true
      }]
    };

    // Configuración del gráfico
    const ventasConfig = {
      type: 'line',
      data: ventasData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'Tendencia de Ventas'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    };

    // Inicializar el gráfico
    window.addEventListener('DOMContentLoaded', (event) => {
      const ventasChart = new Chart(
        document.getElementById('ventasChart'),
        ventasConfig
      );
    });
  </script>
</body>

</html>