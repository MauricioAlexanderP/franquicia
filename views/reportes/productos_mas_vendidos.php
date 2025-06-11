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
  <title>Productos Más Vendidos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .report-header {
      background-color: #4cc9f0;
      color: white;
      padding: 20px;
      border-radius: 10px 10px 0 0;
    }

    .chart-container {
      height: 400px;
      margin-bottom: 30px;
    }

    .product-bar {
      height: 30px;
      background: linear-gradient(to right, #4cc9f0, #4361ee);
      margin-bottom: 5px;
      border-radius: 3px;
      color: white;
      padding: 5px 10px;
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
            <h1 class="report-title">Productos Más Vendidos</h1>
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
          <h4>Productos Más Vendidos</h4>
          <p>Fecha: <?= date('d M Y H:i') ?></p>
          <p>Periodo: <?= date('d M Y', strtotime($fechaInicio)) ?> - <?= date('d M Y', strtotime($fechaFin)) ?></p>
        </div>

        <div class="chart-container no-print">
          <canvas id="productosChart"></canvas>
        </div>

        <?php if (empty($reporte)): ?>
          <div class="alert alert-info">No hay productos vendidos en el rango de fechas seleccionado</div>
        <?php else: ?>
          <div class="row">
            <div class="col-md-6">
              <h5>Top de Productos por Cantidad</h5>
              <?php foreach ($reporte as $producto): ?>
                <div class="d-flex justify-content-between mb-2">
                  <div><?= $producto['nombre'] ?></div>
                  <div><?= $producto['total_vendido'] ?> unidades</div>
                </div>
                <div class="product-bar" style="width: <?= ($producto['total_vendido'] / $reporte[0]['total_vendido']) * 100 ?>%">
                  <?= $producto['total_vendido'] ?>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="col-md-6">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th>Cantidad Vendida</th>
                      <th>Total Ingresos</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $totalUnidades = 0;
                    $totalIngresos = 0;
                    ?>
                    <?php foreach ($reporte as $producto): ?>
                      <tr>
                        <td><?= $producto['nombre'] ?></td>
                        <td><?= $producto['total_vendido'] ?></td>
                        <td>$<?= number_format($producto['total_ingresos'], 2) ?></td>
                      </tr>
                      <?php
                      $totalUnidades += $producto['total_vendido'];
                      $totalIngresos += $producto['total_ingresos'];
                      ?>
                    <?php endforeach; ?>
                    <tr class="total-row">
                      <td>Total:</td>
                      <td><?= $totalUnidades ?></td>
                      <td>$<?= number_format($totalIngresos, 2) ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    // Datos para el gráfico
    const productosData = {
      labels: [<?php
                $names = [];
                $quantities = [];
                foreach ($reporte as $producto) {
                  $names[] = "'" . $producto['nombre'] . "'";
                  $quantities[] = $producto['total_vendido'];
                }
                echo implode(',', $names);
                ?>],
      datasets: [{
        label: 'Unidades Vendidas',
        data: [<?= implode(',', $quantities) ?>],
        backgroundColor: [
          'rgba(67, 97, 238, 0.7)',
          'rgba(76, 201, 240, 0.7)',
          'rgba(58, 12, 163, 0.7)',
          'rgba(247, 37, 133, 0.7)',
          'rgba(72, 149, 239, 0.7)'
        ],
        borderColor: [
          'rgba(67, 97, 238, 1)',
          'rgba(76, 201, 240, 1)',
          'rgba(58, 12, 163, 1)',
          'rgba(247, 37, 133, 1)',
          'rgba(72, 149, 239, 1)'
        ],
        borderWidth: 1
      }]
    };

    // Configuración del gráfico
    const productosConfig = {
      type: 'bar',
      data: productosData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'Top Productos Más Vendidos'
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
      const productosChart = new Chart(
        document.getElementById('productosChart'),
        productosConfig
      );
    });
  </script>
</body>

</html>