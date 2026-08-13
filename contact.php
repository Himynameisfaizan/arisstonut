    <?php include('inc/header.php'); ?>

    <section class="container py-5">
        <div class="row">
            <div class="col-md-5">
                <h2 class="fw-bold mb-4">Get In Touch</h2>
                <p class="text-muted mb-4">Have questions about our premium Makhana or want to collaborate? We'd love to hear from you!</p>

                <?php
                $contact_query = "SELECT phone, email, address FROM contacts LIMIT 1";
                $contact_res = $conn->query($contact_query);

                $c_address = "Subhankarpur, Darbhanga, Bihar-846004";
                $c_email   = "aristowebin@gmail.com";
                $c_phone   = "+91 99997 28084";

                if ($contact_res && $contact_res->num_rows > 0) {
                    $c_info = $contact_res->fetch_assoc();
                    if (!empty($c_info['address'])) $c_address = htmlspecialchars($c_info['address']);
                    if (!empty($c_info['email']))   $c_email   = htmlspecialchars($c_info['email']);
                    if (!empty($c_info['phone']))   $c_phone   = htmlspecialchars($c_info['phone']);
                }
                ?>

                <div class="mb-3">
                    <h5 class="fw-bold">📍 Address</h5>
                    <p class="text-muted"><?php echo $c_address; ?></p>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold">📧 Email</h5>
                    <p class="text-muted">
                        <a href="mailto:<?php echo $c_email; ?>" class="text-decoration-none text-muted hover-brown">
                            <?php echo $c_email; ?>
                        </a>
                    </p>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold">📞 Phone</h5>
                    <p class="text-muted">
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $c_phone); ?>" class="text-decoration-none text-muted hover-brown">
                            <?php echo $c_phone; ?>
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card p-4 shadow-sm border-0">
                    <?php

                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\Exception;

                    $msg_status = "";
                    $msg_class = "";

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_contact'])) {
                        $name    = htmlspecialchars(trim($_POST['full_name']));
                        $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
                        $subject = htmlspecialchars(trim($_POST['subject']));
                        $message = htmlspecialchars(trim($_POST['message']));

                        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                            $msg_status = "⚠️ Please fill in all fields before transmitting data.";
                            $msg_class = "alert-danger";
                        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $msg_status = "⚠️ Invalid Email Address format syntax validation pattern fail.";
                            $msg_class = "alert-danger";
                        } else {

                            $db_stmt = $conn->prepare("INSERT INTO `inquiries` (`name`, `email`, `subject`, `message`) VALUES (?, ?, ?, ?)");
                            $db_stmt->bind_param("ssss", $name, $email, $subject, $message);

                            $db_execute_success = $db_stmt->execute();
                            $db_stmt->close();

                            if ($db_execute_success) {

                                if (file_exists('vendor/autoload.php')) {
                                    require_once 'vendor/autoload.php';

                                    $mail = new PHPMailer(true);

                                    try {
                                        $mail->isSMTP();
                                        $mail->Host       = 'smtp.gmail.com';
                                        $mail->SMTPAuth   = true;
                                        $mail->Username   = 'aristowebin@gmail.com';
                                        $mail->Password   = 'kzte hzkh tysh cezg'; // <-- 16-character App Password yahan dalo
                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                        $mail->Port       = 587;

                                        $mail->setFrom('aristowebin@gmail.com', 'AristoNut Webportal');
                                        $mail->addAddress('aristowebin@gmail.com', 'AristoNut Admin');
                                        $mail->addReplyTo($email, $name);

                                        $mail->isHTML(true);
                                        $mail->Subject = "AristoNut Contact Form: " . $subject;

                                        $mail->Body = "
                                    <div style='font-family: Arial, sans-serif; background-color: #fffaf5; padding: 20px; color: #3e2723;'>
                                        <div style='max-width: 600px; background: #fff; padding: 25px; border-radius: 15px; border: 2px solid #f5e6d3;'>
                                            <h2 style='color: #8b4513; border-bottom: 2px dashed #f5e6d3; padding-bottom: 10px;'>New Enquiry Received</h2>
                                            <p><strong>Full Name:</strong> {$name}</p>
                                            <p><strong>Email Address:</strong> {$email}</p>
                                            <p><strong>Subject:</strong> {$subject}</p>
                                            <p style='background: #fffaf5; padding: 15px; border-radius: 10px; border-left: 4px solid #8b4513;'>
                                                <strong>Message:</strong><br>" . nl2br($message) . "
                                            </p>
                                            <hr style='border: none; border-top: 1px dashed #f5e6d3; margin: 20px 0;'>
                                            <small style='color: #8d6e63;'>This message was routed instantly through the AristoNut customer inquiry pipeline.</small>
                                        </div>
                                    </div>";

                                        $mail->send();

                                        $msg_status = "🎉 Your inquiry has been submitted! Our support team will get back to you shortly.";
                                        $msg_class = "alert-success";

                                        $name = $email = $subject = $message = "";
                                    } catch (Exception $e) {
                                        $msg_status = "⚠️ Data saved to database, but email delivery failed. Error: " . $mail->ErrorInfo;
                                        $msg_class = "alert-warning";
                                    }
                                } else {
                                    $msg_status = "❌ Server configuration error: PHPMailer vendor directory missing!";
                                    $msg_class = "alert-danger";
                                }
                            } else {
                                $msg_status = "❌ Internal Database System Fault. Core execution query failed to bind input parameter streams.";
                                $msg_class = "alert-danger";
                            }
                        }
                    }
                    ?>

                    <?php if (!empty($msg_status)): ?>
                        <div class="alert <?php echo $msg_class; ?> alert-dismissible fade show" role="alert" style="border-radius:12px; font-weight:500;">
                            <?php echo $msg_status; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Full Name</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Enter your name" value="<?php echo isset($name) ? $name : ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?php echo isset($email) ? $email : ''; ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="How can we help?" value="<?php echo isset($subject) ? $subject : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Message</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Your message here..." required><?php echo isset($message) ? $message : ''; ?></textarea>
                        </div>

                        <button type="submit" name="submit_contact" class="btn w-100 py-2 text-white" style="background:#8B4513; font-weight:600; border-radius:25px; transition: 0.3s; border:none;">
                            Send Message <i class="bi bi-send ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>
    </body>
    </html>