<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$name = clean_input($_POST['name']);
$email = clean_input($_POST['email']);
$company = clean_input($_POST['company']);
$subject = clean_input($_POST['subject']);
$message = clean_input($_POST['message']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Invalid email address';
    exit;
}

$to = 'fortemppp@gmail.com'; // Change to your recipient email
$email_subject = 'Contact Request: ' . $subject;

$email_body = "Name: $name\nEmail: $email\nCompany: $company\nSubject: $subject\nMessage: \n$message\n";

$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

if (mail($to, $email_subject, $email_body, $headers)) {
    http_response_code(200);
    echo 'Email sent successfully!';
} else {
    http_response_code(500);
    echo 'Failed to send email';
}
?>
