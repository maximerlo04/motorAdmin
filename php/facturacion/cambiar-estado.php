<?php
include("../../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_factura']);
    $query = "UPDATE facturas SET estado_pago = 'Pagado' WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: ../../ADMINISTRADOR/facturacion.php");
}
?>
