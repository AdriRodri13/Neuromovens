<?php
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

// Verifica si se han enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $consulta = htmlspecialchars($_POST['consulta']);
    $politica = isset($_POST['politica']) ? 'Sí' : 'No';

    // Validar que el checkbox de política de privacidad esté marcado
    if ($politica !== 'Sí') {
        die('Debe aceptar la política de privacidad para continuar.');
    }

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com'; // Servidor SMTP de Hostinger
        $mail->SMTPAuth = true;
        $mail->Username = 'info@neuromovens.es'; // Tu correo profesional
        $mail->Password = '257226Aa!';          // Contraseña de tu correo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('info@neuromovens.es', 'Neuromovens'); // Remitente
        $mail->addAddress('sandraferrandezsanchez@gmail.com', 'Sandra Ferrandez Sanchez'); // Destinatario principal

        // Adjuntar la imagen (asegúrate de que la ruta sea correcta)
        // La imagen debe estar disponible en el servidor o en un directorio accesible
        $cid = $mail->addEmbeddedImage('../images/neuronaBuena.png', 'imagen_cid'); // Aquí 'imagen_cid' es el identificador único de la imagen

        // Contenido del correo
        $mail->isHTML(true); // Enviar como HTML
        $mail->Subject = 'Nueva consulta desde el formulario web';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: #f9f9f9;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <img src='cid:imagen_cid' alt='Imagen representativa' style='width: 400px; height: 200px; border-radius: 50%;'>
                </div>
                <h2 style='color: #007BFF; text-align: center;'>Nueva consulta desde el formulario</h2>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <p><strong>Consulta:</strong></p>
                <p style='background-color: #eef; padding: 10px; border-radius: 4px;'>$consulta</p>
                <p><strong>Aceptó la política de privacidad:</strong> $politica</p>
                <hr style='border: none; border-top: 1px solid #ccc; margin: 20px 0;'>
                <p style='text-align: center; font-size: 0.9em; color: #555;'>Este correo se ha enviado automáticamente desde el sitio web Neuromovens</p>
            </div>
        ";
        $mail->AltBody = "Datos de la consulta:
        Nombre: $nombre
        Email: $email
        Teléfono: $telefono
        Consulta: $consulta
        Aceptó la política de privacidad: $politica";

        // Enviar el correo
        $mail->send();

        // Muestra un mensaje de éxito y redirige después de 5 segundos
        echo "
            <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>
                <h2 style='color: #28a745; font-size: 24px;'>Ha sido enviado correctamente</h2>
                <p style='font-size: 16px; color: #555;'>Gracias por tu consulta. Te responderemos lo antes posible.</p>
            </div>
            <script>
                // Redirige automáticamente a index.php tras 5 segundos
                setTimeout(function() {
                    window.location.href = '../../index.php';
                }, 5000);
            </script>
        ";
    } catch (Exception $e) {
        // Si hay un error en el envío, muestra el mensaje de error
        echo "
            <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>
                <h2 style='color: #dc3545; font-size: 24px;'>Hubo un error al enviar el correo</h2>
                <p style='font-size: 16px; color: #555;'>Por favor, inténtalo de nuevo más tarde. Error: {$mail->ErrorInfo}</p>
            </div>
        ";
    }
} else {
    echo "Método de envío no válido.";
}

