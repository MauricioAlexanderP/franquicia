<?php
$usuarios = $this->d['usuarios'];
$roles = $this->d['roles'];
$tiendas = $this->d['tiendas'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Usuarios</title>
  <?php require_once 'views/header.php'; ?>
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
    <!-- <a href="<?php echo constant('URL'); ?>inventario"><i class="bi bi-clipboard-data"></i> Inventario</a>
    <a href="<?php echo constant('URL'); ?>ventas"><i class="bi bi-receipt-cutoff"></i> Ventas</a> -->
    <a href="<?php echo constant('URL'); ?>logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Usuario</th>
                  <th>Email</th>
                  <td>Telefono</td>
                  <th>Tienda</th>
                  <th>Rol</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                  <tr>
                    <td><?php echo $usuario['usuario_id']; ?></td>
                    <td><?php echo $usuario['nombre_usuario']; ?></td>
                    <td><?php echo $usuario['correo']; ?></td>
                    <td><?php echo $usuario['telefono']; ?></td>
                    <td><?php echo $usuario['tienda_id']; ?></td>
                    <td><?php echo $usuario['rol_id']; ?></td>
                    <td>
                      <!-- Formulario Editar -->
                      <form action="<?php echo constant('URL'); ?>usuario/editar" method="POST" class="d-inline">
                        <input type="hidden" name="usuario_id" value="<?php echo $usuario['usuario_id']; ?>">
                        <button type="button" class="btn btn-edit me-1" onclick="editarUsuario(<?php echo $usuario['usuario_id']; ?>)">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                      </form>
                      <!-- Formulario Eliminar -->
                      <form action="<?php echo constant('URL'); ?>usuarios/deleteUsuario" method="POST" class="d-inline">
                        <input type="hidden" name="usuario_id" value="<?php echo $usuario['usuario_id']; ?>">
                        <button type="button" class="btn btn-delete" onclick="eliminarUsuario(<?php echo $usuario['usuario_id']; ?>, this.form)">
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

  <!-- Modal Usuario -->
  <!-- FORMULARIO PARA GUARDAR -->
  <div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>usuarios/newUsuario" method="POST" id="formUsuario">
            <input type="hidden" name="user_id">
            <div class="mb-3">
              <label class="form-label">Usuario</label>
              <input type="text" class="form-control" name="nombre_usuario" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" name="correo" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Teléfono</label>
              <input type="tel" class="form-control" name="telefono" id="telefono" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tienda</label>
              <select class="form-select" name="tienda_id" required>
                <option value="">Selecciona una tienda</option>
                <?php foreach ($tiendas as $tienda): ?>
                  <option value="<?php echo $tienda['tienda_id']; ?>"><?php echo $tienda['nombre']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label">Rol</label>
              <select class="form-select" name="rol_id" required>
                <option value="">Selecciona un rol</option>
                <?php foreach ($roles as $rol): ?>
                  <option value="<?php echo $rol['rol_id']; ?>"><?php echo $rol['nombre_rol']; ?></option>
                <?php endforeach; ?>
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

  <!-- FORMULARIO PARA EDITAR -->
  <div class="modal fade" id="modalUsuarioEditar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Editar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>usuarios/updateUsuario" method="POST" id="formUsuarioEditar">
            <input type="hidden" name="usuario_id">
            <div class="mb-3">
              <label class="form-label">Usuario</label>
              <input type="text" class="form-control" name="nombre_usuario" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" name="correo" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="telefono" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tienda</label>
              <select class="form-select" name="tienda_id" required>
                <option value="">Selecciona una tienda</option>
                <?php foreach ($tiendas as $tienda): ?>
                  <option value="<?php echo $tienda['tienda_id']; ?>"><?php echo $tienda['nombre']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label">Rol</label>
              <select class="form-select" name="rol_id" required>
                <option value="">Selecciona un rol</option>
                <?php foreach ($roles as $rol): ?>
                  <option value="<?php echo $rol['rol_id']; ?>"><?php echo $rol['nombre_rol']; ?></option>
                <?php endforeach; ?>
              </select>
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
    function editarUsuario(id) {
      fetch('<?php echo constant("URL") ?>usuarios/getUsuarioById', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'usuario_id=' + id
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
          } else {
            // Llenar el formulario con los datos del usuario
            document.querySelector('#formUsuarioEditar input[name="usuario_id"]').value = data.usuario_id;
            document.querySelector('#formUsuarioEditar input[name="nombre_usuario"]').value = data.nombre_usuario;
            document.querySelector('#formUsuarioEditar input[name="correo"]').value = data.correo;
            document.querySelector('#formUsuarioEditar input[name="telefono"]').value = data.telefono;
            document.querySelector('#formUsuarioEditar select[name="tienda_id"]').value = data.tienda_id;
            document.querySelector('#formUsuarioEditar select[name="rol_id"]').value = data.rol_id;

            // Mostrar el modal
            var myModal = new bootstrap.Modal(document.getElementById('modalUsuarioEditar'));
            myModal.show();
          }
        })
    }

    function eliminarUsuario(id, form) {
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

    document.getElementById('telefono').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 4) {
        value = value.slice(0, 4) + '-' + value.slice(4);
      }
      e.target.value = value.slice(0, 9);
    });
  </script>
</body>

</html>