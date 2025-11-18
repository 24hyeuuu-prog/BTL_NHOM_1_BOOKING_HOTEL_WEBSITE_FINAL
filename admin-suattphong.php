<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Chỉnh Sửa Phòng</title>
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
                <a href="admin-KSdsdatphong.php" class="menu-item">
                    <i class="fa-solid fa-calendar-days" style="margin: 10px"></i> Đặt phòng
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>CHỈNH SỬA PHÒNG</h1>
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
                        <center><H4 class="form-header">CHỈNH SỬA THÔNG TIN</H4></center>
                    </div>

                    <!-- Room Edit Form -->
                    <form class="edit-form" id="editRoomForm">
                    
                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">

                    <div class="detail-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Tên phòng:</label>
                                <input type="text" name="room_number" class="form-input" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Loại phòng:</label>
                                <select name="room_type" class="form-input" required>
                                    <option value="Phòng Đơn" <?php echo $room['room_type'] === 'Phòng Đơn' ? 'selected' : ''; ?>>Phòng Đơn</option>
                                    <option value="Phòng Đôi" <?php echo $room['room_type'] === 'Phòng Đôi' ? 'selected' : ''; ?>>Phòng Đôi</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Sức chứa:</label>
                                <input type="number" name="capacity" class="form-input" min="1" max="10" value="<?php echo $room['capacity']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Giá/đêm:</label>
                                <input type="number" name="price_per_night" class="form-input" min="0" step="1000" value="<?php echo $room['price_per_night']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label>Tiện nghi:</label>
                            <textarea name="amenities" class="form-textarea" rows="3" id = 'editor2' ><?php echo htmlspecialchars($room['amenities']); ?></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label>Mô tả:</label>
                            <textarea name="description" class="form-textarea" rows="4" id = 'editor1' ><?php echo htmlspecialchars($room['description']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Trạng thái:</label>
                                <select name="status" class="form-input">
                                    <option value="Có sẵn" <?php echo $room['status'] === 'Có sẵn' ? 'selected' : ''; ?>>Có sẵn</option>
                                    <option value="Bảo trì" <?php echo $room['status'] === 'Bảo trì' ? 'selected' : ''; ?>>Bảo trì</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="admin-CTphong.php?id=<?php echo $room['room_id']; ?>"><button type="button" class="btn-cancel">Quay lại</button></a>
                        <button type="submit" class="btn-save">Lưu thay đổi</button>
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
            if (CKEDITOR.instances.editor1) {
                document.querySelector('textarea[name="description"]').value = CKEDITOR.instances.editor1.getData();
            }
            if (CKEDITOR.instances.editor2) {
                document.querySelector('textarea[name="amenities"]').value = CKEDITOR.instances.editor2.getData();
            }

            const formData = {
                room_id: document.querySelector('input[name="room_id"]').value,
                room_number: document.querySelector('input[name="room_number"]').value,
                room_type: document.querySelector('select[name="room_type"]').value,
                capacity: document.querySelector('input[name="capacity"]').value,
                price_per_night: document.querySelector('input[name="price_per_night"]').value,
                amenities: document.querySelector('textarea[name="amenities"]').value,
                status: document.querySelector('select[name="status"]').value,
                description: document.querySelector('textarea[name="description"]').value
            };

            fetch('api/rooms.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Cập nhật phòng thành công!');
                    setTimeout(() => {
                        window.location.href = 'admin-CTphong.php?id=<?php echo $room['room_id']; ?>';
                    }, 1500);
                } else {
                    showNotification('error', data.message || 'Lỗi khi cập nhật phòng');
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
<script>
    CKEDITOR.replace('editor2', {
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