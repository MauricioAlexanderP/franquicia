<?php

$productos = $this->d['productos'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos para Inventario</title>
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
    <a href="#"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="<?php echo constant('URL'); ?>productosInventario"><i class="bi bi-box2"></i> Productos</a>
    <a href="<?php echo constant('URL'); ?>inventario"><i class="bi bi-clipboard-data"></i> Inventario</a>
    <a href="<?php echo constant('URL'); ?>logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0">📦 Productos</h5>
          <button class="btn btn-success" id="btnAgregarInventario">
            <i class="bi bi-box-arrow-in-down me-1"></i> Agregar al Inventario
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <form id="formSeleccionProductos" action="<?php echo constant('URL'); ?>inventario/newProducto" method="POST">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Seleccionar</th>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Tipo</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($productos as $producto): ?>
                    <tr>
                      <td>
                        <input type="checkbox" name="productos_seleccionados[]" value="<?php echo $producto['producto_id']; ?>">
                      </td>
                      <td><?php echo $producto['producto_id']; ?></td>
                      <td>
                        <img src="<?php echo constant('URL') . 'public/imgs/' . $producto['imagen']; ?>" alt="Imagen" width="50" height="50" style="object-fit: cover;">
                      </td>
                      <td><?php echo $producto['nombre']; ?></td>
                      <td><?php echo $producto['descripcion']; ?></td>
                      <td><?php echo $producto['precio']; ?></td>
                      <td><?php echo $producto['tipo_producto']; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Envía el formulario al hacer clic en "Agregar al Inventario"
    document.getElementById('btnAgregarInventario').addEventListener('click', function() {
      const form = document.getElementById('formSeleccionProductos');
      console.log(form);
      if (form.querySelectorAll('input[type="checkbox"]:checked').length === 0) {
        alert('Selecciona al menos un producto.');
      } else {
        form.submit();
      }
    });
  </script>
</body>

</html>