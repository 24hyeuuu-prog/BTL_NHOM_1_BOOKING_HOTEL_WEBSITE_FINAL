<?php
require_once 'config/config.php';
require_once 'controllers/UserController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    $name = sanitize_input($_POST['name'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $password = sanitize_input($_POST['password'] ?? '');
    $password_confirm = sanitize_input($_POST['password'] ?? '');
    
    try {
        $userController = new UserController($conn);
        $result = $userController->register($name, $email, $phone, $password, $password_confirm);
        
        if ($result['success']) {
            // Set session after successful registration
            $user = User::authenticate($conn, $name, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['Mauser'];
                $_SESSION['username'] = $user['Tendangnhap'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['phone'] = $user['Sdt'];
                $_SESSION['avatar'] = $user['linkavatar'] ?? '';
                $_SESSION['admin'] = $user['admin'] ?? 0;
            }
            
            echo json_encode([
                'success' => true, 
                'message' => $result['message'],
                'redirect' => 'index.php'
            ]);
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
