<?php
require_once 'config/config.php';
require_once 'controllers/HotelController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $maKS = intval($_GET['id'] ?? 0);
    
    if ($maKS <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid hotel ID']);
        exit;
    }
    
    try {
        // Use HotelController to get hotel details with reviews
        $hotelController = new HotelController($conn);
        $hotel = $hotelController->getById($maKS);
        
        if (!$hotel) {
            echo json_encode(['success' => false, 'message' => 'Hotel not found']);
            exit;
        }
        
        // Calculate rating statistics
        $reviews = $hotel['reviews'] ?? [];
        $rating_stats = array(
            'xuất sắc' => 0,
            'tốt' => 0,
            'bình thường' => 0,
            'kém' => 0,
            'rất tệ' => 0
        );
        
        foreach ($reviews as $review) {
            $rating = $review['diemreview'];
            if ($rating >= 4.5) $rating_stats['xuất sắc']++;
            elseif ($rating >= 4.0) $rating_stats['tốt']++;
            elseif ($rating >= 2.5) $rating_stats['bình thường']++;
            elseif ($rating >= 1.5) $rating_stats['kém']++;
            else $rating_stats['rất tệ']++;
        }
        
        echo json_encode([
            'success' => true, 
            'hotel' => $hotel,
            'reviews' => $reviews,
            'total_reviews' => count($reviews),
            'rating_stats' => $rating_stats
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>