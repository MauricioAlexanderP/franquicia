<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Perfil</title>
  <?php require_once 'views/header.php'; ?>
</head>

<body>
  <div class="container my-5" style="max-width: 500px;">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Cambiar Contraseña</h5>
      </div>
      <div class="card-body">

        <?php if (isset($this->error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php if (isset($this->success)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <form action="<?= constant('URL') ?>usuarios/changePassword" method="POST">
          <div class="mb-3">
            <label class="form-label">Contraseña Actual:</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nueva Contraseña:</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Confirmar Nueva Contraseña:</label>
            <input type="password" name="confirm_password" class="form-control" required>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save2 me-1"></i> Guardar Cambios
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

</body>

</html>