<?php

header('Content-Type: application/json');
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    echo json_encode(['status' => 'error', 'message' => 'Vendor folder nahi mila! Server par vendor folder upload check karein.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all mandatory fields.']);
        exit;
    }

    $name        = htmlspecialchars(trim($_POST['name']));
    $email       = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone       = htmlspecialchars(trim($_POST['phone']));
    $country     = htmlspecialchars(trim($_POST['country']));
    $requirement = htmlspecialchars(trim($_POST['requirement']));
    $message     = htmlspecialchars(trim($_POST['message']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid Email address.']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {

   // --- OPTION A: Hostinger SMTP (Most recommended & 100% Reliable for Hostinger) ---
        // $mail->Host       = 'smtp.hostinger.com';
        // $mail->SMTPAuth   = true;
        // $mail->Username   = 'info@yourdomain.com';      // Hostinger me banaya email
        // $mail->Password   = 'YourEmailPassword123';     // Hostinger email ka password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        // $mail->Port       = 465;

        // ---- GMAIL SMTP SETTINGS ----
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'faizanonlink@gmail.com';
        $mail->Password   = 'bfec zlwz bzkr rsuu'; // 16-digit App Password
        
        // Hostinger par Port 587 + STARTTLS sabse best chalta hai
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // ---- SENDER & RECEIVER ----
        $mail->setFrom('faizanonlink@gmail.com', 'Aradhya Agro Website'); 
        $mail->addAddress('faizanonlink@gmail.com', 'Aradhya Agro Admin');
        $mail->addReplyTo($email, $name);

        // ---- EMAIL CONTENT ----
        $mail->isHTML(true);
        $mail->Subject = "New Product Inquiry: " . $requirement . " (" . $name . ")";
        
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
            <h2 style='color: #2e6c36; border-bottom: 2px solid #2e6c36; padding-bottom: 10px;'>New Website Inquiry</h2>
            <p>You have received a new inquiry from your website contact form:</p>
            
            <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>
                <tr>
                    <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold; width: 35%;'>Full Name</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>{$name}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Mobile / WhatsApp</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>{$phone}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Email Address</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>{$email}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Country</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>{$country}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Requirement</td>
                    <td style='padding: 8px; border: 1px solid #ddd; color: #d9534f; font-weight: bold;'>{$requirement}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Message</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>{$message}</td>
                </tr>
            </table>
            <p style='margin-top: 20px; font-size: 12px; color: #777;'>This email was sent from the Aradhya Agro Industries website contact form.</p>
        </div>";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Thank you! Your inquiry has been sent successfully. We will get back to you soon.']);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>