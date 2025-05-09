<?php
$tipoProducto = $this->d['tipoProducto'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tipos de Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/tipoProducto.css">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php
  $this->showMessages();
  ?>
  <!-- Sidebar -->
  <nav class="sidebar">
    <h4><i class="bi bi-shop-window me-2"></i>Mi Tienda</h4>
    <a href="<?php echo constant('URL'); ?>"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="<?php echo constant('URL'); ?>tipoTienda"><i class="bi bi-tags-fill"></i> Tipos de Tienda</a>
    <a href="<?php echo constant('URL'); ?>tienda"><i class="bi bi-building"></i> Tiendas</a>
    <a href="<?php echo constant('URL'); ?>tipoProducto"><i class="bi bi-box"></i> Tipos de Producto</a>
    <a href="<?php echo constant('URL'); ?>producto"><i class="bi bi-box2"></i> Productos</a>
    <a href="<?php echo constant('URL'); ?>roles"><i class="bi bi-person-gear"></i> Roles</a>
    <a href="<?php echo constant('URL'); ?>usuarios"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="<?php echo constant('URL'); ?>inventario"><i class="bi bi-clipboard-data"></i> Inventario</a>
    <a href="<?php echo constant('URL'); ?>ventas"><i class="bi bi-receipt-cutoff"></i> Ventas</a>
    <a href="<?php echo constant('URL'); ?>logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0">📦 Tipos de Producto</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTipoProducto">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Catálogo</th>
                  <th>Descripción</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Fila de ejemplo -->
                <?php foreach ($tipoProducto as $tipo): ?>
                  <tr>
                    <td><?php echo $tipo['tipo_producto_id']; ?></td>
                    <td><?php echo $tipo['catalogo']; ?></td>
                    <td><?php echo $tipo['descripcion']; ?></td>
                    <td>
                      <!-- Formulario Editar -->
                      <form action="<?php echo constant('URL'); ?>tipoProducto/editar" method="POST" class="d-inline">
                        <input type="hidden" name="tipo_producto_id" value="<?php echo $tipo['tipo_producto_id']; ?>">
                        <button type="button" class="btn btn-edit me-1" onclick="editarTipoProducto(<?php echo $tipo['tipo_producto_id']; ?>)">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                      </form>
                      <!-- Formulario Eliminar -->
                      <form action="<?php echo constant('URL'); ?>tipoProducto/deleteTipoProducto" method="POST" class="d-inline">
                        <input type="hidden" name="tipo_producto_id" value="<?php echo $tipo['tipo_producto_id']; ?>">
                        <button type="button" class="btn btn-delete" onclick="eliminarTipoProducto(<?php echo $tipo['tipo_producto_id']; ?>, this.form)">
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

  <!-- Modal -->
  <!-- FORMULARIO PARA AGREGAR -->
  <div class="modal fade" id="modalTipoProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Tipo de Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="<?php echo constant('URL'); ?>tipoProducto/newTipoProducto" id="formTipoProducto">
            <input type="hidden" id="tipo_Producto_id" name="tipo_producto_id">
            <div class="mb-3">
              <label class="form-label">Catálogo</label>
              <input type="text" class="form-control" id="catalogo" name="catalogo" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
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

  <!-- FORMULARIO PARA EDITAR -->
  <div class="modal fade" id="modalTipoProductoEditar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Editar Tipo de Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="<?php echo constant('URL'); ?>tipoProducto/updateTipoProducto" id="formTipoProductoEditar">
            <input type="hidden" id="tipo_Producto_id" name="tipo_producto_id">
            <div class="mb-3">
              <label class="form-label">Catálogo</label>
              <input type="text" class="form-control" id="catalogo" name="catalogo" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
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
    function editarTipoProducto(id) {
      fetch('<?php echo constant('URL'); ?>tipoProducto/getTipoProductoById', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'tipo_producto_id=' + id
      })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          Swal.fire('Error', data.error, 'error');
        } else {
          console.log(data);
          document.querySelector('#formTipoProductoEditar input[name="tipo_producto_id"]').value = data.tipo_producto_id;
          document.querySelector('#formTipoProductoEditar input[name="catalogo"]').value = data.catalogo;
          document.querySelector('#formTipoProductoEditar textarea[name="descripcion"]').value = data.descripcion;
          new bootstrap.Modal(document.getElementById('modalTipoProductoEditar')).show();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Ocurrió un error al obtener los datos.', 'error');
      });
    }

    function eliminarTipoProducto(id, form) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás recuperar este tipo de producto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    }
  </script>
</body>

</html>