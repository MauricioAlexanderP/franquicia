<?php
$tiendas = $this->d['data'];
$tipoTienda = $this->d['tipoTienda'];

// error_log("TIENDAS::index -> " . print_r($tipoTienda, true));
// error_log("TIENDAS::index -> " . print_r($tiendas, true));
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tiendas Registradas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="assets/css/tienda.css">
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
  <!-- TABLA DE DATOS -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0">🏬 Registro de Tiendas</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTienda">
            <i class="bi bi-plus-circle me-1"></i> Nueva Tienda
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Tipo</th>
                  <th>Ubicación</th>
                  <th>Encargado</th>
                  <th>Teléfono</th>
                  <th>Entrada</th>
                  <th>Salida</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($tiendas as $tienda): ?>
                  <tr>
                    <td><?php echo $tienda['tienda_id']; ?></td>
                    <td><?php echo $tienda['tipo_tienda_id']; ?></td>
                    <td><?php echo $tienda['ubicacion']; ?></td>
                    <td><?php echo $tienda['encargado']; ?></td>
                    <td><?php echo $tienda['telefono']; ?></td>
                    <td><?php echo $tienda['hora_entrada']; ?></td>
                    <td><?php echo $tienda['hora_salida']; ?></td>
                    <td>
                      <!-- Formulario Editar -->
                      <form action="<?php echo constant('URL'); ?>tienda/editar" method="POST" class="d-inline">
                        <input type="hidden" name="tienda_id" value="<?php echo $tienda['tienda_id']; ?>">
                        <button type="button" class="btn btn-edit me-1" onclick="editarTienda(<?php echo $tienda['tienda_id']; ?>)">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                      </form>
                      <!-- Formulario Eliminar -->
                      <form action="<?php echo constant('URL'); ?>tienda/deleteTienda" method="POST" class="d-inline">
                        <input type="hidden" name="tienda_id" value="<?php echo $tienda['tienda_id']; ?>">
                        <button type="button" class="btn btn-delete" onclick="eliminarTienda(<?php echo $tienda['tienda_id']; ?>, this.form)">
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
  <div class="modal fade" id="modalTienda" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Tienda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>tienda/newTienda" method="POST" id="formTienda">
            <input type="hidden" id="tienda_id" name="tienda_id" value="">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Tipo de Tienda</label>
                <select class="form-select" name="tipo_tienda_id" required>
                  <option selected disabled value="">Seleccione</option>
                  <?php foreach ($tipoTienda as $tipo): ?>
                    <option value="<?php echo $tipo['tipo_tienda_id']; ?>"><?php echo $tipo['tipo']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Encargado</label>
                <input type="text" class="form-control" name="encargado" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" name="ubicacion" required>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label">Hora de Entrada</label>
                <input type="time" class="form-control" name="hora_entrada" required>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label">Hora de Salida</label>
                <input type="time" class="form-control" name="hora_salida" required>
              </div>
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
  <div class="modal fade" id="modalTiendaEditar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Editar Tienda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>tienda/updateTienda" method="POST" id="formTiendaEditar">
            <input type="hidden" id="tienda_id" name="tienda_id" value="">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Tipo de Tienda</label>
                <select class="form-select" name="tipo_tienda_id" required>
                  <option selected disabled value="">Seleccione</option>
                  <?php foreach ($tipoTienda as $tipo): ?>
                    <option value="<?php echo $tipo['tipo_tienda_id']; ?>"><?php echo $tipo['tipo']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Encargado</label>
                <input type="text" class="form-control" name="encargado" value="" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" name="ubicacion" required>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label">Hora de Entrada</label>
                <input type="time" class="form-control" name="hora_entrada" required>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label">Hora de Salida</label>
                <input type="time" class="form-control" name="hora_salida" required>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
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
    function editarTienda(id) {
      fetch('<?php echo constant("URL") ?>tienda/getTiendaById', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'tienda_id=' + id
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
          } else {
            // Rellena los inputs del formulario de edición con los datos recibidos
            document.querySelector('#formTiendaEditar input[name="tienda_id"]').value = data.tienda_id;
            document.querySelector('#formTiendaEditar select[name="tipo_tienda_id"]').value = data.tipo_tienda_id;
            document.querySelector('#formTiendaEditar input[name="encargado"]').value = data.encargado;
            document.querySelector('#formTiendaEditar input[name="telefono"]').value = data.telefono;
            document.querySelector('#formTiendaEditar input[name="ubicacion"]').value = data.ubicacion;
            document.querySelector('#formTiendaEditar input[name="hora_entrada"]').value = data.hora_entrada;
            document.querySelector('#formTiendaEditar input[name="hora_salida"]').value = data.hora_salida;

            new bootstrap.Modal(document.getElementById('modalTiendaEditar')).show();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
        });
    }

    function eliminarTienda(id, form) {
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
      })
    }
  </script>
</body>

</html>