<?php

$productos = $this->d['productos'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos para Inventario</title>
  <?php require_once 'views/header.php'; ?>s
</head>

<body>
  <?php
  $this->showMessages();
  ?>
  <!-- Sidebar -->
  <nav class="sidebar">
    <h4><i class="bi bi-shop-window me-2"></i>Mi Tienda</h4>
    <a href="#"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="<?php echo constant('URL'); ?>perfil"><i class="bi bi-person-fill"></i> Perfil</a>
    <a href="<?php echo constant('URL'); ?>productosInventario"><i class="bi bi-box2"></i> Productos</a>
    <a href="<?php echo constant('URL'); ?>inventario"><i class="bi bi-clipboard-data"></i> Inventario</a>
    <a href="<?php echo constant('URL'); ?>dashboard"><i class="bi bi-bar-chart"></i> Dashboard</a>
    <a href="<?php echo constant('URL'); ?>evaluaciones"><i class="bi bi-card-checklist"></i> Evaluaciones</a>
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
              <!-- Filtros -->
              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label for="filtroNombre" class="form-label">Filtrar por Nombre</label>
                  <input type="text" id="filtroNombre" class="form-control" placeholder="Nombre del producto">
                </div>
                <div class="col-md-4">
                  <label for="filtroTipo" class="form-label">Filtrar por Tipo</label>
                  <input type="text" id="filtroTipo" class="form-control" placeholder="Tipo de producto">
                </div>
                <div class="col-md-4">
                  <label for="filtroDescripcion" class="form-label">Filtrar por Descripción</label>
                  <input type="text" id="filtroDescripcion" class="form-control" placeholder="Descripción del producto">
                </div>
              </div>

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
                <tbody id="tabla">
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
                      <td>$<?php echo $producto['precio']; ?></td>
                      <td><?php echo $producto['tipo_producto']; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

              <nav>
                <ul class="pagination justify-content-center mt-3" id="paginacion-tabla"></ul>
              </nav>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Scripts -->
  <script src="assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      paginarTabla("tabla", "paginacion-tabla", 6);
    });
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
  <!-- JavaScript para filtros -->
  <script>
    const filtroNombre = document.getElementById('filtroNombre');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroDescripcion = document.getElementById('filtroDescripcion');
    const filas = document.querySelectorAll('#tabla tr');

    function aplicarFiltrosProductos() {
      const textoNombre = filtroNombre.value.toLowerCase();
      const textoTipo = filtroTipo.value.toLowerCase();
      const textoDescripcion = filtroDescripcion.value.toLowerCase();

      filas.forEach(fila => {
        const nombre = fila.cells[3].textContent.toLowerCase();
        const descripcion = fila.cells[4].textContent.toLowerCase();
        const tipo = fila.cells[6].textContent.toLowerCase();

        const coincideNombre = nombre.includes(textoNombre);
        const coincideDescripcion = descripcion.includes(textoDescripcion);
        const coincideTipo = tipo.includes(textoTipo);

        fila.style.display = (coincideNombre && coincideDescripcion && coincideTipo) ? '' : 'none';
      });
    }

    filtroNombre.addEventListener('input', aplicarFiltrosProductos);
    filtroTipo.addEventListener('input', aplicarFiltrosProductos);
    filtroDescripcion.addEventListener('input', aplicarFiltrosProductos);
  </script>
</body>

</html>