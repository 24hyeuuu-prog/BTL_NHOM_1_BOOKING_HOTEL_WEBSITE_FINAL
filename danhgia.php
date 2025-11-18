<?php 
/**
 * Review Page
 * 
 * File: danhgia.php
 * Description: Display review form for a hotel
 * 
 * URL Parameters:
 * - id: Hotel ID
 */

require_once 'config/config.php';
require_once 'controllers/HotelController.php';
require_once 'controllers/ReviewController.php';

// Check login
checkPageAccess(true);

// Get hotel ID from URL
$hotelId = intval($_GET['id'] ?? 0);
if ($hotelId <= 0) {
    header('Location: khachsan2.php');
    exit;
}

// Load hotel information
$hotelController = new HotelController($conn);
$hotel = $hotelController->getById($hotelId);

if (!$hotel) {
    header('Location: khachsan2.php');
    exit;
}

// Load review controller for stats
$reviewController = new ReviewController($conn);
$review_stats = $reviewController->getStats($hotelId);

// Get messages
$message = getMessage();

// Get current user
$user_id = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaValle - Đánh Giá Khách Sạn</title>
    <link rel="stylesheet" href="CSS/danhgia.css">
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
        <div class="container">
            <div class="review-container">
                <!-- Left Column - Hotel Information -->
                <div class="hotel-info-section">
                    <h2 class="section-title">Hãy cho chúng tôi biết đánh giá của bạn về lần lưu trú này</h2>
                    
                    <div class="hotel-card-review">
                        <div class="hotel-image-review">
                            <img src="<?php echo $hotel['anhmain'] ?: '/placeholder.svg?height=150&width=200'; ?>" 
                                 alt="<?php echo htmlspecialchars($hotel['Ten']); ?>">
                            <span class="category-badge"><?php echo htmlspecialchars(ucfirst($hotel['hangkhachsan'])); ?></span>
                        </div>
                        <div class="hotel-details-review">
                            <h3 class="hotel-name-review"><?php echo htmlspecialchars($hotel['Ten']); ?></h3>
                            <div class="hotel-rating-review">
                                <div class="stars-review">
                                    <?php
                                    $rating = floatval($hotel['diemdg'] ?? 0);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="rating-score"><?php echo number_format($rating, 1); ?></span>
                            </div>
                            
                            <?php if ($review_stats && $review_stats['total_reviews'] > 0): ?>
                            <div class="review-stats">
                                <span class="stat-item">
                                    <i class="fas fa-comment"></i>
                                    <?php echo $review_stats['total_reviews']; ?> đánh giá
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="hotel-price-review">
                                <i class="fas fa-tag"></i>
                                <span class="price-text"><?php echo htmlspecialchars($hotel['price'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="hotel-location-review">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="location-text"><?php echo htmlspecialchars($hotel['khuvuc'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Review Form -->
                <div class="review-form-section">
                    <h2 class="form-title">Bạn xếp hạng chuyến lưu trú này bao nhiêu sao?</h2>
                    
                    <form class="review-form" id="reviewForm">
                        <input type="hidden" name="hotel_id" value="<?php echo $hotelId; ?>">
                        
                        <!-- Star Rating -->
                        <div class="star-rating-section">
                            <label class="rating-label">Xếp hạng của bạn *</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5" title="5 sao - Tuyệt vời"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" title="4 sao - Tốt"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" title="3 sao - Bình thường"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" title="2 sao - Kém"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" title="1 sao - Rất kém"><i class="fas fa-star"></i></label>
                            </div>
                            <div class="rating-feedback" id="ratingFeedback"></div>
                        </div>

                        <!-- Tiêu đề đánh giá -->
                        <div class="form-group">
                            <label class="form-label">Tiêu đề cho bài đánh giá *</label>
                            <input type="text" name="title" class="form-input" 
                                   placeholder="Ví dụ: Khách sạn tuyệt vời với dịch vụ chuyên nghiệp" 
                                   maxlength="50" required>
                            <div class="character-count">0/50 ký tự</div>
                        </div>

                        <!-- Trip Purpose -->
                        <div class="form-group">
                            <label class="form-label">Mục đích chuyến đi này là? *</label>
                            <input type="text" name="purpose" class="form-input" 
                                   placeholder="Ví dụ: Công tác, Du lịch cùng gia đình..." 
                                   required>
                        </div>

                        <!-- Travel Companion -->
                        <div class="form-group">
                            <label class="form-label">Bạn đi cùng ai? *</label>
                            <input type="text" name="companion" class="form-input" 
                                   placeholder="Ví dụ: Một mình, Gia đình, Bạn bè..." 
                                   required>
                        </div>

                        <!-- Detailed Review -->
                        <div class="form-group">
                            <label class="form-label">Hãy viết đánh giá chi tiết của bạn *</label>
                            <textarea name="content" class="form-textarea" 
                                      placeholder="Chia sẻ trải nghiệm của bạn về lần lưu trú này (Điểm tốt, điểm cần cải thiện, ...)" 
                                      rows="6" maxlength="1000" required></textarea>
                            <div class="character-count">0/1000 ký tự</div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="terms" class="checkbox-input" required>
                                <label for="terms" class="checkbox-label">
                                    <i class="fas fa-check"></i>
                                    Tôi chứng nhận rằng đánh giá này dựa trên kinh nghiệm thực tế của tôi 
                                    và là ý kiến trung thực. Tôi không có mối quan hệ kinh doanh với khách sạn này 
                                    và không nhận bất kỳ lợi ích nào để viết đánh giá này.
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            GỬI ĐÁNH GIÁ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <!-- Scripts -->
    <script>
        /**
         * Notification System
         */
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

        /**
         * Star Rating Functionality
         */
        const stars = document.querySelectorAll('.star-rating input');
        const starLabels = document.querySelectorAll('.star-rating label');
        const ratingFeedback = document.getElementById('ratingFeedback');
        
        const feedbackMessages = {
            5: '😍 Tuyệt vời! Bạn rất hài lòng',
            4: '😊 Tốt! Bạn khá hài lòng',
            3: '😐 Bình thường! Khá ổn',
            2: '😟 Kém! Cần cải thiện',
            1: '😞 Rất kém! Không hài lòng'
        };

        stars.forEach((star) => {
            star.addEventListener('change', function() {
                const rating = this.value;
                starLabels.forEach((label, labelIndex) => {
                    if (labelIndex >= stars.length - rating) {
                        label.style.color = '#FFD700';
                    } else {
                        label.style.color = '#ddd';
                    }
                });
                
                if (ratingFeedback) {
                    ratingFeedback.textContent = feedbackMessages[rating];
                    ratingFeedback.style.display = 'block';
                }
            });
            
            star.addEventListener('hover', function() {
                // Visual feedback on hover
            });
        });

        /**
         * Character Count Functionality
         */
        const textarea = document.querySelector('.form-textarea');
        const titleInput = document.querySelector('input[name="title"]');
        
        if (textarea) {
            textarea.addEventListener('input', function() {
                const count = this.value.length;
                const counter = this.parentNode.querySelector('.character-count');
                counter.textContent = `${count}/1000 ký tự`;
                if (count > 1000) {
                    counter.style.color = '#ff4444';
                } else if (count > 800) {
                    counter.style.color = '#ff9800';
                } else {
                    counter.style.color = '#666';
                }
            });
        }

        if (titleInput) {
            titleInput.addEventListener('input', function() {
                const count = this.value.length;
                const counter = this.parentNode.querySelector('.character-count');
                counter.textContent = `${count}/50 ký tự`;
                if (count > 50) {
                    counter.style.color = '#ff4444';
                } else if (count > 40) {
                    counter.style.color = '#ff9800';
                } else {
                    counter.style.color = '#666';
                }
            });
        }

        /**
         * Form Submission
         */
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Validate required fields
            const rating = formData.get('rating');
            const purpose = formData.get('purpose');
            const companion = formData.get('companion');
            const content = formData.get('content');
            const title = formData.get('title');
            const terms = document.getElementById('terms').checked;
            
            // Client-side validation
            if (!rating) {
                showNotification('error', 'Vui lòng chọn xếp hạng sao');
                return;
            }
            
            if (!title || title.trim() === '') {
                showNotification('error', 'Vui lòng nhập tiêu đề đánh giá');
                return;
            }
            
            if (!purpose || purpose.trim() === '') {
                showNotification('error', 'Vui lòng nhập mục đích chuyến đi');
                return;
            }
            
            if (!companion || companion.trim() === '') {
                showNotification('error', 'Vui lòng nhập người đi cùng');
                return;
            }
            
            if (!content || content.trim() === '') {
                showNotification('error', 'Vui lòng nhập nội dung đánh giá');
                return;
            }
            
            if (!terms) {
                showNotification('error', 'Vui lòng đồng ý với điều khoản dịch vụ');
                return;
            }
            
            if (content.length > 1000) {
                showNotification('error', 'Nội dung đánh giá không được vượt quá 1000 ký tự');
                return;
            }
            
            if (title.length > 50) {
                showNotification('error', 'Tiêu đề không được vượt quá 50 ký tự');
                return;
            }
            
            // Submit review to API
            fetch('submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    // Redirect after successful submission
                    setTimeout(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = 'khachsan2.php';
                        }
                    }, 1500);
                } else {
                    showNotification('error', data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
            });
        });
    </script>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>