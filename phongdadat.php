<?php 
require_once 'config/config.php';
require_once 'controllers/BookingController.php';
require_once 'models/Booking.php';
require_once 'models/User.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get all pending and confirmed bookings for this user
$booking_sql = "SELECT b.*, h.Ten as hotel_name, r.room_type, r.room_number, u.Tendangnhap 
                FROM bookings b 
                LEFT JOIN khachsan h ON b.hotel_id = h.MaKS 
                LEFT JOIN rooms r ON b.room_id = r.room_id 
                LEFT JOIN nguoidung u ON b.user_id = u.Mauser 
                WHERE b.user_id = ? AND b.status IN ('Chưa xác nhận', 'Đã xác nhận')
                ORDER BY b.check_in_date DESC";

$stmt = $conn->prepare($booking_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$stmt->close();

$user = User::getById($conn, $user_id);
$message = getMessage();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng đã đặt - LaValle</title>
    <link rel="stylesheet" href="CSS/phongdadat2.css">
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
                      <center><h3 class="form-title">PHÒNG ĐÃ ĐẶT</h3></center>  
                    </div>
                    <div class="profile-form" id="personalInfoForm">
                        <div class="reservation-content">
        <div class="left">
            <div class="reservation-detail">
        <H2>ÁBCDJCNF</H2>
        <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
        </div>
        <span><i class="fas fa-map-marker-alt"></i> Hà Nội</span>
        </div>
    <div class="reservation-total-detail">
        <center><h2>Chi tiết đặt phòng</h2></center>
        <div>
            <h3> Nhận phòng</h3>
            <div class="reservation-time">
                <i class="fa-regular fa-calendar"></i>
                <div>
                    <b><p>Thứ sáu, 10 tháng 10 năm 2025 </p></b>
                    <p>Từ 14:00</p>
                </div>
            </div>
        </div>
        <div>
            <b><h3> Trả phòng</h3></b>
            <div class="reservation-time">
                <i class="fa-regular fa-calendar"></i>
                <div>
                    <b><p>Thứ sáu, 10 tháng 10 năm 2025 </p></b>
                    <p>Từ 12:00</p>
                </div>
            </div>
        </div>
        <b><h3> Tổng thời gian lưu trú: </h3></b> <hr>
        <div class="total-room">
            <b><h3 > Phòng đã đặt</h3></b>
            <div>
                <p>- Phòng đôi</p>
                <p>- Phòng đơn</p>
            </div>
        </div>
        <b><h3> Số lượng khách:</h3></b>
        <div class="total-customer">
            <i class="fa-solid fa-users"></i>
            <p>2 người lớn 1 trẻ em</p>
        </div> 
    </div>
    <div class="reservation-price-detail">
    <center><h2> Tổng cộng </h2></center>
        <div class="total"></div>
        <div>
            <h3> Thông tin giá</h3>
            <table>
                <tr>
                    <td width="40%">Phòng đôi</td>
                    <td width="4%">x</td>
                    <td width="10%">1</td>
                    <td width="40%">13.000.000 vnd</td>
                </tr>
            </table>
        </div>
    </div>
        </div>
        <div class="right">
            <div class="reservation-user-detail">
    <span>#1234556</span>
    </div>
<div class="customer-info">
        <center><H2> THÔNG TIN CHI TIẾT</H2></center>
        <form action ="" method="POST" class="">
            <div class="form-name-input">
            <div>
            <p>Họ<p>
            <input type= "text" class="customer-name" name="ho" placeholder="" disabled>
            </div>
            <div>
            <p>Tên<p>
            <input type= "text" class="customer-name" name="ten" placeholder="" disabled>
            </div>
            </div>
            <p>Địa chỉ email<p>
            <input type= "email" class="customer-email" name="email" placeholder="" disabled>
            <div class="form-location-input">
            <div class="location">
            <p>Vùng/quốc gia<p>
            <input type= "text" class="customer-name" name="ho" placeholder="" disabled>
            </div>
            <div class="tel">
            <p>Số điện thoại<p>
            <div class="telephone">
                <select name="area" class="" disabled>
                            <option value="">+84</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                </select>
                <input type= "tel" class="customer-phone" name="phone" placeholder="" disabled>
            </div> 
            </div>
            </div>
            <p>Yêu cầu chi tiết<p>
            <textarea name="special-request" class="special-request" placeholder="" disabled></textarea>
            <div class="submit-button">
            <a href="datchovachuyendi.php" class="submit-btn">QUAY LẠI</a>
            <a href="#" class="submit-btn">HỦY ĐẶT PHÒNG</a> 
            </div>
        </form>
    </div>
    </div>
    </div>
                    </div>
                </div>
                
                
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
</body>
</html>