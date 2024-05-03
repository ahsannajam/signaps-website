<?php
// Ensure this script is accessed via POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

// Basic spam protection (honeypot technique)
if (!empty($_POST['hidden_field'])) {
    http_response_code(400);
    die('Bad Request');
}

// Clean input data to prevent XSS and injection attacks
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$name = clean_input($_POST['name']);
$email = clean_input($_POST['email']);
$company = clean_input($_POST['company']);
$subject = clean_input($_POST['subject']);
$message = clean_input($_POST['message']);

// Validate email address
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die('Invalid email address');
}

// Prepare email headers
$to = 'workflow@signaps.com';  // Replace with your email
$subject = 'Contact Request: ' . $subject;
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Prepare email body
$email_message = "Name: $name\n";
$email_message .= "Email: $email\n";
$email_message .= "Company: $company\n";
$email_message .= "Subject: $subject\n";
$email_message .= "Message: \n$message\n";

// Send the email
if (mail($to, $subject, $email_message, $headers)) {
    http_response_code(200);
    echo 'Email sent successfully';
} else {
    http_response_code(500);
    echo 'Failed to send email';
}
