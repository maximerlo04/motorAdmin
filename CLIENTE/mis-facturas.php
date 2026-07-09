<?php
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: ../PAGES/index.php");
    exit();
}

include '../connection.php';
$id = $_SESSION['id']; // Asegúrate de que esta variable esté en la sesión

$query = "
SELECT f.id, f.fecha_emision, s.nombre AS servicio, f.total, f.estado_pago
FROM facturas f
JOIN trabajos t ON f.id_trabajo = t.id
JOIN servicios s ON t.id_servicio = s.id
WHERE t.id_usuario = $id
ORDER BY f.fecha_emision DESC
";

$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Facturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
    <?php include("../UTILS/header-cliente-pages.php"); ?>

    <div class="container my-5">
        <h2 class="text-center mb-4">Mis Facturas</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Fecha</th>
                            <th>Servicio</th>
                            <th>Monto Total</th>
                            <th>Estado de Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($row['fecha_emision'])) ?></td>
                                <td><?= htmlspecialchars($row['servicio']) ?></td>
                                <td>$<?= number_format($row['total'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= $row['estado_pago'] === 'Pagado' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $row['estado_pago'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="../facturas/factura_<?= $row['id'] ?>.pdf" class="btn btn-sm btn-outline-primary">
                                        Descargar PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">No se encontraron facturas.</p>
        <?php endif; ?>
    </div>

    <?php include("../UTILS/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
