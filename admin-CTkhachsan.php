<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Chi Tiết Khách Sạn</title>
    <link rel="stylesheet" href="CSS/style4.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php
    require_once 'config/config.php';
    require_once 'controllers/HotelController.php';
    
    // Check admin access
    checkPageAccess(true);
    
    $hotelId = intval($_GET['id'] ?? 0);
    if ($hotelId <= 0) {
        header('Location: admin-dashboard.php');
        exit;
    }
    
    $hotelController = new HotelController($conn);
    $hotel = $hotelController->getById($hotelId);
    
    if (!$hotel) {
        header('Location: admin-dashboard.php');
        exit;
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
                        <?= $hotel['Ten'] ?>
                    </h2>
                    <div class="header-actions">
                        <a href="admin-QLphong.php?id=<?= $hotelId ?>"><button class="btn-secondary">Quản lý phòng</button></a>
                    </div>
                </div>

                <!-- Hotel Detail Form -->
                <form class="hotel-form">
                    <center><H4 class="form-header"> THÔNG TIN CHI TIẾT</H4></center>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tên khách sạn:</label>
                            <input type="text" value="<?= $hotel['Ten'] ?>" class="form-input" disabled>
                        </div>
                        <div class="form-group">
                            <label>Hạng khách sạn:</label>
                            <input type="text" value="<?= $hotel['hangkhachsan'] ?>" class="form-input" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Khu vực:</label>
                            <input type="text" value="<?= $hotel['khuvuc'] ?>" class="form-input" disabled>
                        </div>
                        <div class="form-group">
                            <label>Đánh giá:</label>
                            <input type="text" value="<?= $hotel['diemdg'] ?>/5" class="form-input" disabled>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label>Giới thiệu khách sạn:</label>
                        <div class="form-textarea" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; min-height: 100px; white-space: pre-wrap; word-wrap: break-word;" id="motaDisplay"></div>
                    </div>

                    <div class="form-group full-width">
                        <label>Tiện nghi khách sạn:</label>
                        <div class="form-textarea" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; min-height: 75px; white-space: pre-wrap; word-wrap: break-word;" id="motachitietDisplay"></div>
                    </div>

                    <div class="form-group full-width">
                        <label>Giá:</label>
                        <input type="text" value="<?= $hotel['price'] ?> VND" class="form-input" disabled>
                    </div>

                    <div class="form-group full-width">
                        <label>Hình ảnh khách sạn:</label>
                        <div class="image-upload">
                            <?php 
                            $images = [
                                ['src' => $hotel['anhmain'], 'alt' => 'Ảnh chính'],
                                ['src' => $hotel['anh1'], 'alt' => 'Ảnh 1'],
                                ['src' => $hotel['anh2'], 'alt' => 'Ảnh 2'],
                                ['src' => $hotel['anh3'], 'alt' => 'Ảnh 3'],
                                ['src' => $hotel['anh4'], 'alt' => 'Ảnh 4']
                            ];
                            
                            foreach ($images as $image) {
                                if (!empty($image['src'])) {
                                    echo '<img src="' . htmlspecialchars($image['src']) . '" alt="' . htmlspecialchars($image['alt']) . '" class="img-themks">';
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="admin-dashboard.php"><button type="button" class="btn-cancel">Quay lại</button></a>
                        <!-- <button type="submit" class="btn-save">Lưu</button> -->
                        <a href="admin-suattkhachsan.php?id=<?= $hotelId ?>"><button type="button" class="btn-save">Chỉnh sửa</button></a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Hiển thị HTML content đúng cách
        document.getElementById('motaDisplay').innerHTML = <?= json_encode($hotel['mota']) ?>;
        document.getElementById('motachitietDisplay').innerHTML = <?= json_encode($hotel['motachitiet']) ?>;
    </script>
</body>
</html>