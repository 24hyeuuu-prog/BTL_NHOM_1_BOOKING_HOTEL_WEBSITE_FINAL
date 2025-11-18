<?php
require_once 'config/config.php';
require_once 'controllers/HotelController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $limit = intval($_GET['limit'] ?? 4);
    $exclude_id = intval($_GET['exclude'] ?? 0);
    
    try {
        $hotelController = new HotelController($conn);
        $recommendations = $hotelController->getRecommendations($limit, $exclude_id);
        
        echo json_encode(['success' => true, 'recommendations' => $recommendations]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>