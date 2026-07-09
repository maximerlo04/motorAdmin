<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['id_empleado'])) {
    $id = intval($_POST['id']);
    $id_empleado = intval($_POST['id_empleado']);
    
    $query = "UPDATE trabajos SET id_empleado = $id_empleado WHERE id = $id";
    
    if (mysqli_query($connection, $query)) {
        // Obtener el nombre del empleado actualizado
        $query_empleado = "SELECT nombre FROM empleado WHERE id = $id_empleado";
        $result = mysqli_query($connection, $query_empleado);
        $empleado = mysqli_fetch_assoc($result);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Empleado actualizado correctamente',
            'nombre_empleado' => $empleado['nombre']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el empleado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
}
?> 