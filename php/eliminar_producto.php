<?php
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    
    // Primero obtenemos el nombre del producto
    $sql_select = "SELECT nombre_producto FROM stock WHERE id = ?";
    $stmt = $connection->prepare($sql_select);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $nombre_producto = $row['nombre_producto'];
    
    // Luego eliminamos el producto
    $sql_delete = "DELETE FROM stock WHERE id = ?";
    $stmt = $connection->prepare($sql_delete);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => "El producto '$nombre_producto' se eliminó correctamente"
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Error al eliminar el producto"
        ]);
    }
    exit();
}

header("Location: control-stock.php"); // o el nombre de tu archivo principal
exit();