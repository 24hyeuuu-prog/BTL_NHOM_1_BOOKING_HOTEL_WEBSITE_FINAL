<?php

// Bắt tất cả lỗi và cảnh báo PHP
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/bookings_errors.log');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/BookingController.php';

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log("\n=== BOOKING API REQUEST ===");
error_log("Time: " . date('Y-m-d H:i:s'));
error_log("Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Session Status: " . session_status());
error_log("Session ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));
error_log("User ID from session: " . ($_SESSION['user_id'] ?? 'NOT SET'));

// Require login - check if user_id exists
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(401);
    error_log("ERROR: User not logged in - Session user_id not set");
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt phòng']);
    ob_end_clean();
    exit;
}

error_log("User authenticated: " . $_SESSION['user_id']);

$method = $_SERVER['REQUEST_METHOD'];
$controller = new BookingController($conn);
$user_id = $_SESSION['user_id'];

try {
    if ($method === 'GET') {
        // Get user bookings
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'pending') {
                $bookings = $controller->getUserPendingBookings($user_id);
                echo json_encode(['success' => true, 'data' => $bookings]);
            } 
            elseif ($_GET['action'] === 'completed') {
                $bookings = $controller->getUserCompletedBookings($user_id);
                echo json_encode(['success' => true, 'data' => $bookings]);
            }
            elseif ($_GET['action'] === 'cancelled') {
                $bookings = $controller->getUserCancelledBookings($user_id);
                echo json_encode(['success' => true, 'data' => $bookings]);
            }
            elseif ($_GET['action'] === 'all') {
                $bookings = $controller->getUserBookings($user_id);
                echo json_encode(['success' => true, 'data' => $bookings]);
            }
            elseif ($_GET['action'] === 'getById' && isset($_GET['booking_id'])) {
                $booking_id = intval($_GET['booking_id']);
                $booking = $controller->getById($booking_id);
                
                if ($booking) {
                    // Check ownership
                    if ($booking['user_id'] != $user_id) {
                        http_response_code(403);
                        echo json_encode(['success' => false, 'message' => 'Không có quyền xem đặt phòng này']);
                        exit;
                    }
                    echo json_encode(['success' => true, 'data' => $booking]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không tìm thấy đặt phòng']);
                }
            }
            else {
                echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
            }
        } else {
            // Get all user bookings by default
            $bookings = $controller->getUserBookings($user_id);
            echo json_encode(['success' => true, 'data' => $bookings]);
        }
    }
    elseif ($method === 'POST') {
        // Create new booking
        $raw_data = file_get_contents("php://input");
        error_log("Raw POST data: $raw_data");
        
        $data = json_decode($raw_data, true);
        error_log("Decoded data: " . print_r($data, true));
        
        // Log incoming data for debugging
        error_log("Booking request data: " . print_r($data, true));
        
        // Validate required fields with detailed logging
        if (!isset($data['hotel_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'hotel_id is required']);
            error_log("ERROR: Missing hotel_id");
            exit;
        }
        
        if (!isset($data['room_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'room_id is required']);
            error_log("ERROR: Missing room_id");
            exit;
        }
        
        if (!isset($data['check_in_date'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'check_in_date is required']);
            error_log("ERROR: Missing check_in_date");
            exit;
        }
        
        if (!isset($data['check_out_date'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'check_out_date is required']);
            error_log("ERROR: Missing check_out_date");
            exit;
        }
        
        if (!isset($data['num_guests'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'num_guests is required']);
            error_log("ERROR: Missing num_guests");
            exit;
        }
        
        if (!isset($data['total_price'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'total_price is required']);
            error_log("ERROR: Missing total_price");
            exit;
        }
        
        // Optional fields that are provided
        $room_numbers = $data['room_numbers'] ?? '';
        $room_ids = $data['room_ids'] ?? '';
        $room_quantity = $data['room_quantity'] ?? 1;
        
        error_log("Room numbers (tên phòng): $room_numbers");
        error_log("Room IDs (mã phòng): $room_ids");
        error_log("Room quantity: $room_quantity");
        error_log("All validations passed. Creating booking...");
        
        $response = $controller->create($user_id, $data['hotel_id'], $data['room_id'], $data);
        
        // Log response for debugging
        error_log("Booking response: " . print_r($response, true));
        
        // Make sure we send JSON response
        $http_code = $response['success'] ? 201 : 400;
        http_response_code($http_code);
        
        $json_response = json_encode($response);
        error_log("JSON response being sent: " . $json_response);
        
        echo $json_response;
    }
    elseif ($method === 'PUT') {
        // Cancel or update booking
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['booking_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mã đặt phòng bắt buộc']);
            exit;
        }
        
        if (isset($data['action']) && $data['action'] === 'cancel') {
            // User cancellation
            $response = $controller->cancel($data['booking_id'], $user_id);
        } else {
            // Check if admin (for status update)
            $check_admin = "SELECT admin FROM nguoidung WHERE Mauser = ?";
            $stmt = $conn->prepare($check_admin);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            
            if (!$user || !$user['admin']) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Chỉ admin mới có thể cập nhật trạng thái']);
                exit;
            }
            
            if (!isset($data['status'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Trạng thái bắt buộc']);
                exit;
            }
            
            // Map English status to Vietnamese
            $status_map = [
                'pending' => 'Chưa xác nhận',
                'confirmed' => 'Đã xác nhận',
                'completed' => 'Đã hoàn thành',
                'cancelled' => 'Đã hủy'
            ];
            
            $status = isset($status_map[$data['status']]) ? $status_map[$data['status']] : $data['status'];
            $notes = $data['notes'] ?? '';
            
            $response = $controller->updateBooking($data['booking_id'], $status, $notes);
        }
        
        http_response_code($response['success'] ? 200 : 400);
        echo json_encode($response);
    }
    else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ: ' . $e->getMessage()]);
}

// Output buffer needs to be flushed, not cleaned
ob_end_flush();
?>
