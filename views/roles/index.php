<?php
$roles = $this->d['roles'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Roles</title>
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
    <a href="<?php echo constant('URL'); ?>perfil"><i class="bi bi-person-fill"></i> Perfil</a>
    <a href="<?php echo constant('URL'); ?>tipoTienda"><i class="bi bi-tags-fill"></i> Tipos de Tienda</a>
    <a href="<?php echo constant('URL'); ?>tienda"><i class="bi bi-building"></i> Tiendas</a>
    <a href="<?php echo constant('URL'); ?>tipoProducto"><i class="bi bi-box"></i> Tipos de Producto</a>
    <a href="<?php echo constant('URL'); ?>producto"><i class="bi bi-box2"></i> Productos</a>
    <a href="<?php echo constant('URL'); ?>roles"><i class="bi bi-person-gear"></i> Roles</a>
    <a href="<?php echo constant('URL'); ?>usuarios"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="<?php echo constant('URL'); ?>dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <!-- <a href="<?php echo constant('URL'); ?>inventario"><i class="bi bi-clipboard-data"></i> Inventario</a>
    <a href="<?php echo constant('URL'); ?>ventas"><i class="bi bi-receipt-cutoff"></i> Ventas</a> -->
    <a href="<?php echo constant('URL'); ?>logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>Roles de Usuario</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRole">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Rol
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Nombre del Rol</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($roles as $rol): ?>
                  <tr>
                    <td><?php echo $rol['role_id'] ?></td>
                    <td><?php echo $rol['nombre_rol'] ?></td>
                    <td>
                      <!-- Formulario Editar -->
                      <form method="POST" class="d-inline">
                        <input type="hidden" name="role_id" value="<?php echo $rol['role_id']; ?>">
                        <button type="button" class="btn btn-edit me-1" onclick="editarRol(<?php echo $rol['role_id']; ?>)">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                      </form>
                      <!-- Formulario Eliminar -->
                      <form action="<?php echo constant('URL'); ?>roles/deleteRol" method="POST" class="d-inline">
                        <input type="hidden" name="role_id" value="<?php echo $rol['role_id']; ?>">
                        <button type="button" class="btn btn-delete" onclick="eliminarRol(<?php echo $rol['role_id']; ?>, this.form)">
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

  <!-- Modal Role -->
  <!-- FORMULARIO GUARDAR -->
  <div class="modal fade" id="modalRole" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Rol</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>roles/newRol" method="POST" id="formRole">
            <input type="hidden" name="role_id">
            <div class="mb-4">
              <label class="form-label">Nombre del Rol</label>
              <input type="text" class="form-control" name="role_name" required>
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


  <!-- FORMULARIO EDITAR -->
  <div class="modal fade" id="modalRoleEditar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Editar Rol</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>roles/updateRol" method="POST" id="formRoleEditar">
            <input type="hidden" name="role_id">
            <div class="mb-4">
              <label class="form-label">Nombre del Rol</label>
              <input type="text" class="form-control" name="role_name" required>
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
    function editarRol(id) {
      fetch('<?php echo constant('URL'); ?>roles/getRolById', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'role_id=' + id
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
          } else {
            document.querySelector('#formRoleEditar input[name="role_id"]').value = data.role_id;
            document.querySelector('#formRoleEditar input[name="role_name"]').value = data.nombre_rol;
            const modalEditar = new bootstrap.Modal(document.getElementById('modalRoleEditar'));
            modalEditar.show();
          }
        });
    }

    function eliminarRol(id, form) {
      event.preventDefault();
      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás recuperar este rol!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
          //Swal.fire('Eliminado', 'El rol ha sido eliminado.', 'success');
        }
      });
    }
  </script>
</body>

</html>