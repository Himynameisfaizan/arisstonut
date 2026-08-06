<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AristoNut - Premium Makhana | Taste the Excellence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logo.webp">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: #fffaf5;
        }

        /* Top Announcement Bar */
        .announcement-bar {
            background: linear-gradient(90deg, #8B4513, #A0522D);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .announcement-bar .badge {
            background: #FFD700;
            color: #8B4513;
            font-weight: 600;
            margin-left: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* Navbar */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 20px rgba(139, 69, 19, 0.1);
            padding: 12px 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
        }

        .brand-text {
            font-size: 1.6rem;
            font-weight: 700;
            color: #8B4513;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: #A0522D;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .nav-link {
            color: #5D4037;
            font-weight: 500;
            margin: 0 5px;
            padding: 8px 16px !important;
            transition: all 0.3s;
            border-radius: 25px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #8B4513;
            background: #FFF3E0;
        }

        .nav-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-icon {
            position: relative;
            color: #5D4037;
            font-size: 1.3rem;
            transition: color 0.3s;
            cursor: pointer;
        }

        .nav-icon:hover {
            color: #8B4513;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #D2691E;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #FFF8F0 0%, #FFE4C4 50%, #FFF3E0 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(210, 105, 30, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-tagline {
            display: inline-block;
            background: #8B4513;
            color: #FFD700;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 20px;
            animation: slideInLeft 0.8s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            color: #3E2723;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .hero-title span {
            color: #8B4513;
            display: block;
        }

        .hero-description {
            font-size: 1.1rem;
            color: #6D4C41;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .hero-buttons .btn {
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            margin-right: 15px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .btn-primary-custom {
            background: #8B4513;
            color: #fff;
            border: 2px solid #8B4513;
        }

        .btn-primary-custom:hover {
            background: #6D3410;
            border-color: #6D3410;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        }

        .btn-outline-custom {
            border: 2px solid #8B4513;
            color: #8B4513;
            background: transparent;
        }

        .btn-outline-custom:hover {
            background: #8B4513;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        }

        .hero-image-container {
            position: relative;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .hero-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .hero-badge .stars {
            color: #FFD700;
            font-size: 1.2rem;
        }

        .hero-badge .rating {
            font-weight: 700;
            color: #8B4513;
            font-size: 1.5rem;
        }

        /* Quick Products Section */
        .quick-products {
            padding: 60px 0;
            background: #fff;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #3E2723;
            margin-bottom: 10px;
        }

        .section-title p {
            color: #8D6E63;
            font-size: 1.1rem;
        }

        .product-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.4s;
            margin-bottom: 30px;
            border: 2px solid #F5E6D3;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
            border-color: #8B4513;
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 280px;
            background: #FFF8F0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            transition: transform 0.5s;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #8B4513;
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .product-info {
            padding: 25px;
            text-align: center;
        }

        .product-name {
            font-weight: 600;
            color: #3E2723;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .product-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #8B4513;
            margin-bottom: 15px;
        }

        .product-price .weight {
            font-size: 0.9rem;
            color: #8D6E63;
            font-weight: 400;
        }

        .btn-add-cart {
            background: #8B4513;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-add-cart:hover {
            background: #6D3410;
            transform: scale(1.05);
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #FFF8F0, #FFE4C4);
        }

        .feature-card {
            text-align: center;
            padding: 40px 20px;
            transition: all 0.3s;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: #8B4513;
            box-shadow: 0 10px 30px rgba(139, 69, 19, 0.1);
        }

        .feature-card h4 {
            font-weight: 600;
            color: #3E2723;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #8D6E63;
        }

        /* Stats Section */
        .stats-section {
            padding: 60px 0;
            background: #fff;
        }

        .stat-item {
            text-align: center;
            padding: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #8B4513;
        }

        .stat-label {
            color: #8D6E63;
            font-weight: 500;
            font-size: 1.1rem;
        }

        /* Collection Section */
        .collection-section {
            padding: 80px 0;
            background: #fff;
        }

        .flavor-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: 2px solid #F5E6D3;
            transition: all 0.4s;
            margin-bottom: 30px;
            position: relative;
        }

        .flavor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
            border-color: #8B4513;
        }

        .flavor-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #8B4513;
            color: #fff;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .flavor-icon {
            width: 90px;
            height: 90px;
            background: #FFF8F0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
        }

        .flavor-name {
            font-weight: 700;
            color: #3E2723;
            margin-bottom: 5px;
        }

        .flavor-desc {
            color: #8D6E63;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .platform-btns {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-amazon {
            background: #FF9900;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-amazon:hover {
            background: #E68A00;
            color: #fff;
            transform: scale(1.05);
        }

        .btn-flipkart {
            background: #2874F0;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-flipkart:hover {
            background: #1E5FCC;
            color: #fff;
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            background: #3E2723;
            color: #D7CCC8;
            padding: 60px 0 20px;
        }

        .footer h5 {
            color: #fff;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .footer a {
            color: #BCAAA4;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .footer a:hover {
            color: #FFD700;
        }

        .footer .brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: #FFD700;
            margin-bottom: 15px;
        }

        .footer .subtitle {
            color: #BCAAA4;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .whatsapp-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            color: #fff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 5px 25px rgba(37, 211, 102, 0.4);
            transition: all 0.3s;
            z-index: 1000;
            text-decoration: none;
        }

        .whatsapp-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.6);
            color: #fff;
        }

        .developer-credit {
            color: #BCAAA4;
            font-size: 0.85rem;
        }

        .developer-credit a {
            color: #FFD700;
            display: inline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .hero-section {
                padding: 50px 0;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body>


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