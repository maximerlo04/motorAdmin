<?php
include("../../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_trabajo = intval($_POST['id_trabajo']);
    $fecha_emision = date('Y-m-d');

    // 1. Obtener horas estimadas, id_empleado, id_servicio
    $queryTrabajo = "SELECT horas_estimadas, id_empleado, id_servicio FROM trabajos WHERE id = ?";
    $stmtTrabajo = $connection->prepare($queryTrabajo);
    $stmtTrabajo->bind_param("i", $id_trabajo);
    $stmtTrabajo->execute();
    $resultTrabajo = $stmtTrabajo->get_result();
    $trabajo = $resultTrabajo->fetch_assoc();

    $horas = floatval($trabajo['horas_estimadas']);
    $id_empleado = intval($trabajo['id_empleado']);
    $id_servicio = intval($trabajo['id_servicio']);

    // 2. Obtener valor_hora del empleado
    $queryEmpleado = "SELECT valor_hora FROM empleado WHERE id = ?";
    $stmtEmpleado = $connection->prepare($queryEmpleado);
    $stmtEmpleado->bind_param("i", $id_empleado);
    $stmtEmpleado->execute();
    $resultEmpleado = $stmtEmpleado->get_result();
    $empleado = $resultEmpleado->fetch_assoc();
    $valor_hora = floatval($empleado['valor_hora']);

    // 3. Obtener costo_base del servicio
    $queryServicio = "SELECT costo_base FROM servicios WHERE id = ?";
    $stmtServicio = $connection->prepare($queryServicio);
    $stmtServicio->bind_param("i", $id_servicio);
    $stmtServicio->execute();
    $resultServicio = $stmtServicio->get_result();
    $servicio = $resultServicio->fetch_assoc();
    $costo_base = floatval($servicio['costo_base']);

    // 4. Calcular costo de productos utilizados
    $costo_productos = 0;
    if (!empty($_POST['productos']) && !empty($_POST['cantidad'])) {
        $productos = $_POST['productos'];
        $cantidades = $_POST['cantidad'];

        for ($i = 0; $i < count($productos); $i++) {
            $id_stock = intval($productos[$i]);
            $cantidad = intval($cantidades[$i]);

            if ($id_stock <= 0 || $cantidad <= 0) continue;

            $productoQuery = $connection->prepare("SELECT precio_unitario FROM stock WHERE id = ?");
            $productoQuery->bind_param("i", $id_stock);
            $productoQuery->execute();
            $resultProducto = $productoQuery->get_result();
            $producto = $resultProducto->fetch_assoc();

            if ($producto) {
                $precio_unitario = floatval($producto['precio_unitario']);
                $subtotal_producto = $cantidad * $precio_unitario;
                $costo_productos += $subtotal_producto;
            }
        }
    }



    // 5. Calcular subtotal y total
    $subtotal = $costo_base + ($horas * $valor_hora) + $costo_productos;
    $iva = $subtotal * 0.21;
    $total = $subtotal + $iva;

    // 6. Insertar la factura
    $query = "INSERT INTO facturas (id_trabajo, fecha_emision, subtotal, iva, total, estado_pago)
            VALUES (?, ?, ?, ?, ?, 'Pendiente')";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("issdd", $id_trabajo, $fecha_emision, $subtotal, $iva, $total);
    $stmt->execute();

    $id_factura = $stmt->insert_id; // << Ahora ya tenés el ID generado

    // 7. Registrar productos usados y descontar stock
    if (!empty($_POST['productos']) && !empty($_POST['cantidad'])) {
        $productos = $_POST['productos'];
        $cantidades = $_POST['cantidad'];

        for ($i = 0; $i < count($productos); $i++) {
            $id_stock = intval($productos[$i]);
            $cantidad = intval($cantidades[$i]);

            if ($id_stock <= 0 || $cantidad <= 0) continue;

            // Insertar en productos_usados
            $insertProducto = $connection->prepare("INSERT INTO productos_usados (id_factura, id_stock, cantidad) VALUES (?, ?, ?)");
            $insertProducto->bind_param("iii", $id_factura, $id_stock, $cantidad);
            $insertProducto->execute();

            // Descontar del stock
            $updateStock = $connection->prepare("UPDATE stock SET cantidad = cantidad - ? WHERE id = ?");
            $updateStock->bind_param("ii", $cantidad, $id_stock);
            $updateStock->execute();
        }
    }

    header("Location: ../../ADMINISTRADOR/facturacion.php?success=1");
    exit();

}
?>
