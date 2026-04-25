<?php
include("../connection.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

$nombre = $connection->real_escape_string($_POST['nombre']);
$email = $connection->real_escape_string($_POST['email']);
$dni = $connection->real_escape_string($_POST['dni']);
$telefono = $connection->real_escape_string($_POST['telefono']);
$direccion = $connection->real_escape_string($_POST['direccion']);
$especialidad = $_POST['id_especialidad'];
$valor_hora = $_POST['valor_hora'];

// Usar prepared statements
$stmt = $connection->prepare("INSERT INTO empleado (nombre, email, dni, telefono, direccion, id_especialidad, valor_hora) VALUES (?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Error en la preparación: " . $connection->error);
}

$stmt->bind_param("sssssid", $nombre, $email, $dni, $telefono, $direccion, $especialidad, $valor_hora);

if ($stmt->execute()) {
    header("Location: ../../motor-admin/ADMINISTRADOR/tabla-usuarios.php?tabla=empleados");
    exit();
} else {
    echo "Error al insertar: " . $stmt->error;
}

$stmt->close();


    /*$nombre = $connection->real_escape_string($_POST['nombre']);
    $email = $connection->real_escape_string($_POST['email']);
    $dni = $connection->real_escape_string($_POST['dni']);
    $telefono = $connection->real_escape_string($_POST['telefono']);
    $direccion = $connection->real_escape_string($_POST['direccion']);
    $especialidad = $_POST['id'];
    $valor_hora = $_POST['valor_hora'];
    
    $sql = "INSERT INTO empleado (nombre, email, dni, telefono, direccion, id_especialidad, valor_hora) 
            VALUES ('$nombre', '$email', '$dni', '$telefono', '$direccion', '$especialidad', '$valor_hora')";

    if ($connection->query($sql) === TRUE) {
        header("Location: ../../motor-admin/ADMINISTRADOR/tabla-usuarios.php?tabla=empleados");
        exit();
    }

    $connection->close();*/
}


?>