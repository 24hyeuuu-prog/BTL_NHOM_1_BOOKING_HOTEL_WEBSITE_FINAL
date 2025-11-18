<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Khách Sạn</title>
    <link rel="stylesheet" href="CSS/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>
<body>
    <?php
    require_once 'config/config.php';
    require_once 'controllers/HotelController.php';
    
    // Check admin access
    checkPageAccess(true);
    
    $hotelController = new HotelController($conn);
    $hotels = $hotelController->getAll();
    
    // Handle search
    $searchQuery = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_name'])) {
        $searchQuery = trim($_POST['search_name']);
        if (!empty($searchQuery)) {
            $hotels = $hotelController->searchByName($searchQuery);
        }
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
                    <h2>Khách sạn</h2>
                    <div class="header-actions">
                     <div class = "search-box">
                        <form method="POST" class="search-form" style="display: flex; gap: 10px; flex: 1;">
                            <input type="text" placeholder="Tìm kiếm" class="search-input" name="search_name" value="<?= htmlspecialchars($searchQuery) ?>">
                            <button type="submit" class="fa-solid fa-magnifying-glass" ></button>
                        </form>
                        </div>
                        
                        <a href="admin-themkhachsan.php"><button class="btn-add">Thêm mới</button></a>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?= count($hotels) ?></div>
                        <div class="stat-label">Số lượng khách sạn</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= count($hotels) > 0 ? number_format(array_sum(array_map(function($h) { return $h['diemdg']; }, $hotels)) / count($hotels), 1) : 0 ?>/5.0</div>
                        <div class="stat-label">Trung bình chất lượng</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= count($hotels) > 0 ? number_format(array_sum(array_map(function($h) { return $h['price'] ?? 0; }, $hotels)) / count($hotels), 0) : 0 ?></div>
                        <div class="stat-label">Chi phí trung bình</div>
                    </div>
                </div>
                
                <?php if (count($hotels) == 0 && !empty($searchQuery)): ?>
                    <div style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
                        <p style="font-size: 16px; color: #333; margin: 0;">Khách sạn không được tìm thấy</p>
                    </div>
                <?php endif; ?>
                
                <div class="table-container">
                    <table class="room-table">
                        <thead>
                            <tr>
                                <th>Mã khách sạn</th>
                                <th>Tên </th>
                                <th>Địa chỉ</th>
                                <th>Đánh giá</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead> 
                        <tbody>
                            <?php foreach ($hotels as $hotel): ?>
                                <tr>
                                    <td class="room-code"><?= $hotel['MaKS'] ?></td>
                                    <td><?= $hotel['Ten'] ?></td>
                                    <td><?= $hotel['khuvuc'] ?></td>
                                    <td><span class="star"><?= $hotel['diemdg'] ?><span class="stars" style="margin: 5px"><i class="fa-solid fa-star"></i></span></td>
                                    <td>
                                        <span class="status-badge available">
                                            Còn Phòng
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                        <a href="admin-CTkhachsan.php?id=<?= $hotel['MaKS'] ?>"><button class="menu-btn">☰</button></a>
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