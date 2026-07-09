<?php
if (!isset($_SESSION)) {
    session_start();
}
// Verificar si la sesión está activa y el rol es 'Cliente'
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: ../PAGES/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../CSS/index.css">
    </head>
<body>
    <?php include("../UTILS/header-cliente-pages.php"); ?>
    <?php
    include '../connection.php';
    $servicios = $connection->query("SELECT * FROM servicios");
    ?>

    <div class="content-wrap">
        <section class="container text-center my-5 seccion-presupuestos">
            <h2 class="mb-5">Solicitar presupuesto</h2>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5 animate__animated animate__pulse">
                <div class="col">
                    <div class="card h-100 text-center p-3 shadow-sm"> <i class="ri-oil-line fs-1 text-primary"></i>
                        <p class="mt-2">Chequeo y cambio de aceite, cambio líquido refrigerante, rellenar el sapito</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 text-center p-3 shadow-sm"> <i class="ri-tools-fill fs-1 text-success"></i>
                        <p class="mt-2">Cambio de ruedas, cambio de frenos</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 text-center p-3 shadow-sm"> <i class="ri-steering-fill fs-1 text-warning"></i>
                        <p class="mt-2">Dirección y alineación, ajuste de balanceo</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 text-center p-3 shadow-sm"> <i class="ri-car-fill fs-1 text-danger"></i>
                        <p class="mt-2">Chequeos generales</p>
                    </div>
                </div>
            </div>

            <form id="formPresupuesto" class="mx-auto container p-4 rounded shadow-lg animate__animated animate__fadeInUp" style="max-width: 600px;">
                <h3 class="text-center mb-4 text-dark">Solicitar Presupuesto</h3>
                <div class="mb-3 text-start">
                    <label for="nombre" class="form-label text-dark">Nombre:</label>
                    <input
                        type="text"
                        class="form-control text-dark bg-white border-secondary" id="nombre"
                        name="nombre"
                        value="<?= isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : '' ?>"
                        <?= isset($_SESSION['nombre']) ? 'readonly' : 'required' ?>
                    >
                </div>
                <div class="mb-3 text-start">
                    <label for="email" class="form-label text-dark">Email:</label>
                    <input
                        type="email"
                        class="form-control text-dark bg-white border-secondary" id="email"
                        name="email"
                        value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>"
                        <?= isset($_SESSION['email']) ? 'readonly' : 'required' ?>
                    >
                </div>

                <div class="mb-3 text-start">
                    <label for="servicio" class="form-label text-dark">Servicio:</label>
                    <select
                        id="servicio"
                        name="servicio"
                        class="form-select text-dark bg-white border-secondary" required
                    >
                        <option value="" class="text-dark bg-white">-- Selecciona el servicio --</option> <?php while ($row = $servicios->fetch_assoc()): ?>
                            <option value="<?= $row['nombre'] ?>" class="text-dark bg-white"><?= $row['nombre'] ?></option>
                        <?php endwhile ?>
                        <option value="otro" class="text-dark bg-white">Otro</option>
                    </select>
                </div>

                <div class="mb-4 text-start">
                    <label for="mensaje" class="form-label text-dark">Mensaje:</label>
                    <textarea
                        id="mensaje"
                        name="mensaje"
                        rows="5"
                        class="form-control text-dark bg-white border-secondary" required
                    ></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-danger btn-lg text-uppercase fw-bold shadow-sm">Enviar</button>
                </div>
            </form>
        </section>

        <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalExitoLabel">Mensaje Enviado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Gracias por tu mensaje. Pronto nos pondremos en contacto.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div> <?php include("../UTILS/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('formPresupuesto').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../php/procesar_consultas.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
                    modalExito.show();
                    this.reset();
                } else {
                    alert('Error al enviar el mensaje: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar el mensaje');
            });
        });
    </script>
</body>
</html>