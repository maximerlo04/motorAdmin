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
    <title>Control de Stock</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <?php
include("../UTILS/sidebar.php");
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    
    if (isset($_POST["cantidad"])) {
        $cantidad = $_POST["cantidad"];
        $sql_update = "UPDATE stock SET cantidad = ? WHERE id = ?";
        $stmt = $connection->prepare($sql_update);
        $stmt->bind_param("ii", $cantidad, $id);
        $stmt->execute();
    }
    
    if (isset($_POST["precio"])) {
        $precio = $_POST["precio"];
        $sql_update = "UPDATE stock SET precio_unitario = ? WHERE id = ?";
        $stmt = $connection->prepare($sql_update);
        $stmt->bind_param("di", $precio, $id);
        $stmt->execute();
    }
}

$sql = "SELECT * FROM stock ORDER BY id DESC";
$result = mysqli_query($connection, $sql);
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Stock de Productos</h2>
    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Agregar nuevo producto
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="agregar" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="agregar">Agregar Producto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" action="../php/agregar_producto.php" method="POST" enctype="multipart/form-data">
            <div class="col-md-6">
                <input name="Nombre" type="text" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-6">
                <input name="Marca" type="text" class="form-control" placeholder="Marca" required>
            </div>
            <div class="col-md-6">
                <input name="Cantidad" type="number" class="form-control" placeholder="Cantidad">
            </div>
            <div class="col-md-6">
                <input name="Precio" type="number" class="form-control" placeholder="Precio">
            </div>
            <div class="col-md-12">
                <label for="imagen" class="form-label">Subir archivo (foto del producto):</label>
                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary px-5">Enviar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
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
        <button type="button" class="btn btn-danger" onclick="deleteProduct()">Eliminar</button>
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

<!-- Modal de Confirmación de Actualización -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Confirmar Actualización</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="updateMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="confirmUpdate()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmación de Actualización de Precio -->
<div class="modal fade" id="updatePriceModal" tabindex="-1" aria-labelledby="updatePriceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updatePriceModalLabel">Confirmar Actualización de Precio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="updatePriceMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="confirmPriceUpdate()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

    <div class="table-responsive">
        <table id="tabla-stock" class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombre del Producto</th>
                    <th>Marca</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <?php if (!empty($row['imagen'])): ?>
                                <img src="<?= htmlspecialchars($row['imagen']) ?>" alt="Foto producto" style="width:60px; height:60px; object-fit:cover; border-radius:10px;">
                            <?php else: ?>
                                <span class="text-muted">Sin foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($row['marca']) ?></td>
                        <td>
                            <form method="POST" class="d-flex align-items-center" onsubmit="event.preventDefault(); confirmUpdateQuantity(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nombre_producto']) ?>', this.cantidad.value, this);">
                                <input type="number" name="cantidad" class="form-control me-2" value="<?= $row['cantidad'] ?>" min="0">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" class="d-flex align-items-center" onsubmit="event.preventDefault(); confirmPriceUpdateQuantity(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nombre_producto']) ?>', this.precio.value, this);">
                                <input type="number" name="precio" class="form-control me-2" value="<?= $row['precio_unitario'] ?>" min="0" step="0.01">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                            </form>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nombre_producto']) ?>')">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
let currentDeleteId = null;
let currentUpdateId = null;
let currentUpdateForm = null;
let currentPriceUpdateForm = null;

function confirmDelete(id, nombre) {
    currentDeleteId = id;
    document.getElementById('deleteMessage').textContent = `¿Está seguro que desea eliminar el producto "${nombre}"?`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function deleteProduct() {
    if (!currentDeleteId) return;

    const formData = new FormData();
    formData.append('id', currentDeleteId);

    fetch('../php/eliminar_producto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Cerrar el modal de confirmación
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        deleteModal.hide();

        // Mostrar el modal de éxito
        document.getElementById('successMessage').textContent = data.message;
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el producto');
    });
}

function confirmUpdateQuantity(id, nombre, cantidad, form) {
    currentUpdateId = id;
    currentUpdateForm = form;
    document.getElementById('updateMessage').textContent = `¿Desea actualizar la cantidad de "${nombre}" a ${cantidad}?`;
    const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
    updateModal.show();
}

function confirmUpdate() {
    if (currentUpdateForm) {
        currentUpdateForm.submit();
    }
}

function confirmPriceUpdateQuantity(id, nombre, precio, form) {
    currentPriceUpdateForm = form;
    document.getElementById('updatePriceMessage').textContent = `¿Desea actualizar el precio de "${nombre}" a $${precio}?`;
    const updatePriceModal = new bootstrap.Modal(document.getElementById('updatePriceModal'));
    updatePriceModal.show();
}

function confirmPriceUpdate() {
    if (currentPriceUpdateForm) {
        currentPriceUpdateForm.submit();
    }
}
</script>

<script>
    const btnAbrir=document.querySelector("btnAbrir");
    btnAbrir.addEventListener("click",()=>{})
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tabla-stock').DataTable({
        order: [[0, 'desc']],
        columnDefs: [
          { orderable: false, targets: [4, 5, 6] } 
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
        });
    });
</script>
</body>
</html>