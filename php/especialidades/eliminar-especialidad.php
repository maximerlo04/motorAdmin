<?php
include '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $connection->prepare("DELETE FROM especialidades WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../../ADMINISTRADOR/ABM-especialidades.php");
    exit();
}
?>