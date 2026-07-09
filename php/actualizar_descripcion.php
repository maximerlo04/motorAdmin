<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['descripcion'])) {
    $id = intval($_POST['id']);
    $descripcion = mysqli_real_escape_string($connection, $_POST['descripcion']);
    
    $query = "UPDATE trabajos SET descripcion = '$descripcion' WHERE id = $id";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true, 'message' => 'Descripción actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la descripción']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
}
?> 