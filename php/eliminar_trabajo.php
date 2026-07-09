<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    $query = "DELETE FROM trabajos WHERE id = $id";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true, 'message' => 'Trabajo eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el trabajo']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
}
?> 