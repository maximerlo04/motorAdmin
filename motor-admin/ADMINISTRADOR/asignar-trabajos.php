<?php 
if (!isset($_SESSION)) {
    session_start();
}
        if ($_SESSION['rol']!=='Admin')
        {
          header("Location:../PAGES/index.php");
        } 
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handsontable - Hoja de Cálculo Web</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <?php
        include("../UTILS/sidebar.php");
        include("../connection.php");
        // Obtener lista de clientes
        $clients = mysqli_query($connection, "SELECT id, nombre, patente, modelo FROM usuarios WHERE rol != 'Admin'");
        // Obtener lista de empleados
        $employees = mysqli_query($connection, "SELECT id, nombre FROM empleado");
        $queryTrabajos = "
            SELECT 
                trabajos.id,
                usuarios.nombre AS nombre,
                usuarios.patente,
                usuarios.modelo,
                trabajos.id_empleado,
                empleado.nombre AS nombre_empleado,
                trabajos.descripcion,
                trabajos.estado,
                trabajos.informe,
                servicios.nombre AS nombre_servicio,
                trabajos.horas_estimadas
            FROM trabajos
            JOIN usuarios ON trabajos.id_usuario = usuarios.id
            JOIN empleado ON trabajos.id_empleado = empleado.id
            LEFT JOIN servicios ON trabajos.id_servicio = servicios.id
            ORDER BY trabajos.id DESC
        ";
        $resultUsuario = mysqli_query($connection, $queryTrabajos);
        $servicios = mysqli_query($connection, "SELECT id, nombre FROM servicios");

        $trabajosActivos = mysqli_query($connection, "
            SELECT trabajos.id, trabajos.id_usuario, trabajos.id_empleado, trabajos.estado, usuarios.patente
            FROM trabajos
            JOIN usuarios ON trabajos.id_usuario = usuarios.id
            WHERE trabajos.estado != 'Finalizado'
        ");

        $listaTrabajos = [];
        while($t = mysqli_fetch_assoc($trabajosActivos)) {
            $listaTrabajos[] = $t;
        }

    ?>
    
    
    <div class="container my-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="fas fa-briefcase me-2"></i> Asignar Nuevo Trabajo</h4>
            </div>
            <div class="card-body">
                <form action="../php/guardar-trabajo.php" method="POST" class="row g-4">

                    <div class="col-12"> <label for="cliente" class="form-label">Cliente</label>
                        <select id="cliente" name="id_usuario" class="form-select select2" required>
                            <option value="">– Seleccioná cliente –</option>
                            <?php while($c = mysqli_fetch_assoc($clients)): ?>
                            <option value="<?= $c['id'] ?>"
                                data-patente="<?= htmlspecialchars($c['patente']) ?>"
                                data-modelo="<?= htmlspecialchars($c['modelo']) ?>">
                                <?= htmlspecialchars($c['nombre']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Patente</label>
                        <input id="patente" type="text" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Modelo</label>
                        <input id="modelo" type="text" class="form-control" readonly>
                    </div>

                    <div class="col-12">
                        <label for="empleado" class="form-label">Empleado Asignado</label>
                        <select id="empleado" name="id_empleado" class="form-select select2" required>
                            <option value="">– Elegí empleado –</option>
                            <?php while($e = mysqli_fetch_assoc($employees)): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="servicio" class="form-label">Servicio</label>
                        <select id="servicio" name="id_servicio" class="form-select select2" required>
                            <option value="">– Elegí un servicio –</option>
                            <?php while($s = mysqli_fetch_assoc($servicios)): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción del Trabajo</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="col-md-6"> <label for="horas_estimadas" class="form-label">Horas Estimadas</label>
                        <input type="number" name="horas_estimadas" step="0.1" min="0" class="form-control" required>
                    </div>
                    <div class="col-12 text-end"> <button type="submit" class="btn btn-primary btn-lg mt-3"> <i class="fas fa-save me-2"></i> Guardar Trabajo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <table id="tabla-asignar-trabajo" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Cliente</th>
                    <th>Patente</th>
                    <th>Modelo</th>
                    <th>Empleado Asignado</th>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Horas Estimadas</th>
                    <th>Estado</th>
                    <th>Informe</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultUsuario)) : ?> 
            <?php 
                // Asignar clase según estado
                $claseEstado = '';
                switch ($row['estado']) {
                    case 'Pendiente':
                        $claseEstado = 'table-secondary'; // amarillo
                        break;
                    case 'En progreso':
                        $claseEstado = 'table-info'; // azul claro
                        break;
                    case 'Finalizado':
                        $claseEstado = 'table-success'; // verde
                        break;
                    default:
                        $claseEstado = '';
                }
            ?>
            <tr data-trabajo-id="<?= $row['id'] ?>" class="<?= $claseEstado ?>">
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['patente']) ?></td>
                <td><?= htmlspecialchars($row['modelo']) ?></td>
                <td>
                    <select class="form-select form-select-sm empleado-select" 
                            data-trabajo-id="<?= $row['id'] ?>" 
                            onchange="actualizarEmpleado(this)">
                        <?php 
                        mysqli_data_seek($employees, 0);
                        while($e = mysqli_fetch_assoc($employees)): 
                        ?>
                            <option value="<?= $e['id'] ?>" <?= $e['id'] == $row['id_empleado'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
                <td><?= htmlspecialchars($row['nombre_servicio']) ?></td>
                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                <td><?= htmlspecialchars($row['horas_estimadas']) ?? '-' ?></td>
                <td>
                    <form method="POST" action="../php/actualizar-estado.php">
                        <input type="hidden" name="id_trabajo" value="<?= $row['id'] ?>">
                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="Pendiente" <?= $row['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="En progreso" <?= $row['estado'] == 'En progreso' ? 'selected' : '' ?>>En progreso</option>
                            <option value="Finalizado" <?= $row['estado'] == 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
                        </select>
                    </form>
                </td>
                <td>
                    <button 
                        class="btn btn-info btn-sm" 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalInforme"
                        onclick="abrirModalInforme(<?= $row['id'] ?>, `<?= htmlspecialchars($row['informe'], ENT_QUOTES) ?>`)"
                    >
                        Ver
                    </button>
                    <div class="collapse" id="informe<?= $row['id'] ?>">
                        <div class="card card-body mt-2"><?= nl2br(htmlspecialchars($row['informe'])) ?></div>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editarTrabajo(<?= $row['id'] ?>, `<?= htmlspecialchars($row['descripcion'], ENT_QUOTES) ?>`)">Editar</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminarTrabajo(<?= $row['id'] ?>)">Eliminar</button>
                </td>
            </tr>
        <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Confirmación para Eliminar Trabajo -->
    <div class="modal fade" id="modalEliminarTrabajo" tabindex="-1" aria-labelledby="modalEliminarTrabajoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarTrabajoLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Trabajo Asignado?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarTrabajo()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Éxito -->
    <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExitoLabel">Éxito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Trabajo eliminado correctamente</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalInforme" tabindex="-1" aria-labelledby="modalInformeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form id="formInforme" method="POST" action="../php/guardar-informe.php">
                <div class="modal-header">
                <h5 class="modal-title" id="modalInformeLabel">Informe del Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" name="id_trabajo" id="modalTrabajoId">
                <div class="mb-3">
                    <label for="informeTexto" class="form-label">Informe</label>
                    <textarea class="form-control" id="informeTexto" name="informe" rows="6" required></textarea>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Descripción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        <input type="hidden" id="editTrabajoId">
                        <div class="mb-3">
                            <label for="editDescripcion" class="form-label">Descripción del Trabajo</label>
                            <textarea class="form-control" id="editDescripcion" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicion()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Trabajos Finalizados -->
    <div class="modal fade" id="modalTrabajosFinalizados" tabindex="-1" aria-labelledby="modalTrabajosFinalizadosLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTrabajosFinalizadosLabel">Trabajos Finalizados</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="table-success">
                  <tr>
                    <th>Cliente</th>
                    <th>Patente</th>
                    <th>Modelo</th>
                    <th>Empleado</th>
                    <th>Descripción</th>
                    <th>Informe</th>
                  </tr>
                </thead>
                <tbody id="tbodyFinalizados">
                  <!-- Se llenará por JS -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Llenar patente y modelo según cliente seleccionado
    document.getElementById('cliente').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('patente').value = selected.getAttribute('data-patente') || '';
    document.getElementById('modelo').value = selected.getAttribute('data-modelo') || '';
    });

    function abrirModalInforme(id, informe) {
        document.getElementById('modalTrabajoId').value = id;
        document.getElementById('informeTexto').value = informe;
    }

    const trabajosActivos = <?= json_encode($listaTrabajos) ?>;

    document.querySelector('form[action="../php/guardar-trabajo.php"]').addEventListener('submit', function(e) {
        const idEmpleado = document.getElementById('empleado').value;
        const selectCliente = document.getElementById('cliente');
        const idCliente = selectCliente.value;
        const patente = selectCliente.options[selectCliente.selectedIndex]?.getAttribute('data-patente');

        let empleadoAsignado = false;
        let vehiculoAsignado = false;

        trabajosActivos.forEach(trabajo => {
            if (trabajo.id_empleado === idEmpleado) {
                empleadoAsignado = true;
            }
            if (trabajo.patente === patente) {
                vehiculoAsignado = true;
            }
        });

        if (empleadoAsignado || vehiculoAsignado) {
            let mensaje = "Atención:\n";
            if (empleadoAsignado) mensaje += "- El empleado ya está asignado a un trabajo activo.\n";
            if (vehiculoAsignado) mensaje += "- El vehículo ya está asignado a un trabajo activo.\n";
            mensaje += "¿Deseás continuar de todas formas?";

            if (!confirm(mensaje)) {
                e.preventDefault(); // Cancelar envío
            }
        }
    });

    let trabajoIdAEliminar = null;

    function confirmarEliminarTrabajo(id) {
        trabajoIdAEliminar = id;
        const modal = new bootstrap.Modal(document.getElementById('modalEliminarTrabajo'));
        modal.show();
    }

    function eliminarTrabajo() {
        if (trabajoIdAEliminar) {
            const formData = new FormData();
            formData.append('id', trabajoIdAEliminar);

            fetch('../php/eliminar_trabajo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar el modal de confirmación
                    const modalConfirmacion = bootstrap.Modal.getInstance(document.getElementById('modalEliminarTrabajo'));
                    modalConfirmacion.hide();
                    
                    // Obtener la instancia de DataTable
                    const tabla = $('#tabla-asignar-trabajo').DataTable();
                    
                    // Encontrar y eliminar la fila de la tabla
                    const fila = tabla.row(`tr[data-trabajo-id="${trabajoIdAEliminar}"]`);
                    fila.remove();
                    
                    // Redibujar la tabla
                    tabla.draw();
                    
                    // Mostrar modal de éxito
                    const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
                    modalExito.show();
                } else {
                    alert('Error al eliminar el trabajo: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el trabajo');
            });
        }
    }

    let trabajoIdAEditar = null;

    function editarTrabajo(id, descripcion) {
        trabajoIdAEditar = id;
        document.getElementById('editTrabajoId').value = id;
        document.getElementById('editDescripcion').value = descripcion;
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
        modal.show();
    }

    function guardarEdicion() {
        if (!trabajoIdAEditar) return;

        const descripcion = document.getElementById('editDescripcion').value;
        const formData = new FormData();
        formData.append('id', trabajoIdAEditar);
        formData.append('descripcion', descripcion);

        fetch('../php/actualizar_descripcion.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar el modal de edición
                const modalEdicion = bootstrap.Modal.getInstance(document.getElementById('modalEditar'));
                modalEdicion.hide();

                // Actualizar la descripción en la tabla
                const tabla = $('#tabla-asignar-trabajo').DataTable();
                const fila = tabla.row(`tr[data-trabajo-id="${trabajoIdAEditar}"]`);
                const datos = fila.data();
                datos[4] = descripcion; // La descripción está en la columna 5 (índice 4)
                fila.data(datos);

                // Mostrar modal de éxito
                const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
                document.querySelector('#modalExito .modal-body p').textContent = 'Descripción actualizada correctamente';
                modalExito.show();
            } else {
                alert('Error al actualizar la descripción: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar la descripción');
        });
    }

    function actualizarEmpleado(select) {
        const trabajoId = select.getAttribute('data-trabajo-id');
        const empleadoId = select.value;
        
        const formData = new FormData();
        formData.append('id', trabajoId);
        formData.append('id_empleado', empleadoId);

        fetch('../php/actualizar_empleado.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar modal de éxito
                const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
                document.querySelector('#modalExito .modal-body p').textContent = 'Empleado actualizado correctamente';
                modalExito.show();
            } else {
                alert('Error al actualizar el empleado: ' + data.message);
                // Revertir la selección en caso de error
                select.value = select.getAttribute('data-original-value');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el empleado');
            // Revertir la selección en caso de error
            select.value = select.getAttribute('data-original-value');
        });
    }

    // Guardar el valor original del select cuando se abre
    document.querySelectorAll('.empleado-select').forEach(select => {
        select.addEventListener('focus', function() {
            this.setAttribute('data-original-value', this.value);
        });
    });

    // Colorear el select de estado según el valor y bloquear si es finalizado
    function colorearEstados() {
        document.querySelectorAll('.estado-select').forEach(function(select) {
            select.classList.remove('bg-success', 'bg-warning', 'bg-danger', 'text-white');
            if (select.value === 'Finalizado') {
                select.classList.add('bg-success', 'text-white');
                select.disabled = true;
                // Solo deshabilitar el select de empleado, pero mantener el botón de informe habilitado
                const tr = select.closest('tr');
                if (tr) {
                    const empleadoSelect = tr.querySelector('.empleado-select');
                    if (empleadoSelect) empleadoSelect.disabled = true;
                    const btnEditar = tr.querySelector('.btn-warning');
                    if (btnEditar) btnEditar.disabled = true;
                    // NO deshabilitar el botón de informe
                }
            } else if (select.value === 'En progreso') {
                select.classList.add('bg-warning');
                select.disabled = false;
                const tr = select.closest('tr');
                if (tr) {
                    const empleadoSelect = tr.querySelector('.empleado-select');
                    if (empleadoSelect) empleadoSelect.disabled = false;
                    const btnEditar = tr.querySelector('.btn-warning');
                    if (btnEditar) btnEditar.disabled = false;
                }
            } else if (select.value === 'Pendiente') {
                select.classList.add('bg-danger', 'text-white');
                select.disabled = false;
                const tr = select.closest('tr');
                if (tr) {
                    const empleadoSelect = tr.querySelector('.empleado-select');
                    if (empleadoSelect) empleadoSelect.disabled = false;
                    const btnEditar = tr.querySelector('.btn-warning');
                    if (btnEditar) btnEditar.disabled = false;
                }
            }
        });
    }
    colorearEstados();
    document.querySelectorAll('.estado-select').forEach(function(select) {
        select.addEventListener('change', colorearEstados);
    });

    // Llenar el modal de trabajos finalizados
    function llenarModalFinalizados() {
        const filas = document.querySelectorAll('#tabla-asignar-trabajo tbody tr');
        const tbody = document.getElementById('tbodyFinalizados');
        tbody.innerHTML = '';
        filas.forEach(function(tr) {
            if (tr.getAttribute('data-estado') === 'Finalizado') {
                const tds = tr.querySelectorAll('td');
                const informe = tr.getAttribute('data-informe') || '';
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${tds[0].innerText}</td>
                    <td>${tds[1].innerText}</td>
                    <td>${tds[2].innerText}</td>
                    <td>${tds[3].querySelector('select') ? tds[3].querySelector('select').selectedOptions[0].text : ''}</td>
                    <td>${tds[4].innerText}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="verInformeFinalizado('${informe.replace(/'/g, "\\'")}')">
                            Ver Informe
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            }
        });
    }
    document.getElementById('btnTrabajosRealizados').addEventListener('click', llenarModalFinalizados);

    function verInformeFinalizado(informe) {
        if (informe && informe.trim() !== '') {
            alert('Informe del trabajo:\n\n' + informe);
        } else {
            alert('No hay informe disponible para este trabajo.');
        }
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#tabla-asignar-trabajo').DataTable({
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [3, 7, 8, 9] } 
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
            $('.select2').select2({
                placeholder: "Buscá una opción...",
                allowClear: true
            });

            $('#cliente').on('change', function() {
                var selected = $(this).find(':selected');
                $('#patente').val(selected.data('patente') || '');
                $('#modelo').val(selected.data('modelo') || '');
            });
        });
    </script>
</body>
</html>