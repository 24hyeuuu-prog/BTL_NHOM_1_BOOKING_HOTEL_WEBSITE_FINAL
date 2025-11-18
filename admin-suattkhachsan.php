<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Chi Tiết Khách Sạn</title>
    <link rel="stylesheet" href="CSS/style4.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
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
                </div>

                <!-- Hotel Detail Form -->
                <form class="hotel-form" id="hotelForm">
                    <center><H4 class="form-header">CHỈNH SỬA KHÁCH SẠN</H4></center>
                    <div class="form-group full-width">
                        <label>Tên khách sạn:</label>
                        <input type="text" name="Ten" value="<?= $hotel['Ten'] ?>" class="form-input" placeholder="Nhập tên khách sạn" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Hạng khách sạn:</label>
                        <input type="text" name="hangkhachsan" value="<?= $hotel['hangkhachsan'] ?>" class="form-input" placeholder="Nhập hạng (sang trọng, cao cấp,...)" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Điểm đánh giá của khách hàng:</label>
                        <input type="int" name="diemdanhgia" value="<?= $hotel['diemdg'] ?>" class="form-input" placeholder=""readonly>
                    </div>
                    <div class="form-group full-width">
                        <label>Khu vực:</label>
                        <input type="text" name="khuvuc" value="<?= $hotel['khuvuc'] ?>" class="form-input" placeholder="Nhập khu vực" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Mô tả khách sạn:</label>
                        <textarea name="mota" class="form-textarea" placeholder="Mô tả khách sạn" required rows="4" id = 'editor1'><?= $hotel['mota'] ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Giá trị số 1:</label>
                            <input type="text" name="giatri1" value="<?= $hotel['giatri1'] ?>" class="form-input" placeholder="Giá trị số 1">
                        </div>
                        <div class="form-group">
                            <label>Giá trị số 2:</label>
                            <input type="text" name="giatri2" value="<?= $hotel['giatri2'] ?>" class="form-input" placeholder="Giá trị số 2">
                        </div>
                        <div class="form-group">
                            <label>Giá trị số 3:</label>
                            <input type="text" name="giatri3" value="<?= $hotel['giatri3'] ?>" class="form-input" placeholder="Giá trị số 3">
                        </div>
                        <div class="form-group">
                            <label>Giá trị số 4:</label>
                            <input type="text" name="giatri4" value="<?= $hotel['giatri4'] ?>" class="form-input" placeholder="Giá trị số 4">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label>Ảnh chính của khách sạn:</label>
                        <input type="text" name="anhmain" value="<?= $hotel['anhmain'] ?>" class="form-input" placeholder="Ảnh chính diện" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Ảnh 1:</label>
                            <input type="text" name="anh1" value="<?= $hotel['anh1'] ?>" class="form-input" placeholder="Ảnh 1">
                        </div>
                        <div class="form-group">
                            <label>Ảnh 2:</label>
                            <input type="text" name="anh2" value="<?= $hotel['anh2'] ?>" class="form-input" placeholder="Ảnh 2">
                        </div>
                        <div class="form-group">
                            <label>Ảnh 3:</label>
                            <input type="text" name="anh3" value="<?= $hotel['anh3'] ?>" class="form-input" placeholder="Ảnh 3">
                        </div>
                        <div class="form-group">
                            <label>Ảnh 4:</label>
                            <input type="text" name="anh4" value="<?= $hotel['anh4'] ?>" class="form-input" placeholder="Ảnh 4">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label>Mô tả chi tiết khách sạn:</label>
                        <textarea name="motachitiet" class="form-textarea" placeholder="Nhập mô tả chi tiết về khách sạn" required rows="3" id = 'editor2' ><?= $hotel['motachitiet'] ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Vị trí:</label>
                        <textarea name="vitri" class="form-textarea" placeholder="Đưa iframe nhúng vị trí khách sạn" required rows="3"><?= $hotel['vitri'] ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Giá trung bình:</label>
                        <input type="number" name="price" value="<?= $hotel['price'] ?>" class="form-input" placeholder="Nhập giá" required>
                    </div>

                    <div class="form-actions">
                        <a href="admin-CTkhachsan.php?id=<?= $hotelId ?>"><button type="button" class="btn-cancel">Quay lại</button></a>
                        <button type="submit" class="btn-save">Lưu</button>
                        <button type="button" class="btn-delete" id="deleteBtn">Xóa khách sạn</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('hotelForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                id: '<?= $hotelId ?>',
                Ten: document.querySelector('input[name="Ten"]').value,
                hangkhachsan: document.querySelector('input[name="hangkhachsan"]').value,
                vitri: document.querySelector('textarea[name="vitri"]').value,
                khuvuc: document.querySelector('input[name="khuvuc"]').value,
                mota: document.querySelector('textarea[name="mota"]').value,
                giatri1: document.querySelector('input[name="giatri1"]').value,
                giatri2: document.querySelector('input[name="giatri2"]').value,
                giatri3: document.querySelector('input[name="giatri3"]').value,
                giatri4: document.querySelector('input[name="giatri4"]').value,
                anhmain: document.querySelector('input[name="anhmain"]').value,
                anh1: document.querySelector('input[name="anh1"]').value,
                anh2: document.querySelector('input[name="anh2"]').value,
                anh3: document.querySelector('input[name="anh3"]').value,
                anh4: document.querySelector('input[name="anh4"]').value,
                motachitiet: document.querySelector('textarea[name="motachitiet"]').value,
                price: document.querySelector('input[name="price"]').value
            };
            
            fetch('api/hotels.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Content-Type:', response.headers.get('Content-Type'));
                
                // Get response as text to debug
                return response.text().then(text => {
                    console.log('Raw response text length:', text.length);
                    console.log('Raw response text:', text.substring(0, 1000));
                    
                    // If empty, return error
                    if (!text || text.trim() === '') {
                        throw new Error('Empty response from server');
                    }
                    
                    // Try to parse JSON
                    try {
                        const data = JSON.parse(text);
                        console.log('Parsed JSON:', data);
                        return data;
                    } catch (e) {
                        console.error('JSON parse failed:', e);
                        console.error('Response was:', text);
                        throw new Error('Server returned invalid JSON: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    alert('Cập nhật khách sạn thành công');
                    window.location.href = 'admin-dashboard.php';
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể cập nhật khách sạn'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Có lỗi xảy ra: ' + error.message);
            });
        });

        // Delete hotel function
        document.getElementById('deleteBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Confirm deletion
            const hotelName = '<?= addslashes($hotel['Ten']) ?>';
            if (confirm('Bạn có chắc chắn muốn xóa khách sạn "' + hotelName + '" không?\n\nHành động này không thể hoàn tác!')) {
                fetch('api/hotels.php?id=<?= $hotelId ?>', {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Xóa khách sạn thành công');
                        setTimeout(() => {
                            window.location.href = 'admin-dashboard.php';
                        }, 500);
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể xóa khách sạn'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra: ' + error.message);
                });
            }
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