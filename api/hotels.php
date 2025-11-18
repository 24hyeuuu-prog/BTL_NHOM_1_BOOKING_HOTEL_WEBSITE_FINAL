<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/HotelController.php';

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

session_start();

$method = $_SERVER['REQUEST_METHOD'];
$hotelController = new HotelController($conn);

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

try {
    if ($method === 'GET') {
        // Get hotel by ID or search
        if (isset($_GET['id'])) {
            $hotelId = intval($_GET['id']);
            $result = $hotelController->getById($hotelId);
            
            if ($result) {
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy khách sạn']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Vui lòng chỉ định hành động']);
        }
    }
    elseif ($method === 'POST') {
        // Create new hotel (admin only)
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
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới có thể thêm khách sạn']);
            exit;
        }
        
        // Prepare data for creation
        $createData = [
            'Ten' => sanitize_input($_POST['Ten'] ?? ''),
            'hangkhachsan' => sanitize_input($_POST['hangkhachsan'] ?? ''),
            'khuvuc' => sanitize_input($_POST['khuvuc'] ?? ''),
            'mota' => trim($_POST['mota'] ?? ''),
            'giatri1' => sanitize_input($_POST['giatri1'] ?? ''),
            'giatri2' => sanitize_input($_POST['giatri2'] ?? ''),
            'giatri3' => sanitize_input($_POST['giatri3'] ?? ''),
            'giatri4' => sanitize_input($_POST['giatri4'] ?? ''),
            'anhmain' => sanitize_input($_POST['anhmain'] ?? ''),
            'anh1' => sanitize_input($_POST['anh1'] ?? ''),
            'anh2' => sanitize_input($_POST['anh2'] ?? ''),
            'anh3' => sanitize_input($_POST['anh3'] ?? ''),
            'anh4' => sanitize_input($_POST['anh4'] ?? ''),
            'motachitiet' => trim($_POST['motachitiet'] ?? ''),
            'vitri' => trim($_POST['vitri'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0)
        ];
        
        $response = $hotelController->create($createData);
        http_response_code($response['success'] ? 201 : 400);
        echo json_encode($response);
    }
    elseif ($method === 'PUT') {
        // Update hotel (admin only)
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
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới có thể cập nhật khách sạn']);
            exit;
        }
        
        // Get JSON data from request body
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mã khách sạn bắt buộc']);
            exit;
        }
        
        $hotelId = intval($data['id']);
        
        // Prepare data for update
        $updateData = [
            'Ten' => sanitize_input($data['Ten'] ?? ''),
            'hangkhachsan' => sanitize_input($data['hangkhachsan'] ?? ''),
            'khuvuc' => sanitize_input($data['khuvuc'] ?? ''),
            'mota' => trim($data['mota'] ?? ''),
            'giatri1' => sanitize_input($data['giatri1'] ?? ''),
            'giatri2' => sanitize_input($data['giatri2'] ?? ''),
            'giatri3' => sanitize_input($data['giatri3'] ?? ''),
            'giatri4' => sanitize_input($data['giatri4'] ?? ''),
            'anhmain' => sanitize_input($data['anhmain'] ?? ''),
            'anh1' => sanitize_input($data['anh1'] ?? ''),
            'anh2' => sanitize_input($data['anh2'] ?? ''),
            'anh3' => sanitize_input($data['anh3'] ?? ''),
            'anh4' => sanitize_input($data['anh4'] ?? ''),
            'motachitiet' => trim($data['motachitiet'] ?? ''),
            'vitri' => trim($data['vitri'] ?? ''),
            'price' => floatval($data['price'] ?? 0)
        ];
        
        $response = $hotelController->update($hotelId, $updateData);
        http_response_code($response['success'] ? 200 : 400);
        echo json_encode($response);
    }
    elseif ($method === 'DELETE') {
        // Delete hotel (admin only)
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
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới có thể xóa khách sạn']);
            exit;
        }
        
        if (isset($_GET['id'])) {
            $hotelId = intval($_GET['id']);
            $response = $hotelController->delete($hotelId);
            http_response_code($response['success'] ? 200 : 400);
            echo json_encode($response);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mã khách sạn không hợp lệ']);
        }
    }
    else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>
