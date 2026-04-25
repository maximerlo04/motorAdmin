<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];

    $sql = "UPDATE especialidades SET nombre = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        header("Location: ../../ADMINISTRADOR/ABM-especialidades.php");
        exit();
    } else {
        echo "Error al actualizar el especialidad.";
    }
}
?>