<?php
require_once '../PHPMailer-master/PHPMailer-master/src/Exception.php';
require_once '../PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    private $mailer;
    
    public function __construct() {
        // Incluir configuración
        require_once 'mail_config.php';
        
        // Crear instancia de PHPMailer
        $this->mailer = new PHPMailer(true);
        
        // Configurar PHPMailer
        $this->configureMailer();
    }
    
    private function configureMailer() {
        try {
            // Configuración del servidor
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = SMTP_AUTH;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = SMTP_SECURE;
            $this->mailer->Port = SMTP_PORT;
            
            // Configuración de caracteres
            $this->mailer->CharSet = MAIL_CHARSET;
            $this->mailer->Encoding = MAIL_ENCODING;
            
            // Configurar remitente
            $this->mailer->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            
            // Configurar formato HTML
            $this->mailer->isHTML(true);
            
        } catch (Exception $e) {
            throw new Exception("Error configurando PHPMailer: " . $e->getMessage());
        }
    }
    
    /**
     * Envía una respuesta a una consulta de contacto
     * 
     * @param string $toEmail Email del destinatario
     * @param string $toName Nombre del destinatario
     * @param string $originalSubject Asunto original de la consulta
     * @param string $originalMessage Mensaje original del usuario
     * @param string $response Respuesta del administrador
     * @return bool True si se envió correctamente, false en caso contrario
     */
    public function sendContactResponse($toEmail, $toName, $originalSubject, $originalMessage, $response) {
        try {
            // Limpiar el buffer de salida
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Configurar destinatario
            $this->mailer->addAddress($toEmail, $toName);
            
            // Configurar asunto
            $this->mailer->Subject = "Respuesta a tu consulta: " . $originalSubject;
            
            // Crear cuerpo del email en HTML
            $htmlBody = $this->createResponseEmailHTML($toName, $originalSubject, $originalMessage, $response);
            
            // Crear cuerpo del email en texto plano
            $textBody = $this->createResponseEmailText($toName, $originalSubject, $originalMessage, $response);
            
            // Configurar cuerpo del email
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;
            
            // Enviar email
            $result = $this->mailer->send();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envía una confirmación de consulta recibida
     * 
     * @param string $toEmail Email del destinatario
     * @param string $toName Nombre del destinatario
     * @param string $subject Asunto de la consulta
     * @param string $message Mensaje de la consulta
     * @param string $telefono Teléfono del usuario
     * @return bool True si se envió correctamente, false en caso contrario
     */
    public function sendContactConfirmation($toEmail, $toName, $subject, $message, $telefono) {
        try {
            // Limpiar el buffer de salida
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Configurar destinatario
            $this->mailer->addAddress($toEmail, $toName);
            
            // Configurar asunto
            $this->mailer->Subject = "Confirmación de consulta recibida - MotorAdmin";
            
            // Crear cuerpo del email en HTML
            $htmlBody = $this->createConfirmationEmailHTML($toName, $subject, $message, $telefono);
            
            // Crear cuerpo del email en texto plano
            $textBody = $this->createConfirmationEmailText($toName, $subject, $message, $telefono);
            
            // Configurar cuerpo del email
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;
            
            // Enviar email
            $result = $this->mailer->send();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error enviando email de confirmación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crea el cuerpo HTML del email de respuesta
     */
    private function createResponseEmailHTML($toName, $originalSubject, $originalMessage, $response) {
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Respuesta a tu consulta</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #1b263b; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .original-message { background-color: #e9ecef; padding: 15px; margin: 15px 0; border-left: 4px solid #007bff; }
                .response { background-color: #d4edda; padding: 15px; margin: 15px 0; border-left: 4px solid #28a745; }
                .footer { background-color: #6c757d; color: white; padding: 15px; text-align: center; font-size: 12px; }
                .contact-info { background-color: #fff3cd; padding: 15px; margin: 15px 0; border-left: 4px solid #ffc107; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>MotorAdmin - Taller Mecánico</h1>
                    <p>Respuesta a tu consulta</p>
                </div>
                
                <div class="content">
                    <p>Hola <strong>' . htmlspecialchars($toName) . '</strong>,</p>
                    
                    <p>Gracias por contactarnos. Hemos recibido tu consulta y te respondemos a continuación:</p>
                    
                    <div class="original-message">
                        <h4>Tu consulta original:</h4>
                        <p><strong>Asunto:</strong> ' . htmlspecialchars($originalSubject) . '</p>
                        <p><strong>Mensaje:</strong></p>
                        <p>' . nl2br(htmlspecialchars($originalMessage)) . '</p>
                    </div>
                    
                    <div class="response">
                        <h4>Nuestra respuesta:</h4>
                        <p>' . nl2br(htmlspecialchars($response)) . '</p>
                    </div>
                    
                    <div class="contact-info">
                        <h4>Información de contacto:</h4>
                        <p><strong>Teléfono:</strong> +54 9 11 1234 5678</p>
                        <p><strong>Email:</strong> contacto@motorAdmin.com</p>
                        <p><strong>Dirección:</strong> París 532, Haedo, Buenos Aires</p>
                    </div>
                    
                    <p>Si tienes alguna pregunta adicional, no dudes en contactarnos nuevamente.</p>
                    
                    <p>Saludos cordiales,<br>
                    <strong>Equipo MotorAdmin</strong></p>
                </div>
                
                <div class="footer">
                    <p>Este es un email automático, por favor no respondas a este mensaje.</p>
                    <p>&copy; 2024 MotorAdmin - Taller Mecánico. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Crea el cuerpo en texto plano del email de respuesta
     */
    private function createResponseEmailText($toName, $originalSubject, $originalMessage, $response) {
        $text = "MotorAdmin - Taller Mecánico\n";
        $text .= "Respuesta a tu consulta\n\n";
        $text .= "Hola " . $toName . ",\n\n";
        $text .= "Gracias por contactarnos. Hemos recibido tu consulta y te respondemos a continuación:\n\n";
        $text .= "TU CONSULTA ORIGINAL:\n";
        $text .= "Asunto: " . $originalSubject . "\n";
        $text .= "Mensaje: " . $originalMessage . "\n\n";
        $text .= "NUESTRA RESPUESTA:\n";
        $text .= $response . "\n\n";
        $text .= "INFORMACIÓN DE CONTACTO:\n";
        $text .= "Teléfono: +54 9 11 1234 5678\n";
        $text .= "Email: contacto@motorAdmin.com\n";
        $text .= "Dirección: París 532, Haedo, Buenos Aires\n\n";
        $text .= "Si tienes alguna pregunta adicional, no dudes en contactarnos nuevamente.\n\n";
        $text .= "Saludos cordiales,\n";
        $text .= "Equipo MotorAdmin\n\n";
        $text .= "Este es un email automático, por favor no respondas a este mensaje.\n";
        $text .= "© 2024 MotorAdmin - Taller Mecánico. Todos los derechos reservados.";
        
        return $text;
    }
    
    /**
     * Crea el cuerpo HTML del email de confirmación
     */
    private function createConfirmationEmailHTML($toName, $subject, $message, $telefono) {
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmación de consulta recibida</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #1b263b; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .confirmation { background-color: #d4edda; padding: 15px; margin: 15px 0; border-left: 4px solid #28a745; }
                .message-details { background-color: #e9ecef; padding: 15px; margin: 15px 0; border-left: 4px solid #007bff; }
                .footer { background-color: #6c757d; color: white; padding: 15px; text-align: center; font-size: 12px; }
                .contact-info { background-color: #fff3cd; padding: 15px; margin: 15px 0; border-left: 4px solid #ffc107; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>MotorAdmin - Taller Mecánico</h1>
                    <p>Confirmación de consulta recibida</p>
                </div>
                
                <div class="content">
                    <p>Hola <strong>' . htmlspecialchars($toName) . '</strong>,</p>
                    
                    <div class="confirmation">
                        <h4>✅ Consulta recibida correctamente</h4>
                        <p>Hemos recibido tu consulta y la estamos procesando. Nuestro equipo se pondrá en contacto contigo pronto.</p>
                    </div>
                    
                    <div class="message-details">
                        <h4>Detalles de tu consulta:</h4>
                        <p><strong>Asunto:</strong> ' . htmlspecialchars($subject) . '</p>
                        <p><strong>Mensaje:</strong></p>
                        <p>' . nl2br(htmlspecialchars($message)) . '</p>
                        <p><strong>Teléfono:</strong> ' . htmlspecialchars($telefono) . '</p>
                        <p><strong>Fecha de envío:</strong> ' . date('d/m/Y H:i:s') . '</p>
                    </div>
                    
                    <div class="contact-info">
                        <h4>Información de contacto del taller:</h4>
                        <p><strong>Teléfono:</strong> +54 9 11 1234 5678</p>
                        <p><strong>Email:</strong> contacto@motorAdmin.com</p>
                        <p><strong>Dirección:</strong> París 532, Haedo, Buenos Aires</p>
                        <p><strong>Horarios:</strong> Lunes a Viernes 8:00 - 18:00, Sábados 8:00 - 12:00</p>
                    </div>
                    
                    <p>Gracias por confiar en MotorAdmin para el cuidado de tu vehículo.</p>
                    
                    <p>Saludos cordiales,<br>
                    <strong>Equipo MotorAdmin</strong></p>
                </div>
                
                <div class="footer">
                    <p>Este es un email automático, por favor no respondas a este mensaje.</p>
                    <p>&copy; 2024 MotorAdmin - Taller Mecánico. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Crea el cuerpo en texto plano del email de confirmación
     */
    private function createConfirmationEmailText($toName, $subject, $message, $telefono) {
        $text = "MotorAdmin - Taller Mecánico\n";
        $text .= "Confirmación de consulta recibida\n\n";
        $text .= "Hola " . $toName . ",\n\n";
        $text .= "Hemos recibido tu consulta y la estamos procesando. Nuestro equipo se pondrá en contacto contigo pronto.\n\n";
        $text .= "DETALLES DE TU CONSULTA:\n";
        $text .= "Asunto: " . $subject . "\n";
        $text .= "Mensaje: " . $message . "\n";
        $text .= "Teléfono: " . $telefono . "\n";
        $text .= "Fecha de envío: " . date('d/m/Y H:i:s') . "\n\n";
        $text .= "INFORMACIÓN DE CONTACTO DEL TALLER:\n";
        $text .= "Teléfono: +54 9 11 1234 5678\n";
        $text .= "Email: contacto@motorAdmin.com\n";
        $text .= "Dirección: París 532, Haedo, Buenos Aires\n";
        $text .= "Horarios: Lunes a Viernes 8:00 - 18:00, Sábados 8:00 - 12:00\n\n";
        $text .= "Gracias por confiar en MotorAdmin para el cuidado de tu vehículo.\n\n";
        $text .= "Saludos cordiales,\n";
        $text .= "Equipo MotorAdmin\n\n";
        $text .= "Este es un email automático, por favor no respondas a este mensaje.\n";
        $text .= "© 2024 MotorAdmin - Taller Mecánico. Todos los derechos reservados.";
        
        return $text;
    }
}
?> 