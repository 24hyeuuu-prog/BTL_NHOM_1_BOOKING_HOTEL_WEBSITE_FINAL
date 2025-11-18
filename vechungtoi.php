<?php 
require_once 'config/config.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaValle - Về Chúng Tôi</title>
    <link rel="stylesheet" href="CSS/vechungtoi.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-image">
            <img src="img/banner2.jpg?height=400&width=1200" alt="Resort on Water" class="hero-bg">
        </div>
        <div class="hero-overlay">
            <div class="hero-content">
                <h1 class="hero-title">VỀ CHÚNG TÔI</h1>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Why Choose Us Section -->
        <section class="why-choose-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Vì sao nên chọn chúng tôi?</h2>
                    <p class="section-subtitle">Tự hào là đơn vị review khách sạn uy tín cho bạn</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Review uy tín khách quan</h3>
                        <p class="feature-description">Đánh giá hữu ích và khách quan của khách hàng để có sự lựa chọn uy tín nhất cho bạn</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Đặt phòng dễ dàng</h3>
                        <p class="feature-description">Website liên kết trực tiếp tới các trang chính thức của trang đặt phòng chính thức</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">Dịch vụ khách hàng 24h</h3>
                        <p class="feature-description">Chúng tôi tiếp nhận thông tin phản hồi đóng góp của khách hàng mọi thời gian trong ngày</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Content Section -->
        <section class="about-content-section">
            <div class="container">
                <div class="about-content-grid">
                    <div class="about-text">
                        <h2 class="about-title">Lavalle.com</h2>
                        <p class="about-description">
                            Trong thời đại du lịch trở thành nhu cầu thiết yếu, mỗi khách sạn không đơn thuần là nơi nghỉ ngơi 
                            mà còn là không gian tận hưởng, là tâm gương phản chiếu tinh hoa địa vụ của mỗi vùng đất. 
                            Lavalle ra đời từ mong muốn mỗi chuyến đi của bạn đều có ý nghĩa và đáng nhớ, giúp 
                            khách quan có thể tìm thấy địa điểm toàn trải nghiệm du lịch của hàng triệu người.
                        </p>
                        
                        <p class="about-description">Chúng tôi tin rằng một website review khách sạn chất lượng phải:</p>
                        
                        <ul class="about-list">
                            <li>Vượt qua những con số đánh giá khô khan để kể những câu chuyện sống động</li>
                            <li>Không dừng lại ở bề ngoài sáng trong mà đi sâu vào chất lượng trải nghiệm thực</li>
                            <li>Cân bằng giữa tiêu chuẩn khách quan và cảm nhận chủ quan của người dùng</li>
                        </ul>
                    </div>
                    
                    <div class="about-images">
                        <div class="image-container">
                            <img src="img/banner3.jpg?height=300&width=400" alt="Travel Experience" class="about-img">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="team-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Nhóm của chúng tôi</h2>
                    <p class="section-subtitle">Đội ngũ chuyên nghiệp nhiều năm kinh nghiệm</p>
                </div>
                
                <div class="team-grid">
                    <div class="team-member">
                        <div class="member-image">
                            <img src="img/chenfeiyu.jpg?height=200&width=200" alt="Chen FeiYu" class="member-photo">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Chen FeiYu</h3>
                            <p class="member-role">Web Designer</p>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <div class="member-image">
                            <img src="img/chenfeiyu.jpg?height=200&width=200" alt="Chen FeiYu" class="member-photo">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Chen FeiYu</h3>
                            <p class="member-role">Marketing Director</p>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <div class="member-image">
                            <img src="img/chenfeiyu.jpg?height=200&width=200" alt="Chen FeiYu" class="member-photo">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Chen FeiYu</h3>
                            <p class="member-role">International Relations</p>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <div class="member-image">
                            <img src="img/chenfeiyu.jpg?height=200&width=200" alt="Chen FeiYu" class="member-photo">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Chen FeiYu</h3>
                            <p class="member-role">Deployment manager</p>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <div class="member-image">
                            <img src="img/chenfeiyu.jpg?height=200&width=200" alt="Chen FeiYu" class="member-photo">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Chen FeiYu</h3>
                            <p class="member-role">Operator manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services-section">
            <div class="container">
                <div class="services-grid">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-ship"></i>
                        </div>
                        <h3 class="service-title">Chuyến đi</h3>
                    </div>
                    
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="service-title">Tour du lịch</h3>
                    </div>
                    
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-umbrella-beach"></i>
                        </div>
                        <h3 class="service-title">Du lịch biển</h3>
                    </div>
                    
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="service-title">Khám phá</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>