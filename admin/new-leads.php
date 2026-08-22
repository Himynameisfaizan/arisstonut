<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include "db-conn.php";
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Customer Inquiries | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-unread {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid #ffc107;
        }
        .status-read {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid #6c757d;
        }
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .indicator-unread {
            background-color: #ffc107;
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2);
        }
        .indicator-read {
            background-color: #6c757d;
            box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.2);
        }
        .customer-name {
            font-weight: 600;
            color: #0d6efd;
        }
        .customer-email {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .inquiry-subject {
            font-weight: 500;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .message-preview {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #495057;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.875rem;
        }
        .filter-badge {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .filter-badge:hover {
            transform: translateY(-2px);
        }
        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .message-cell {
            max-width: 250px;
            cursor: pointer;
        }
        .message-modal .modal-body {
            white-space: pre-wrap;
            line-height: 1.6;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
            max-height: 400px;
            overflow-y: auto;
        }
        .select-all-checkbox {
            margin-right: 10px;
        }
        .bulk-actions {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table tbody td {
            vertical-align: middle;
            padding: 12px 8px;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }
        .empty-state i {
            font-size: 48px;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        .date-cell {
            font-size: 0.85rem;
            color: #6c757d;
            white-space: nowrap;
        }
        .stats-card {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .unread-row {
            background-color: rgba(13, 110, 253, 0.03);
        }
        .checkbox-cell {
            width: 40px;
        }
        .table-container {
            position: relative;
        }
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            display: none;
        }
    </style>
</head>

<body class="crm_body_bg">
    <?php include "header.php"; ?>
    
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <?php include "top_nav.php"; ?>
                </div>
            </div>
        </div>
        
        <div class="main_content_iner">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <h3 class="m-0">
                                                <i class="fas fa-envelope me-2"></i>Customer Inquiries
                                            </h3>
                                            <p class="text-muted mb-0 mt-1">Manage all customer inquiries in one place</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-primary" onclick="exportToExcel()">
                                                <i class="fas fa-file-export me-1"></i>Export
                                            </button>
                                            <button class="btn btn-success" onclick="printTable()">
                                                <i class="fas fa-print me-1"></i>Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="white_card_body">
                                <!-- Stats Cards -->
                                <div class="row mb-4">
                                    <?php
                                    // Get counts
                                    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM inquiries");
                                    $total = mysqli_fetch_assoc($total_query)['total'];
                                    
                                    $unread_query = mysqli_query($conn, "SELECT COUNT(*) as unread FROM inquiries WHERE status = 0");
                                    $unread = mysqli_fetch_assoc($unread_query)['unread'];
                                    
                                    $today_query = mysqli_query($conn, "SELECT COUNT(*) as today FROM inquiries WHERE DATE(created_at) = CURDATE()");
                                    $today = mysqli_fetch_assoc($today_query)['today'];
                                    
                                    $week_query = mysqli_query($conn, "SELECT COUNT(*) as week FROM inquiries WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)");
                                    $week = mysqli_fetch_assoc($week_query)['week'];
                                    ?>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-gradient text-white rounded p-3 me-3">
                                                        <i class="fas fa-inbox"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="text-muted mb-1">Total Inquiries</h6>
                                                        <h3 class="mb-0"><?php echo $total; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-gradient text-white rounded p-3 me-3">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="text-muted mb-1">Unread Inquiries</h6>
                                                        <h3 class="mb-0 unread-counter"><?php echo $unread; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-gradient text-white rounded p-3 me-3">
                                                        <i class="fas fa-calendar-day"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="text-muted mb-1">Today</h6>
                                                        <h3 class="mb-0"><?php echo $today; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-gradient text-white rounded p-3 me-3">
                                                        <i class="fas fa-calendar-week"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="text-muted mb-1">This Week</h6>
                                                        <h3 class="mb-0"><?php echo $week; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filters and Actions -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <span class="badge bg-primary filter-badge px-3 py-2" onclick="filterInquiries('all')">
                                                <i class="fas fa-list me-1"></i>All (<?php echo $total; ?>)
                                            </span>
                                            <span class="badge bg-warning filter-badge px-3 py-2" onclick="filterInquiries('unread')">
                                                <i class="fas fa-envelope me-1"></i>Unread (<?php echo $unread; ?>)
                                            </span>
                                            <span class="badge bg-success filter-badge px-3 py-2" onclick="filterInquiries('today')">
                                                <i class="fas fa-calendar-day me-1"></i>Today (<?php echo $today; ?>)
                                            </span>
                                            <span class="badge bg-info filter-badge px-3 py-2" onclick="filterInquiries('week')">
                                                <i class="fas fa-calendar-week me-1"></i>This Week (<?php echo $week; ?>)
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <div class="input-group" style="max-width: 300px;">
                                                <input type="text" id="searchInput" class="form-control" 
                                                       placeholder="Search inquiries...">
                                                <button class="btn btn-primary" type="button" onclick="searchTable()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <select class="form-select" style="max-width: 150px;" id="sortSelect" onchange="sortTable()">
                                                <option value="newest">Newest</option>
                                                <option value="oldest">Oldest</option>
                                                <option value="name">Name A-Z</option>
                                                <option value="unread">Unread First</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bulk Actions -->
                                <div class="bulk-actions mb-3" style="display: none;" id="bulkActions">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span id="selectedCount">0</span> inquiries selected
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-success" onclick="markSelectedAsRead()">
                                                <i class="fas fa-check me-1"></i>Mark as Read
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteSelected()">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                                                <i class="fas fa-times me-1"></i>Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inquiries Table -->
                                <div class="table-container">
                                    <div class="loading-overlay" id="loadingOverlay">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="inquiriesTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th scope="col" class="checkbox-cell">
                                                        <div class="form-check">
                                                            <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                                        </div>
                                                    </th>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col">Contact</th>
                                                    <th scope="col">Subject</th>
                                                    <th scope="col">Message</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sno = 1;
                                                $sql = "SELECT * FROM `inquiries` ORDER BY `created_at` DESC";
                                                $result = mysqli_query($conn, $sql);
                                                
                                                if(mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $statusClass = $row['status'] == 0 ? 'status-unread' : 'status-read';
                                                        $indicatorClass = $row['status'] == 0 ? 'indicator-unread' : 'indicator-read';
                                                        $statusText = $row['status'] == 0 ? 'Unread' : 'Read';
                                                        $rowClass = $row['status'] == 0 ? 'unread-row' : '';
                                                        
                                                        $messagePreview = htmlspecialchars($row['message']);
                                                        $isLongMessage = strlen($messagePreview) > 50;
                                                        $shortMessage = $isLongMessage ? substr($messagePreview, 0, 50) . '...' : $messagePreview;
                                                ?>
                                                <tr class="inquiry-row <?php echo $rowClass; ?>" 
                                                    data-id="<?php echo $row['id']; ?>"
                                                    data-status="<?php echo $row['status']; ?>"
                                                    data-date="<?php echo $row['created_at']; ?>"
                                                    data-name="<?php echo strtolower(htmlspecialchars($row['name'])); ?>"
                                                    data-email="<?php echo strtolower(htmlspecialchars($row['email'])); ?>"
                                                    data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                                    data-subject="<?php echo strtolower(htmlspecialchars($row['subject'])); ?>"
                                                    data-message="<?php echo htmlspecialchars($row['message']); ?>">
                                                    <td class="checkbox-cell">
                                                        <div class="form-check">
                                                            <input class="form-check-input row-checkbox" type="checkbox" value="<?php echo $row['id']; ?>" onclick="updateBulkActions()">
                                                        </div>
                                                    </td>
                                                    <th scope="row"><?php echo $sno++; ?></th>
                                                    <td>
                                                        <div>
                                                            <span class="customer-name"><?php echo htmlspecialchars($row['name']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="customer-email mb-1">
                                                            <i class="far fa-envelope me-1"></i>
                                                            <?php echo htmlspecialchars($row['email']); ?>
                                                        </div>
                                                        <div class="customer-phone">
                                                            <i class="fas fa-phone me-1"></i>
                                                            <?php echo htmlspecialchars($row['phone']); ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="inquiry-subject" title="<?php echo htmlspecialchars($row['subject']); ?>">
                                                            <?php echo htmlspecialchars($row['subject']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="message-cell" onclick="showMessageModal('<?php echo addslashes(htmlspecialchars($row['message'])); ?>', '<?php echo htmlspecialchars($row['name']); ?>')">
                                                        <span class="message-preview" title="<?php echo htmlspecialchars($row['message']); ?>">
                                                            <?php echo $shortMessage; ?>
                                                        </span>
                                                        <?php if($isLongMessage): ?>
                                                        <small class="text-primary d-block mt-1">
                                                            <i class="fas fa-expand-alt me-1"></i>Click to view full message
                                                        </small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge <?php echo $statusClass; ?>">
                                                            <span class="status-indicator <?php echo $indicatorClass; ?>"></span>
                                                            <?php echo $statusText; ?>
                                                        </span>
                                                    </td>
                                                    <td class="date-cell">
                                                        <i class="far fa-clock me-1"></i>
                                                        <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                                                        <br>
                                                        <small><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="action-buttons">
                                                            <?php if($row['status'] == 0): ?>
                                                            <button class="btn btn-sm btn-success" 
                                                                    onclick="markAsRead(<?php echo $row['id']; ?>)"
                                                                    data-bs-toggle="tooltip" title="Mark as Read">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <?php endif; ?>
                                                            <button class="btn btn-sm btn-info" 
                                                                    onclick="viewInquiry(<?php echo $row['id']; ?>)"
                                                                    data-bs-toggle="tooltip" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-primary" 
                                                                    onclick="respondToInquiry(<?php echo $row['id']; ?>)"
                                                                    data-bs-toggle="tooltip" title="Respond via Email">
                                                                <i class="fas fa-reply"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" 
                                                                    onclick="deleteInquiry(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>')"
                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                ?>
                                                <tr>
                                                    <td colspan="9" class="empty-state">
                                                        <i class="fas fa-inbox fa-4x mb-4"></i>
                                                        <h4 class="text-muted">No Inquiries Yet</h4>
                                                        <p class="text-muted mb-4">Customer inquiries will appear here once they contact you.</p>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted">
                                                Showing <span id="showingCount"><?php echo mysqli_num_rows($result); ?></span> of <?php echo $total; ?> inquiries
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                                    </li>
                                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">Next</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>
    </section>

    <!-- Message Modal -->
    <div class="modal fade message-modal" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message from <span id="customerName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="messageContent">
                    <!-- Message content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="copyMessage()">
                        <i class="fas fa-copy me-1"></i>Copy Message
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
    // Initialize tooltips
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
    
    let selectedInquiries = new Set();
    
    // Filter inquiries
    function filterInquiries(filter) {
        const rows = document.querySelectorAll('.inquiry-row');
        let visibleCount = 0;
        const today = new Date().toISOString().split('T')[0];
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
        
        rows.forEach(row => {
            let show = false;
            const itemDate = new Date(row.dataset.date);
            
            switch(filter) {
                case 'all':
                    show = true;
                    break;
                case 'unread':
                    show = row.dataset.status === '0';
                    break;
                case 'today':
                    show = row.dataset.date.includes(today);
                    break;
                case 'week':
                    show = itemDate >= oneWeekAgo;
                    break;
            }
            
            row.style.display = show ? '' : 'none';
            if(show) visibleCount++;
        });
        
        document.getElementById('showingCount').textContent = visibleCount;
    }
    
    // Search table
    function searchTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.inquiry-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const name = row.dataset.name;
            const email = row.dataset.email;
            const phone = row.dataset.phone;
            const subject = row.dataset.subject;
            const message = row.dataset.message;
            
            const matches = name.includes(searchTerm) || 
                           email.includes(searchTerm) || 
                           phone.includes(searchTerm) || 
                           subject.includes(searchTerm) ||
                           message.includes(searchTerm);
            
            row.style.display = matches ? '' : 'none';
            if(matches) visibleCount++;
        });
        
        document.getElementById('showingCount').textContent = visibleCount;
    }
    
    // Sort table
    function sortTable() {
        const sortBy = document.getElementById('sortSelect').value;
        const tbody = document.querySelector('#inquiriesTable tbody');
        const rows = Array.from(tbody.querySelectorAll('.inquiry-row'));
        
        rows.sort((a, b) => {
            switch(sortBy) {
                case 'newest':
                    return new Date(b.dataset.date) - new Date(a.dataset.date);
                case 'oldest':
                    return new Date(a.dataset.date) - new Date(b.dataset.date);
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'unread':
                    return parseInt(b.dataset.status) - parseInt(a.dataset.status);
            }
        });
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    }
    
    // Show message modal
    function showMessageModal(message, customerName) {
        document.getElementById('customerName').textContent = customerName;
        document.getElementById('messageContent').textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('messageModal'));
        modal.show();
    }
    
    // Copy message to clipboard
    function copyMessage() {
        const message = document.getElementById('messageContent').textContent;
        navigator.clipboard.writeText(message).then(() => {
            alert('Message copied to clipboard!');
        });
    }
    
    // Mark as read
    function markAsRead(id) {
        if(confirm('Mark this inquiry as read?')) {
            showLoading(true);
            // You can use AJAX here or redirect
            window.location.href = 'mark-as-read.php?id=' + id;
        }
    }
    
    // Mark all as read
    function markAllAsRead() {
        if(confirm('Mark all inquiries as read?')) {
            showLoading(true);
            window.location.href = 'mark-all-read.php';
        }
    }
    
    // Respond to inquiry
    function respondToInquiry(id) {
        const row = document.querySelector(`.inquiry-row[data-id="${id}"]`);
        const name = row.dataset.name;
        const email = row.querySelector('.customer-email').textContent.split(' ')[1];
        const subject = row.querySelector('.inquiry-subject').textContent;
        
        const mailto = `mailto:${email}?subject=Re: ${encodeURIComponent(subject)}&body=Dear ${encodeURIComponent(name)},\n\n`;
        window.location.href = mailto;
    }
    
    // Delete inquiry
    function deleteInquiry(id, name) {
        if(confirm(`Are you sure you want to delete inquiry from "${name}"?`)) {
            showLoading(true);
            window.location.href = 'delete-inquiry.php?id=' + id;
        }
    }
    
    // Delete all read
    function deleteAllRead() {
        if(confirm('Delete all read inquiries? This action cannot be undone.')) {
            showLoading(true);
            window.location.href = 'delete-all-read.php';
        }
    }
    
    // Toggle select all
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
            if(selectAll.checked) {
                selectedInquiries.add(checkbox.value);
            } else {
                selectedInquiries.delete(checkbox.value);
            }
        });
        
        updateBulkActions();
    }
    
    // Update bulk actions
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        selectedInquiries.clear();
        
        checkboxes.forEach(checkbox => {
            selectedInquiries.add(checkbox.value);
        });
        
        document.getElementById('selectedCount').textContent = selectedInquiries.size;
        document.getElementById('bulkActions').style.display = selectedInquiries.size > 0 ? 'block' : 'none';
        document.getElementById('selectAll').checked = selectedInquiries.size === document.querySelectorAll('.row-checkbox').length;
    }
    
    // Mark selected as read
    function markSelectedAsRead() {
        if(selectedInquiries.size === 0) return;
        
        if(confirm(`Mark ${selectedInquiries.size} selected inquiry(s) as read?`)) {
            showLoading(true);
            // You can use AJAX here
            const ids = Array.from(selectedInquiries).join(',');
            window.location.href = 'mark-selected-read.php?ids=' + ids;
        }
    }
    
    // Delete selected
    function deleteSelected() {
        if(selectedInquiries.size === 0) return;
        
        if(confirm(`Delete ${selectedInquiries.size} selected inquiry(s)? This action cannot be undone.`)) {
            showLoading(true);
            const ids = Array.from(selectedInquiries).join(',');
            window.location.href = 'delete-selected.php?ids=' + ids;
        }
    }
    
    // Clear selection
    function clearSelection() {
        selectedInquiries.clear();
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateBulkActions();
    }
    
    // Export to Excel
    function exportToExcel() {
        // You can implement Excel export using SheetJS or server-side export
        alert('Export feature would be implemented here');
    }
    
    // Print table
    function printTable() {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Inquiries Report</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .date { text-align: right; color: #666; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Customer Inquiries Report</h2>
                    <p class="date">Generated on ${new Date().toLocaleDateString()}</p>
                </div>
                ${document.getElementById('inquiriesTable').outerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
    
    // Show/hide loading
    function showLoading(show) {
        document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
    }
    
    // Search on Enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            searchTable();
        }
    });
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Add any initialization code here
    });
    </script>
</body>
</html>