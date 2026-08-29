<?php 
$pageTitle = "Contact Us";
include ('inc/header.php');
include ('inc/breadcrumb.php');
?>
<section class="contact-page-wrapper">
    <div class="container">

        <div class="contact-glass-container cp-reveal">
            
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

            <!-- LEFT PANEL: Info -->
            <div class="col-lg-5 contact-info-panel">
                <h2 class="info-panel-title">Get In Touch</h2>
                <p class="info-panel-desc">Have questions about our premium Makhana or want to collaborate? We'd love to hear from you!</p>

                <div class="info-item">
                    <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
                    <div class="info-text">
                        <h5>Head Office</h5>
                        <p><?php echo $c_address; ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="bi bi-envelope"></i></div>
                    <div class="info-text">
                        <h5>Email Us</h5>
                        <a href="mailto:<?php echo $c_email; ?>"><?php echo $c_email; ?></a>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="bi bi-telephone"></i></div>
                    <div class="info-text">
                        <h5>Call Us</h5>
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $c_phone); ?>"><?php echo $c_phone; ?></a>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: Form -->
            <div class="col-lg-7 contact-form-panel">
                
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
                                    $mail->Password   = 'kzte hzkh tysh cezg'; 
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
                                        </div>
                                    </div>";

                                    $mail->send();
                                    $msg_status = "🎉 Your inquiry has been submitted! Our team will get back to you shortly.";
                                    $msg_class = "alert-success";
                                    $name = $email = $subject = $message = "";
                                } catch (Exception $e) {
                                    $msg_status = "⚠️ Data saved, but email delivery failed. Error: " . $mail->ErrorInfo;
                                    $msg_class = "alert-warning";
                                }
                            } else {
                                $msg_status = "❌ Server configuration error: PHPMailer vendor directory missing!";
                                $msg_class = "alert-danger";
                            }
                        } else {
                            $msg_status = "❌ Internal Database System Fault. Core execution query failed.";
                            $msg_class = "alert-danger";
                        }
                    }
                }
                // --- YOUR PHP LOGIC ENDS HERE ---
                ?>

                <?php if (!empty($msg_status)): ?>
                    <div class="alert <?php echo $msg_class; ?> alert-dismissible fade show mb-4" role="alert" style="border-radius:12px; font-family: 'Inter', sans-serif;">
                        <?php echo $msg_status; ?>
                        <!-- Minimal close button if Bootstrap JS fails -->
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none';"></button>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control custom-input" placeholder="e.g. John Doe" value="<?php echo isset($name) ? $name : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" value="<?php echo isset($email) ? $email : ''; ?>" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control custom-input" placeholder="How can we help you?" value="<?php echo isset($subject) ? $subject : ''; ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control custom-input" rows="5" placeholder="Write your message here..." required><?php echo isset($message) ? $message : ''; ?></textarea>
                    </div>

                    <button type="submit" name="submit_contact" class="btn-submit">
                        Send Message <i class="bi bi-send ms-2"></i>
                    </button>
                </form>
            </div>
        </div>


        <!-- ================= PREMIUM GOOGLE MAP SECTION ================= -->
        <div class="map-container cp-reveal">
            <!-- Darbhanga, Bihar Google Map Embed -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d229569.17641275997!2d85.76065584879644!3d26.1521727780003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39edb7a4bf6b4b47%3A0xc6c4293e62f02cb8!2sDarbhanga%2C%20Bihar!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>


        <!-- ================= B2B / WHOLESALE CTA ================= -->
        <div class="b2b-banner cp-reveal">
            <h3>Looking for Bulk Orders?</h3>
            <p>Partner with AristoNut for Wholesale, Distribution, Private Label, or Export opportunities.</p>
            <a href="mailto:<?php echo $c_email; ?>?subject=Wholesale%20Inquiry" class="b2b-btn">
                Become a Partner <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>


        <!-- ================= CUSTOM JS FAQ SECTION (NO BOOTSTRAP REQUIRED) ================= -->
        <div class="faq-section cp-reveal">
            <h2 class="faq-title">Frequently Asked Questions</h2>
            
            <div class="custom-faq-wrapper">
                
                <!-- FAQ Item 1 -->
                <div class="custom-faq-item">
                    <button class="custom-faq-btn active">
                        Where do you source your Makhana from?
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <!-- Initial state open -->
                    <div class="custom-faq-content" style="max-height: 500px;">
                        <div class="custom-faq-body">
                            Our premium foxnuts are directly sourced from the heart of Mithila, Darbhanga in Bihar. This region is globally recognized for producing the finest quality and largest size of Makhana.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="custom-faq-item">
                    <button class="custom-faq-btn">
                        Do you offer free shipping?
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="custom-faq-content">
                        <div class="custom-faq-body">
                            Yes! We offer free delivery across India on all orders over ₹500. For orders below this amount, a nominal standard shipping fee applies.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="custom-faq-item">
                    <button class="custom-faq-btn">
                        Are your flavored Makhanas fried or roasted?
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="custom-faq-content">
                        <div class="custom-faq-body">
                            All our flavored Makhanas are 100% slow-air roasted. We do not fry our products, ensuring that they remain a healthy, guilt-free, and low-calorie snacking option.
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<?php include ('inc/footer.php') ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Scroll Reveal Animation
        const cpObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target); 
                }
            });
        }, { root: null, rootMargin: '0px', threshold: 0.15 });

        document.querySelectorAll('.cp-reveal').forEach(el => cpObserver.observe(el));

        const faqButtons = document.querySelectorAll('.custom-faq-btn');
        
        faqButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const isActive = this.classList.contains('active');

                document.querySelectorAll('.custom-faq-btn').forEach(b => {
                    b.classList.remove('active');
                    b.nextElementSibling.style.maxHeight = null;
                });

                if (!isActive) {
                    this.classList.add('active');
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        });
    });
</script>