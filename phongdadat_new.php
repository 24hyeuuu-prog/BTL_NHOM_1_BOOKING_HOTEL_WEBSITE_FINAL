<?php 
require_once 'config/config.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get all pending and confirmed bookings for this user
$booking_sql = "SELECT b.*, h.Ten as hotel_name, r.room_type, r.room_number, u.Tendangnhap 
                FROM bookings b 
                LEFT JOIN khachsan h ON b.hotel_id = h.MaKS 
                LEFT JOIN rooms r ON b.room_id = r.room_id 
                LEFT JOIN nguoidung u ON b.user_id = u.Mauser 
                WHERE b.user_id = ? AND b.status IN ('Chưa xác nhận', 'Đã xác nhận')
                ORDER BY b.check_in_date DESC";

$stmt = $conn->prepare($booking_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = [];
while ($row = $result->fetch_assoc()) {
    // If room_numbers is set, use it; otherwise, fall back to single room_number
    if (!empty($row['room_numbers'])) {
        // Parse room_numbers CSV string
        $room_numbers_array = array_map('trim', explode(',', $row['room_numbers']));
        $row['room_numbers_list'] = $room_numbers_array;
        $row['display_rooms'] = implode(', ', $room_numbers_array);
    } else {
        // Fallback to single room number from JOIN - check if it exists first
        if (!empty($row['room_number'])) {
            $row['display_rooms'] = $row['room_number'];
            $row['room_numbers_list'] = [$row['room_number']];
        } else {
            $row['display_rooms'] = 'N/A';
            $row['room_numbers_list'] = [];
        }
    }
    
    $bookings[] = $row;
}
$stmt->close();

// Get user info
$user_sql = "SELECT * FROM nguoidung WHERE Mauser = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng đã đặt - LaValle</title>
    <link rel="stylesheet" href="CSS/phongdadat2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .bookings-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .bookings-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .bookings-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .bookings-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .booking-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .booking-card-header {
            background: linear-gradient(135deg, #479cd4ff 0%, #2694e8ff 100%);
            color: white;
            padding: 20px;
        }

        .booking-card-header h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .booking-card-body {
            padding: 20px;
        }

        .booking-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .booking-info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .booking-info-label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
        }

        .booking-info-value {
            color: #333;
            font-size: 14px;
        }

        .booking-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .status-pending {
            background-color: #ffc107;
            color: #333;
        }

        .status-confirmed {
            background-color: #28a745;
            color: white;
        }

        .status-completed {
            background-color: #17a2b8;
            color: white;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }

        .booking-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .booking-actions .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-view {
            background-color: #007bff;
            color: white;
        }

        .btn-view:hover {
            background-color: #0056b3;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c82333;
        }

        .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-message i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }

        .empty-message h2 {
            color: #666;
            margin-bottom: 10px;
        }

        .empty-message p {
            margin-bottom: 20px;
        }

        .empty-message a {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .empty-message a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>

    <div class="notification-container" id="notificationContainer"></div>

    <div class="bookings-container">
        <div class="bookings-header">
            <h1>Phòng đã đặt</h1>
            <p>Quản lý các đặt phòng của bạn</p>
        </div>

        <?php if (count($bookings) > 0): ?>
            <div class="bookings-list">
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card">
                        <div class="booking-card-header">
                            <h3><?php echo htmlspecialchars($booking['hotel_name']); ?></h3>
                            <span class="booking-status status-<?php echo strtolower($booking['status']); ?>">
                                <?php echo $booking['status']; ?>
                            </span>
                        </div>

                        <div class="booking-card-body">
                            <div class="booking-info-row">
                                <span class="booking-info-label">Loại phòng</span>
                                <span class="booking-info-value"><?php echo htmlspecialchars($booking['room_type'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="booking-info-row">
                                <span class="booking-info-label">Số phòng</span>
                                <span class="booking-info-value">
                                    <?php 
                                    if (!empty($booking['room_numbers'])) {
                                        // Multiple rooms
                                        echo '<div style="word-wrap: break-word;">';
                                        foreach ($booking['room_numbers_list'] as $room) {
                                            echo '<span style="display: inline-block; background: #e9ecef; padding: 4px 8px; margin: 2px; border-radius: 3px;">' . htmlspecialchars($room) . '</span>';
                                        }
                                        echo '</div>';
                                    } else {
                                        // Single room fallback
                                        echo htmlspecialchars($booking['display_rooms']);
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="booking-info-row">
                                <span class="booking-info-label">Số lượng phòng</span>
                                <span class="booking-info-value"><?php echo $booking['room_quantity'] ?? 1; ?> phòng</span>
                            </div>

                            <div class="booking-info-row">
                                <span class="booking-info-label">Nhận phòng</span>
                                <span class="booking-info-value"><?php echo date('d/m/Y', strtotime($booking['check_in_date'])); ?></span>
                            </div>

                            <div class="booking-info-row">
                                <span class="booking-info-label">Trả phòng</span>
                                <span class="booking-info-value"><?php echo date('d/m/Y', strtotime($booking['check_out_date'])); ?></span>
                            </div>

                            <div class="booking-info-row">
                                <span class="booking-info-label">Số khách</span>
                                <span class="booking-info-value"><?php echo $booking['num_guests']; ?> người</span>
                            </div>

                            <div class="booking-info-row">
                                <span class="booking-info-label">Tổng giá</span>
                                <span class="booking-info-value"><?php echo number_format($booking['total_price'], 0, ',', '.'); ?> VNĐ</span>
                            </div>

                            <div class="booking-actions">
                                <a href="chitietkhachsan.php?id=<?php echo $booking['hotel_id']; ?>" class="btn btn-view">
                                    Xem khách sạn
                                </a>
                                <?php if ($booking['status'] !== 'Đã hủy'): ?>
                                    <button class="btn btn-cancel" onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)">
                                        Hủy
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-message">
                <i class="fas fa-inbox"></i>
                <h2>Chưa có đặt phòng</h2>
                <p>Bạn chưa đặt phòng nào. Hãy bắt đầu tìm kiếm khách sạn ngay!</p>
                <a href="khachsan2.php">Tìm khách sạn</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function cancelBooking(bookingId) {
            if (confirm('Bạn chắc chắn muốn hủy đặt phòng này?')) {
                fetch('api/bookings.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: bookingId,
                        action: 'cancel'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Hủy đặt phòng thành công!');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('error', data.message || 'Lỗi khi hủy đặt phòng');
                    }
                })
                .catch(error => {
                    showNotification('error', 'Lỗi: ' + error.message);
                });
            }
        }

        function showNotification(type, message) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <span>${message}</span>
                <button class="close-btn" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
</body>
</html>
