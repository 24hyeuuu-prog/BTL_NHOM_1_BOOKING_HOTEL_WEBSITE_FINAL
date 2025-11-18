<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/ReviewController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to continue']);
    exit;
}

$reviewController = new ReviewController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get reviews
    if (isset($_GET['hotel_id'])) {
        // Get hotel reviews
        $hotel_id = intval($_GET['hotel_id']);
        $limit = intval($_GET['limit'] ?? 10);
        $offset = intval($_GET['offset'] ?? 0);
        
        $reviews = $reviewController->getHotelReviews($hotel_id, $limit, $offset);
        echo json_encode(['success' => true, 'data' => $reviews]);
        
    } elseif (isset($_GET['user_id'])) {
        // Get user reviews
        $user_id = intval($_GET['user_id']);
        
        if ($user_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $reviews = $reviewController->getUserReviews($user_id);
        echo json_encode(['success' => true, 'data' => $reviews, 'total' => count($reviews)]);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Submit or update review
    
    if (!isset($_POST['hotel_id'])) {
        echo json_encode(['success' => false, 'message' => 'Hotel ID is required']);
        exit;
    }
    
    $hotel_id = intval($_POST['hotel_id']);
    
    $review_data = [
        'rating' => $_POST['rating'] ?? 0,
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
        'purpose' => $_POST['purpose'] ?? '',
        'companion' => $_POST['companion'] ?? ''
    ];
    
    $result = $reviewController->submit($_SESSION['user_id'], $hotel_id, $review_data);
    echo json_encode($result);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update review
    parse_str(file_get_contents("php://input"), $_PUT);
    
    if (!isset($_PUT['review_id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID is required']);
        exit;
    }
    
    $review_id = intval($_PUT['review_id']);
    
    $review_data = [
        'rating' => $_PUT['rating'] ?? 0,
        'title' => $_PUT['title'] ?? '',
        'content' => $_PUT['content'] ?? '',
        'purpose' => $_PUT['purpose'] ?? '',
        'companion' => $_PUT['companion'] ?? ''
    ];
    
    $result = $reviewController->update($review_id, $_SESSION['user_id'], $review_data);
    echo json_encode($result);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete review
    parse_str(file_get_contents("php://input"), $_DELETE);
    
    if (!isset($_DELETE['review_id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID is required']);
        exit;
    }
    
    $review_id = intval($_DELETE['review_id']);
    
    $result = $reviewController->delete($review_id, $_SESSION['user_id']);
    echo json_encode($result);
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
