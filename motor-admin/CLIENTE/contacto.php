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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9Oer+0wPthOhA8rsVjQerV_D3B3z_oB-4o5uG0i3F_M4hK2f" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
    <?php
        include("../UTILS/header-cliente-pages.php");
    ?>
    <section class="section container py-5">
        <h2 class="mb-4 text-center">Contáctanos</h2>
        
        <?php
        // Mostrar mensajes de éxito o error
        if (isset($_GET['success']) && $_GET['success'] == '1') {
            if (isset($_GET['email_error']) && $_GET['email_error'] == '1') {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡Consulta enviada!</strong> Tu mensaje se ha guardado correctamente, pero hubo un problema al enviar el email de confirmación.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } else {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Consulta enviada exitosamente!</strong> Hemos recibido tu mensaje y te hemos enviado un email de confirmación.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        } elseif (isset($_GET['error']) && $_GET['error'] == '1') {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> No se pudo enviar tu consulta. Por favor, inténtalo nuevamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
        ?>
        
        <form class="row g-3" action="../php/procesar_formulario_de_contacto.php" method="POST">
            <div class="col-md-6">
                <input
                    type="text"
                    class="form-control text-dark bg-white border-secondary" id="nombre"
                    name="nombre"
                    placeholder="Nombre" value="<?= isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : '' ?>"
                    <?= isset($_SESSION['nombre']) ? 'readonly' : 'required' ?>
                >
            </div>
            <div class="col-md-6">
                <input
                    type="email"
                    class="form-control text-dark bg-white border-secondary" id="email"
                    name="email"
                    placeholder="Email" value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>"
                    <?= isset($_SESSION['email']) ? 'readonly' : 'required' ?>
                >
            </div>
            <div class="col-md-6">
                <input
                    type="tel"
                    class="form-control text-dark bg-white border-secondary" id="telefono"
                    name="telefono"
                    placeholder="Teléfono"
                    value="<?= isset($_SESSION['telefono']) ? htmlspecialchars($_SESSION['telefono']) : '' ?>"
                    <?= isset($_SESSION['telefono']) ? 'readonly' : 'required' ?>
                >
            </div>
            <div class="col-md-6">
                <input
                    name="Asunto"
                    type="text"
                    class="form-control text-dark bg-white border-secondary" placeholder="Asunto"
                >
            </div>
            <div class="col-12">
                <textarea
                    name="Mensaje"
                    class="form-control text-dark bg-white border-secondary" rows="5"
                    placeholder="Escribe tu mensaje aquí..."
                    required
                ></textarea>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm px-5">Enviar</button>
            </div>
        </form>
    </section>

    <section class="container py-5">
        <h2 class="mb-4 text-center">¿Dónde Encontrarnos?</h2>
        <div class="ratio ratio-16x9">
            <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3282.5617645559846!2d-58.604789324522756!3d-34.64051325944197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcb880263d379f%3A0xfa00dafc9277b841!2sPar%C3%ADs%20532%2C%20B1706EAH%20Haedo%2C%20Provincia%20de%20Buenos%20Aires!5e0!3m2!1ses!2sar!4v1749080904254!5m2!1ses!2sar"
            style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>