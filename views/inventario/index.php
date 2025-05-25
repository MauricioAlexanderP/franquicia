<?php
$inventario = $this->d['inventario'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inventario</title>
  <?php require_once 'views/header.php'; ?>
</head>

<body>
  <?php
  $this->showMessages();
  ?>
  <!-- Sidebar -->
  <nav class="sidebar">
    <h4><i class="bi bi-shop-window me-2"></i>Mi Tienda</h4>
    <a href="#"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="<?php echo constant('URL'); ?>productosInventario"><i class="bi bi-box2"></i> Productos</a>
    <a href="<?php echo constant('URL'); ?>inventario"><i class="bi bi-clipboard-data"></i> Inventario</a>
    <a href="<?php echo constant('URL'); ?>logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Inventario</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInventario">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Registro
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Producto</th>
                  <th>Imagen</th>
                  <th>Cantidad</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($inventario as $item): ?>
                  <tr>
                    <td><?php echo $item['producto_id'] ?></td>
                    <td><img src="<?php echo constant('URL') . 'public/imgs/' . $item['imagen'] ?>" width="50" height="50" style="object-fit: cover;"></td>
                    <td><?php echo $item['stock'] ?></td>
                    <td>
                      <button class="btn btn-edit me-1" onclick="editarInventario(1, 1, 1, 35, '2025-04-08')">
                        <i class="bi bi-pencil-square"></i> Editar
                      </button>
                      <!-- Formulario Eliminar -->
                      <form action="<?php echo constant('URL'); ?>inventario/deleteProducto" method="POST" class="d-inline">
                        <input type="hidden" name="inventario_id" value="<?php echo $item['inventario_id']; ?>">
                        <button type="button" class="btn btn-delete" onclick="eliminarProducto(<?php echo $item['inventario_id']; ?>, this.form)">
                          <i class="bi bi-trash3-fill"></i> Eliminar
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Inventario -->
  <div class="modal fade" id="modalInventario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo Registro de Inventario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formInventario">
            <input type="hidden" id="inventario_id">
            <div class="mb-3">
              <label class="form-label">Tienda</label>
              <select class="form-select" id="tienda_id" required>
                <option value="">Selecciona una tienda</option>
                <option value="1">Tienda Centro</option>
                <option value="2">Tienda Norte</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Producto</label>
              <select class="form-select" id="producto_id" required>
                <option value="">Selecciona un producto</option>
                <option value="1">Smartphone Galaxy A14</option>
                <option value="2">Laptop HP 250</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Cantidad</label>
              <input type="number" class="form-control" id="cantidad" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Última Actualización</label>
              <input type="date" class="form-control" id="last_updated" required>
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
    function editarInventario(id, tienda_id, producto_id, cantidad, fecha) {
      document.getElementById('inventario_id').value = id;
      document.getElementById('tienda_id').value = tienda_id;
      document.getElementById('producto_id').value = producto_id;
      document.getElementById('cantidad').value = cantidad;
      document.getElementById('last_updated').value = fecha;
      document.querySelector('#modalInventario .modal-title').textContent = 'Editar Registro de Inventario';
      new bootstrap.Modal(document.getElementById('modalInventario')).show();
    }

    function eliminarProducto(id, form) {
      console.log(id);
      event.preventDefault();
      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo'
      }).then((result) => {
        if (result.isConfirmed) {
          // Si se confirma, envía el formulario para llamar a deleteTienda del controller
          form.submit();
        }
      });
    }
  </script>
</body>

</html>