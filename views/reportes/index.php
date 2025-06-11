<?php
$tiendas = $this->d['tiendas'] ?? [];
$tienda_id = $this->d['tienda_id'] ?? null;
$error = $this->d['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes - Sistema de Tiendas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .report-form {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <h1 class="mb-4"><i class="bi bi-file-earmark-bar-graph me-2"></i>Generador de Reportes</h1>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="report-form">
          <form action="<?= constant('URL') ?>reportes/generarReporteVentas" method="POST">
            <div class="row mb-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Tipo de Reporte</label>
                  <select class="form-select" name="tipo_reporte" required>
                    <option value="ventas_detalladas">Ventas Detalladas</option>
                    <option value="ventas_resumen">Resumen de Ventas</option>
                    <option value="productos_mas_vendidos">Productos Más Vendidos</option>
                    <option value="evaluaciones">Evaluaciones de Clientes</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Tienda</label>
                  <select class="form-select" name="tienda_id">
                    <option value="">Todas las tiendas</option>
                    <?php foreach ($tiendas as $tienda): ?>
                      <option value="<?= $tienda->getTiendaId() ?>"
                        <?= $tienda_id == $tienda->getTiendaId() ? 'selected' : '' ?>>
                        <?= $tienda->getNombreTienda() ?> - <?= $tienda->getUbicacion() ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin" required>
                </div>
              </div>
            </div>

            <div class="d-grid">
              <div class="d-grid gap-2">
                <!-- Botón principal para generar el reporte -->
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="bi bi-file-earmark-arrow-down me-2"></i> Generar Reporte
                </button>
                <a href="<?= constant('URL') ?>tienda"
                  class="btn btn-outline-secondary btn-lg">
                  <i class="bi bi-arrow-left me-2"></i> Regresar al inicio
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>