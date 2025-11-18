<?php
require_once 'config/config.php';
require_once 'controllers/HotelController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Prepare filter parameters
        $filters = [];
        
        if (isset($_GET['khuvuc']) && $_GET['khuvuc'] !== '') {
            $filters['khuvuc'] = sanitize_input($_GET['khuvuc']);
        }
        
        if (isset($_GET['hangkhachsan']) && $_GET['hangkhachsan'] !== '') {
            $filters['hangkhachsan'] = sanitize_input($_GET['hangkhachsan']);
        }
        
        if (isset($_GET['xemhang']) && $_GET['xemhang'] !== '') {
            $filters['xemhang'] = sanitize_input($_GET['xemhang']);
        }
        
        if (!empty($_GET['limit'])) {
            $filters['limit'] = intval($_GET['limit']);
        } else {
            $filters['limit'] = 50;
        }
        
        // Use HotelController to search hotels
        $hotelController = new HotelController($conn);
        $hotels = $hotelController->search($filters);
        
        // Return results
        echo json_encode([
            'success' => true,
            'hotels' => $hotels,
            'total' => count($hotels),
            'filters_applied' => $filters
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

$conn->close();
?>