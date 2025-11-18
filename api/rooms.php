<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/RoomController.php';

// Set JSON response header
header('Content-Type: application/json');

// Check login for booking/admin operations
session_start();

$method = $_SERVER['REQUEST_METHOD'];
$controller = new RoomController($conn);

try {
    if ($method === 'GET') {
        // Get available rooms for hotel and dates
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'getByHotel' && isset($_GET['hotel_id'])) {
                $hotel_id = intval($_GET['hotel_id']);
                $rooms = $controller->getByHotel($hotel_id);
                
                if ($rooms) {
                    echo json_encode(['success' => true, 'data' => $rooms]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không tìm thấy phòng']);
                }
            } 
            elseif ($_GET['action'] === 'getGrouped' && isset($_GET['hotel_id'])) {
                $hotel_id = intval($_GET['hotel_id']);
                $rooms = $controller->getGroupedByType($hotel_id);
                
                if ($rooms) {
                    echo json_encode(['success' => true, 'data' => $rooms]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không tìm thấy phòng']);
                }
            }
            elseif ($_GET['action'] === 'getAvailable' && isset($_GET['hotel_id']) && 
                   isset($_GET['check_in']) && isset($_GET['check_out'])) {
                $hotel_id = intval($_GET['hotel_id']);
                $check_in = sanitize_input($_GET['check_in']);
                $check_out = sanitize_input($_GET['check_out']);
                
                $rooms = $controller->getAvailable($hotel_id, $check_in, $check_out);
                
                if ($rooms) {
                    echo json_encode(['success' => true, 'data' => $rooms]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không có phòng trống']);
                }
            }
            elseif ($_GET['action'] === 'getById' && isset($_GET['room_id'])) {
                $room_id = intval($_GET['room_id']);
                $room = $controller->getById($room_id);
                
                if ($room) {
                    echo json_encode(['success' => true, 'data' => $room]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không tìm thấy phòng']);
                }
            }
            else {
                echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Vui lòng chỉ định hành động']);
        }
    }
    elseif ($method === 'POST') {
        // Create new room (admin only)
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }
        
        // Check if admin
        $check_admin = "SELECT admin FROM nguoidung WHERE Mauser = ?";
        $stmt = $conn->prepare($check_admin);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if (!$user || !$user['admin']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới có thể thêm phòng']);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $response = $controller->create($data);
        
        http_response_code($response['success'] ? 201 : 400);
        echo json_encode($response);
    }
    elseif ($method === 'PUT') {
        // Update room (admin only)
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }
        
        // Check if admin
        $check_admin = "SELECT admin FROM nguoidung WHERE Mauser = ?";
        $stmt = $conn->prepare($check_admin);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if (!$user || !$user['admin']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới có thể cập nhật phòng']);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['room_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mã phòng bắt buộc']);
            exit;
        }
        
        $response = $controller->update($data['room_id'], $data);
        http_response_code($response['success'] ? 200 : 400);
        echo json_encode($response);
    }
    elseif ($method === 'DELETE') {
        // Delete room (admin only)
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }
        
        // Check if admin
        $check_admin = "SELECT admin FROM nguoidung WHERE Mauser = ?";
        $stmt = $conn->prepare($check_admin);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if (!$user || !$user['admin']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới có thể xóa phòng']);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['room_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mã phòng bắt buộc']);
            exit;
        }
        
        $response = $controller->delete($data['room_id']);
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

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
