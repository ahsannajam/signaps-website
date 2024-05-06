<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload (ensure the correct path)
require 'vendor/autoload.php';

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
// Get reCAPTCHA token from form
$recaptchaToken = clean_input($_POST['recaptchaToken']);

// Validate reCAPTCHA token
$secretKey = '6Le5G9MpAAAAAP0jFLnYsGZLKiAHT2MCvqolT-Wb'; // Your reCAPTCHA secret key
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaToken");
$responseKeys = json_decode($response, true);

if (intval($responseKeys["success"]) !== 1) {
    http_response_code(403);
    echo 'reCAPTCHA verification failed.';
    exit;
}
// Sanitize and validate input
$name = clean_input($_POST['name']);
$email = clean_input($_POST['email']);
$company = clean_input($_POST['company']);
$subject = clean_input($_POST['subject']);
$message = clean_input($_POST['message']);

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Invalid email address';
    exit;
}

$mail = new PHPMailer(true);

try {
    // Configure SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.titan.email'; // Titan SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'workflow@signaps.com'; // Your Titan Email address
    $mail->Password = '.>{R^lLq-lw~Yj.'; // Your Titan Email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use TLS
    $mail->Port = 465; // Common port for SMTP with TLS

    // Set email details
    $mail->setFrom('workflow@signaps.com', $name);
    $mail->addAddress('workflow@signaps.com'); // Recipient email
    $mail->addReplyTo('workflow@signaps.com', $name); // Reply-to address

    // Email content
    $mail->isHTML(false); // Plain text email
    $mail->Subject = 'Signaps.com - Contact Request: ' . $subject;
    $mail->Body = "Name: $name\nEmail: $email\nCompany: $company\nSubject: $subject\nMessage: \n$message\n";

    // Send email
    $mail->send();
    http_response_code(200);
    echo 'Email sent successfully';
} catch (Exception $e) {
    http_response_code(500);
    echo 'Failed to send email.'; // Detailed error information
}
?>
