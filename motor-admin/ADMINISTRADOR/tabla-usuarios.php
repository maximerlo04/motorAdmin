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
    <title>Handsontable - Hoja de Cálculo Web</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <?php
        include("../UTILS/sidebar.php");
        include("../connection.php");
        $sqlEmpleado = "SELECT 
  e.id,
  e.nombre AS nombre,
  e.email,
  e.dni,
  e.telefono,
  e.direccion,
  esp.nombre AS especialidad,
  e.valor_hora,
  e.fecha AS fecha
  
FROM empleado e
JOIN especialidades esp ON e.id_especialidad = esp.id
";


        $resultEmpleado = mysqli_query($connection,$sqlEmpleado);

        $sqlUsuario = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
        $resultUsuario = mysqli_query($connection,$sqlUsuario);

        $especialidades=mysqli_query($connection,"SELECT * FROM especialidades");
    ?>
    
    
    <div class="container my-5">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                El empleado ha sido eliminado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Ha ocurrido un error al eliminar el empleado.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="tablaSelect" class="form-label fw-bold">Seleccionar tabla:</label>
            <select class="form-select" id="tablaSelect">
                <option value="usuarios" <?= (isset($_GET['tabla']) && $_GET['tabla'] === 'empleados') ? '' : 'selected' ?>>Usuarios</option>
                <option value="empleados" <?= (isset($_GET['tabla']) && $_GET['tabla'] === 'empleados') ? 'selected' : '' ?>>Empleados</option>
            </select>
        </div>

        <div id="tabla-empleados" class="tabla-content <?= (isset($_GET['tabla']) && $_GET['tabla'] === 'empleados') ? '' : 'd-none' ?>">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Registrar Nuevo Empleado</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="../php/register-empleado.php" class="row g-4">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="col-md-4">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>

                        <div class="col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>

                        <div class="col-md-4">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>

                    
                    <div class="col-md-6">
                        <select id="especialidad" name="id_especialidad" class="form-select select2" required>
                            <?php while($especialidad = mysqli_fetch_assoc($especialidades)): ?>
                            <option value="<?= $especialidad['id'] ?>"><?= htmlspecialchars($especialidad['nombre']) ?></option>
                            <?php endwhile; ?>
                        </select>

                    </div>
                        <div class="col-md-6">
                            <label for="valor_hora" class="form-label">Valor por hora ($)</label>
                            <input type="number" step="0.01" class="form-control" id="valor_hora" name="valor_hora" >
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary btn-lg mt-3">
                                <i class="fas fa-user-plus me-2"></i> Registrar Empleado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <table class="table table-bordered table-hover align-middle" id="tabla-empleado">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Especialidad</th>
                        <th>Fecha de Registro</th>
                        <th>Valor por hora</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultEmpleado)) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['dni']) ?></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['direccion']) ?></td>
                            <td><?= htmlspecialchars($row['especialidad']) ?></td>
                            <td><?= htmlspecialchars($row['fecha']) ?></td>
                            <td>
                                <form method="POST" action="../php/actualizar_valor_hora.php" class="d-flex">
                                    <input type="hidden" name="id_empleado" value="<?= $row['id'] ?>">
                                    <input type="number" step="0.01" name="valor_hora" class="form-control form-control-sm me-1" 
                                        value="<?= $row['valor_hora'] ?>" style="width: 90px;">
                                    <button type="submit" class="btn btn-sm btn-success">💾</button>
                                </form>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nombre']) ?>', 'empleado')">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div id="tabla-usuarios" class="table-responsive tabla-content <?= (isset($_GET['tabla']) && $_GET['tabla'] === 'empleados') ? 'd-none' : '' ?>">
            <h4 class="mb-3">Usuarios Registrados</h4>
            <table id="tabla-usuario" class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>DNI</th>
                        <th>Patente</th>
                        <th>Modelo</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th></th>
                        <th>Actualizar rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultUsuario)) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['dni']) ?></td>
                            <td><?= htmlspecialchars($row['patente']) ?></td>
                            <td><?= htmlspecialchars($row['modelo']) ?></td>
                            <td><?= htmlspecialchars($row['rol']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_registro']) ?></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nombre']) ?>', 'user')">
                                    Eliminar
                                </button>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="checkbox-<?= $row['id'] ?>" 
                                    data-id="<?= $row['id'] ?>"
                                    <?= ($row['rol'] === 'Admin') ? 'checked' : '' ?>
                                >
                                <label class="form-check-label" for="checkbox-<?= $row['id'] ?>">Admin</label>
                                </div>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="deleteEmpleado()">Eliminar</button>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
    const tablaSelect = document.getElementById('tablaSelect');
    const tablas = document.querySelectorAll('.tabla-content');

    tablaSelect.addEventListener('change', () => {
        tablas.forEach(tabla => tabla.classList.add('d-none'));
        const tablaActiva = document.getElementById(`tabla-${tablaSelect.value}`);
        tablaActiva.classList.remove('d-none');
    });
        $('#tabla-usuario').DataTable({
            order: [[0, 'desc']], 
            columnDefs: [
                { orderable: false, targets: [8, 9] } 
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        $(document).ready(function () {
            $('#tabla-empleado').DataTable({
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [8, 9] } 
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
            });
        });
    </script>

    

    <script>
    let currentDeleteId = null;
    let currentDeleteName = null;
    let isUser = false;

    function confirmDelete(id, nombre, type) {
        currentDeleteId = id;
        currentDeleteName = nombre;
        isUser = type === 'user';
        document.getElementById('deleteMessage').textContent = `¿Desea quitar a ${nombre} de esta lista?`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    function deleteEmpleado() {
        if (!currentDeleteId) return;

        const formData = new FormData();
        formData.append('id', currentDeleteId);

        const url = isUser ? '../php/eliminar_usuario.php' : '../php/eliminar_empleado.php';

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            // Cerrar el modal de confirmación
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            deleteModal.hide();

            if (data.success) {
                // Mostrar el modal de éxito
                document.getElementById('successMessage').textContent = data.message;
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            } else {
                // Mostrar error específico del servidor
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar: ' + error.message);
        });
    }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
      const userId = this.dataset.id;
      if (!userId) {
        alert('ID de usuario no definido');
        this.checked = !this.checked; // revertir cambio
        return;
      }
      const nuevoRol = this.checked ? 'Admin' : 'Cliente';

      // Guardar estado anterior para revertir si hay error
      const estadoAnterior = !this.checked;

      fetch('../php/actualizar-rol.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${encodeURIComponent(userId)}&rol=${encodeURIComponent(nuevoRol)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.estado === 'success') {
          // Recargar la página para reflejar cambios
          location.reload();
        } else {
          alert('Error al actualizar el rol');
          this.checked = estadoAnterior; // revertir checkbox
        }
      })
      .catch(error => {
        alert('Error en la conexión');
        this.checked = estadoAnterior; // revertir checkbox
      });
            });
        });
    });

    </script>
</body>
</html>