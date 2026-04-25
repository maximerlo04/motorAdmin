<?php
include("../connection.php");

    $nombre = $_POST["Nombre"];
    $marca = $_POST["Marca"];
    $cantidad = $_POST["Cantidad"];
    $precio = $_POST["Precio"];
    
    $imagen_ruta = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaDestino = '../images/' . uniqid() . '_' . $nombreArchivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen_ruta = $rutaDestino;
        }
    }

    mysqli_query($connection,"INSERT INTO stock (nombre_producto, marca, cantidad, precio_unitario, imagen) VALUES ('$nombre', '$marca', '$cantidad', '$precio', '$imagen_ruta')");

    header("location:../ADMINISTRADOR/control-stock.php");

?>