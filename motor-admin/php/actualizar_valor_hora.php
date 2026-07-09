<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_empleado'];
    $valor_hora = $_POST['valor_hora'];

    $sql = "UPDATE empleado SET valor_hora = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("di", $valor_hora, $id);

    if ($stmt->execute()) {
        header("Location: ../ADMINISTRADOR/tabla-usuarios.php?tabla=empleados&actualizado=ok");
        exit();
    } else {
        echo "Error al actualizar el valor.";
    }
}
?>
