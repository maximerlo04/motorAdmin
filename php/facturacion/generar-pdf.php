<?php
require_once '../../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include("../../connection.php");

if (!isset($_GET['id'])) {
    die("ID de factura no especificado.");
}

$id_factura = intval($_GET['id']);

// Obtener datos principales de la factura y datos necesarios para mostrar
$query = "SELECT f.*, t.horas_estimadas, s.nombre AS servicio, s.costo_base,
                e.nombre AS empleado, e.valor_hora, 
                u.nombre AS cliente
          FROM facturas f
          JOIN trabajos t ON f.id_trabajo = t.id
          JOIN servicios s ON t.id_servicio = s.id
          JOIN empleado e ON t.id_empleado = e.id
          JOIN usuarios u ON t.id_usuario = u.id
          WHERE f.id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $id_factura);
$stmt->execute();
$resultado = $stmt->get_result();
$factura = $resultado->fetch_assoc();

if (!$factura) {
    die("Factura no encontrada.");
}

// Obtener productos usados
$queryProductos = "SELECT pu.*, p.nombre_producto, p.precio_unitario
                   FROM productos_usados pu
                   JOIN stock p ON pu.id_stock = p.id
                   WHERE pu.id_factura = ?";
$stmtProd = $connection->prepare($queryProductos);
$stmtProd->bind_param("i", $id_factura);
$stmtProd->execute();
$resultadoProd = $stmtProd->get_result();

$productos_html = "";
$total_productos = 0;

while ($producto = $resultadoProd->fetch_assoc()) {
    $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
    $total_productos += $subtotal;
    $productos_html .= "<tr>
        <td>{$producto['nombre_producto']}</td>
        <td>{$producto['cantidad']}</td>
        <td>$ " . number_format($producto['precio_unitario'], 2) . "</td>
        <td>$ " . number_format($subtotal, 2) . "</td>
    </tr>";
}

// Variables de costos
$costo_base = floatval($factura['costo_base']);
$horas = floatval($factura['horas_estimadas']);
$valor_hora = floatval($factura['valor_hora']);

// Calcular mano de obra (por si quieres mostrar el cálculo)
$costo_mano_obra = $horas * $valor_hora;

// Usar valores reales calculados, no los que vienen en la factura (para evitar inconsistencias)
$subtotal_calculado = $costo_base + $costo_mano_obra + $total_productos;
$iva = $subtotal_calculado * 0.21;
$total_final = $subtotal_calculado + $iva;

// Si querés mostrar los valores guardados en la factura (menos recomendado si hay inconsistencias)
// $subtotal_calculado = floatval($factura['subtotal']);
// $iva = floatval($factura['iva']);
// $total_final = floatval($factura['total']);

// Generar contenido HTML del PDF
$html = "
<h1>Factura #{$factura['id']}</h1>
<p><strong>Cliente:</strong> {$factura['cliente']}</p>
<p><strong>Servicio:</strong> {$factura['servicio']}</p>
<p><strong>Empleado asignado:</strong> {$factura['empleado']}</p>
<p><strong>Fecha de emisión:</strong> {$factura['fecha_emision']}</p>

<h3>Detalle de costos</h3>
<ul>
    <li><strong>Costo base:</strong> $ " . number_format($costo_base, 2) . "</li>
    <li><strong>Mano de obra ({$horas} h x $" . number_format($valor_hora, 2) . "):</strong> $ " . number_format($costo_mano_obra, 2) . "</li>
    <li><strong>Productos utilizados:</strong> $ " . number_format($total_productos, 2) . "</li>
</ul>

<h3>Productos usados</h3>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio unitario</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        $productos_html
    </tbody>
</table>

<h3>Resumen</h3>
<p><strong>Subtotal:</strong> $ " . number_format($subtotal_calculado, 2) . "</p>
<p><strong>IVA (21%):</strong> $ " . number_format($iva, 2) . "</p>
<p><strong>Total final:</strong> $ " . number_format($total_final, 2) . "</p>
";

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$nombre_archivo = "factura_{$factura['id']}.pdf";
$ruta_archivo = "../../facturas/" . $nombre_archivo;
file_put_contents($ruta_archivo, $dompdf->output());

// Guardar nombre del archivo en la base de datos
$queryUpdate = "UPDATE facturas SET archivo_pdf = ? WHERE id = ?";
$stmtUpdate = $connection->prepare($queryUpdate);
$stmtUpdate->bind_param("si", $nombre_archivo, $id_factura);
$stmtUpdate->execute();

// Redirigir a página para visualizar
header("Location: ../../facturas/$nombre_archivo");
exit();
