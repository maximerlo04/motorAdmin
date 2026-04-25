<?php
// Archivo de prueba para verificar el sistema de emails
// IMPORTANTE: Este archivo debe ser eliminado en producción

// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la clase MailHelper
require_once 'config/MailHelper.php';

// Verificar si se está ejecutando desde línea de comandos o web
$is_cli = php_sapi_name() === 'cli';

if ($is_cli) {
    echo "=== PRUEBA DEL SISTEMA DE EMAILS ===\n\n";
} else {
    echo "<h2>Prueba del Sistema de Emails</h2>";
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        .warning { color: orange; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>";
}

// Función para mostrar mensajes
function showMessage($message, $type = 'info') {
    global $is_cli;
    if ($is_cli) {
        echo $message . "\n";
    } else {
        echo "<p class='$type'>$message</p>";
    }
}

// Verificar configuración
showMessage("Verificando configuración...", "info");

// Verificar que existe el archivo de configuración
if (!file_exists('config/mail_config.php')) {
    showMessage("❌ Error: No se encuentra el archivo config/mail_config.php", "error");
    exit;
}

// Verificar que existe PHPMailer
if (!file_exists('../PHPMailer-master/PHPMailer-master/src/PHPMailer.php')) {
    showMessage("❌ Error: No se encuentra PHPMailer", "error");
    exit;
}

showMessage("✅ Archivos de configuración encontrados", "success");

try {
    // Crear instancia de MailHelper
    showMessage("Creando instancia de MailHelper...", "info");
    $mailHelper = new MailHelper();
    showMessage("✅ Clase MailHelper creada correctamente", "success");
    
    // Datos de prueba - CAMBIA ESTE EMAIL POR EL TUYO
    $testEmail = "tu-email-de-prueba@gmail.com"; // ⚠️ CAMBIA ESTO POR TU EMAIL REAL
    $testName = "Usuario de Prueba";
    $testSubject = "Consulta de prueba - " . date('Y-m-d H:i:s');
    $testMessage = "Este es un mensaje de prueba para verificar el sistema de emails de MotorAdmin.";
    $testResponse = "Esta es una respuesta de prueba. El sistema de emails está funcionando correctamente. Fecha: " . date('Y-m-d H:i:s');
    
    showMessage("Enviando email de prueba a: <strong>$testEmail</strong>", "info");
    
    // Intentar enviar el email
    $result = $mailHelper->sendContactResponse($testEmail, $testName, $testSubject, $testMessage, $testResponse);
    
    if ($result) {
        showMessage("✅ Email enviado correctamente", "success");
        showMessage("Revisa tu bandeja de entrada (y carpeta de spam) para verificar el email.", "info");
    } else {
        showMessage("❌ Error al enviar el email", "error");
        showMessage("Verifica la configuración en mail_config.php", "warning");
        showMessage("Asegúrate de:", "warning");
        showMessage("1. Haber habilitado verificación en dos pasos en Gmail", "warning");
        showMessage("2. Haber generado una contraseña de aplicación", "warning");
        showMessage("3. Usar credenciales reales (no de ejemplo)", "warning");
    }
    
} catch (Exception $e) {
    showMessage("❌ Error: " . $e->getMessage(), "error");
    
    // Mostrar información adicional para debugging
    if (!$is_cli) {
        echo "<details><summary>Información adicional del error</summary>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</details>";
    }
}

// Mostrar configuración actual (sin contraseñas)
if (!$is_cli) {
    echo "<hr><h3>Configuración Actual:</h3>";
    echo "<p><strong>Host:</strong> " . (defined('SMTP_HOST') ? SMTP_HOST : 'No definido') . "</p>";
    echo "<p><strong>Puerto:</strong> " . (defined('SMTP_PORT') ? SMTP_PORT : 'No definido') . "</p>";
    echo "<p><strong>Usuario:</strong> " . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'No definido') . "</p>";
    echo "<p><strong>Email remitente:</strong> " . (defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'No definido') . "</p>";
    
    if (defined('SMTP_USERNAME') && strpos(SMTP_USERNAME, 'tu-email-real') !== false) {
        echo "<p class='warning'>⚠️ Aún tienes credenciales de ejemplo. Cambia 'tu-email-real@gmail.com' por tu email real.</p>";
    }
}

if ($is_cli) {
    echo "\n=== FIN DE LA PRUEBA ===\n";
} else {
    echo "<hr>";
    echo "<p><strong>Nota:</strong> Este archivo debe ser eliminado en producción por seguridad.</p>";
    echo "<p><a href='../ADMINISTRADOR/almacen-resenia.php'>Volver al panel de administración</a></p>";
}
?> 