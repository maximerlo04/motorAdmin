<?php
include("../connection.php");

// Incluir la clase MailHelper
require_once 'config/MailHelper.php';

// Obtener datos del formulario
$email = mysqli_real_escape_string($connection, $_POST["email"]);
$asunto = mysqli_real_escape_string($connection, $_POST["asunto"]);
$mensaje = mysqli_real_escape_string($connection, $_POST["mensaje"]);
$respuesta = mysqli_real_escape_string($connection, $_POST["respuesta"]);

// Obtener el nombre del usuario desde la base de datos
$sql_nombre = "SELECT nombre FROM mensajes WHERE email = '$email' AND asunto = '$asunto' AND mensaje = '$mensaje' ORDER BY fecha DESC LIMIT 1";
$result_nombre = mysqli_query($connection, $sql_nombre);
$row_nombre = mysqli_fetch_assoc($result_nombre);
$nombre_usuario = $row_nombre ? $row_nombre['nombre'] : 'Usuario';

// Guardar la respuesta en la base de datos
$sql = "INSERT INTO mensajes_respondidos (email, asunto, mensaje, respuesta) VALUES ('$email', '$asunto', '$mensaje', '$respuesta')";
$result_db = mysqli_query($connection, $sql);

// Enviar email al usuario
$mailHelper = new MailHelper();
$email_sent = $mailHelper->sendContactResponse($email, $nombre_usuario, $asunto, $mensaje, $respuesta);

// Redirigir con mensaje de éxito o error
if ($email_sent) {
    header("Location: ../ADMINISTRADOR/almacen-resenia.php?success=1");
} else {
    header("Location: ../ADMINISTRADOR/almacen-resenia.php?error=1");
}
?>