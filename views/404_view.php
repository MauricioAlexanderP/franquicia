<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Error 404 - Página no encontrada</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Iconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .error-container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
    }

    .error-code {
      font-size: 120px;
      font-weight: bold;
      color: #343a40;
    }

    .error-message {
      font-size: 24px;
      color: #6c757d;
      margin-bottom: 20px;
    }

    .home-btn {
      margin-top: 20px;
    }

    .icon {
      font-size: 60px;
      color: #0d6efd;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <div class="icon mb-3">
      <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <div class="error-code">404</div>
    <div class="error-message">Lo sentimos, la página que buscas no existe.</div>
    <a href="<?php echo constant('URL'); ?>tienda" class="btn btn-primary home-btn">
      <i class="bi bi-house-door-fill me-2"></i>Volver al inicio
    </a>
  </div>
</body>

</html>