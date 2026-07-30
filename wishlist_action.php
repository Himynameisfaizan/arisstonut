<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');

header('Content-Type: application/json');

if (isset($_POST['action']) && $_POST['action'] == 'toggle_wishlist') {
    $pid = intval($_POST['product_id']);

    if ($pid > 0) {
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }

        // Agar item pehle se wishlist mein hai to remove karein, nahi to add karein
        if (in_array($pid, $_SESSION['wishlist'])) {
            $key = array_search($pid, $_SESSION['wishlist']);
            unset($_SESSION['wishlist'][$key]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']); // Re-index array
            $status = 'removed';
            $message = 'Removed from your wishlist collection.';
        } else {
            $_SESSION['wishlist'][] = $pid;
            $status = 'added';
            $message = 'Added to your wishlist collection!';
        }

        echo json_encode([
            'status' => 'success',
            'action_status' => $status,
            'message' => $message,
            'wishlist_count' => count($_SESSION['wishlist'])
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Product ID targeting setup.']);
    }
    exit();
}
?>