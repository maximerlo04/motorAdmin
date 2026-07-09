<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_usuario = intval($_POST['id_usuario']);
  $id_empleado = intval($_POST['id_empleado']);
  $descripcion = mysqli_real_escape_string($connection, $_POST['descripcion']);
  $horas_estimadas = intval($_POST['horas_estimadas']);
  $id_servicio = intval($_POST['id_servicio']);

  $query = "INSERT INTO trabajos (id_usuario, id_empleado, id_servicio, descripcion, horas_estimadas, estado)
            VALUES (?, ?, ?, ?, ?, 'Pendiente')";

  $stmt = $connection->prepare($query);
  $stmt->bind_param("iiisi", $id_usuario, $id_empleado, $id_servicio, $descripcion, $horas_estimadas);
  if ($stmt->execute()) {
        header("Location: ../ADMINISTRADOR/asignar-trabajos.php");
        exit();
    } else {
        echo "Error al crear trabajo.";
    }
}

?>
