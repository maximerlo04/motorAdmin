<?php
if (!isset($_SESSION)) {
    session_start();
}
// Verificar si la sesión está activa y el rol es 'Admin'
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
    header("Location: ../PAGES/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacén de Contactos</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9Oer+0wPthOhA8rsVjQerV_D3B3z_oB-4o5uG0i3F_M4hK2f" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/turnos.css">
    
</head>

<body>
    <?php
    include("../UTILS/sidebar.php");
    include("../connection.php");
    $sql = "SELECT * FROM mensajes ORDER BY fecha DESC";
    $result = mysqli_query($connection,$sql);
    
    echo'<div class="container my-5">
        <h2 class="text-center mb-4">Responder Consultas</h2>';
    
    // Mostrar mensajes de éxito o error
    if (isset($_GET['success']) && $_GET['success'] == '1') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>¡Éxito!</strong> La respuesta se ha enviado correctamente al usuario.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } elseif (isset($_GET['error']) && $_GET['error'] == '1') {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> No se pudo enviar el email. La respuesta se guardó en la base de datos.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    
    echo '<div class="table-responsive">
            <table id="tabla-contactos" class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Télefono</th>
                <th scope="col">Asunto</th>
                <th scope="col">Mensaje</th>
                <th scope="col">Fecha</th>
                <th scope="col">Responder</th>
                </tr>
            </thead>
            <tbody>';

    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
        
        <td>".$row['nombre']."</td>
        <td>".$row['email']."</td>
        <td>".$row['telefono']."</td>
        <td>".$row['asunto']."</td>
        <td>".$row['mensaje']."</td>
        <td>".$row['fecha']."</td>
        <td>
         <button type='button' class='btn btn-primary' 
            data-bs-toggle='modal' 
            data-bs-target='#exampleModal' 
            data-nombre='" . htmlspecialchars($row['nombre'], ENT_QUOTES) . "'
            data-email='" . htmlspecialchars($row['email'], ENT_QUOTES) . "'
            data-telefono='" . htmlspecialchars($row['telefono'], ENT_QUOTES) . "'
            data-asunto='" . htmlspecialchars($row['asunto'], ENT_QUOTES) . "'
            data-mensaje='" . htmlspecialchars($row['mensaje'], ENT_QUOTES) . "'>
            Responder
        </button>
        </td>
        </tr>";
    }
    echo " 
    </tbody>
    </table>";
    ?>

    <div class="modal" tabindex="-1" id="exampleModal">
        <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Respuesta a :</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../php/respuesta.php" method="POST">
                    <div class="mb-3">
                        <label for="modal-email">Email:</label>
                        <input name="email" type="email" class="form-control" id="modal-email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modal-asunto">Asunto:</label>
                        <input name="asunto" type="text" class="form-control" id="modal-asunto" readonly>
                    </div>
                    <div class="mb-3">
                        <strong>Teléfono:</strong> <span id="modal-telefono"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Mensaje Original:</strong>
                        <input name="mensaje" class="form-control" rows="4" readonly id="modal-mensaje">
                    </div>
                    <div class="mb-3">
                        <label for="respuesta">Tu Respuesta:</label>
                        <textarea name="respuesta" class="form-control" rows="4" id="respuesta"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                    </form>
                </div>
                
                </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tabla-contactos').DataTable({
        order: [[5, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var exampleModal = document.getElementById('exampleModal');
    exampleModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        // Obtén los datos del botón
        var email = button.getAttribute('data-email');
        var asunto = button.getAttribute('data-asunto');
        var telefono = button.getAttribute('data-telefono');
        var mensaje = button.getAttribute('data-mensaje');
        // Llena los campos del modal
        document.getElementById('modal-email').value = email;
        document.getElementById('modal-asunto').value = asunto;
        document.getElementById('modal-telefono').textContent = telefono;
        document.getElementById('modal-mensaje').value = mensaje;
        // Limpia el campo de respuesta
        document.getElementById('respuesta').value = "";
    });
});


</script>

</body>
</html>



