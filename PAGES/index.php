<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotorAdmin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/inicio.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
      .transition-span {
        transition: all 0.3s ease-in-out;
        display: inline-block;
      }

      .transition-span-motor:hover {
        transform: scale(1.1);
        color: #0d6efd !important; 
      }

      .transition-span-admin:hover {
        transform: scale(1.1);
        color:rgb(253, 13, 13) !important; 
      }
    </style>
</head>
<body>
    <?php
      include("../UTILS/header-pages.php");
    ?>
    <h1 class="text-center fw-bold display-4 my-4 animate__animated animate__rubberBand animate__fast">
      <span class="transition-span transition-span-motor" style="color: #12344D;">Motor</span>
      <span class="transition-span transition-span-admin text-danger">Admin</span>
    </h1>
    <section class="py-5 animate__animated animate__fadeInRightBig">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 mb-3 mb-md-0">
            <h3>Historia</h3>
            <p>Con muchos años de trayectoria, nuestra historia se ha construido sobre la pasión por los vehículos y el 
              compromiso con un servicio excepcional. Desde aquellos primeros talleres hasta la moderna infraestructura 
              de hoy, hemos evolucionado junto a la industria automotriz, manteniendo siempre la calidad y la confianza 
              como pila res fundamentales.</p>
          </div>
          <div class="col-md-6 text-center">
            <img src="../images/historia-taller.jpeg" alt="fotoAuto" class="img-fluid rounded">
          </div>
        </div>
      </div>
    </section>
    
    <section class="py-5 animate__animated animate__fadeInLeftBig">
      <div class="container">
        <div class="row align-items-center flex-column flex-md-row">
          <div class="col-md-6 mb-3 mb-md-0 text-center">
            <img src="../images/quienes-somos.jpg" alt="fotoNosotros" class="img-fluid rounded w-100">
          </div>
          <div class="col-md-6">
            <h3>¿Quiénes somos?</h3>
            <p>En motorAdmin, somos especialistas en mecánica automotriz, respaldados por años de experiencia y 
              una formación continua en las últimas tecnologías del sector. Nuestro equipo de técnicos 
              certificados utiliza herramientas de diagnóstico de vanguardia para asegurar la máxima precisión 
              y eficiencia en cada intervención, garantizando la seguridad y el óptimo rendimiento de tu 
              vehículo</p>
          </div>
        </div>
      </div>
    </section>


    <section class="py-5 animate__animated animate__fadeInRightBig">
      <div class="container">
        <div class="row align-items-center flex-column-reverse flex-md-row">
          <div class="col-md-6 mt-3 mt-md-0">
            <h3>Misión y Visión</h3>
            <p>Proveer servicios de mantenimiento y reparación automotriz de la más alta calidad, 
              garantizando la seguridad, el rendimiento y la durabilidad de cada vehículo, 
              superando las expectativas de nuestros clientes a través de la excelencia y la confianza.</p>
            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#loginModal">
              Reserva tu turno
            </button>
          </div>
          <div class="col-md-6 text-center">
            <img src="../images/mision-vision.jpg" alt="fotoCalendario" class="img-fluid rounded w-100">
          </div>
        </div>
      </div>
    </section>


    <section id="servicios" class="py-5 bg-light">
      <div class="container">
        <h2 class="text-center mb-4">Nuestros Servicios</h2>
        <div class="row text-center">
          <div class="col-sm-6 col-lg-3 mb-4">
            <div class="servicio p-3 border rounded h-100">
              <i class="ri-oil-line display-4 mb-2"></i>
              <p>Chequeo y cambio de aceite, líquido refrigerante, rellenado del sapito</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-4">
            <div class="servicio p-3 border rounded h-100">
              <i class="ri-tools-fill display-4 mb-2"></i>
              <p>Cambio de ruedas, cambio de frenos</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-4">
            <div class="servicio p-3 border rounded h-100">
              <i class="ri-steering-fill display-4 mb-2"></i>
              <p>Dirección y alineación, ajuste de balanceo</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-4">
            <div class="servicio p-3 border rounded h-100">
              <i class="ri-car-fill display-4 mb-2"></i>
              <p>Chequeos generales</p>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section id="contacto" class="py-5">
    <div class="container">
        <div class="row align-items-center flex-column flex-md-row">
            <div class="col-md-12"> <div class="row"> <div class="col-md-6 map-column"> <div class="map-responsive">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3278.4357497216654!2d-58.58309112461937!3d-34.74976786657929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc635b75f8549%3A0xc3f80c611484f23b!2sParis%20532%2C%20Haedo%2C%20Provincia%20de%20Buenos%20Aires!5e0!3m2!1ses!2sar!4v1718320666060!5m2!1ses!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-md-6 text-column"> 
                        <a href="https://www.google.com/maps/place/UTN+HAEDO/@-34.6405795,-58.6046059,17z/data=!4m6!3m5!1s0x95bc951c0fe2d9f5:0x9f1c540898efecbe!8m2!3d-34.6405839!4d-58.602031!16zL20vMGZtMjlw?authuser=0&entry=ttu&g_ep=EgoyMDI1MDYxNi4wIKXMDSoASAFQAw%3D%3D" target="_blank">Google Maps</a>
                        <p>Estamos ubicados en Paris 532, Haedo, Buenos Aires. Te invitamos a visitar 
                            nuestras instalaciones y experimentar la calidad de nuestro servicio de primera mano. 
                            ¡Te esperamos para brindarte la mejor atención y cuidado para tu vehículo!</p>
                        <a href="./contacto.php"><button class="btn btn-primary mt-2">Contactanos</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <?php
      include("../UTILS/footer.php");
    ?>
    <script src="../JS/inicio.js"></script>
</body>