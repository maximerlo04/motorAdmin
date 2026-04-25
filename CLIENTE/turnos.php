<?php
if (!isset($_SESSION)) {
    session_start();
}
// Verificar si la sesión está activa y el rol es 'Admin'
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: ../PAGES/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Turno - MotorAdmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9Oer+0wPthOhA8rsVjQerV_D3B3z_oB-4o5uG0i3F_M4hK2f" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/turnos.css">
</head>
<body>
    <?php
      include("../UTILS/header-cliente-pages.php");
    ?>
    <div class="main-content-wrapper">
        <main class="container my-5">
            <h2 class="text-center mb-5">Solicitar Turno</h2>

            <div class="row justify-content-center g-4">
                <div class="card shadow p-4 h-100"> 
                      <h4 class="mb-3 text-center">Calendario de Citas</h4>
                      <div class="calendar-iframe-container">
                        <iframe src="https://calendar.google.com/calendar/appointments/schedules/AcZssZ0-liA5wn8hCf4LEX9pPhQqREAG7ZbjSmp5JaJLP91SkJ8RvfAHUn8fdQRT-pUDG1luZn3Z3WIR?gv=true" style="border: 0;" width="100%" height="600" frameborder="0"></iframe>
                      </div>
                </div>
            </div>
        </main>
    <?php
      include("../UTILS/footer.php");
    ?>
</body>
