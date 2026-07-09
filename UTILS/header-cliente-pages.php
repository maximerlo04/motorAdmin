<?php
if (!isset($_SESSION)) {
    session_start();
}
// Verificar si la sesión está activa y el rol es 'Cliente' o 'Empleado'
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'Cliente' && $_SESSION['rol'] !== 'Empleado')) {
    header("Location: ../PAGES/index.php");
    exit();
}
?>
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/vite.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../CSS/index.css" rel="stylesheet" type="text/css">
  <link href="../CSS/pages-style.css" rel="stylesheet" type="text/css">
  <title>MotorAdmin Cliente</title>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
      <a class="navbar-brand" href="../CLIENTE/inicio.php"><img src="../images/motorAdmin-logo.png" alt="Logo" style="width: 50px; height: auto;"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse bg-light" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../CLIENTE/inicio.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../CLIENTE/servicios.php">Servicios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../CLIENTE/turnos.php">Turnos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../CLIENTE/presupuesto.php">Presupuesto</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../CLIENTE/mis-facturas.php">Mis Facturas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../CLIENTE/contacto.php">Contáctanos</a>
          </li>
        </ul>
        <div class="d-flex align-items-center">
          <?php if (isset($_SESSION['nombre'])): ?>
            <span class="me-3 text-dark fw-bold">¡Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</span>
          <?php endif; ?>
          <a href="../php/logout.php" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
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

  <!-- Modal de Éxito -->
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
   /* document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('Form submitted');
      
      const formData = new FormData(this);
      console.log('Form data:', Object.fromEntries(formData));
      
      fetch('../php/logging.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Response received:', response);
        return response.json();
      })
      .then(data => {
        console.log('Data:', data);
        if (data.success) {
          window.location.reload();
        } else {
          // Cerrar el modal de login
          const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
          loginModal.hide();
          
          // Mostrar el modal de error
          document.getElementById('errorMessage').textContent = data.message;
          const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
          errorModal.show();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        // Cerrar el modal de login
        const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        loginModal.hide();
        
        // Mostrar el error en el modal
        document.getElementById('errorMessage').textContent = 'Error al procesar la solicitud';
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
      });
    });
  */

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
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('errorMessage').textContent = 'Error al procesar el registro';
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    });
  });
  </script>
</body>