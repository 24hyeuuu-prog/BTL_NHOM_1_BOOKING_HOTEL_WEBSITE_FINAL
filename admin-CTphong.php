<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Chi Tiết Phòng</title>
    <link rel="stylesheet" href="CSS/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php
    require_once 'config/config.php';
    require_once 'controllers/RoomController.php';
    
    // Check admin access
    checkPageAccess(true);
    
    $roomId = intval($_GET['id'] ?? 0);
    if ($roomId <= 0) {
        header('Location: admin-QLphong.php');
        exit;
    }
    
    // Get room details
    $room_sql = "SELECT r.*, h.Ten as hotel_name FROM rooms r 
                 LEFT JOIN khachsan h ON r.hotel_id = h.MaKS 
                 WHERE r.room_id = ?";
    $stmt = $conn->prepare($room_sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header('Location: admin-QLphong.php');
        exit;
    }
    
    $room = $result->fetch_assoc();
    $stmt->close();
    
    // Get booking count for this room
    $booking_sql = "SELECT COUNT(*) as booking_count FROM bookings 
                   WHERE room_id = ? AND status IN ('Đã xác nhận', 'Chưa xác nhận')";
    $stmt = $conn->prepare($booking_sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $booking_result = $stmt->get_result();
    $booking_data = $booking_result->fetch_assoc();
    $stmt->close();
    ?>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <a href="index.php"><img src="img/logo.jpg" alt="Lavaliee Hotels"></a>
            </div>
            <nav class="menu">
                <a href="admin-dashboard.php" class="menu-item active">
                    <i class="fa-solid fa-hotel" style="margin: 10px"></i> Khách sạn
                </a>
                <a href="admin-KSdsdatphong.php" class="menu-item">
                    <i class="fa-solid fa-calendar-days" style="margin: 10px"></i> Đặt phòng
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>CHI TIẾT PHÒNG</h1>
            </header>

            <div class="content-wrapper">
                <div class="page-header">
                    <h2>
                        <a href="admin-dashboard.php" class="breadcrumb">Khách sạn</a>
                        <span class="separator">›</span>
                        <a href="admin-CTkhachsan.php?id=<?php echo $room['hotel_id']; ?>" class="breadcrumb"><?php echo htmlspecialchars($room['hotel_name']); ?></a>
                        <span class="separator">›</span>
                        <a href="admin-QLphong.php?id=<?php echo $room['hotel_id']; ?>" class="breadcrumb">Quản lý phòng</a>
                        <span class="separator">›</span>
                        Phòng <?php echo htmlspecialchars($room['room_number']); ?>
                    </h2>
                </div>

                <!-- Room Details Card -->
                <div class="room-detail-card">
                    <div class="detail-header">
                        <center><H4 class="form-header"> THÔNG TIN CHI TIẾT</H4></center>
                    </div>

            


                        <div class="form-row">
                            <div class="form-group">
                                <label>Loại phòng:</label>
                                <input type="text" class="form-input" value="<?php echo htmlspecialchars($room['room_type']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Tên phòng:</label>
                                <input type="text" class="form-input" value="<?php echo htmlspecialchars($room['room_number']); ?>" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Giá/đêm:</label>
                                <input type="text" class="form-input" value="<?php echo number_format($room['price_per_night'], 0, ',', '.'); ?> VNĐ" disabled>
                            </div>
                            <div class="form-group">
                                <label>Trạng thái:</label>
                                <input type="text" class="form-input" value="<?php echo htmlspecialchars($room['status']); ?>" disabled>
                            </div>
                        </div>


                        <div class="form-group full-width">
                            <label>Tiện nghi:</label>
                            <div class="form-textarea" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; min-height: 75px; white-space: pre-wrap; word-wrap: break-word;" id="amenitiesDisplay"></div>
                        </div>

                        <div class="form-group full-width">
                            <label>Mô tả:</label>
                            <div class="form-textarea" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; min-height: 100px; white-space: pre-wrap; word-wrap: break-word;" id="descriptionDisplay"></div>
                        </div>


               

                    <div class="form-actions">
                        <a href="admin-QLphong.php?id=<?php echo $room['hotel_id']; ?>"><button type="button" class="btn-cancel">Quay lại</button></a>
                        <a href="admin-suattphong.php?id=<?php echo $room['room_id']; ?>"><button type="button" class="btn-save">Chỉnh sửa</button></a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <script>
        // Hiển thị HTML content đúng cách
        document.getElementById('amenitiesDisplay').innerHTML = <?php echo json_encode(!empty($room['amenities']) ? $room['amenities'] : '<p>Không có thông tin</p>'); ?>;
        document.getElementById('descriptionDisplay').innerHTML = <?php echo json_encode(!empty($room['description']) ? $room['description'] : '<p>Không có mô tả</p>'); ?>;
    </script>

    <script>
        function deleteRoom(roomId) {
            if (confirm('Bạn chắc chắn muốn xóa phòng này?')) {
                fetch('api/rooms.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ room_id: roomId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Xóa phòng thành công!');
                        setTimeout(() => {
                            window.location.href = 'admin-QLphong.php';
                        }, 1500);
                    } else {
                        showNotification('error', data.message || 'Lỗi khi xóa phòng');
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