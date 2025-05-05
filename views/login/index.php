<?php

use class\ErrorMessages;
use class\SuccessMessages;
use libs\View;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Botstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <link rel="stylesheet" href="assets/css/login.css" />
</head>

<body>
  <?php
  $this->showMessages();
  ?>
  <div class="login-card">
    <div class="text-center">
      <div class="logo mb-2"><i class="bi bi-shop-window me-1"></i>Mi Tienda</div>
      <p class="text-muted mb-4">Inicia sesión para continuar</p>
    </div>
    <form action="<?php echo constant('URL');?>login/authenticate" id="loginForm" method="post">
      <div class="mb-3 position-relative">
        <i class="bi bi-person form-icon"></i>
        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Usuario" required>
      </div>
      <div class="mb-4 position-relative">
        <i class="bi bi-lock form-icon"></i>
        <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Contraseña" required>
      </div>
      <div class="d-grid mb-3">
        <button type="submit" id="login" class="btn btn-primary">
          <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
        </button>
      </div>
      <div class="text-center">
        <small class="text-muted">¿Olvidaste tu contraseña?</small>
      </div>
    </form>
  </div>

  <!-- <script>
    $(document).on('click', '#login', function() {
      var username = $('#username').val();
      var password = $('#password').val();

      if (username === '' || password === '') {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Por favor, completa todos los campos.',
        });
        return;
      }
      $.ajax({
        url: 'login/login',
        type: 'POST',
        data: {
          username: username,
          password: password
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === 200) {
            Swal.fire({
              icon: 'success',
              title: 'Éxito',
              text: response.massage,
            }).then(() => {
              window.location.href = response.route;
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response,
            });
          }
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al procesar la solicitud.',
          });
        }
      });
    });
  </script> -->

</body>

</html>