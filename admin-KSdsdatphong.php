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
    
    // Check admin access
    checkPageAccess(true);
    
    $searchQuery = '';
    
    // Get summary grouped by hotel
    $sql = "SELECT 
                h.MaKS,
                h.Ten,
                SUM(CASE WHEN r.room_type = 'Phòng Đơn' THEN COALESCE(b.room_quantity, 0) ELSE 0 END) as single_rooms,
                SUM(CASE WHEN r.room_type = 'Phòng Đôi' THEN COALESCE(b.room_quantity, 0) ELSE 0 END) as double_rooms,
                SUM(COALESCE(b.room_quantity, 0)) as total_booked,
                COUNT(DISTINCT b.user_id) as total_users
            FROM khachsan h
            LEFT JOIN bookings b ON h.MaKS = b.hotel_id AND b.status NOT IN ('Đã hủy')
            LEFT JOIN rooms r ON b.room_id = r.room_id
            GROUP BY h.MaKS, h.Ten
            HAVING COUNT(DISTINCT b.user_id) > 0
            ORDER BY h.Ten ASC";
    
    // Handle search
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_name'])) {
        $searchQuery = trim($_POST['search_name']);
        if (!empty($searchQuery)) {
            $sql = "SELECT 
                        h.MaKS,
                        h.Ten,
                        SUM(CASE WHEN r.room_type = 'Phòng Đơn' THEN COALESCE(b.room_quantity, 0) ELSE 0 END) as single_rooms,
                        SUM(CASE WHEN r.room_type = 'Phòng Đôi' THEN COALESCE(b.room_quantity, 0) ELSE 0 END) as double_rooms,
                        SUM(COALESCE(b.room_quantity, 0)) as total_booked,
                        COUNT(DISTINCT b.user_id) as total_users
                    FROM khachsan h
                    LEFT JOIN bookings b ON h.MaKS = b.hotel_id AND b.status NOT IN ('Đã hủy')
                    LEFT JOIN rooms r ON b.room_id = r.room_id
                    WHERE h.Ten = ?
                    GROUP BY h.MaKS, h.Ten
                    HAVING COUNT(DISTINCT b.user_id) > 0
                    ORDER BY h.Ten ASC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $searchQuery);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }
    } else {
        $result = $conn->query($sql);
    }
    
    $hotelSummary = [];
    while ($row = $result->fetch_assoc()) {
        $hotelSummary[] = $row;
    }
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
                    <h2>Đặt phòng</h2>
                    <div class="header-actions">
                     <div class = "search-box">
                        <form method="POST" class="search-form" style="display: flex; gap: 10px; flex: 1;">
                            <input type="text" placeholder="Tìm kiếm" class="search-input" name="search_name" value="<?= htmlspecialchars($searchQuery) ?>">
                            <button type="submit" class="fa-solid fa-magnifying-glass" ></button>
                        </form>
                        </div>
                    
                    </div>
                </div>

                <!-- Booking Table -->
                <div class="table-container">
                    <?php if (count($hotelSummary) == 0 && !empty($searchQuery)): ?>
                        <div style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
                            <p style="font-size: 16px; color: #333; margin: 0;">Khách sạn không được tìm thấy</p>
                        </div>
                    <?php endif; ?>
                    
                    <table class="room-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Số lượng khách</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hotelSummary as $key => $hotel): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td class="room-code"><?= htmlspecialchars($hotel['Ten']) ?></td>
                                    <td><?= (int)$hotel['total_users'] ?></td>
                                    <td>
                                        <span class="status-badge available">
                                            Còn Phòng
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                        <a href="admin-KSdatphong.php?id=<?= $hotel['MaKS'] ?>"><button class="menu-btn">☰</button></a>
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