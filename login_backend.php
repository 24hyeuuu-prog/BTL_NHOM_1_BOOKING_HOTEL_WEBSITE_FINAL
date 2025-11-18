<?php
require_once 'config/config.php';
require_once 'controllers/UserController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = sanitize_input($_POST['login'] ?? '');
    $password = sanitize_input($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);
    
    // Validate input
    if (empty($login) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
        exit;
    }
    
    try {
        $userController = new UserController($conn);
        $result = $userController->login($login, $password);
        
        if ($result['success']) {
            // Get user details
            $user = User::authenticate($conn, $login, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['Mauser'];
                $_SESSION['username'] = $user['Tendangnhap'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['phone'] = $user['Sdt'];
                $_SESSION['avatar'] = $user['linkavatar'] ?? '';
                $_SESSION['admin'] = $user['admin'] ?? 0;
                
                // Set remember me cookie nếu được chọn
                if ($remember) {
                    setcookie('remember_user', $user['Mauser'], time() + (30 * 24 * 60 * 60), '/');
                }
                
                // Xét điều kiện chuyển hướng dựa trên role
                $redirect = $user['admin'] == 1 ? 'admin-dashboard.php' : 'index.php';
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đăng nhập thành công',
                    'redirect' => $redirect
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>