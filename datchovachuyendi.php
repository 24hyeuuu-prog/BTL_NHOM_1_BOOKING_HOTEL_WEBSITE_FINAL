<?php 
require_once 'config/config.php';

// Kiểm tra đăng nhập
checkPageAccess(true);

$message = getMessage();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaValle - Hồ Sơ Người Dùng</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">  
    <link rel="stylesheet" href="CSS/chuyendi3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>

    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer">
        <?php if($message): ?>
            <div class="notification <?php echo $message['type']; ?> show">
                <div class="notification-content">
                    <div class="notification-icon">
                        <?php if($message['type'] == 'success'): ?>
                            <i class="fas fa-check-circle"></i>
                        <?php elseif($message['type'] == 'error'): ?>
                            <i class="fas fa-exclamation-triangle"></i>
                        <?php endif; ?>
                    </div>
                    <div class="notification-message"><?php echo $message['text']; ?></div>
                    <button class="notification-close" onclick="closeNotification(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Background Image -->
        <div class="background-section">
            <img src="img/bannerhome.png" alt="Resort Background" class="background-img">
        </div>

        <!-- Profile Container -->
        <div class="profile-container">
            <!-- User Profile Card -->
            <div class="user-profile-card">
                <div class="profile-avatar">
                    <?php if(isset($_SESSION['avatar']) && $_SESSION['avatar']): ?>
                        <img src="<?php echo $_SESSION['avatar']; ?>" alt="<?php echo $_SESSION['username']; ?>" class="avatar-img">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h2 class="profile-name"><?php echo $_SESSION['username']; ?></h2>
                    <div class="profile-actions">
                        <span class="profile-email"><?php echo $_SESSION['email']; ?></span>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">Đăng xuất</a>
            </div>

            <!-- Forms Section -->
            <div class="forms-section">
                <!-- Personal Information Form -->
                <div class="form-card">
                    <div class="form-header">
                      <center><h3 class="form-title">Đặt chỗ và chuyến đi</h3></center>  
                    </div>
                    <div class="profile-form" id="personalInfoForm">
                        <div class="card" style="width: 100%;">
                        <img src="img/vungtau.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdadat.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>    
                        <div class="card" style="width: 100%;">
                        <img src="img/vungtau.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdadat.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>   
                        <div class="card" style="width: 100%;">
                        <img src="img/vungtau.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdadat.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>   
                    </div>
                </div>
                
                <!-- User Reviews Section -->
                <div class="form-card">
                    <div class="form-header">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="submit-btn active " id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Đã hoàn thành </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="submit-btn" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Đã hủy</button>
  </li>
  
</ul>
                    </div>
                    
                        <div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active  " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="tab-pane-2">
                        <div class="card" style="width: 100%;">
                        <img src="img/vungtau.jpg..." class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdahoanthanh.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>    
                        <div class="card" style=" width:100%;">
                        <img src="img/vungtau.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdahoanthanh.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>   
                        <div class="card" style="width: 100%;">
                        <img src="img/vungtau.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdahoanthanh.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>   
                    </div>
</div>
  <div class="tab-pane fade " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
  <div class="tab-pane-2">  
  <div class="card" style="width: 100%;">
                        <img src="img/vungtau.jpg..." class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdahuy.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>    
                        <div class="card" style=" width:100%;">
                        <img src="img/vungtau.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdahuy.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>   
                        <div class="card" style="width: 100%;">
                        <img src="img/vungtau.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                        <h5 class="card-title">HOTEL</h5>
                        <p class="card-text">T6 10 tháng 10 - T7 11 tháng 10</p>
                        <a href="phongdahuy.php" class="btn btn-primary">Chi Tiết</a>
                        </div>
                        </div>   
                    </div>
                </div>
</div>
                    </div>
                
            </div>
        </div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </main>
   <?php include 'footer.php'; ?>
    <script>
        const triggerTabList = document.querySelectorAll('#myTab button')
triggerTabList.forEach(triggerEl => {
  const tabTrigger = new bootstrap.Tab(triggerEl)

  triggerEl.addEventListener('click', event => {
    event.preventDefault()
    tabTrigger.show()
  })
})
        // Notification functions
        function showNotification(type, message) {
            const container = document.getElementById('notificationContainer');
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-triangle"></i>';
                    break;
            }
            
            notification.innerHTML = `
                <div class="notification-content">
                    <div class="notification-icon">${icon}</div>
                    <div class="notification-message">${message}</div>
                    <button class="notification-close" onclick="closeNotification(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                closeNotification(notification.querySelector('.notification-close'));
            }, 5000);
        }

        function closeNotification(closeBtn) {
            const notification = closeBtn.closest('.notification');
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }

        // Password toggle functionality
        function togglePassword(button) {
            const passwordInput = button.previousElementSibling;
            const icon = button.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Handle personal info form submission
        document.getElementById('personalInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    // Update session data
                    if (data.updated_data) {
                        document.querySelector('.profile-name').textContent = data.updated_data.username;
                        document.querySelector('.profile-email').textContent = data.updated_data.email;
                    }
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra khi cập nhật thông tin');
            });
        });

        // Handle password form submission
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('update_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    this.reset();
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra khi đổi mật khẩu');
            });
        });

        // Load user reviews
        function loadUserReviews() {
            fetch('get_user_reviews.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('userReviews');
                        if (data.reviews.length > 0) {
                            container.innerHTML = data.reviews.map(review => `
                                <div class="review-item">
                                    <div class="review-header">
                                        <h4>${review.hotel_name}</h4>
                                        <div class="review-rating">
                                            ${generateStars(review.diemreview)}
                                            <span>${review.diemreview}</span>
                                        </div>
                                    </div>
                                    <h5>${review.tieude}</h5>
                                    <p>${review.noidung}</p>
                                    <small>Đánh giá vào: ${formatDate(review.thoigian)}</small>
                                </div>
                            `).join('');
                        } else {
                            container.innerHTML = '<p>Bạn chưa có đánh giá nào.</p>';
                        }
                    }
                })
                .catch(error => console.error('Error loading reviews:', error));
        }

        function generateStars(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            
            return '<i class="fas fa-star"></i>'.repeat(fullStars) +
                   (hasHalfStar ? '<i class="fas fa-star-half-alt"></i>' : '') +
                   '<i class="far fa-star"></i>'.repeat(emptyStars);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN');
        }

        // Phone number formatting
        document.querySelector('input[type="tel"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.slice(0, 3) + ' ' + value.slice(3);
                } else if (value.length <= 10) {
                    value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
                } else {
                    value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
                }
            }
            e.target.value = value;
        });

        // Load user reviews on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUserReviews();
        });
    </script>
</body>
</html>