<?php
require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['email']) || !isset($input['uid'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $email = sanitize_input($input['email']);
    $uid = sanitize_input($input['uid']);
    $displayName = sanitize_input($input['displayName'] ?? '');
    $photoURL = sanitize_input($input['photoURL'] ?? '');
    
    // Kiểm tra xem người dùng đã tồn tại hay chưa
    $checkUserSql = "SELECT Mauser, Tendangnhap, Email FROM nguoidung WHERE Email = ? OR google_uid = ?";
    $checkStmt = $conn->prepare($checkUserSql);
    
    if (!$checkStmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
    
    $checkStmt->bind_param("ss", $email, $uid);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        // Người dùng đã tồn tại, cập nhật google_uid nếu chưa có
        $user = $result->fetch_assoc();
        $userId = $user['Mauser'];
        
        $updateSql = "UPDATE nguoidung SET google_uid = ?, linkavatar = ? WHERE Mauser = ?";
        $updateStmt = $conn->prepare($updateSql);
        
        if (!$updateStmt) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
            exit;
        }
        
        $updateStmt->bind_param("ssi", $uid, $photoURL, $userId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // Tạo tài khoản mới từ Google
        $username = explode('@', $email)[0]; // Lấy phần trước @ làm username
        $username = sanitize_input($username . '_' . substr($uid, 0, 5)); // Thêm ID để đảm bảo unique
        
        // Kiểm tra xem username đã tồn tại chưa
        $checkUsernameSql = "SELECT Mauser FROM nguoidung WHERE Tendangnhap = ?";
        $checkUsernameStmt = $conn->prepare($checkUsernameSql);
        $checkUsernameStmt->bind_param("s", $username);
        $checkUsernameStmt->execute();
        $usernameResult = $checkUsernameStmt->get_result();
        
        $counter = 0;
        while ($usernameResult->num_rows > 0) {
            $counter++;
            $username = sanitize_input(explode('@', $email)[0] . '_' . $counter);
            $checkUsernameStmt = $conn->prepare($checkUsernameSql);
            $checkUsernameStmt->bind_param("s", $username);
            $checkUsernameStmt->execute();
            $usernameResult = $checkUsernameStmt->get_result();
        }
        $checkUsernameStmt->close();
        
        // Tạo password ngẫu nhiên (không dùng, vì sử dụng Google OAuth)
        $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        $role = 0; // User bình thường
        $createdAt = date('Y-m-d H:i:s');
        
        $insertSql = "INSERT INTO nguoidung (Tendangnhap, Email, Matkhau, linkavatar, google_uid, admin, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        
        if (!$insertStmt) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
            exit;
        }
        
        $insertStmt->bind_param("sssssss", $username, $email, $password, $photoURL, $uid, $role, $createdAt);
        
        if (!$insertStmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to create account: ' . $insertStmt->error]);
            exit;
        }
        
        $userId = $conn->insert_id;
        $insertStmt->close();
    }
    
    // Tạo session cho người dùng
    $_SESSION['user_id'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $displayName ?: $email;
    $_SESSION['avatar'] = $photoURL;
    $_SESSION['admin'] = 0;
    
    // Lấy thông tin đầy đủ
    $getUserSql = "SELECT * FROM nguoidung WHERE Mauser = ?";
    $getUserStmt = $conn->prepare($getUserSql);
    $getUserStmt->bind_param("i", $userId);
    $getUserStmt->execute();
    $userResult = $getUserStmt->get_result();
    $userData = $userResult->fetch_assoc();
    
    $_SESSION['phone'] = $userData['Sdt'] ?? '';
    
    echo json_encode([
        'success' => true,
        'message' => 'Đăng nhập thành công',
        'redirect' => 'index.php',
        'user' => [
            'id' => $userId,
            'email' => $email,
            'username' => $displayName ?: $email
        ]
    ]);
    
    $checkStmt->close();
    $getUserStmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
