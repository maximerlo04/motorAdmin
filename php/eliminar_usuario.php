<?php
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);
    
    // Primero verificamos si el usuario existe
    $sql_select = "SELECT nombre FROM usuarios WHERE id = ?";
    $stmt = $connection->prepare($sql_select);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => "Error: Usuario no encontrado"
        ]);
        exit();
    }
    
    // Iniciamos una transacción para asegurar la integridad de los datos
    $connection->begin_transaction();
    
    try {
        // Primero eliminamos los trabajos relacionados con este usuario
        $sql_delete_trabajos = "DELETE FROM trabajos WHERE id_usuario = ?";
        $stmt = $connection->prepare($sql_delete_trabajos);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Ahora eliminamos el usuario
        $sql_delete = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $connection->prepare($sql_delete);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Confirmamos la transacción
            $connection->commit();
            echo json_encode([
                'success' => true,
                'message' => "El usuario ha sido eliminado correctamente"
            ]);
        } else {
            // Revertimos la transacción en caso de error
            $connection->rollback();
            echo json_encode([
                'success' => false,
                'message' => "Error al eliminar el usuario"
            ]);
        }
    } catch (Exception $e) {
        // Revertimos la transacción en caso de excepción
        $connection->rollback();
        echo json_encode([
            'success' => false,
            'message' => "Error al eliminar el usuario: " . $e->getMessage()
        ]);
    }
    exit();
}

// Si se accede directamente a este archivo sin POST, redirigir a la página principal
header("Location: ../ADMINISTRADOR/tabla-usuarios.php");
exit();
?> 