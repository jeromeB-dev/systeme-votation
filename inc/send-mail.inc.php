<?
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};

require 'auth.inc.php';

// mandatory components for PHPMailer
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require './vendor/PHPMailer/src/Exception.php';
require './vendor/PHPMailer/src/PHPMailer.php';
require './vendor/PHPMailer/src/SMTP.php';
// mandatory components for PHPMailer

// Function def \\

## Function sendmail
function sendmail($objet, $contenu, $destinataire)
{
// Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    // $SMTP_AUTH = $GLOBALS['SMTP_AUTH'];
    global $SMTP_AUTH;

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = $SMTP_AUTH['host']; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $SMTP_AUTH['username']; // SMTP username
        $mail->Password = $SMTP_AUTH['password']; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = $SMTP_AUTH['port']; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('register@vote2.local', 'Vote 2.0');
        // $mail->addAddress('joe@example.net', 'John Doe'); // Add a recipient
        $mail->addAddress($destinataire); // Name is optional
        $mail->addReplyTo('info@vote2.local', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = utf8_decode($objet);
        $mail->Body = utf8_decode($contenu);
        $mail->AltBody = $contenu;

        $mail->send();
        // return 'Votre message a bien été envoyé.';
        return;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}