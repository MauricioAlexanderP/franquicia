<?php
$tiendas = $this->d['tiendas'] ?? [];
$error = $this->d['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Evaluación</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .rating-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .rating-category {
      text-align: center;
      flex: 1;
      padding: 10px;
    }

    .rating-stars {
      display: flex;
      justify-content: center;
      gap: 5px;
    }

    .star {
      font-size: 1.5rem;
      color: #ddd;
      cursor: pointer;
      transition: color 0.2s;
    }

    .star.selected {
      color: #ffc107;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Evaluar Tienda</h3>
          </div>
          <div class="card-body">
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form action="<?= constant('URL') ?>evaluaciones/save" method="POST">
              <div class="mb-3">
                <label class="form-label fw-bold">Tienda a evaluar</label>
                <select class="form-select" name="tienda_id" required>
                  <option value="">Seleccionar tienda</option>
                  <?php foreach ($tiendas as $tienda): ?>
                    <option value="<?= $tienda->getTiendaId() ?>">
                      <?= $tienda->getNombreTienda() ?> - <?= $tienda->getUbicacion() ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="rating-container">
                <!-- Instalaciones -->
                <div class="rating-category">
                  <label class="form-label fw-bold">Instalaciones</label>
                  <div class="rating-stars" data-category="instalaciones">
                    <i class="bi bi-star star" data-value="1"></i>
                    <i class="bi bi-star star" data-value="2"></i>
                    <i class="bi bi-star star" data-value="3"></i>
                    <i class="bi bi-star star" data-value="4"></i>
                    <i class="bi bi-star star" data-value="5"></i>
                  </div>
                  <input type="hidden" name="instalaciones" value="0" required>
                </div>

                <!-- Servicio -->
                <div class="rating-category">
                  <label class="form-label fw-bold">Servicio</label>
                  <div class="rating-stars" data-category="servicio">
                    <i class="bi bi-star star" data-value="1"></i>
                    <i class="bi bi-star star" data-value="2"></i>
                    <i class="bi bi-star star" data-value="3"></i>
                    <i class="bi bi-star star" data-value="4"></i>
                    <i class="bi bi-star star" data-value="5"></i>
                  </div>
                  <input type="hidden" name="servicio" value="0" required>
                </div>

                <!-- Productos -->
                <div class="rating-category">
                  <label class="form-label fw-bold">Productos</label>
                  <div class="rating-stars" data-category="productos">
                    <i class="bi bi-star star" data-value="1"></i>
                    <i class="bi bi-star star" data-value="2"></i>
                    <i class="bi bi-star star" data-value="3"></i>
                    <i class="bi bi-star star" data-value="4"></i>
                    <i class="bi bi-star star" data-value="5"></i>
                  </div>
                  <input type="hidden" name="productos" value="0" required>
                </div>
              </div>

              <div class="rating-container">
                <!-- Limpieza -->
                <div class="rating-category">
                  <label class="form-label fw-bold">Limpieza</label>
                  <div class="rating-stars" data-category="limpieza">
                    <i class="bi bi-star star" data-value="1"></i>
                    <i class="bi bi-star star" data-value="2"></i>
                    <i class="bi bi-star star" data-value="3"></i>
                    <i class="bi bi-star star" data-value="4"></i>
                    <i class="bi bi-star star" data-value="5"></i>
                  </div>
                  <input type="hidden" name="limpieza" value="0" required>
                </div>

                <!-- Atención -->
                <div class="rating-category">
                  <label class="form-label fw-bold">Atención</label>
                  <div class="rating-stars" data-category="atencion">
                    <i class="bi bi-star star" data-value="1"></i>
                    <i class="bi bi-star star" data-value="2"></i>
                    <i class="bi bi-star star" data-value="3"></i>
                    <i class="bi bi-star star" data-value="4"></i>
                    <i class="bi bi-star star" data-value="5"></i>
                  </div>
                  <input type="hidden" name="atencion" value="0" required>
                </div>

                <!-- Espacio vacío para mantener estructura -->
                <div class="rating-category"></div>
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold">Comentario General</label>
                <textarea class="form-control" name="comentario" rows="4"
                  placeholder="Describe tu experiencia en la tienda..." required></textarea>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="bi bi-send-check me-2"></i>Enviar Evaluación
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sistema de calificación con estrellas
    document.querySelectorAll('.rating-stars').forEach(starsContainer => {
      const category = starsContainer.dataset.category;
      const stars = starsContainer.querySelectorAll('.star');
      const hiddenInput = document.querySelector(`input[name="${category}"]`);

      stars.forEach(star => {
        star.addEventListener('click', () => {
          const value = parseInt(star.dataset.value);

          // Actualizar estrellas seleccionadas
          stars.forEach((s, index) => {
            if (index < value) {
              s.classList.add('selected');
            } else {
              s.classList.remove('selected');
            }
          });

          // Actualizar valor oculto
          hiddenInput.value = value;
        });
      });
    });
  </script>
</body>

</html>