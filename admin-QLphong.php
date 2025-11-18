<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Quản Lý Phòng</title>
    <link rel="stylesheet" href="CSS/style4.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php
    require_once 'config/config.php';
    require_once 'controllers/RoomController.php';
    
    // Check admin access
    checkPageAccess(true);
    
    // Get hotel ID from URL
    $hotelId = intval($_GET['id'] ?? 0);
    if ($hotelId <= 0) {
        header('Location: admin-dashboard.php');
        exit;
    }
    
    // Get hotel info
    $hotel_sql = "SELECT Ten FROM khachsan WHERE MaKS = ?";
    $stmt = $conn->prepare($hotel_sql);
    $stmt->bind_param("i", $hotelId);
    $stmt->execute();
    $hotel_result = $stmt->get_result();
    if ($hotel_result->num_rows == 0) {
        header('Location: admin-dashboard.php');
        exit;
    }
    $hotel = $hotel_result->fetch_assoc();
    $stmt->close();
    
    $roomController = new RoomController($conn);
    $searchQuery = '';
    
    // Get rooms for this hotel
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_room'])) {
        $searchQuery = trim($_POST['search_room']);
        if (!empty($searchQuery)) {
            $rooms = $roomController->searchByRoomNumber($hotelId, $searchQuery);
        } else {
            $rooms = $roomController->getByHotel($hotelId);
        }
    } else {
        $rooms = $roomController->getByHotel($hotelId);
    }
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
                <h1>QUẢN TRỊ</h1>
            </header>

            <div class="content-wrapper">
                <div class="page-header">
                    <h2>
                        <a href="admin-dashboard.php" class="breadcrumb">Khách sạn</a>
                        <span class="separator">›</span>
                        <a href="admin-CTkhachsan.php?id=<?= $hotelId ?>" class="breadcrumb"><?= htmlspecialchars(string: $hotel['Ten']) ?></a>
                        <span class="separator">›</span>
                        Quản lý phòng
                    </h2>
                    <div class="header-actions">
                        <div class = 'search-box'>
                        <form method="POST" class="search-form" style="display: flex; gap: 10px; flex: 1;">
                            <input type="text" placeholder="Tìm kiếm theo tên phòng" class="search-input" name="search_room" value="<?= htmlspecialchars($searchQuery) ?>">
                            <button type="submit" class="fa-solid fa-magnifying-glass" style="background: none; border: none; font-size: 18px; cursor: pointer;"></button>
                        </form>
                        </div>
                        <a href="admin-themphong.php?id=<?= $hotelId ?>"><button class="btn-add">Thêm phòng</button></a>
                    </div>
                </div>

                <!-- Room Table -->
                <div class="table-container">
                    <?php if (count($rooms) == 0 && !empty($searchQuery)): ?>
                        <div style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
                            <p style="font-size: 16px; color: #333; margin: 0;">Phòng không được tìm thấy</p>
                        </div>
                    <?php endif; ?>
                    
                    <table class="room-table">
                        <thead>
                            <tr>
                                <th>Tên phòng</th>
                                <th>Loại Phòng</th>
                                <th>Sức chứa</th>
                                <th>Giá/đêm</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td class="room-code"><?= htmlspecialchars($room['room_number']) ?></td>
                                    <td><?= htmlspecialchars($room['room_type']) ?></td>
                                    <td><?= $room['capacity'] ?> người</td>
                                    <td><?= number_format($room['price_per_night'], 0) ?> VNĐ</td>
                                    <td>
                                        <span class="status-badge <?= $room['status'] == 'Có sẵn' ? 'available' : 'unavailable' ?>">
                                            <?= htmlspecialchars($room['status']) ?>
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                        <a href="admin-CTphong.php?id=<?= $room['room_id'] ?>"><button class="menu-btn">☰</button></a>
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