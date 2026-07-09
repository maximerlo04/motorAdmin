<?php
// Configuración de PHPMailer para MotorAdmin
// Configuración SMTP para Gmail (puedes cambiar según tu proveedor de email)

// IMPORTANTE: Para Gmail necesitas:
// 1. Habilitar verificación en dos pasos en tu cuenta de Google
// 2. Generar una contraseña de aplicación (no tu contraseña normal)
// 3. Usar esa contraseña de aplicación aquí

// Configuración del servidor SMTP
define('SMTP_HOST', 'smtp.gmail.com');  // Cambia según tu proveedor
define('SMTP_PORT', 587);               // Puerto para TLS
define('SMTP_USERNAME', 'tomascipollone@gmail.com');  // Tu email real de Gmail
define('SMTP_PASSWORD', 'cutmuiqflojbtinb');  // Contraseña de aplicación de Gmail
define('SMTP_FROM_EMAIL', 'tomascipollone@gmail.com');  // Mismo email que SMTP_USERNAME
define('SMTP_FROM_NAME', 'MotorAdmin - Taller Mecánico');  // Nombre del remitente

// Configuración de seguridad
define('SMTP_SECURE', 'tls');  // tls o ssl
define('SMTP_AUTH', true);     // Habilitar autenticación

// Configuración adicional
define('MAIL_CHARSET', 'UTF-8');
define('MAIL_ENCODING', '8bit');
?> 