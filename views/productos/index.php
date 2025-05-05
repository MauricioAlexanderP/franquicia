<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f3f5;
    }

    .sidebar {
      height: 100vh;
      width: 240px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      color: white;
      padding-top: 1rem;
      display: flex;
      flex-direction: column;
    }

    .sidebar h4 {
      padding: 0 1.25rem;
      margin-bottom: 1.5rem;
    }

    .sidebar a {
      color: #adb5bd;
      text-decoration: none;
      padding: 0.75rem 1.25rem;
      display: flex;
      align-items: center;
      transition: background-color 0.2s ease-in-out;
    }

    .sidebar a:hover {
      background-color: #495057;
      color: #ffffff;
    }

    .sidebar i {
      margin-right: 0.75rem;
      font-size: 1.2rem;
    }

    .main-content {
      margin-left: 240px;
      padding: 2rem;
    }

    .card {
      border-radius: 1rem;
    }

    .card-header {
      background-color: #ffffff;
      border-bottom: 1px solid #e3e6f0;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
    }

    .btn-edit, .btn-delete {
      border-radius: 0.375rem;
      padding: 0.375rem 0.65rem;
      font-size: 0.875rem;
    }

    .btn-edit {
      color: #0d6efd;
      background-color: #e7f1ff;
      border: 1px solid #cfe2ff;
    }

    .btn-edit:hover {
      background-color: #d0e5ff;
    }

    .btn-delete {
      color: #dc3545;
      background-color: #f8d7da;
      border: 1px solid #f5c2c7;
    }

    .btn-delete:hover {
      background-color: #f1b0b7;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
      border-color: #86b7fe;
    }

    .modal-content {
      border-radius: 0.75rem;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <nav class="sidebar">
    <h4><i class="bi bi-shop-window me-2"></i>Mi Tienda</h4>
    <a href="#"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <a href="#"><i class="bi bi-tags-fill"></i> Tipos de Tienda</a>
    <a href="#"><i class="bi bi-building"></i> Tiendas</a>
    <a href="#"><i class="bi bi-box"></i> Tipos de Producto</a>
    <a href="#"><i class="bi bi-box2"></i> Productos</a>
    <a href="#"><i class="bi bi-person-gear"></i> Roles</a>
    <a href="#"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="#"><i class="bi bi-clipboard-data"></i> Inventario</a>
    <a href="#"><i class="bi bi-receipt-cutoff"></i> Ventas</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
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
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Precio</th>
                  <th>Tipo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Fila de ejemplo -->
                <tr>
                  <td>1</td>
                  <td>Smartphone</td>
                  <td>Teléfono inteligente de gama media</td>
                  <td>$350.00</td>
                  <td>Electrónica</td>
                  <td>
                    <button class="btn btn-edit me-1" onclick="editarProducto(1)">
                      <i class="bi bi-pencil-square"></i> Editar
                    </button>
                    <button class="btn btn-delete" onclick="eliminarProducto(1)">
                      <i class="bi bi-trash3-fill"></i> Eliminar
                    </button>
                  </td>
                </tr>
                <!-- Puedes agregar más filas dinámicamente -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Producto -->
  <div class="modal fade" id="modalProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formProducto">
            <input type="hidden" id="producto_id">
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Precio</label>
              <input type="number" step="0.01" class="form-control" id="precio" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Tipo de Producto</label>
              <select class="form-select" id="tipoProducto" required>
                <option value="">Seleccione un tipo</option>
                <option value="1">Electrónica</option>
                <option value="2">Ropa</option>
                <option value="3">Hogar</option>
                <!-- Se puede llenar dinámicamente desde la base de datos -->
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

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function editarProducto(id) {
      alert("Editar producto con ID: " + id);
      new bootstrap.Modal(document.getElementById('modalProducto')).show();
    }

    function eliminarProducto(id) {
      if (confirm("¿Estás seguro de eliminar este producto?")) {
        alert("Producto con ID " + id + " eliminado (simulado)");
      }
    }

    document.getElementById('formProducto').addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Producto guardado (simulado)");
      new bootstrap.Modal(document.getElementById('modalProducto')).hide();
    });
  </script>
</body>
</html>
