<?php
if (!isset($_SESSION)) {
    session_start();
}
// Verificar si la sesión está activa
if (!isset($_SESSION['rol'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
  <?php
    include("../UTILS/header-cliente-pages.php");
  ?>
  <section class="container my-5 d-grid gap-3">
    <h2 class="text-center mb-5">Reseñas y Testimonios</h2>
    <!-- Testimonio 1 -->
    <div class="row align-items-center shadow rounded p-4 mb-4">
      <div class="col-md-3 text-center">
        <img src="img/testimonio1.jpg" alt="Foto cliente" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
      </div>
      <div class="col-md-9">
        <blockquote class="blockquote">
          <p class="mb-0">"Excelente servicio, atención personalizada y resultados impecables. ¡100% recomendado!"</p>
          <footer class="blockquote-footer mt-2">María Gómez</footer>
        </blockquote>
      </div>
    </div>

    <!-- Testimonio 2 (invertido) -->
    <div class="row align-items-center shadow rounded p-4 mb-4 flex-md-row-reverse">
      <div class="col-md-3 text-center">
        <img src="img/testimonio2.jpg" alt="Foto cliente" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
      </div>
      <div class="col-md-9">
        <blockquote class="blockquote">
          <p class="mb-0">"Muy profesionales. Desde que llegué me sentí bien atendido y el resultado fue perfecto."</p>
          <footer class="blockquote-footer mt-2">Carlos Pérez</footer>
        </blockquote>
      </div>
    </div>

    <!-- Testimonio 3 -->
    <div class="row align-items-center shadow rounded p-4 mb-4">
      <div class="col-md-3 text-center">
        <img src="img/testimonio3.jpg" alt="Foto cliente" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
      </div>
      <div class="col-md-9">
        <blockquote class="blockquote">
          <p class="mb-0">"Volveré sin dudarlo. ¡Gran experiencia! Todo salió mejor de lo esperado."</p>
          <footer class="blockquote-footer mt-2">Luciana Rivas</footer>
        </blockquote>
      </div>
    </div>

    <!-- Testimonio 4 (invertido) -->
    <div class="row align-items-center shadow rounded p-4 mb-4 flex-md-row-reverse">
      <div class="col-md-3 text-center">
        <img src="img/testimonio2.jpg" alt="Foto cliente" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
      </div>
      <div class="col-md-9">
        <blockquote class="blockquote">
          <p class="mb-0">"Muy profesionales. Desde que llegué me sentí bien atendido y el resultado fue perfecto."</p>
          <footer class="blockquote-footer mt-2">Carlos Pérez</footer>
        </blockquote>
      </div>
    </div>

    <!-- Testimonio 5 -->
    <div class="row align-items-center shadow rounded p-4 mb-4">
      <div class="col-md-3 text-center">
        <img src="img/testimonio3.jpg" alt="Foto cliente" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
      </div>
      <div class="col-md-9">
        <blockquote class="blockquote">
          <p class="mb-0">"Volveré sin dudarlo. ¡Gran experiencia! Todo salió mejor de lo esperado."</p>
          <footer class="blockquote-footer mt-2">Luciana Rivas</footer>
        </blockquote>
      </div>
    </div>
  </section>
  <?php
    include("../UTILS/footer.php");
  ?>
</body>
</html>