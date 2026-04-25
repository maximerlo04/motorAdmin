<?php
include("../connection.php");
// Obtener los datos del POST
$userId = $_POST['id'];
$nuevoRol = $_POST['rol'];

// Actualizar el rol en la base de datos
$query = "UPDATE usuarios SET rol = ? WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "si", $nuevoRol, $userId);
mysqli_stmt_execute($stmt);

// Verificar si la actualización fue exitosa
if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['estado' => 'success']);
} else {
    echo json_encode(['estado' => 'error', 'mensaje' => 'No se pudo actualizar el rol']);
}

mysqli_close($connection);
?>