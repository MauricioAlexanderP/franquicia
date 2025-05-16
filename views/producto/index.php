<?php
$tipoProducto = $this->d['tipoProducto'];
$productos = $this->d['productos'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="assets/css/productos.css">
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
          <h5 class="mb-0">📦 Productos</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProducto">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Producto
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Imagen</th> <!-- Nueva columna -->
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Precio</th>
                  <th>Tipo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($productos as $producto): ?>
                  <tr>
                    <td><?php echo $producto['producto_id']; ?></td>
                    <td>
                      <img src="<?php echo constant('URL') . 'public/imgs/' . $producto['imagen']; ?>" alt="Imagen" width="50" height="50" style="object-fit: cover;">
                    </td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo $producto['precio']; ?></td>
                    <td><?php echo $producto['tipo_producto']; ?></td>
                    <td>
                      <!-- Formulario Editar -->
                      <form action="<?php echo constant('URL'); ?>producto/editar" method="POST" class="d-inline">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>">
                        <button type="button" class="btn btn-edit me-1" onclick="editarProducto(<?php echo $producto['producto_id']; ?>)">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                      </form>
                      <!-- Formulario Eliminar -->
                      <form action="<?php echo constant('URL'); ?>producto/deleteProducto" method="POST" class="d-inline">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>">
                        <button type="button" class="btn btn-delete" onclick="eliminarProducto(<?php echo $producto['producto_id']; ?>, this.form)">
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

  <!-- Modal Producto -->
  <!-- FORMULARIO PARA AGREGAR  -->
  <div class="modal fade" id="modalProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="<?php echo constant('URL'); ?>producto/newProducto" method="post" enctype="multipart/form-data" class="" id="formProducto">
            <input type="hidden" id="producto_id">
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <textarea class="form-control" name="descripcion" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Precio</label>
              <input type="number" step="0.01" class="form-control" name="precio" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Tipo de Producto</label>
              <select class="form-select" name="tipoProducto" required>
                <option value="">Seleccione un tipo</option>
                <?php foreach ($tipoProducto as $tipo): ?>
                  <option value="<?php echo $tipo['tipo_producto_id']; ?>"><?php echo $tipo['catalogo']; ?></option>
                <?php endforeach; ?>
                <!-- Se puede llenar dinámicamente desde la base de datos -->
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Imagen</label>
              <input type="file" class="form-control" name="imagen" accept="image/*" onchange="vistaPrevia(this, '#previewAgregar')">
              <img id="previewAgregar" src="#" alt="Vista previa" class="img-thumbnail mt-2" style="display: none; max-width: 100px;">
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
<div class="modal fade" id="modalProductoEditar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title">Editar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo constant('URL'); ?>producto/updateProducto" id="formProductoEditar">
          <input type="hidden" name="producto_id" id="editar_producto_id">
          <div class="mb-3">
            <label class="form-label">Tipo de Producto</label>
            <select class="form-select" name="tipoProducto" id="editar_tipoProducto" required>
              <option selected disabled value="">Seleccione</option>
              <?php foreach ($tipoProducto as $tipo): ?>
                <option value="<?php echo $tipo['tipo_producto_id']; ?>"><?php echo $tipo['catalogo']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="editar_nombre" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" id="editar_descripcion" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" class="form-control" name="precio" id="editar_precio" required>
          </div>
          <!-- Puedes agregar aquí el campo para imagen si lo deseas -->
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
    function editarProducto(id) {
      console.log('producto_id=' + id);
      fetch('<?php echo constant("URL") ?>producto/getProductoById', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'producto_id=' + id
          
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
          } else {
            document.querySelector('#formProductoEditar input[name="producto_id"]').value = data.producto_id;
            document.querySelector('#formProductoEditar select[name="tipoProducto"]').value = data.tipo_producto_id;
            document.querySelector('#formProductoEditar input[name="nombre"]').value = data.nombre;
            document.querySelector('#formProductoEditar textarea[name="descripcion"]').value = data.descripcion;
            document.querySelector('#formProductoEditar input[name="precio"]').value = data.precio;
            new bootstrap.Modal(document.getElementById('modalProductoEditar')).show();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
        });
    }

    function eliminarProducto(id, form) {
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

    function vistaPrevia(input, selector) {
      const file = input.files[0];
      const img = document.querySelector(selector);

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          img.src = e.target.result;
          img.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        img.style.display = 'none';
      }
    }
  </script>
</body>

</html>