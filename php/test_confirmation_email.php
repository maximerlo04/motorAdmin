<?php
// Archivo de prueba para verificar el email de confirmación
// IMPORTANTE: Este archivo debe ser eliminado en producción

// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la clase MailHelper
require_once 'config/MailHelper.php';

// Verificar si se está ejecutando desde línea de comandos o web
$is_cli = php_sapi_name() === 'cli';

if ($is_cli) {
    echo "=== PRUEBA DEL EMAIL DE CONFIRMACIÓN ===\n\n";
} else {
    echo "<h2>Prueba del Email de Confirmación</h2>";
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

try {
    // Crear instancia de MailHelper
    showMessage("Creando instancia de MailHelper...", "info");
    $mailHelper = new MailHelper();
    showMessage("✅ Clase MailHelper creada correctamente", "success");
    
    // Datos de prueba - CAMBIA ESTE EMAIL POR EL TUYO
    $testEmail = "tu-email-de-prueba@gmail.com"; // ⚠️ CAMBIA ESTO POR TU EMAIL REAL
    $testName = "Usuario Registrado de Prueba";
    $testSubject = "Consulta sobre cambio de aceite";
    $testMessage = "Hola, necesito información sobre el cambio de aceite para mi auto. ¿Cuánto cuesta y cuánto tiempo toma?";
    $testTelefono = "+54 9 11 1234 5678";
    
    showMessage("Enviando email de confirmación a: <strong>$testEmail</strong>", "info");
    showMessage("Simulando consulta de: $testName", "info");
    showMessage("Asunto: $testSubject", "info");
    
    // Intentar enviar el email de confirmación
    $result = $mailHelper->sendContactConfirmation($testEmail, $testName, $testSubject, $testMessage, $testTelefono);
    
    if ($result) {
        showMessage("✅ Email de confirmación enviado correctamente", "success");
        showMessage("Revisa tu bandeja de entrada (y carpeta de spam) para verificar el email.", "info");
        showMessage("El email debe contener:", "info");
        showMessage("- Confirmación de recepción de la consulta", "info");
        showMessage("- Detalles de la consulta enviada", "info");
        showMessage("- Información de contacto del taller", "info");
    } else {
        showMessage("❌ Error al enviar el email de confirmación", "error");
        showMessage("Verifica la configuración en mail_config.php", "warning");
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

if ($is_cli) {
    echo "\n=== FIN DE LA PRUEBA ===\n";
} else {
    echo "<hr>";
    echo "<p><strong>Nota:</strong> Este archivo debe ser eliminado en producción por seguridad.</p>";
    echo "<p><a href='../CLIENTE/contacto.php'>Ir a la página de contacto</a></p>";
    echo "<p><a href='../ADMINISTRADOR/almacen-resenia.php'>Ir al panel de administración</a></p>";
}
?> 