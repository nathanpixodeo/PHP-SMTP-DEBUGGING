<?php

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form inputs
    $smtpHost = $_POST["smtp_host"];
    $smtpPort = $_POST["smtp_port"];
    $smtpUsername = $_POST["smtp_username"];
    $smtpPassword = $_POST["smtp_password"];
    $emailTo = $_POST["email_to"];
    $emailFrom = $_POST["email_from"];
    $subject = "Test SMTP Connection";
    $message = "This is a test email to check the SMTP connection.";

    // Attempt to send the test email
    if (sendTestEmail($smtpHost, $smtpPort, $smtpUsername, $smtpPassword, $emailTo, $emailFrom, $subject, $message)) {
        $resultMessage = "Test email sent successfully!";
    } else {
        $resultMessage = "Failed to send the test email. Check your SMTP settings.";
    }
}

function sendTestEmail($host, $port, $username, $password, $to, $from, $subject, $message)
{
    // Set the SMTP configuration
    $config = [
        'host' => $host,
        'port' => $port,
        'auth' => true,
        'username' => $username,
        'password' => $password,
        'encryption' => 'tls', // You can use 'ssl' if your server requires it
    ];

    // Create the SMTP transport
    $transport = new \Swift_SmtpTransport($config['host'], $config['port'], $config['encryption']);
    $transport->setUsername($config['username']);
    $transport->setPassword($config['password']);

    // Create the Mailer using the created transport
    $mailer = new \Swift_Mailer($transport);

    // Create the message
    $message = (new \Swift_Message($subject))
        ->setFrom([$from])
        ->setTo([$to])
        ->setBody($message);

    // Attempt to send the message
    try {
        return $mailer->send($message);
    } catch (\Swift_TransportException $e) {
        return false;
    }
}
