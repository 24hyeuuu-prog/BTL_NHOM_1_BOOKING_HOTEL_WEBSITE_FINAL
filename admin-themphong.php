<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Thêm Phòng</title>
    <link rel="stylesheet" href="CSS/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
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
                        <?php if ($hotel): ?>
                        <span class="separator">›</span>
                        <a href="admin-CTkhachsan.php?id=<?= $hotelId ?>" class="breadcrumb"><?= htmlspecialchars($hotel['Ten']) ?></a>
                        <?php endif; ?>
                        <span class="separator">›</span>
                        Thêm phòng mới
                    </h2>
                </div>

                <!-- Room Details Card -->
                <div class="room-detail-card">
                    <div class="detail-header">
                        <center><H4 class="form-header">THÊM PHÒNG MỚI</H4></center>
                    </div>

                    <!-- Room Create Form -->
                    <form class="edit-form" id="editRoomForm">
                        <div class="form-group full-width">
                            <label>Khách sạn:</label>
                            <input type="text" class="form-input" value="<?= htmlspecialchars($hotel['Ten']) ?>" disabled>
                            <input type="hidden" name="hotel_id" value="<?= $hotelId ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Tên phòng:</label>
                                <input type="text" name="room_number" class="form-input" placeholder="VD: A101, B202" required>
                            </div>
                            <div class="form-group">
                                <label>Loại phòng:</label>
                                <select name="room_type" class="form-input" required>
                                    <option value="Phòng Đơn">Phòng Đơn</option>
                                    <option value="Phòng Đôi">Phòng Đôi</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Sức chứa:</label>
                                <input type="number" name="capacity" class="form-input" min="1" max="10" value="1" required>
                            </div>
                            <div class="form-group">
                                <label>Giá/đêm:</label>
                                <input type="number" name="price_per_night" class="form-input" min="0" step="1000" required>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label>Tiện nghi:</label>
                            <textarea name="amenities" class="form-textarea" placeholder="VD: WiFi, Điều hòa, TV, Phòng tắm riêng" rows="3" id = 'editor1'></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label>Mô tả:</label>
                            <textarea name="description" class="form-textarea" placeholder="Mô tả chi tiết về phòng" rows="4" id= 'editor'></textarea>
                        </div>
                   
                    <div class="form-actions">
                        <a href="admin-QLphong.php"><button type="button" class="btn-cancel">Quay lại</button></a>
                        <button type="submit" class="btn-save">Thêm phòng</button>
                    </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <script>
        document.getElementById('editRoomForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Đồng bộ CKEditor data trước khi gửi
            if (CKEDITOR.instances.editor) {
                document.querySelector('textarea[name="description"]').value = CKEDITOR.instances.editor.getData();
            }
            if (CKEDITOR.instances.editor1) {
                document.querySelector('textarea[name="amenities"]').value = CKEDITOR.instances.editor1.getData();
            }

            const formData = {
                hotel_id: document.querySelector('input[name="hotel_id"]').value,
                room_number: document.querySelector('input[name="room_number"]').value,
                room_type: document.querySelector('select[name="room_type"]').value,
                capacity: document.querySelector('input[name="capacity"]').value,
                price_per_night: document.querySelector('input[name="price_per_night"]').value,
                amenities: document.querySelector('textarea[name="amenities"]').value,
                description: document.querySelector('textarea[name="description"]').value,
                status: 'Có sẵn' // Default status for new rooms
            };

            fetch('api/rooms.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Thêm phòng thành công!');
                    setTimeout(() => {
                        window.location.href = 'admin-QLphong.php';
                    }, 1500);
                } else {
                    showNotification('error', data.message || 'Lỗi khi thêm phòng');
                }
            })
            .catch(error => {
                showNotification('error', 'Lỗi: ' + error.message);
            });
        });

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
 <script>
    CKEDITOR.replace('editor', {
      height: 350,
      // cho phép paste từ Word tốt hơn:
      extraPlugins: 'pastefromword',
      // bật upload ảnh (cần upload handler ở server)
      filebrowserUploadUrl: 'upload.php',
      // nếu muốn cho phép hầu hết định dạng:
      allowedContent: true,
      // toolbar đơn giản, bạn có thể tùy biến:
      toolbar: [
        { name: 'clipboard', items: ['Undo','Redo'] },
        { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike'] },
        { name: 'paragraph', items: ['NumberedList','BulletedList','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight'] },
        { name: 'links', items: ['Link','Unlink'] },
        { name: 'insert', items: ['Image','Table','HorizontalRule','SpecialChar'] },
        { name: 'styles', items: ['Format','FontSize'] }
      ]
    });
  </script>
 <script>
    CKEDITOR.replace('editor1', {
      height: 350,
      // cho phép paste từ Word tốt hơn:
      extraPlugins: 'pastefromword',
      // bật upload ảnh (cần upload handler ở server)
      filebrowserUploadUrl: 'upload.php',
      // nếu muốn cho phép hầu hết định dạng:
      allowedContent: true,
      // toolbar đơn giản, bạn có thể tùy biến:
      toolbar: [
        { name: 'clipboard', items: ['Undo','Redo'] },
        { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike'] },
        { name: 'paragraph', items: ['NumberedList','BulletedList','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight'] },
        { name: 'links', items: ['Link','Unlink'] },
        { name: 'insert', items: ['Image','Table','HorizontalRule','SpecialChar'] },
        { name: 'styles', items: ['Format','FontSize'] }
      ]
    });
  </script>
</body>
</html>