<?php
// Cấu hình kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lavalle_db";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Thiết lập charset UTF-8
$conn->set_charset("utf8");

// Bắt đầu session
session_start();

// Hàm kiểm tra và lấy thông tin người dùng
function getUserInfo($conn) {
    if (isset($_SESSION['user_id'])) {
        $user_sql = "SELECT * FROM nguoidung WHERE Mauser = ?";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("i", $_SESSION['user_id']);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        
        if ($user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
            $_SESSION['avatar'] = $user_data['linkavatar'];
            $_SESSION['username'] = $user_data['Tendangnhap'];
            $_SESSION['email'] = $user_data['Email'];
            $_SESSION['phone'] = $user_data['Sdt'];
            return $user_data;
        }
        $user_stmt->close();
    }
    return null;
}

// Hàm tính xếp hạng từ điểm đánh giá ----------------------------------------SỬA LẠI--------------------------------------------------
function getRankingFromScore($score) {
    if ($score >= 4.5) return 'xuất sắc';
    elseif ($score >= 4.0) return 'tốt';
    elseif ($score >= 2.5) return 'bình thường';
    elseif ($score >= 1.5) return 'kém';
    else return 'rất tệ';
}
// Lấy thông tin người dùng nếu đã đăng nhập
$user_info = getUserInfo($conn);

// Hàm kiểm tra đăng nhập
function requireLogin() { 
    if (!isset($_SESSION['user_id'])) { // Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng đến trang đăng nhập
        header('Location: login.php');
        exit;
    }
}
/*
// Hàm tính toán rating trung bình-------------------------------------HÀM CŨ---------------------------------
function updateHotelRating($conn, $maKS) {
    $sql = "SELECT AVG(diemreview) as avg_rating, COUNT(*) as total_reviews FROM review WHERE MaKS = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maKS);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $avg_rating = round($row['avg_rating'], 1);
    
    // Cập nhật xem hạng dựa trên điểm
    $xemhang = 'bình thường';
    if ($avg_rating >= 4.5) $xemhang = 'xuất sắc';
    elseif ($avg_rating >= 4.0) $xemhang = 'tốt';
    elseif ($avg_rating >= 2.5) $xemhang = 'bình thường';
    elseif ($avg_rating >= 1.5) $xemhang = 'kém';
    else $xemhang = 'rất tệ';
    
    $update_sql = "UPDATE khachsan SET diemdg = ?, xemhang = ? WHERE MaKS = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dsi", $avg_rating, $xemhang, $maKS);
    $update_stmt->execute();
    $update_stmt->close();
    $stmt->close();
}
*/
// Hàm tính toán rating trung bình--------------------------------------HÀM MỚI---------------------------------
function updateHotelRating($conn, $maKS) {
    $sql = "SELECT AVG(diemreview) as avg_rating, COUNT(*) as total_reviews FROM review WHERE MaKS = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maKS);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $avg_rating = round($row['avg_rating'], 1);
    
    // Chỉ cập nhật điểm đánh giá, không cập nhật xemhang nữa và k cần phải tính xephang trước khi update nữa
    $update_sql = "UPDATE khachsan SET diemdg = ? WHERE MaKS = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("di", $avg_rating, $maKS);
    $update_stmt->execute();
    $update_stmt->close();
    $stmt->close();
}
//cyber 
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Hàm tạo thông báo session
function setMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
}

// Hàm lấy và xóa thông báo
function getMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return null;
}

// Hàm kiểm tra quyền truy cập trang
function checkPageAccess($required_login = false) {
    if ($required_login && !isset($_SESSION['user_id'])) {
        setMessage('error', 'Vui lòng đăng nhập để truy cập trang này');
        header('Location: login.php');
        exit;
    }
}
?>