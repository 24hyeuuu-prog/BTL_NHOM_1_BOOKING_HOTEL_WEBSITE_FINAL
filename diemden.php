<?php 
require_once 'config/config.php';
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaValle - Điểm đến</title>
    <link rel="stylesheet" href="CSS/diemden.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-image">
            <img src="img/banner3.webp" alt="Điểm đến" class="hero-bg">
        </div>
    </section>

    <!-- Main Content -->
    <main class="main">
        <div class="content-section">
            <h1 class="main-title">ĐIỂM ĐẾN TUYỆT VỜI CHO KỲ NGHỈ CỦA BẠN</h1>
            <p class="main-subtitle">Mang tới cho bạn những điểm đến lý tưởng giúp bạn có kỳ nghỉ tuyệt vời</p>
            <div class="divider"></div>
        </div>

        <!-- Destinations Section -->
        <section class="destinations-section">
            <div class="container">
                <!-- Destination 1 -->
                <div class="destination-item">
                    <div class="destination-image">
                        <img src="img/halong1.jpg" alt="Vịnh Hạ Long">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Vịnh Hạ Long</h2>
                        <p class="destination-description">
                            Vịnh Hạ Long là một kỳ quan thiên nhiên đặc biệt quan trọng của Việt Nam và giá trị toàn cầu. Vịnh Hạ Long được UNESCO công nhận là Di sản thiên nhiên thế giới. Vịnh Hạ Long được biết đến với hàng nghìn đảo đá vôi được bao phủ bởi rừng nhiệt đới cùng hệ sinh thái đa dạng.
                        </p>
                        <a href="https://www.xanhsm.com/news/vinh-ha-long" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>

                <!-- Destination 2 -->
                <div class="destination-item reverse">
                    <div class="destination-image">
                        <img src="img/catba1.jpg" alt="Đảo Cát Bà">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Đảo Cát Bà</h2>
                        <p class="destination-description">
                            Đảo Cát Bà là hòn đảo lớn nhất trong quần đảo Cát Bà, thuộc địa phận thành phố Hải Phòng. Đảo Cát Bà được biết đến với những bãi biển hoang sơ, những vịnh đẹp và hệ sinh thái đa dạng. Đảo Cát Bà là điểm đến lý tưởng cho những ai yêu thích thiên nhiên và các hoạt động ngoài trời.
                        </p>
                        <a href="https://vietnamtourism.gov.vn/post/18583" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>

                <!-- Destination 3 -->
                <div class="destination-item">
                    <div class="destination-image">
                        <img src="img/sapa1.jpg" alt="Sapa">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Sapa</h2>
                        <p class="destination-description">
                            Sapa là một thị trấn vùng cao nổi tiếng với những thửa ruộng bậc thang vàng óng vào mùa lúa chín. Sapa nổi tiếng với những ngọn núi hùng vĩ, những thửa ruộng bậc thang và nền văn hóa đặc sắc của các dân tộc thiểu số. Sapa là điểm đến lý tưởng cho những ai yêu thích khám phá văn hóa và thiên nhiên.
                        </p>
                        <a href="https://vietnamtourism.gov.vn/post/31125" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>

                <!-- Destination 4 -->
                <div class="destination-item reverse">
                    <div class="destination-image">
                        <img src="img/mochau1.jpg" alt="Mộc Châu">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Mộc Châu</h2>
                        <p class="destination-description">
                            Mộc Châu là một cao nguyên xinh đẹp thuộc tỉnh Sơn La, nổi tiếng với những đồng cỏ xanh mướt và vườn hoa đẹp. Mộc Châu nổi tiếng với những đồng cỏ xanh mướt, vườn chè, vườn hoa và những con người thân thiện. Mộc Châu là điểm đến lý tưởng cho những ai yêu thích không gian yên bình và thiên nhiên.
                        </p>
                        <a href="https://vietnamtourism.gov.vn/post/60274" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>

                <!-- Destination 5 -->
                <div class="destination-item">
                    <div class="destination-image">
                        <img src="img/hoian1.jpg" alt="Hội An">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Hội An</h2>
                        <p class="destination-description">
                            Hội An là một thành phố cổ xinh đẹp nằm ở tỉnh Quảng Nam, được UNESCO công nhận là Di sản văn hóa thế giới. Hội An nổi tiếng với kiến trúc cổ kính, những con phố đèn lồng rực rỡ và ẩm thực đặc sắc. Hội An là điểm đến lý tưởng cho những ai yêu thích lịch sử và văn hóa.
                        </p>
                        <a href="https://bambooairways.com/vn/vi/travel-guide/domestic-travels/quang-nam/kham-pha-pho-co-hoi-an-qua-goc-nhin-lich-su" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>

                <!-- Destination 6 -->
                <div class="destination-item reverse">
                    <div class="destination-image">
                        <img src="img/dongthap1.jpg" alt="Đồng Tháp">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Đồng Tháp</h2>
                        <p class="destination-description">
                            Đồng Tháp là một tỉnh thuộc vùng Đồng bằng sông Cửu Long, nổi tiếng với những cánh đồng sen trải dài bát ngát. Đồng Tháp nổi tiếng với những cánh đồng sen, vườn hoa và những con người thân thiện. Đồng Tháp là điểm đến lý tưởng cho những ai yêu thích không gian yên bình và thiên nhiên.
                        </p>
                        <a href="https://www.momo.vn/blog/du-lich-dong-thap-c101dt799" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>

                <!-- Destination 7 -->
                <div class="destination-item">
                    <div class="destination-image">
                        <img src="img/vungtau.jpg" alt="Vũng Tàu">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Vũng Tàu</h2>
                        <p class="destination-description">
                            Vũng Tàu là một thành phố biển xinh đẹp thuộc tỉnh Bà Rịa - Vũng Tàu, nổi tiếng với những bãi biển đẹp và ẩm thực hải sản phong phú. Vũng Tàu nổi tiếng với những bãi biển đẹp, những ngọn núi hùng vĩ và ẩm thực hải sản phong phú. Vũng Tàu là điểm đến lý tưởng cho những ai yêu thích biển và các hoạt động ngoài trời.
                        </p>
                        <a href="https://vinpearl.com/vi/gioi-thieu-ve-phu-quoc-cap-nhat-moi-va-chi-tiet-nhat" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>

                <!-- Destination 8 -->
                <div class="destination-item reverse">
                    <div class="destination-image">
                        <img src="img/phuquoc1.jpg" alt="Phú Quốc">
                    </div>
                    <div class="destination-info">
                        <h2 class="destination-title">Phú Quốc</h2>
                        <p class="destination-description">
                            Phú Quốc là một hòn đảo xinh đẹp thuộc tỉnh Kiên Giang, nổi tiếng với những bãi biển cát trắng và nước biển trong xanh. Phú Quốc nổi tiếng với những bãi biển cát trắng, nước biển trong xanh và hải sản tươi ngon. Phú Quốc là điểm đến lý tưởng cho những ai yêu thích biển và các hoạt động ngoài trời.
                        </p>
                        <a href="https://vinpearl.com/vi/gioi-thieu-ve-phu-quoc-cap-nhat-moi-va-chi-tiet-nhat" class="read-more-btn">Đọc thêm</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials-section">
            <div class="container">
                <h2 class="section-title">Khách hàng nói gì về chúng tôi</h2>
                <div class="testimonials-grid">
                    <!-- Testimonial 1 -->
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <img src="img/chenfeiyu.jpg" alt="Chen FeiYu">
                            </div>
                            <div class="testimonial-info">
                                <div class="testimonial-name">Chen FeiYu</div>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p>Chuyến đi của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi. Tôi sẽ quay lại đây trong những chuyến đi tiếp theo. Dịch vụ tuyệt vời và giá cả hợp lý.</p>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <img src="img/chenfeiyu.jpg" alt="Chen FeiYu">
                            </div>
                            <div class="testimonial-info">
                                <div class="testimonial-name">Chen FeiYu</div>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p>Chuyến đi của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi. Tôi sẽ quay lại đây trong những chuyến đi tiếp theo. Dịch vụ tuyệt vời và giá cả hợp lý.</p>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <img src="img/chenfeiyu.jpg" alt="Chen FeiYu">
                            </div>
                            <div class="testimonial-info">
                                <div class="testimonial-name">Chen FeiYu</div>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p>Chuyến đi của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi. Tôi sẽ quay lại đây trong những chuyến đi tiếp theo. Dịch vụ tuyệt vời và giá cả hợp lý.</p>
                        </div>
                    </div>

                    <!-- Testimonial 4 -->
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <img src="img/chenfeiyu.jpg" alt="Chen FeiYu">
                            </div>
                            <div class="testimonial-info">
                                <div class="testimonial-name">Chen FeiYu</div>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p>Chuyến đi của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi. Tôi sẽ quay lại đây trong những chuyến đi tiếp theo. Dịch vụ tuyệt vời và giá cả hợp lý.</p>
                        </div>
                    </div>

                    <!-- Testimonial 5 -->
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <img src="img/chenfeiyu.jpg" alt="Chen FeiYu">
                            </div>
                            <div class="testimonial-info">
                                <div class="testimonial-name">Chen FeiYu</div>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p>Chuyến đi của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi. Tôi sẽ quay lại đây trong những chuyến đi tiếp theo. Dịch vụ tuyệt vời và giá cả hợp lý.</p>
                        </div>
                    </div>

                    <!-- Testimonial 6 -->
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <img src="img/chenfeiyu.jpg" alt="Chen FeiYu">
                            </div>
                            <div class="testimonial-info">
                                <div class="testimonial-name">Chen FeiYu</div>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p>Chuyến đi của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi. Tôi sẽ quay lại đây trong những chuyến đi tiếp theo. Dịch vụ tuyệt vời và giá cả hợp lý.</p>
                        </div>
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