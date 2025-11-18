<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Thêm Khách Sạn</title>
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
                <!-- Hotel Detail Form -->
                <form class="hotel-form" id="addHotelForm">
                    <center><H4 class="form-header">THÊM KHÁCH SẠN</H4></center>
                    <div class="form-group full-width">
                        <label>Tên khách sạn:</label>
                        <input type="text" name="Ten" class="form-input" placeholder="Nhập tên khách sạn" required>
                    </div>
                    <div class="form-group full-width">
                            <label>Hạng khách sạn:</label>
                            <input type="text" name="hangkhachsan" class="form-input" placeholder="Nhập hạng (sang trọng, cao cấp,...)" required>
                    </div>
                    <div class="form-group full-width">
                            <label>Vị trí:</label>
                            <input type="text" name="vitri" class="form-input" placeholder="Nhập vị trí khách sạn">
                    </div>
                    <div class="form-group full-width">
                        <label>Khu vực:</label>
                        <input type="text" name="khuvuc" class="form-input" placeholder="Nhập khu vực" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Mô tả khách sạn:</label>
                        <textarea name="mota" class="form-textarea" placeholder="Mô tả khách sạn" required rows="4"  id="editor1" ></textarea>
                    </div>


                    <div class="form-row">
                            <div class="form-group">
                            <!-- <label>Khu vực:</label>
                            <input type="text" name="location" class="form-input" placeholder="Nhập khu vực" required> -->
                        </div>
                    <div class="form-group full-width">
                        <!-- <label>Khu Vực:</label>
                        <input type="text" name="name" class="form-input" placeholder="Khu vực tọa lạc " required> -->
                    </div>
                        <!-- <div class="form-group">
                            <label>Mô tả</label>
                            <input type="text" name="text" class="form-input" placeholder="Nhập mô tả sơ qua về khách sạn">
                        </div> -->
                        <div class="form-group">
                            <label>Giá trị số 1:</label>
                            <input type="text" name="giatri1" class="form-input" placeholder="Giá trị số 1">
                        </div>
                        <div class="form-group">
                            <label>Giá trị số 2:</label>
                            <input type="text" name="giatri2" class="form-input" placeholder="Giá trị số 2">
                        </div>
                        <div class="form-group">
                            <label>Giá trị số 3:</label>
                            <input type="text" name="giatri3" class="form-input" placeholder="Giá trị số 3">
                        </div>
                        <div class="form-group">
                            <label>Giá trị số 4:</label>
                            <input type="text" name="giatri4" class="form-input" placeholder="Giá trị số 4">
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label>Ảnh chính của khách sạn:</label>
                        <input type="text" name="anhmain" class="form-input" placeholder="Ảnh chính diện" required>
                    </div>
                        <div class="form-row">
                        <div class="form-group">
                            <label>Ảnh phụ 1:</label>
                            <input type="text" name="anh1" class="form-input" placeholder="Ảnh phụ">
                        </div>
                        <div class="form-group">
                            <label>Ảnh phụ 2:</label>
                            <input type="text" name="anh2" class="form-input" placeholder="Ảnh phụ">
                        </div>
                        <div class="form-group">
                            <label>Ảnh phụ 3: </label>
                            <input type="text" name="anh3" class="form-input" placeholder="Ảnh phụ">
                        </div>
                        <div class="form-group">
                            <label>Ảnh phụ 4: </label>
                            <input type="text" name="anh4" class="form-input" placeholder="Ảnh phụ">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label>Mô tả chi tiết khách sạn:</label>
                        <textarea name="motachitiet" class="form-textarea" placeholder="Nhập mô tả chi tiết về khách sạn" required rows="3" id="editor2"></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Giá trung bình:</label>
                        <input type="number" name="price" class="form-input" placeholder="Nhập giá" required>
                    </div>

                    <!-- <div class="form-group full-width">
                        <label>Ảnh chính:</label>
                        <div class="image-upload">
                            <button type="button" class="btn-upload">
                                <span class="plus-icon">+</span>
                            </button>
                        </div>
                    </div> -->

                    <div class="form-actions">
                        <a href="admin-dashboard.php"><button type="button" class="btn-cancel">Hủy</button></a>
                        <button type="submit" class="btn-save">Thêm khách sạn</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('addHotelForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Update textarea với dữ liệu từ CKEditor trước khi submit
            if (CKEDITOR.instances.editor1) {
                document.querySelector('textarea[name="mota"]').value = CKEDITOR.instances.editor1.getData();
            }
            if (CKEDITOR.instances.editor2) {
                document.querySelector('textarea[name="motachitiet"]').value = CKEDITOR.instances.editor2.getData();
            }
            
            const formData = new FormData(this);
            
            fetch('api/hotels.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Thêm khách sạn thành công');
                    window.location.href = 'admin-dashboard.php';
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể thêm khách sạn'));
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