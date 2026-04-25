<?php
session_start();
?>
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/vite.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../CSS/index.css" rel="stylesheet" type="text/css">
  <link href="../CSS/pages-style.css" rel="stylesheet" type="text/css">
  <title>MotorAdmin</title>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
      <a class="navbar-brand" href="../PAGES/index.php"><img src="../images/motorAdmin-logo.png" alt="Logo" style="width: 50px; height: auto;"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse bg-light" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../PAGES/index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../PAGES/servicios.php">Servicios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../PAGES/contacto.php">Contáctanos</a>
          </li>
        </ul>
        <div class="d-flex">
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar</button>
        </div>
      </div>
    </nav>
  </header>

  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="loginForm" action="../php/logging.php" method="POST">
            <div class="mb-3">
              <label for="loginEmail" class="form-label">Email</label>
              <input type="text" name="email" class="form-control" id="loginEmail" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Contraseña</label>
              <input type="password" name="password" class="form-control" id="loginPassword">
            </div>
            <button type="submit" class="btn btn-primary">Ingresar</button>
          </form>
          <div class="mt-3 text-center">
            ¿No tienes una cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Regístrate aquí</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Error -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="errorModalLabel">Error</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="errorMessage"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Crear Cuenta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="registerForm" action="../php/register.php" method="POST">
            <div class="mb-3">
              <label for="registerName" class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" id="registerName" required>
            </div>
            <div class="mb-3">
              <label for="registerEmail" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="registerEmail" required>
            </div>
            <div class="mb-3">
              <label for="registerDNI" class="form-label">DNI</label>
              <input type="number" name="dni"  class="form-control" id="registerDNI" required>
            </div>
            <div class="mb-3">
              <label for="registerPatente" class="form-label">Patente</label>
              <input type="text" name="patente" class="form-control" id="registerPatente" required>
            </div>
            <div class="mb-3">
              <label for="registerModelo" class="form-label">Modelo</label>
              <input type="text" name="modelo" class="form-control" id="registerModelo" required>
            </div>
            <div class="mb-3">
              <label for="registerPassword" class="form-label">Contraseña</label>
              <input type="password" name="contrasenia" class="form-control" id="registerPassword" required>
            </div>
            <button type="submit" class="btn btn-success">Registrarse</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Éxito</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="successMessage"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.reload()">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Crear instancia global de errorModal
    const errorModalEl = document.getElementById('errorModal');
    const errorModal = new bootstrap.Modal(errorModalEl);

    document.addEventListener('DOMContentLoaded', function () {
      const params = new URLSearchParams(window.location.search);

      const loginModalEl = document.getElementById('loginModal');
      const loginModal = new bootstrap.Modal(loginModalEl);

      // Si viene de login fallido
      if (params.get('login') === '1') {
        // Limpiar campos
        const email = document.getElementById('loginEmail');
        const pass = document.getElementById('loginPassword');
        if (email) email.value = '';
        if (pass) pass.value = '';

        // Mostrar el modal de error si corresponde
        if (params.get('error') === '1') {
          loginModal.hide(); // Cierra el modal de login antes de abrir el error
          document.getElementById('errorMessage').textContent = "Email o contraseña incorrectos.";
          errorModal.show();
        }

        // Enfocar campo email
        const emailInput = loginModalEl.querySelector('input[type="text"], input[type="email"]');
        if (emailInput) emailInput.focus();

        // Limpiar la URL
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    });

    // Evento del formulario de registro
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch('../php/register.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Cerrar el modal de registro
          const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
          registerModal.hide();

          // Mostrar el modal de éxito
          document.getElementById('successMessage').textContent = data.message;
          const successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
        } else {
          // Mostrar el error en el modal de error
          document.getElementById('errorMessage').textContent = data.message;
          errorModal.show();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('errorMessage').textContent = 'Error al procesar el registro';
        errorModal.show();
      });
    });
  </script>
</body>