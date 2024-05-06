<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload (ensure the correct path)
require 'vendor/autoload.php';

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
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
    $mail->setFrom($email, $name);
    $mail->addAddress('workflow@signaps.com'); // Recipient email
    $mail->addReplyTo($email, $name); // Reply-to address

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
    echo 'Failed to send email.'. $mail->ErrorInfo; // Detailed error information
}
?>
