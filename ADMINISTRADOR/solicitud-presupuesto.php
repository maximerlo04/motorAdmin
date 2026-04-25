<?php
if (!isset($_SESSION)) {
    session_start();
}
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
    <title>Tabla de Contactos y Presupuestos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <?php
    include("../UTILS/sidebar.php");
    include("../connection.php");

    // Consulta para presupuestos (consultas)
    $sqlConsultas = "SELECT * FROM consultas ORDER BY fecha DESC";
    $resultConsultas = $connection->query($sqlConsultas);
    
    // Consulta para contactos (mensajes)
    $sqlMensajes = "SELECT * FROM mensajes ORDER BY fecha DESC";
    $resultMensajes = $connection->query($sqlMensajes);
    
    ?>
    <div class="container my-5">
        <h2 class="text-center mb-4">Tabla de Contactos y Presupuestos</h2>
        
        <div class="mb-3">
            <label for="tablaSelect" class="form-label fw-bold">Seleccionar tabla:</label>
            <select class="form-select" id="tablaSelect">
                <option value="presupuestos">Solicitudes de Presupuesto</option>
                <option value="contactos">Consultas Recibidas</option>
            </select>
        </div>

        <!-- Tabla de Presupuestos -->
        <div id="tabla-presupuestos" class="tabla-content">
            <div class="table-responsive">
                <table id="tabla-consultas" class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Servicio</th>
                            <th>Mensaje</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultConsultas->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["id"] ?></td>
                            <td><?= htmlspecialchars($row["nombre"]) ?></td>
                            <td><?= htmlspecialchars($row["email"]) ?></td>
                            <td><?= htmlspecialchars($row["servicio"]) ?></td>
                            <td><?= nl2br(htmlspecialchars($row["mensaje"])) ?></td>
                            <td><?= $row["fecha"] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla de Contactos -->
        <div id="tabla-contactos" class="tabla-content d-none">
            <div class="table-responsive">
                <table id="tabla-mensajes" class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Asunto</th>
                            <th>Mensaje</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultMensajes->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["id"] ?></td>
                            <td><?= htmlspecialchars($row["nombre"]) ?></td>
                            <td><?= htmlspecialchars($row["email"]) ?></td>
                            <td><?= htmlspecialchars($row["telefono"]) ?></td>
                            <td><?= htmlspecialchars($row["asunto"]) ?></td>
                            <td><?= nl2br(htmlspecialchars($row["mensaje"])) ?></td>
                            <td><?= $row["fecha"] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- DataTables Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function () {
        // Inicializar DataTable para presupuestos
        var tablaConsultas = $('#tabla-consultas').DataTable({
            order: [[5, 'desc']], // Ordenar por fecha descendente
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        // Inicializar DataTable para contactos
        var tablaMensajes = $('#tabla-mensajes').DataTable({
            order: [[6, 'desc']], // Ordenar por fecha descendente
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        // Función para cambiar entre tablas
        $('#tablaSelect').change(function() {
            var selectedValue = $(this).val();
            
            // Ocultar todas las tablas
            $('.tabla-content').addClass('d-none');
            
            // Mostrar la tabla seleccionada
            if (selectedValue === 'presupuestos') {
                $('#tabla-presupuestos').removeClass('d-none');
                tablaConsultas.columns.adjust(); // Ajustar columnas
            } else if (selectedValue === 'contactos') {
                $('#tabla-contactos').removeClass('d-none');
                tablaMensajes.columns.adjust(); // Ajustar columnas
            }
        });
    });
    </script>
</body>
</html>