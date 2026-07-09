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
        include("../UTILS/header-pages.php");
    ?>
    <section class="section container py-5">
        <h2 class="mb-4 text-center">Contáctanos</h2>
        <form class="row g-3" action="../php/procesar_formulario_de_contacto.php" method="POST">
            <div class="col-md-6">
                <input
                    name="nombre"
                    type="text"
                    class="form-control text-dark bg-white border-secondary" placeholder="Nombre"
                    required
                >
            </div>
            <div class="col-md-6">
                <input
                    name="email"
                    type="email"
                    class="form-control text-dark bg-white border-secondary" placeholder="Email"
                    required
                >
            </div>
            <div class="col-md-6">
                <input
                    name="telefono"
                    type="tel"
                    class="form-control text-dark bg-white border-secondary" placeholder="Teléfono"
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
</body>
</html>