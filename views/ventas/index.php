<?php
$ventas = $this->d['ventas'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ventas</title>
  <?php require_once 'views/header.php'; ?>
</head>

<body>
  <?php
  $this->showMessages();
  ?>
  <!-- Sidebar -->
  <nav class="sidebar">
    <h4><i class="bi bi-shop-window me-2"></i>Mi Tienda</h4>
    <a href="<?php echo constant('URL'); ?>ventas"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="<?php echo constant('URL'); ?>perfil"><i class="bi bi-person-fill"></i> Perfil</a>
    <a href="<?php echo constant('URL'); ?>ventas"><i class="bi bi-receipt-cutoff"></i> Ventas</a>
    <a href="<?php echo constant('URL'); ?>registrarVenta"><i class="bi bi-cart2"></i> Registrar Venta</a>
    <a href="<?php echo constant('URL'); ?>logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Registro de Ventas</h5>
          <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVenta">
            <i class="bi bi-plus-circle me-1"></i> Nueva Venta
          </button> -->
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Fecha</th>
                  <th>Monto Total</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ventas as $venta): ?>
                  <tr>
                    <td><?php echo $venta->getVentaId(); ?></td>
                    <td><?php echo $venta->getFechaVenta(); ?></td>
                    <td><?php echo '$ ' . number_format($venta->getMontoTotal(), 2); ?></td>
                    <td>
                      <!-- <button class="btn btn-edit me-1" onclick="editarVenta(<?php echo $venta->getVentaId(); ?>, <?php echo $venta->getTiendaId(); ?>, '<?php echo $venta->getFechaVenta(); ?>', <?php echo $venta->getMontoTotal(); ?>, 'Efectivo')">
                        <i class="bi bi-pencil-square"></i> Editar
                      </button> -->
                      <!-- <button class="btn btn-delete" onclick="eliminarVenta(<?php echo $venta->getVentaId(); ?>)">
                        <i class="bi bi-trash3-fill"></i> Eliminar
                      </button>
                    </td> -->
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Venta -->
  <div class="modal fade" id="modalVenta" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Nueva Venta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formVenta">
            <input type="hidden" id="sale_id">
            <div class="mb-3">
              <label class="form-label">Tienda</label>
              <select class="form-select" id="store_id" required>
                <option value="">Selecciona una tienda</option>
                <option value="1">Tienda Centro</option>
                <option value="2">Tienda Norte</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Fecha de Venta</label>
              <input type="datetime-local" class="form-control" id="sale_date" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Monto Total</label>
              <input type="number" step="0.01" class="form-control" id="total_amount" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Método de Pago</label>
              <select class="form-select" id="payment_method" required>
                <option value="">Selecciona método</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
              </select>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save2 me-1"></i> Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function editarVenta(id, store_id, date, amount, method) {
      document.getElementById('sale_id').value = id;
      document.getElementById('store_id').value = store_id;
      document.getElementById('sale_date').value = date;
      document.getElementById('total_amount').value = amount;
      document.getElementById('payment_method').value = method;
      document.querySelector('#modalVenta .modal-title').textContent = 'Editar Venta';
      new bootstrap.Modal(document.getElementById('modalVenta')).show();
    }

    function eliminarVenta(id) {
      if (confirm('¿Deseas eliminar esta venta?')) {
        alert('Venta con ID ' + id + ' eliminada (simulado)');
      }
    }

    document.getElementById('formVenta').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Venta guardada (simulado)');
      new bootstrap.Modal(document.getElementById('modalVenta')).hide();
    });
  </script>
</body>

</html>