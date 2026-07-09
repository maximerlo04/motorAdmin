<?php
include '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $connection->prepare("DELETE FROM servicios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../../ADMINISTRADOR/ABM-servicios.php");
    exit();
}
?>
