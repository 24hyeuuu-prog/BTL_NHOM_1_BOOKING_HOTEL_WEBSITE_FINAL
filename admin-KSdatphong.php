<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Đặt Phòng</title>
    <link rel="stylesheet" href="CSS/style4.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php
    require_once 'config/config.php';
    require_once 'controllers/BookingController.php';
    
    // Check admin access
    checkPageAccess(true);
    
    // Get hotel ID from URL
    $hotelId = intval($_GET['id'] ?? 0);
    if ($hotelId <= 0) {
        header('Location: admin-KSdsdatphong.php');
        exit;
    }
    
    // Get hotel info
    $hotel_sql = "SELECT MaKS, Ten FROM khachsan WHERE MaKS = ?";
    $stmt = $conn->prepare($hotel_sql);
    $stmt->bind_param("i", $hotelId);
    $stmt->execute();
    $hotel_result = $stmt->get_result();
    
    if ($hotel_result->num_rows == 0) {
        header('Location: admin-KSdsdatphong.php');
        exit;
    }
    
    $hotel = $hotel_result->fetch_assoc();
    $stmt->close();
    
    $searchQuery = '';
    
    // Get bookings for this hotel
    $bookings_sql = "SELECT b.*, u.Tendangnhap, h.Ten as hotel_name, r.room_type 
                     FROM bookings b 
                     JOIN nguoidung u ON b.user_id = u.Mauser 
                     JOIN khachsan h ON b.hotel_id = h.MaKS 
                     JOIN rooms r ON b.room_id = r.room_id 
                     WHERE b.hotel_id = ?
                     ORDER BY b.check_in_date DESC";
    
    // Handle search by customer username
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_customer'])) {
        $searchQuery = trim($_POST['search_customer']);
        if (!empty($searchQuery)) {
            $bookings_sql = "SELECT b.*, u.Tendangnhap, h.Ten as hotel_name, r.room_type 
                             FROM bookings b 
                             JOIN nguoidung u ON b.user_id = u.Mauser 
                             JOIN khachsan h ON b.hotel_id = h.MaKS 
                             JOIN rooms r ON b.room_id = r.room_id 
                             WHERE b.hotel_id = ? AND u.Tendangnhap = ?
                             ORDER BY b.check_in_date DESC";
            
            $stmt = $conn->prepare($bookings_sql);
            $stmt->bind_param("is", $hotelId, $searchQuery);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare($bookings_sql);
            $stmt->bind_param("i", $hotelId);
            $stmt->execute();
        }
    } else {
        $stmt = $conn->prepare($bookings_sql);
        $stmt->bind_param("i", $hotelId);
        $stmt->execute();
    }
    
    $bookings_result = $stmt->get_result();
    $bookings = [];
    while ($row = $bookings_result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $stmt->close();
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
                        <?= htmlspecialchars($hotel['Ten']) ?>
                    </h2>
                    <div class="header-actions">
                        <div class = 'search-box'>
                        <form method="POST" class="search-form" style="display: flex; gap: 10px; flex: 1;">
                            <input type="text" placeholder="Tìm kiếm theo tên khách hàng" class="search-input" name="search_customer" value="<?= htmlspecialchars($searchQuery) ?>">
                            <button type="submit" class="fa-solid fa-magnifying-glass" style="background: none; border: none; font-size: 18px; cursor: pointer;"></button>
                        </form>
                        </div>
    
                    </div>
                </div>

                <!-- Booking Orders Table -->
                <div class="table-container">
                    <?php if (count($bookings) == 0 && !empty($searchQuery)): ?>
                        <div style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
                            <p style="font-size: 16px; color: #333; margin: 0;">Khách hàng không được tìm thấy</p>
                        </div>
                    <?php endif; ?>
                    
                    <table class="room-table">
                        <thead>
                            <tr>
                                <th>Mã phòng</th>
                                <th>Khách</th>
                                <th>Ngày tới</th>
                                <th>Ngày rời</th>
                                <th>Loại phòng</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td class="room-code">#<?= $booking['booking_id'] ?></td>
                                    <td><?= htmlspecialchars($booking['Tendangnhap']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($booking['check_in_date'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($booking['check_out_date'])) ?></td>
                                    <td><?= htmlspecialchars($booking['room_type']) ?></td>
                                    <td><?= number_format($booking['total_price'], 0) ?> VNĐ</td>
                                    <td>
                                        <?php 
                                        $status_class = strtolower(str_replace(' ', '-', $booking['status']));
                                        $status_display = str_replace(['Chưa xác nhận', 'Đã xác nhận', 'Đã hoàn thành', 'Đã hủy'], 
                                                                     ['Đang chờ', 'Đã xác nhận', 'Hoàn thành', 'Đã hủy'], 
                                                                     $booking['status']);
                                        ?>
                                        <span class="status-badge <?= $status_class ?>">
                                            <?= $status_display ?>
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                       <a href="admin-CTdatphong.php?id=<?= $booking['booking_id'] ?>"><button class="menu-btn">☰</button></a> 
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>