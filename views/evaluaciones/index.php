<?php
$evaluaciones = $this->d['evaluaciones'] ?? [];
$promedio = $this->d['promedio'] ?? 0;
$success = $this->d['success'] ?? '';
$error = $this->d['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Evaluaciones de Tienda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .rating-stars {
      color: #ffc107;
    }

    .evaluation-card {
      border-left: 4px solid #4361ee;
      transition: transform 0.2s;
    }

    .evaluation-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .average-rating {
      font-size: 2.5rem;
      font-weight: bold;
      color: #4361ee;
    }

    .param-rating {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
    }

    .param-bar {
      height: 8px;
      background-color: #e9ecef;
      border-radius: 4px;
      margin-top: 5px;
    }

    .param-fill {
      height: 100%;
      background-color: #4361ee;
      border-radius: 4px;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0"><i class="bi bi-clipboard-check me-2"></i>Evaluaciones</h1>
      <a href="<?= constant('URL') ?>evaluaciones/nuevaEvaluacion" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nueva Evaluación
      </a>
    </div>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
      <div class="card-body text-center">
        <h5 class="card-title">Calificación Promedio</h5>
        <div class="average-rating"><?= $promedio ?></div>
        <div class="rating-stars mb-3">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <i class="bi bi-star<?= $i <= round($promedio) ? '-fill' : '' ?> fs-4"></i>
          <?php endfor; ?>
        </div>
        <p class="mb-0">Basado en <?= count($evaluaciones) ?> evaluaciones</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card shadow">
          <div class="card-header bg-light">
            <h6 class="mb-0">Detalle por Parámetros</h6>
          </div>
          <div class="card-body">
            <?php if (!empty($evaluaciones)): ?>
              <?php
              // Calcular promedios por parámetro
              $parametros = ['instalaciones', 'servicio', 'productos', 'limpieza', 'atencion'];
              $totales = array_fill_keys($parametros, 0);

              foreach ($evaluaciones as $e) {
                foreach ($parametros as $param) {
                  $totales[$param] += $e[$param];
                }
              }

              foreach ($parametros as $param) {
                $promedio = count($evaluaciones) ? $totales[$param] / count($evaluaciones) : 0;
              ?>
                <div class="param-rating">
                  <div>
                    <span class="text-capitalize"><?= $param ?></span>
                    <div class="rating-stars">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= round($promedio) ? '-fill' : '' ?>"></i>
                      <?php endfor; ?>
                    </div>
                  </div>
                  <div class="fw-bold"><?= number_format($promedio, 1) ?></div>
                </div>
                <div class="param-bar">
                  <div class="param-fill" style="width: <?= ($promedio / 5) * 100 ?>%"></div>
                </div>
              <?php } ?>
            <?php else: ?>
              <div class="text-center text-muted py-3">
                No hay datos suficientes para mostrar estadísticas
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <h5 class="mb-3">Evaluaciones Recientes</h5>

        <?php if (empty($evaluaciones)): ?>
          <div class="card shadow">
            <div class="card-body text-center py-5">
              <i class="bi bi-clipboard-x fs-1 text-muted mb-3"></i>
              <h5>No hay evaluaciones registradas</h5>
              <p class="mb-0">Comienza agregando tu primera evaluación</p>
            </div>
          </div>
        <?php else: ?>
          <?php foreach ($evaluaciones as $evaluacion): ?>
            <div class="card shadow-sm mb-3 evaluation-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0"><?= $evaluacion['nombre_usuario'] ?></h6>
                  <small class="text-muted"><?= date('d/m/Y', strtotime($evaluacion['fecha_Evaluacion'])) ?></small>
                </div>

                <div class="rating-stars mb-2">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="bi bi-star<?= $i <= $evaluacion['calificacion'] ? '-fill' : '' ?>"></i>
                  <?php endfor; ?>
                  <span class="ms-2 fw-bold"><?= number_format($evaluacion['calificacion'], 1) ?></span>
                </div>

                <p class="mb-0"><?= $evaluacion['comentario'] ?></p>

                <div class="mt-3 small text-muted">
                  Tienda: <?= $evaluacion['nombre_tienda'] ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>

</html>