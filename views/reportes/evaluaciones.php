<?php
$reporte = $this->d['reporte'];
$fechaInicio = $this->d['fechaInicio'];
$fechaFin = $this->d['fechaFin'];
$tiendaId = $this->d['tiendaId'];
$tipoReporte = $this->d['tipoReporte'];
error_log("reporte: " . print_r($reporte, true));
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Evaluaciones de Clientes</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .report-header {
      background-color: #f72585;
      color: white;
      padding: 20px;
      border-radius: 10px 10px 0 0;
    }

    .rating {
      color: #FFD700;
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
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="report-container">
      <div class="report-header">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="report-title">Evaluaciones de Clientes</h1>
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
          <h4>Evaluaciones de Clientes</h4>
          <p>Fecha: <?= date('d M Y H:i') ?></p>
          <p>Periodo: <?= date('d M Y', strtotime($fechaInicio)) ?> - <?= date('d M Y', strtotime($fechaFin)) ?></p>
        </div>

        <?php if (empty($reporte)): ?>
          <div class="alert alert-info">No hay evaluaciones en el rango de fechas seleccionado</div>
        <?php else: ?>
          <div class="row">
            <div class="col-md-4 mb-4">
              <div class="card bg-light">
                <div class="card-body text-center">
                  <h2><?= number_format($this->d['promedio'], 1) ?>/5</h2>
                  <div class="rating fs-4">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <i class="bi bi-star<?= $i <= round($this->d['promedio']) ? '-fill' : '' ?>"></i>
                    <?php endfor; ?>
                  </div>
                  <p class="mb-0">Calificación Promedio</p>
                </div>
              </div>
            </div>

            <div class="col-md-8">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Tienda</th>
                      <th>Calificación</th>
                      <th>Comentario</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($reporte as $evaluacion): ?>
                      <tr>
                        <td><?= date('d/m/Y', strtotime($evaluacion['fecha_Evaluacion'])) ?></td>
                        <td><?= $evaluacion['nombre_tienda'] ?></td>
                        <td>
                          <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                              <i class="bi bi-star<?= $i <= $evaluacion['calificacion'] ? '-fill' : '' ?>"></i>
                            <?php endfor; ?>
                          </div>
                        </td>
                        <td><?= $evaluacion['comentario'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>

</html>