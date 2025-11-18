<?php 
require_once 'config/config.php';
require_once 'controllers/UserController.php';
require_once 'controllers/ReviewController.php';

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
    <link rel="stylesheet" href="CSS/userpro.css">
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
                        <h3 class="form-title">Thông tin cá nhân</h3>
                    </div>
                    <form class="profile-form" id="personalInfoForm">
                        <div class="form-group">
                            <label class="form-label">Họ và Tên</label>
                            <input type="text" name="username" class="form-input" value="<?php echo $_SESSION['username']; ?>" placeholder="Nhập họ và tên" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="<?php echo $_SESSION['email']; ?>" placeholder="Nhập email" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-input" value="<?php echo $_SESSION['phone'] ?? ''; ?>" placeholder="Nhập số điện thoại">
                        </div>
                        <button type="submit" class="update-btn">Cập nhật</button>
                    </form>
                </div>

                <!-- Password Change Form -->
                <div class="form-card">
                    <div class="form-header">
                        <h3 class="form-title">Đổi mật khẩu</h3>
                    </div>
                    <form class="profile-form" id="passwordForm">
                        <div class="form-group">
                            <label class="form-label">Mật khẩu hiện tại</label>
                            <div class="password-input-container">
                                <input type="password" name="current_password" class="form-input password-input" placeholder="Nhập mật khẩu hiện tại" required>
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mật khẩu mới</label>
                            <div class="password-input-container">
                                <input type="password" name="new_password" class="form-input password-input" placeholder="Nhập mật khẩu mới" required>
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <div class="password-input-container">
                                <input type="password" name="confirm_password" class="form-input password-input" placeholder="Xác nhận mật khẩu mới" required>
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="update-btn">Cập nhật</button>
                    </form>
                </div>

            </div>
            
                <!-- User Reviews Section -->
                <div class="form-card reviews-card">
                    <div class="form-header">
                        <h3 class="form-title">Đánh giá của tôi</h3>
                    </div>
                    <!-- JS will populate this container with rating summary + reviews list -->
                    <div class="reviews-content" id="userReviews"></div>
                </div>
        </div>
    </main>
   <?php include 'footer.php'; ?>
    <script>
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
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>