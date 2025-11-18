<?php
/**
 * Google Login Helper Functions
 * 
 * Sử dụng: require_once 'helpers/google-login-helper.php';
 * 
 * Functions:
 * - isGoogleUser()
 * - getGoogleUserInfo()
 * - logoutGoogleUser()
 * - updateGoogleUserAvatar()
 */

/**
 * Kiểm tra người dùng đã đăng nhập qua Google
 * 
 * @return bool
 */
function isGoogleUser() {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    global $conn;
    $user_id = $_SESSION['user_id'];
    
    $sql = "SELECT google_uid FROM nguoidung WHERE Mauser = ? AND google_uid IS NOT NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    return $result->num_rows > 0;
}

/**
 * Lấy thông tin người dùng Google
 * 
 * @return array|null
 */
function getGoogleUserInfo() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    global $conn;
    $user_id = $_SESSION['user_id'];
    
    $sql = "SELECT Mauser, Tendangnhap, Email, linkavatar, google_uid FROM nguoidung WHERE Mauser = ? AND google_uid IS NOT NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    return $user;
}

/**
 * Đăng xuất người dùng (kể cả Google session)
 * 
 * @param bool $firebase_logout - Có gửi logout request tới Firebase không
 * @return void
 */
function logoutGoogleUser($firebase_logout = false) {
    // Xóa session
    $_SESSION = [];
    
    // Xóa session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Xóa remember cookie
    if (isset($_COOKIE['remember_user'])) {
        setcookie('remember_user', '', time() - 42000, '/');
    }
    
    // Destroy session
    session_destroy();
    
    // Nếu cần logout từ Firebase (nếu frontend send request)
    if ($firebase_logout) {
        // Firebase logout sẽ được xử lý từ frontend (JavaScript)
        // Backend chỉ cần xóa PHP session
    }
}

/**
 * Cập nhật avatar từ Google
 * 
 * @param int $user_id - User ID
 * @param string $photoURL - URL ảnh từ Google
 * @return bool
 */
function updateGoogleUserAvatar($user_id, $photoURL) {
    global $conn;
    
    $photoURL = sanitize_input($photoURL);
    
    $sql = "UPDATE nguoidung SET linkavatar = ? WHERE Mauser = ? AND google_uid IS NOT NULL";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("si", $photoURL, $user_id);
    $result = $stmt->execute();
    $stmt->close();
    
    if ($result) {
        $_SESSION['avatar'] = $photoURL;
    }
    
    return $result;
}

/**
 * Link Google account dengan tài khoản hiện tại
 * 
 * Nếu user đã đăng nhập bằng password, có thể link Google account
 * 
 * @param int $user_id - User ID
 * @param string $google_uid - Firebase UID
 * @return bool
 */
function linkGoogleAccount($user_id, $google_uid) {
    global $conn;
    
    $google_uid = sanitize_input($google_uid);
    
    // Kiểm tra google_uid chưa được sử dụng
    $checkSql = "SELECT Mauser FROM nguoidung WHERE google_uid = ? AND Mauser != ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("si", $google_uid, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $checkStmt->close();
    
    if ($checkResult->num_rows > 0) {
        return false; // Google UID đã được sử dụng bởi user khác
    }
    
    // Update google_uid
    $updateSql = "UPDATE nguoidung SET google_uid = ? WHERE Mauser = ?";
    $updateStmt = $conn->prepare($updateSql);
    
    if (!$updateStmt) {
        return false;
    }
    
    $updateStmt->bind_param("si", $google_uid, $user_id);
    $result = $updateStmt->execute();
    $updateStmt->close();
    
    return $result;
}

/**
 * Unlink Google account
 * 
 * @param int $user_id - User ID
 * @return bool
 */
function unlinkGoogleAccount($user_id) {
    global $conn;
    
    // Kiểm tra user có password
    $checkSql = "SELECT matkhau FROM nguoidung WHERE Mauser = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $user_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $user = $result->fetch_assoc();
    $checkStmt->close();
    
    if (!$user || empty($user['matkhau'])) {
        return false; // Không thể unlink nếu không có password
    }
    
    // Unlink
    $updateSql = "UPDATE nguoidung SET google_uid = NULL WHERE Mauser = ?";
    $updateStmt = $conn->prepare($updateSql);
    
    if (!$updateStmt) {
        return false;
    }
    
    $updateStmt->bind_param("i", $user_id);
    $result = $updateStmt->execute();
    $updateStmt->close();
    
    return $result;
}

/**
 * Lấy tất cả OAuth providers của user
 * 
 * @param int $user_id - User ID
 * @return array
 */
function getUserOAuthProviders($user_id) {
    global $conn;
    
    $providers = [];
    
    $sql = "SELECT matkhau, google_uid FROM nguoidung WHERE Mauser = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        if (!empty($user['matkhau'])) {
            $providers[] = 'password';
        }
        if (!empty($user['google_uid'])) {
            $providers[] = 'google';
        }
    }
    
    return $providers;
}

/**
 * Check người dùng có thể unlink provider
 * 
 * User chỉ có thể unlink nếu còn ít nhất 1 provider khác
 * 
 * @param int $user_id - User ID
 * @param string $provider - 'google' hoặc 'password'
 * @return bool
 */
function canUnlinkProvider($user_id, $provider) {
    $providers = getUserOAuthProviders($user_id);
    
    if (count($providers) <= 1) {
        return false; // Không thể unlink nếu chỉ còn 1 provider
    }
    
    if (!in_array($provider, $providers)) {
        return false; // Provider này không được linked
    }
    
    return true;
}

?>
