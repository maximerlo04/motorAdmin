<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $costo_base = $_POST['costo_base'];

    $sql = "UPDATE servicios SET nombre = ?, costo_base = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sdi", $nombre, $costo_base, $id);

    if ($stmt->execute()) {
        header("Location: ../../ADMINISTRADOR/ABM-servicios.php");
        exit();
    } else {
        echo "Error al actualizar el servicio.";
    }
}
?>

