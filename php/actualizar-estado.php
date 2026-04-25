<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_trabajo']);
    $estado = mysqli_real_escape_string($connection, $_POST['estado']);

    $query = "UPDATE trabajos SET estado = '$estado' WHERE id = $id";

    if (mysqli_query($connection, $query)) {
        header("Location: ../ADMINISTRADOR/asignar-trabajos.php");
        exit;
    } else {
        echo "Error al actualizar el estado: " . mysqli_error($connection);
    }
}
?>
