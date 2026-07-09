<?php

include("../connection.php");

// Incluir la clase MailHelper
require_once 'config/MailHelper.php';

// Obtener datos del formulario
$nombre = mysqli_real_escape_string($connection, $_POST["nombre"]);
$email = mysqli_real_escape_string($connection, $_POST["email"]);
$asunto = mysqli_real_escape_string($connection, $_POST["Asunto"]);
$telefono = mysqli_real_escape_string($connection, $_POST["telefono"]);
$mensaje = mysqli_real_escape_string($connection, $_POST["Mensaje"]);

// Guardar en la base de datos
$sql = "INSERT INTO mensajes (nombre, email, asunto, telefono, mensaje) VALUES ('$nombre', '$email', '$asunto', '$telefono', '$mensaje')";
$result = mysqli_query($connection, $sql);

// Enviar email de confirmación al usuario
if ($result) {
    try {
        $mailHelper = new MailHelper();
        
        // Crear mensaje de confirmación
        $confirmacionAsunto = "Confirmación de consulta recibida";
        $confirmacionMensaje = "Hemos recibido tu consulta y la estamos procesando. Te responderemos pronto.";
        
        // Enviar email de confirmación
        $email_sent = $mailHelper->sendContactConfirmation($email, $nombre, $asunto, $mensaje, $telefono);
        
        // Redirigir con mensaje de éxito
        if ($email_sent) {
            header("Location: ../CLIENTE/contacto.php?success=1");
        } else {
            header("Location: ../CLIENTE/contacto.php?success=1&email_error=1");
        }
        
    } catch (Exception $e) {
        // Si hay error en el email, igual redirigir con éxito pero indicando el problema
        header("Location: ../CLIENTE/contacto.php?success=1&email_error=1");
    }
} else {
    // Error al guardar en la base de datos
    header("Location: ../CLIENTE/contacto.php?error=1");
}
?>
