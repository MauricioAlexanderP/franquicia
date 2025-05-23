<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Cambiar Contraseña</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/actializarContraseña.css">
</head>

<body>
  <div class="change-password-container">
    <h4 class="mb-4 text-center"><i class="bi bi-lock-fill me-1"></i>Cambiar Contraseña</h4>
    <form action="<?php echo constant('URL') ?>usuarios/updatePassword" method="POST">
      <div class="mb-3 form-group">
        <i class="bi bi-shield-lock-fill form-icon"></i>
        <input type="password" class="form-control" name="nueva_password" placeholder="Nueva contraseña" required>
      </div>

      <div class="mb-4 form-group">
        <i class="bi bi-shield-check form-icon"></i>
        <input type="password" class="form-control" name="confirmar_password" placeholder="Confirmar contraseña" required>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="bi bi-arrow-repeat me-1"></i>Actualizar Contraseña
      </button>
    </form>
    <div class="text-center mt-3">
      <a href="login"><i class="bi bi-arrow-left"></i> Volver al Login</a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>