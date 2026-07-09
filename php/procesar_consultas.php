<?php
session_start();
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si el usuario está logueado, toma nombre y email de la sesión
    if (isset($_SESSION['nombre']) && isset($_SESSION['email'])) {
        $nombre = $connection->real_escape_string($_SESSION['nombre']);
        $email = $connection->real_escape_string($_SESSION['email']);
    } else {
        // Si no está logueado, toma los datos del formulario
        $nombre = $connection->real_escape_string($_POST['nombre']);
        $email = $connection->real_escape_string($_POST['email']);
    }

    $servicio = $connection->real_escape_string($_POST['servicio']);
    $mensaje = $connection->real_escape_string($_POST['mensaje']);

    $sql = "INSERT INTO consultas (nombre, email, servicio, mensaje) 
            VALUES ('$nombre', '$email', '$servicio', '$mensaje')";

    if ($connection->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Mensaje enviado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
