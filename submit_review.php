<?php
session_start();
include('config/connect.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $name = htmlspecialchars(trim($_POST['reviewer_name']));
    $email = filter_var(trim($_POST['reviewer_email']), FILTER_SANITIZE_EMAIL);
    $rating = intval($_POST['rating']);
    $review_text = htmlspecialchars(trim($_POST['review_text']));

    // Basic Validation
    if ($product_id > 0 && !empty($name) && !empty($review_text) && $rating > 0 && $rating <= 5) {
        
        $stmt = $conn->prepare("INSERT INTO `product_reviews` (`product_id`, `customer_name`, `customer_email`, `rating`, `review_text`, `status`) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("issis", $product_id, $name, $email, $rating, $review_text);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Thank you! Your review has been posted.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields correctly.']);
    }
}
?>