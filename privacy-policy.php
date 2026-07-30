<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Configuration matrix aur routing layer load line 1
include('config/connect.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - AristoNut | Premium Quality Makhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #fffaf5; color: #3E2723; overflow-x: hidden; }
        .text-brown { color: #ff0100;}
        
        /* Legal Layout Custom Styling Canvas */
        .legal-wrapper-card { background: #ffffff; border-radius: 24px; border: 2px solid #F5E6D3; overflow: hidden; padding: 40px; }
        .legal-header { background: linear-gradient(135deg, #FFF8F0 0%, #FFE4C4 100%); padding: 40px 0; border-bottom: 2px solid #F5E6D3; text-align: center; margin-bottom: 40px; border-radius: 20px; }
        
        .legal-content h4 { color: #ff0100; font-weight: 700; font-size: 1.25rem; margin-top: 25px; margin-bottom: 12px; position: relative; }
        .legal-content p { color: #5D4037; font-size: 0.95rem; line-height: 1.8; margin-bottom: 15px; text-align: justify; }
        .legal-content ul { color: #5D4037; font-size: 0.95rem; line-height: 1.8; margin-bottom: 20px; padding-left: 20px; }
        .legal-content li { margin-bottom: 8px; }
        
        @media (max-width: 768px) {
            .legal-wrapper-card { padding: 25px; }
            .legal-header { padding: 30px 15px; }
        }
    </style>
</head>
<body>

    <?php include('inc/header.php'); ?>

    <main class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $site; ?>index.php" class="text-brown text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
            </ol>
        </nav>

        <div class="card legal-wrapper-card shadow-sm mb-5">
            
            <div class="legal-header">
                <h1 class="fw-bold text-brown mb-2"><i class="bi bi-shield-lock-fill me-2"></i> Privacy Policy</h1>
                <p class="text-muted mb-0 small">Last Updated: <?php echo date('F d, Y'); ?> • AristoNut Snacks Enterprise</p>
            </div>

            <div class="legal-content px-lg-3">
                <p>Welcome to <strong>AristoNut</strong>. Your data security and privacy protection structure are core pillars of our governance. This Privacy Policy describes how your personal metrics and dataset coordinates are safely collected, processed, and maintained when you use or place an order through our webportal platform.</p>

                <h4>1. Information We Collect</h4>
                <p>When you interact with the AristoNut store ecosystem, certain dynamic fields are logged automatically or through manual entry initialization layers:</p>
                <ul>
                    <li><strong>Personal Identity Coordinates:</strong> Name, Email Address, Phone Number, and physical Shipping Address transmitted during the checkout processing sequences.</li>
                    <li><strong>Device Configuration Data Logs:</strong> IP Address, Session Identification matrix identifiers, and network parameter records utilized for security logs validation profiling.</li>
                    <li><strong>Volatile Shopping Metadata:</strong> Selected flavors preferences arrays, cart tracking memory nodes, and wishlist collections arrays.</li>
                </ul>

                <h4>2. How We Use Your Data</h4>
                <p>AristoNut processes input data vectors under absolute operational necessity protocols:</p>
                <ul>
                    <li>To fulfill, log, and trace order tracking parameters onto our structural schema table registries.</li>
                    <li>To safely transmit order confirmation references bills, and invoices generation indicators.</li>
                    <li>To process customer inquiries, emails validations, and background inquiry pipeline tasks safely.</li>
                    <li>To detect potential risk or fraudulent transaction activities against internal platform layers.</li>
                </ul>

                <h4>3. Data Protection and Session Cookies Locking</h4>
                <p>We implement a strict <strong>Global Session Cookies Scope Path Locking Strategy</strong>. Cookies generated during browser reloads are locked safely to the root domain path ("/"), preventing cross-directory volatile leakages or unauthorized memory split faults when browsing nested paths like <code>/product/</code> sub-folders.</p>
                <p>Your passwords or financial authorization logs are never directly parsed or cached within local storage units. Transaction pathways operate strictly via authenticated verification standard channels.</p>

                <h4>4. Third-Party Information Interchanges</h4>
                <p>We safely share minimal data variables only with vital operational partners to execute logistics:
                    <br>• <strong>Logistics Partners:</strong> Delivery channels access names and addresses to transport packages to your specific coordinates.
                    <br>• <strong>Database Hosts:</strong> Safe data archiving within isolated hosting parameters under standard security firewalls encryption controls.
                </p>

                <h4>5. Your Data Ownership Rights</h4>
                <p>As a verified user or customer, you retain absolute privileges over your recorded metadata layers. You can request changes, manual data erasure blocks, or complete tracking restrictions from our background logs registry parameters at any moment by contacting our web administration team.</p>

                <h4>6. Contact Corporate Governance</h4>
                <p>For questions or complaints about our dynamic data processing standard sequences, feel free to connect with our administrative support cell directly:</p>
                <p>
                    <i class="bi bi-envelope-fill text-brown me-2"></i> <strong>Email Correspondence:</strong> aristonut@gmail.com<br>
                    <i class="bi bi-geo-alt-fill text-brown me-2"></i> <strong>Corporate Node:</strong> Subhankarpur, Darbhanga, Bihar - 846004
                </p>
            </div>

        </div>
    </main>

    <?php include('inc/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>