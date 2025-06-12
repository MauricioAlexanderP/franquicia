<?php
$dashboard = $this->d['dashboard'];
$ventas = $dashboard['ventas'];
$inventario = $dashboard['inventario'];
$evaluaciones = $dashboard['evaluaciones'];
$topProductos = $dashboard['top_productos'];
$metricas = $dashboard['metricas_generales'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Sistema de Tiendas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --success: #4cc9f0;
      --warning: #f72585;
      --info: #4895ef;
      --light: #f8f9fa;
      --dark: #212529;
    }

    .card-dashboard {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      margin-bottom: 20px;
      border: none;
    }

    .card-dashboard:hover {
      transform: translateY(-5px);
    }

    .metric-card {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
    }

    .inventory-warning {
      background: linear-gradient(135deg, #ff9a9e, #fad0c4);
      color: white;
    }

    .inventory-critical {
      background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
      color: white;
    }

    .evaluation-card {
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: white;
    }

    .top-products-card {
      background: linear-gradient(135deg, #0ba360, #3cba92);
      color: white;
    }

    .stats-number {
      font-size: 1.8rem;
      font-weight: bold;
    }

    .trend-up {
      color: #28a745;
    }

    .trend-down {
      color: #dc3545;
    }
  </style>
</head>

<body>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
      <div class="text-muted"><?= date('d M Y') ?></div>
    </div>

    <!-- Sección de métricas principales -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card card-dashboard metric-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Ventas Hoy</h5>
                <p class="stats-number">$<?= number_format($ventas['hoy'], 2) ?></p>
              </div>
              <i class="bi bi-currency-dollar fs-1"></i>
            </div>
            <div class="mt-2">
              <small class="d-block">Última venta: <?= date('H:i') ?></small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card card-dashboard metric-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Ventas Semana</h5>
                <p class="stats-number">$<?= number_format($ventas['semana'], 2) ?></p>
              </div>
              <i class="bi bi-graph-up fs-1"></i>
            </div>
            <div class="mt-2">
              <small class="d-block">+15% vs semana anterior</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card card-dashboard inventory-warning">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Bajo Stock</h5>
                <p class="stats-number"><?= $inventario['bajo_stock'] ?></p>
              </div>
              <i class="bi bi-exclamation-triangle fs-1"></i>
            </div>
            <div class="mt-2">
              <small class="d-block">Productos con stock bajo</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card card-dashboard evaluation-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Evaluación</h5>
                <p class="stats-number"><?= $evaluaciones['promedio'] ?>/5</p>
              </div>
              <i class="bi bi-star-fill fs-1"></i>
            </div>
            <div class="mt-2">
              <small class="d-block">Promedio de calificaciones</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección de gráficos y datos detallados -->
    <div class="row">
      <!-- Gráfico de tendencia de ventas -->
      <div class="col-md-8">
        <div class="card card-dashboard">
          <div class="card-header bg-white">
            <h5 class="mb-0">Tendencia de Ventas (Últimos 7 días)</h5>
          </div>
          <div class="card-body">
            <canvas id="ventasChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Top productos y alertas -->
      <div class="col-md-4">
        <div class="card card-dashboard mb-4">
          <div class="card-header bg-white">
            <h5 class="mb-0">Top 5 Productos</h5>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <?php foreach ($topProductos as $index => $producto): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <span class="badge bg-primary me-2"><?= $index + 1 ?></span>
                    <?= $producto['nombre'] ?>
                  </div>
                  <span class="badge bg-success rounded-pill"><?= $producto['total_vendido'] ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <div class="card card-dashboard inventory-critical">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Stock Crítico</h5>
                <p class="stats-number"><?= $inventario['stock_critico'] ?></p>
              </div>
              <i class="bi bi-exclamation-octagon fs-1"></i>
            </div>
            <div class="mt-2">
              <small class="d-block">Productos con stock menor a 5 unidades</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Evaluaciones recientes y métricas globales -->
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card card-dashboard">
          <div class="card-header bg-white">
            <h5 class="mb-0">Evaluaciones Recientes</h5>
          </div>
          <div class="card-body">
            <?php if (empty($evaluaciones['recientes'])): ?>
              <div class="alert alert-info">No hay evaluaciones recientes</div>
            <?php else: ?>
              <div class="list-group">
                <?php foreach ($evaluaciones['recientes'] as $evaluacion): ?>
                  <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                      <h6 class="mb-1"><?= $evaluacion['nombre_usuario'] ?></h6>
                      <small><?= date('d M', strtotime($evaluacion['fecha_Evaluacion'])) ?></small>
                    </div>
                    <div class="mb-1">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= $evaluacion['calificacion'] ? '-fill' : '' ?> text-warning"></i>
                      <?php endfor; ?>
                    </div>
                    <p class="mb-1"><?= $evaluacion['comentario'] ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card card-dashboard">
          <div class="card-header bg-white">
            <h5 class="mb-0">Métricas Globales</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="card bg-light">
                  <div class="card-body text-center">
                    <i class="bi bi-shop fs-1 text-primary"></i>
                    <h5 class="mt-2"><?= $metricas['total_tiendas'] ?></h5>
                    <p class="mb-0">Tiendas</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card bg-light">
                  <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1 text-success"></i>
                    <h5 class="mt-2"><?= $metricas['total_productos'] ?></h5>
                    <p class="mb-0">Productos</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-body text-center">
                    <i class="bi bi-receipt fs-1 text-info"></i>
                    <h5 class="mt-2"><?= $metricas['total_ventas'] ?></h5>
                    <p class="mb-0">Ventas Totales</p>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-warning"></i>
                    <h5 class="mt-2">15</h5>
                    <p class="mb-0">Usuarios</p>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Configuración del gráfico de ventas
    const ventasData = {
      labels: [<?php
                $dates = [];
                foreach ($ventas['tendencia'] as $venta) {
                  $dates[] = "'" . date('d M', strtotime($venta['fecha'])) . "'";
                }
                echo implode(',', $dates);
                ?>],
      datasets: [{
        label: 'Ventas Diarias',
        data: [<?php
                $amounts = [];
                foreach ($ventas['tendencia'] as $venta) {
                  $amounts[] = $venta['total'];
                }
                echo implode(',', $amounts);
                ?>],
        backgroundColor: 'rgba(67, 97, 238, 0.2)',
        borderColor: 'rgba(67, 97, 238, 1)',
        borderWidth: 2,
        tension: 0.4,
        fill: true
      }]
    };

    const ventasConfig = {
      type: 'line',
      data: ventasData,
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false
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