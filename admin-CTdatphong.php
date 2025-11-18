<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Chi Tiết Đặt Phòng</title>
    <link rel="stylesheet" href="CSS/style4.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php
    require_once 'config/config.php';
    require_once 'controllers/BookingController.php';
    require_once 'models/User.php';
    
    // Check admin access
    checkPageAccess(true);
    
    $bookingId = intval($_GET['id'] ?? 0);
    if ($bookingId <= 0) {
        header('Location: admin-KSdsdatphong.php');
        exit;
    }
    
    // Get booking details with all related info
    $booking_sql = "SELECT b.*, 
                           u.Tendangnhap, u.Email, u.Sdt, 
                           h.Ten as hotel_name, 
                           r.room_type 
                    FROM bookings b 
                    JOIN nguoidung u ON b.user_id = u.Mauser 
                    JOIN khachsan h ON b.hotel_id = h.MaKS 
                    JOIN rooms r ON b.room_id = r.room_id 
                    WHERE b.booking_id = ?";
    $stmt = $conn->prepare($booking_sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header('Location: admin-KSdsdatphong.php');
        exit;
    }
    
    $booking = $result->fetch_assoc();
    $stmt->close();
    
    // Calculate duration
    $check_in = new DateTime($booking['check_in_date']);
    $check_out = new DateTime($booking['check_out_date']);
    $duration = $check_in->diff($check_out)->days;
    
    // Map Vietnamese status to display
    $status_display_map = [
        'Chưa xác nhận' => 'Đang chờ',
        'Đã xác nhận' => 'Đã xác nhận',
        'Đã hoàn thành' => 'Hoàn thành',
        'Đã hủy' => 'Đã hủy'
    ];
    
    $status_display = $status_display_map[$booking['status']] ?? $booking['status'];
    ?>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <a href="index.php"><img src="img/logo.jpg" alt="Lavaliee Hotels"></a>
            </div>
            <nav class="menu">
                <a href="admin-dashboard.php" class="menu-item">
                    <i class="fa-solid fa-hotel" style="margin: 10px"></i> Khách sạn
                </a>
                <a href="admin-KSdsdatphong.php" class="menu-item active">
                     <i class="fa-solid fa-calendar-days" style="margin: 10px"></i> Đặt phòng
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>QUẢN TRỊ</h1>
            </header>

            <div class="content-wrapper">
                <div class="page-header">
                    <h2>
                        <a href="admin-KSdsdatphong.php" class="breadcrumb">Đặt phòng</a>
                        <span class="separator">›</span>
                        <a href="admin-KSdatphong.php?id=<?= $booking['hotel_id'] ?>" class="breadcrumb"><?= htmlspecialchars($booking['hotel_name']) ?></a>
                        <span class="separator">›</span>
                        #<?= $booking['booking_id'] ?>
                    </h2>
                </div>

                <!-- Booking Detail Form -->
                <form class="hotel-form booking-detail-form" id="bookingForm">
                    <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tên khách hàng:</label>
                            <input type="text" name="customer_name" value="<?= htmlspecialchars($booking['Tendangnhap']) ?>" class="form-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ email:</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($booking['Email']) ?>" class="form-input" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Số điện thoại:</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($booking['Sdt']) ?>" class="form-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái:</label>
                            <select name="status" class="form-input">
                                <option value="Chưa xác nhận" <?= $booking['status'] == 'Chưa xác nhận' ? 'selected' : '' ?>>Đang chờ</option>
                                <option value="Đã xác nhận" <?= $booking['status'] == 'Đã xác nhận' ? 'selected' : '' ?>>Đã xác nhận</option>
                                <option value="Đã hoàn thành" <?= $booking['status'] == 'Đã hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
                                <option value="Đã hủy" <?= $booking['status'] == 'Đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row booking-duration">
                        <div class="form-group">
                            <label>Từ ngày:</label>
                            <input type="date" name="checkin" value="<?= $booking['check_in_date'] ?>" class="form-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>đến ngày:</label>
                            <input type="date" name="checkout" value="<?= $booking['check_out_date'] ?>" class="form-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Số khách:</label>
                            <input type="number" name="guests" value="<?= $booking['num_guests'] ?>" class="form-input" readonly>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label>Loại phòng:</label>
                        <input type="text" name="room_type" value="<?= htmlspecialchars($booking['room_type']) ?>" class="form-input" readonly>
                    </div>

                    <div class="form-group full-width">
                        <label>Ghi chú:</label>
                        <textarea name="notes" class="form-textarea" rows="3"><?= htmlspecialchars($booking['notes'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Tổng giá:</label>
                        <input type="number" name="total_price" value="<?= $booking['total_price'] ?>" class="form-input" readonly>
                    </div>

                    <div class="form-group full-width">
                        <label>Chi tiết phòng đặt:</label>
                        <div class="room-booking-table">
                            <table class="booking-rooms-table">
                                <thead>
                                    <tr>
                                        <th>Loại Phòng</th>
                                        <th>Số lượng phòng</th>
                                        <th>Đơn giá/đêm</th>
                                        <th>Số đêm</th>
                                        <th>Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= htmlspecialchars($booking['room_type']) ?></td>
                                        <td><?= $booking['room_quantity'] ?? 1 ?></td>
                                        <td><?= number_format($booking['total_price'] / ($duration > 0 ? $duration : 1), 0) ?> VNĐ</td>
                                        <td><?= $duration ?> đêm</td>
                                        <td><?= number_format($booking['total_price'], 0) ?> VNĐ</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="admin-KSdsdatphong.php"><button type="button" class="btn-cancel">Quay lại</button></a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const bookingId = document.querySelector('input[name="booking_id"]').value;
            const statusValue = document.querySelector('select[name="status"]').value;
            const notesValue = document.querySelector('textarea[name="notes"]').value;
            
            // Map Vietnamese status to English for API
            const statusMap = {
                'Chưa xác nhận': 'pending',
                'Đã xác nhận': 'confirmed',
                'Đã hoàn thành': 'completed',
                'Đã hủy': 'cancelled'
            };
            
            const formData = {
                booking_id: bookingId,
                status: statusMap[statusValue] || statusValue,
                notes: notesValue
            };
            
            console.log('Form data:', formData);
            
            fetch('api/bookings.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    if (!text || text.trim() === '') {
                        throw new Error('Empty response from server');
                    }
                    return JSON.parse(text);
                });
            })
            .then(data => {
                console.log('Parsed data:', data);
                if (data.success) {
                    alert('Cập nhật thành công');
                    window.location.href = 'admin-KSdsdatphong.php';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra: ' + error.message);
            });
        });
    </script>
</body>
</html>