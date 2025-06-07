<?php

$productos = $this->d['productos'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos para Venta</title>
  <?php require_once 'views/header.php'; ?>
</head>

<body>
  <?php $this->showMessages();
  // error_log("VENTAS::registrarVenta -> SESSION: " . print_r($_SESSION['carrito'], true));
  ?>

  <!-- Sidebar -->
  <nav class="sidebar">
    <h4><i class="bi bi-shop-window me-2"></i>Mi Tienda</h4>
    <a href="<?php echo constant('URL'); ?>ventas"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="<?php echo constant('URL'); ?>ventas"><i class="bi bi-receipt-cutoff"></i> Ventas</a>
    <a href="<?php echo constant('URL'); ?>registrarVenta"><i class="bi bi-cart2"></i> Registrar Venta</a>
    <a href="<?php echo constant('URL'); ?>logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="card shadow mb-5">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📦 Productos</h5>
      </div>
      <div class="card-body">

        <!-- Filtros -->
        <div class="row mb-3">
          <div class="col-md-4">
            <input type="number" class="form-control" id="buscarID" placeholder="Buscar por ID...">
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="buscarNombre" placeholder="Buscar por nombre...">
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="buscarTipo" placeholder="Buscar por tipo...">
          </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
          <form id="formSeleccionProductos" action="<?php echo constant('URL'); ?>registrarVenta/VentaSession" method="post">
            <table class="table table-hover align-middle" id="tablaProductos">
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
                      <input
                        type="checkbox"
                        class="producto-checkbox"
                        data-id="<?php echo $producto['producto_id']; ?>"
                        data-imagen="<?php echo constant('URL') . 'public/imgs/' . $producto['imagen']; ?>"
                        data-nombre="<?php echo $producto['nombre']; ?>"
                        data-precio="<?php echo $producto['precio']; ?>"
                        data-productoId="<?php echo $producto['producto_id']; ?>">
                    </td>
                    <td><?php echo $producto['producto_id']; ?></td>
                    <td><img src="<?php echo constant('URL') . 'public/imgs/' . $producto['imagen']; ?>" alt="Imagen" width="50" height="50" style="object-fit: cover;"></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                    <td><?php echo $producto['tipo_producto']; ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <!-- Campo oculto para enviar los datos seleccionados -->
            <input type="hidden" name="productos_seleccionados" id="productosSeleccionados">
            <!-- Botón para agregar productos seleccionados -->
            <div class="d-flex justify-content-end mt-3">
              <button type="button" class="btn btn-secondary" id="btnSeleccionProductos">
                <i class="bi bi-cart2 me-1"></i> Agregar seleccionados
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>

    <!-- Carrito y Resumen -->
    <div class="row">
      <!-- Carrito -->
      <div class="col-lg-8 mb-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="mb-4">🛒 Carrito de Compras</h5>
            <?php if (!empty($_SESSION['carrito'])): ?>
              <?php foreach ($_SESSION['carrito'] as $index => $producto): ?>
                <div class="row mb-4 border-bottom pb-3 product-item">
                  <div class="col-md-1 col-4">
                    <img src="<?= $producto['imagen'] ?>" alt="Producto" class="img-fluid product-img">
                  </div>
                  <div class="col-md-5 col-8">
                    <h6><?= $producto['nombre'] ?></h6>
                  </div>
                  <div class="col-md-3 col-3 mt-md-0 mt-3">
                    <div class="input-group">
                      <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                      <input type="number" class="form-control text-center quantity" value="1" min="1">
                      <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                    </div>
                  </div>
                  <div class="col-md-3 text-end mt-md-0 mt-3">
                    <span class="product-price" data-price="<?= $producto['precio'] ?>">$<?= number_format($producto['precio'], 2) ?></span>
                    <!-- Eliminar producto -->
                    <form action="<?= constant('URL') ?>registrarVenta/deleteProducto" method="post">
                      <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                      <button type="submit" class="btn btn-link text-danger delete-btn"><i class="bi bi-trash"></i></button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted">No hay productos en el carrito.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Resumen -->
      <div class="col-lg-4 mb-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title mb-4">Resumen del Pedido</h5>
            <?php
            $subtotal = array_sum(array_column($_SESSION['carrito'] ?? [], 'precio'));
            $total = $subtotal;
            ?>
            <form action="<?= constant('URL') ?>ventas/newVenta" method="post">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                  Subtotal <span id="subtotal">$<?= number_format($subtotal, 2) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between fw-bold">
                  Total <span id="total">$<?= number_format($total, 2) ?></span>
                </li>
              </ul>
              <!-- Campos ocultos para enviar los valores -->
              <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
              <input type="hidden" name="Total" value="<?= $total ?>">
              <!-- Botón para procesar el pago -->
              <button type="submit" class="btn btn-success w-100 mt-4">Procesar pago</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Filtrado en tiempo real
    function filtrarTabla() {
      const inputNombre = document.getElementById('buscarNombre').value.toLowerCase();
      const inputID = document.getElementById('buscarID').value.toLowerCase();
      const inputTipo = document.getElementById('buscarTipo').value.toLowerCase();
      const filas = document.querySelectorAll('#tablaProductos tbody tr');

      filas.forEach(fila => {
        const id = fila.children[1].textContent.toLowerCase();
        const nombre = fila.children[3].textContent.toLowerCase();
        const tipo = fila.children[6].textContent.toLowerCase();

        const coincideNombre = nombre.includes(inputNombre);
        const coincideID = id.includes(inputID);
        const coincideTipo = tipo.includes(inputTipo);

        if (coincideNombre && coincideID && coincideTipo) {
          fila.style.display = '';
        } else {
          fila.style.display = 'none';
        }
      });
    }
    document.getElementById('buscarNombre').addEventListener('input', filtrarTabla);
    document.getElementById('buscarID').addEventListener('input', filtrarTabla);
    document.getElementById('buscarTipo').addEventListener('input', filtrarTabla);

    function calcularTotal() {
      let subtotal = 0;

      // Recalcular el subtotal basado en los productos del carrito
      document.querySelectorAll('.product-item').forEach(item => {
        const price = parseFloat(item.querySelector('.product-price').dataset.price);
        const quantity = parseInt(item.querySelector('.quantity').value);
        subtotal += price * quantity;
      });

      const total = subtotal;

      // Actualizar los valores mostrados en la vista
      document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
      document.getElementById('total').textContent = `$${total.toFixed(2)}`;

      // Actualizar el valor del campo oculto en el formulario
      document.querySelector('input[name="Total"]').value = total.toFixed(2);
    }

    document.getElementById('btnSeleccionProductos').addEventListener('click', function(event) {
      event.preventDefault();
      const checkboxes = document.querySelectorAll('.producto-checkbox:checked');
      const productosSeleccionados = [];
      checkboxes.forEach(checkbox => {
        const producto = {
          id: checkbox.dataset.id,
          imagen: checkbox.dataset.imagen,
          nombre: checkbox.dataset.nombre,
          precio: checkbox.dataset.precio
        };
        productosSeleccionados.push(producto);
      });
      if (productosSeleccionados.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: '¡Atención!',
          text: 'Selecciona al menos un producto para agregar al carrito.',
          confirmButtonText: 'Aceptar'
        });
        return;
      }
      // Convertir los datos seleccionados a JSON y asignarlos al campo oculto
      document.getElementById('productosSeleccionados').value = JSON.stringify(productosSeleccionados);
      // Enviar el formulario
      document.getElementById('formSeleccionProductos').submit();
      console.log(productosSeleccionados);
    });

    // Función para manejar cambios en la cantidad
    function manejarCambioCantidad(input) {
      const productItem = input.closest('.product-item');
      const productId = productItem.querySelector('input[name="producto_id"]').value;
      const nuevaCantidad = parseInt(input.value);

      // Validación básica
      if (nuevaCantidad < 1) {
        input.value = 1;
        return;
      }

      // Mostrar loader opcional
      const loader = productItem.querySelector('.quantity-loader');
      if (loader) loader.style.display = 'inline-block';

      fetch('<?= constant("URL") ?>registrarVenta/actualizarCantidad', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `producto_id=${productId}&cantidad=${nuevaCantidad}`,
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Actualizar los totales
            document.getElementById('subtotal').textContent = `$${data.subtotal}`;
            document.getElementById('total').textContent = `$${data.total}`;
          } else {
            console.error('Error:', data.message);
            // Revertir el cambio si falla
            input.value = input.dataset.prevValue || 1;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          input.value = input.dataset.prevValue || 1;
        })
        .finally(() => {
          if (loader) loader.style.display = 'none';
        });
    }

    // Eventos para los inputs de cantidad
    document.querySelectorAll('.quantity').forEach(input => {
      // Guardar valor previo para posible revertir
      input.dataset.prevValue = input.value;

      input.addEventListener('change', function() {
        manejarCambioCantidad(this);
      });

      // También para los botones +/-
      const plusBtn = input.nextElementSibling;
      const minusBtn = input.previousElementSibling;

      if (plusBtn && plusBtn.classList.contains('plus-btn')) {
        plusBtn.addEventListener('click', function() {
          input.value = parseInt(input.value) + 1;
          manejarCambioCantidad(input);
        });
      }

      if (minusBtn && minusBtn.classList.contains('minus-btn')) {
        minusBtn.addEventListener('click', function() {
          if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            manejarCambioCantidad(input);
          }
        });
      }
    });
  </script>
</body>

</html>