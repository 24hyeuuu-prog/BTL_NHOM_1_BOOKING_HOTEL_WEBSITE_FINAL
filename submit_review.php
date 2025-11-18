<?php
/**
 * API Endpoint: Submit Review
 * 
 * POST /submit_review.php
 * Submits a new review for a hotel
 * 
 * Required Parameters:
 * - hotel_id: int - ID of the hotel
 * - rating: float - Rating from 1 to 5
 * - title: string - Review title
 * - content: string - Review content
 * - purpose: string - Purpose of the trip
 * - companion: string - Travel companion
 */

require_once 'config/config.php';
require_once 'controllers/ReviewController.php';

header('Content-Type: application/json; charset=utf-8');

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không được hỗ trợ'
    ]);
    exit;
}

// Check login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để gửi đánh giá'
    ]);
    exit;
}

// Validate hotel ID
$hotel_id = intval($_POST['hotel_id'] ?? 0);
if ($hotel_id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID khách sạn không hợp lệ'
    ]);
    exit;
}

// Prepare review data
$review_data = [
    'rating' => floatval($_POST['rating'] ?? 0),
    'title' => $_POST['title'] ?? '',
    'content' => $_POST['content'] ?? '',
    'purpose' => $_POST['purpose'] ?? '',
    'companion' => $_POST['companion'] ?? ''
];

try {
    $reviewController = new ReviewController($conn);
    $result = $reviewController->submit($_SESSION['user_id'], $hotel_id, $review_data);
    
    // Set appropriate HTTP status
    $status_code = $result['success'] ? 201 : 400;
    http_response_code($status_code);
    
    // Add redirect URL for successful submission
    if ($result['success']) {
        $result['redirect'] = 'chitietkhachsan.php?id=' . $hotel_id;
    }
    
    echo json_encode($result);
    
} catch (Exception $e) {
    error_log('Review submission error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi máy chủ: ' . $e->getMessage()
    ]);
}

$conn->close();
?>

