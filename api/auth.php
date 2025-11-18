<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/UserController.php';

header('Content-Type: application/json');

$userController = new UserController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['action'])) {
        $action = sanitize_input($_POST['action']);
        
        if ($action === 'register') {
            // Register new user
            $username = sanitize_input($_POST['username'] ?? '');
            $email = sanitize_input($_POST['email'] ?? '');
            $phone = sanitize_input($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            
            $result = $userController->register($username, $email, $phone, $password, $password_confirm);
            
            if ($result['success']) {
                // Set session
                $_SESSION['user_id'] = $result['user_id'];
                // Get user data
                $user = User::getById($conn, $result['user_id']);
                $_SESSION['username'] = $user['Tendangnhap'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['avatar'] = $user['linkavatar'];
                $_SESSION['admin'] = $user['admin'] ?? 0;
            }
            
            echo json_encode($result);
            
        } elseif ($action === 'login') {
            // Login user
            $username = sanitize_input($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $result = $userController->login($username, $password);
            
            if ($result['success']) {
                // Set session
                $user = $result['user'];
                $_SESSION['user_id'] = $user['Mauser'];
                $_SESSION['username'] = $user['Tendangnhap'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['avatar'] = $user['linkavatar'];
                $_SESSION['phone'] = $user['Sdt'];
                $_SESSION['admin'] = $user['admin'] ?? 0;
                
                // Xét điều kiện chuyển hướng dựa trên role
                $redirect = $user['admin'] == 1 ? 'admin-dashboard.php' : 'index.php';
                
                // Remove sensitive data from response
                unset($result['user']['matkhau']);
                $result['user_id'] = $user['Mauser'];
                $result['redirect'] = $redirect;
            }
            
            echo json_encode($result);
            
        } elseif ($action === 'logout') {
            // Logout user
            session_unset();
            session_destroy();
            echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
            
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Action parameter is required']);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get current user info
    
    if (isset($_GET['action']) && $_GET['action'] === 'profile') {
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }
        
        $result = $userController->getProfile($_SESSION['user_id']);
        echo json_encode($result);
        
    } elseif (isset($_GET['action']) && $_GET['action'] === 'check') {
        // Check if user is logged in
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => true,
                'logged_in' => true,
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'] ?? '',
                'email' => $_SESSION['email'] ?? '',
                'avatar' => $_SESSION['avatar'] ?? ''
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'logged_in' => false
            ]);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update user profile or password
    parse_str(file_get_contents("php://input"), $_PUT);
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }
    
    if (isset($_PUT['action']) && $_PUT['action'] === 'update_profile') {
        
        $profile_data = [
            'Tendangnhap' => sanitize_input($_PUT['username'] ?? ''),
            'Email' => sanitize_input($_PUT['email'] ?? ''),
            'Sdt' => sanitize_input($_PUT['phone'] ?? ''),
            'linkavatar' => sanitize_input($_PUT['avatar'] ?? '')
        ];
        
        $result = $userController->updateProfile($_SESSION['user_id'], $profile_data);
        
        if ($result['success']) {
            // Update session
            $_SESSION['username'] = $profile_data['Tendangnhap'];
            $_SESSION['email'] = $profile_data['Email'];
            $_SESSION['phone'] = $profile_data['Sdt'];
            $_SESSION['avatar'] = $profile_data['linkavatar'];
        }
        
        echo json_encode($result);
        
    } elseif (isset($_PUT['action']) && $_PUT['action'] === 'change_password') {
        
        $old_password = $_PUT['old_password'] ?? '';
        $new_password = $_PUT['new_password'] ?? '';
        $new_password_confirm = $_PUT['new_password_confirm'] ?? '';
        
        $result = $userController->changePassword($_SESSION['user_id'], $old_password, $new_password, $new_password_confirm);
        echo json_encode($result);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
