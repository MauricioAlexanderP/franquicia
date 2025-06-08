<?php
$inventario = $this->d['inventario'];
$mostrar_notificacion_global = false;
if (isset($_SESSION['notificacion_global']) && $_SESSION['notificacion_global']['mostrar_a_usuarios']) {
  $notificacion_global = $_SESSION['notificacion_global'];
  $mostrar_notificacion_global = true;
  unset($_SESSION['notificacion_global']);
}
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
  <!-- Después de cargar SweetAlert2 -->
  <?php if ($mostrar_notificacion_global): ?>
    <script>
      Swal.fire({
        title: "<?= $notificacion_global['mensaje'] ?>",
        html: "<?= $notificacion_global['detalle'] ?>",
        icon: "<?= $notificacion_global['tipo'] ?>",
        timer: 7000,
        timerProgressBar: true,
        showConfirmButton: true,
        confirmButtonText: "Entendido"
      });
    </script>
  <?php endif; ?>

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
                      <!-- Formulario Editar -->
                      <form method="POST" class="d-inline">
                        <input type="hidden" name="tienda_id" value="<?php echo $item['tienda_id']; ?>">
                        <input type="hidden" name="producto_id" value="<?php echo $item['producto_id']; ?>">
                        <!-- <input type="hidden" name="inventario_id" value="<?php echo $item['inventario_id']; ?>"> -->
                        <button type="button" class="btn btn-edit me-1" onclick="editarInventario(<?php echo $item['tienda_id']; ?>, <?php echo $item['nombre']; ?>)">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                      </form>
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
  <!-- Modal Editar Inventario -->
  <div class="modal fade" id="modalInventario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Editar Stock</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>inventario/updateStock" method="POST" id="formEditarStock">
            <input type="hidden" name="inventario_id" id="inventario_id">
            <input type="hidden" name="tienda_id" id="tienda_id">
            <input type="hidden" name="producto_id" id="producto_id">
            <div class="mb-3">
              <label class="form-label">Nombre del Producto</label>
              <input type="text" class="form-control" id="nombre_producto" readonly>
            </div>
            <div class="mb-3 text-center">
              <img id="imagen_producto" src="" alt="Imagen del producto" class="img-thumbnail" style="max-height: 150px;">
            </div>
            <div class="mb-3">
              <label class="form-label">Stock</label>
              <input type="number" class="form-control" name="stock" id="cantidad" min="0" required>
            </div>
            <div class="mb-3 d-none">
              <input type="text" class="form-control" id="last_updated" readonly>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save me-1"></i> Guardar Cambios
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
    function editarInventario(tienda_id, producto_id) {
      console.log('Editar Inventario:', tienda_id, producto_id);
      fetch('<?php echo constant("URL") ?>inventario/getInventarioById', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'tienda_id=' + tienda_id + '&producto_id=' + producto_id
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
          } else {
            // Rellena los inputs ocultos
            document.getElementById('inventario_id').value = data.inventario_id;
            document.getElementById('tienda_id').value = data.tienda_id;
            document.getElementById('producto_id').value = data.producto_id;

            // Solo editable
            document.getElementById('cantidad').value = data.stock;

            // Información visual (no editable)
            document.getElementById('nombre_producto').value = data.nombre;
            document.getElementById('imagen_producto').src = '<?php echo constant("URL"); ?>public/imgs/' + data.imagen;

            // (opcional) Última actualización
            document.getElementById('last_updated').value = data.ultima_actualizacion;

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalInventario'));
            modal.show();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
        });
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
          form.submit();
        }
      });
    }
  </script>
</body>

</html>