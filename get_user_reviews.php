<?php
require_once 'config/config.php';
require_once 'controllers/ReviewController.php';

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $reviewController = new ReviewController($conn);
        $reviews = $reviewController->getUserReviews($_SESSION['user_id']);
        
        echo json_encode([
            'success' => true, 
            'reviews' => $reviews,
            'total' => count($reviews)
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>