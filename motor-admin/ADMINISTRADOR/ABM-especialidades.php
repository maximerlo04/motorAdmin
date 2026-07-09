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
    <title>Especialidades</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9Oer+0wPthOhA8rsVjQerV_D3B3z_oB-4o5uG0i3F_M4hK2f" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/index.css">   
    <link rel="stylesheet" href="../CSS/admin.css">     
</head>

<body>
    <?php
    include '../connection.php';
    include("../UTILS/sidebar.php");
    $servicios = $connection->query("SELECT * FROM especialidades");
    ?>
    
    <div class="container mt-4">
        <h2>Gestión de Especialidades</h2>
        <form method="POST" action="../php/especialidades/crear-especialidad.php" class="mb-4 d-flex">
            <input type="text" name="nombre" class="form-control me-2" placeholder="Nueva Especialidad" required>
            <button type="submit" class="btn btn-success">Agregar</button>
        </form>

        <table id="tablaServicios" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del servicio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $servicios->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td>
                            <button 
                                class="btn btn-warning btn-sm btn-editar" 
                                data-id="<?= $row['id'] ?>" 
                                data-nombre="<?= htmlspecialchars($row['nombre']) ?>"  
                                data-bs-toggle="modal" 
                                data-bs-target="#editarModal">
                                Editar
                            </button>
                            <button 
                                class="btn btn-danger btn-sm btn-eliminar" 
                                data-id="<?= $row['id'] ?>" 
                                data-bs-toggle="modal" 
                                data-bs-target="#eliminarModal">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditarServicio" method="POST" action="../php/especialidades/actualizar-especialidad.php">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" name="id" id="editarId">
                <div class="mb-3">
                    <label for="editarNombre" class="form-label">Nombre del servicio</label>
                    <input type="text" class="form-control" name="nombre" id="editarNombre" required>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    
    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="../php/especialidades/eliminar-especialidad.php">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="eliminarModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                ¿Seguro que deseas eliminar esta Especialidad?
                <input type="hidden" name="id" id="eliminarId">
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tablaServicios').DataTable({
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [2] } 
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
            });
        });
    </script>
    
    <script>
        $(document).on("click", ".btn-editar", function () {
            const id = $(this).data("id");
            const nombre = $(this).data("nombre");
            $("#editarId").val(id);
            $("#editarNombre").val(nombre);
        });
    </script>
    <script>
        $(document).on("click", ".btn-eliminar", function () {
            const id = $(this).data("id");
            $("#eliminarId").val(id);
        });
    </script>
    
</body>
</html>



