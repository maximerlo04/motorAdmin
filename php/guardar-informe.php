<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_trabajo'];
    $informe = $_POST['informe'];

    $stmt = $connection->prepare("UPDATE trabajos SET informe = ? WHERE id = ?");
    $stmt->bind_param("si", $informe, $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../ADMINISTRADOR/asignar-trabajos.php");
exit;
?>
