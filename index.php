<?php require_once 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaValle - Du lịch Việt Nam</title>
    <link rel="stylesheet" href="CSS/home.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-image">
            <img src="img/bannerhome.png" alt="Ha Long Bay" class="hero-bg">
        </div>
        <div class="hero-content">
            <div class="search-container">
                <form id="searchForm" class="search-box">
                    <div class="search-input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="khuvuc" class="search-select" required>
                            <option value="">Điểm đến</option>
                            <option value="Hà Nội">Hà Nội</option>
                            <option value="TP.HCM">TP. Hồ Chí Minh</option>
                            <option value="Hải Phòng">Hải Phòng</option>
                            <option value="Đà Nẵng">Đà Nẵng</option>
                            <option value="Cần Thơ">Cần Thơ</option>
                            <option value="An Giang">An Giang</option>
                            <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                            <option value="Bắc Giang">Bắc Giang</option>
                            <option value="Bắc Kạn">Bắc Kạn</option>
                            <option value="Bạc Liêu">Bạc Liêu</option>
                            <option value="Bắc Ninh">Bắc Ninh</option>
                            <option value="Bến Tre">Bến Tre</option>
                            <option value="Bình Định">Bình Định</option>
                            <option value="Bình Dương">Bình Dương</option>
                            <option value="Bình Phước">Bình Phước</option>
                            <option value="Bình Thuận">Bình Thuận</option>
                            <option value="Cà Mau">Cà Mau</option>
                            <option value="Cao Bằng">Cao Bằng</option>
                            <option value="Đắk Lắk">Đắk Lắk</option>
                            <option value="Đắk Nông">Đắk Nông</option>
                            <option value="Điện Biên">Điện Biên</option>
                            <option value="Đồng Nai">Đồng Nai</option>
                            <option value="Đồng Tháp">Đồng Tháp</option>
                            <option value="Gia Lai">Gia Lai</option>
                            <option value="Hà Giang">Hà Giang</option>
                            <option value="Hà Nam">Hà Nam</option>
                            <option value="Hà Tĩnh">Hà Tĩnh</option>
                            <option value="Hải Dương">Hải Dương</option>
                            <option value="Hậu Giang">Hậu Giang</option>
                            <option value="Hòa Bình">Hòa Bình</option>
                            <option value="Khánh Hòa">Khánh Hòa</option>
                            <option value="Kiên Giang">Kiên Giang</option>
                            <option value="Kon Tum">Kon Tum</option>
                            <option value="Lai Châu">Lai Châu</option>
                            <option value="Lâm Đồng">Lâm Đồng</option>
                            <option value="Lạng Sơn">Lạng Sơn</option>
                            <option value="Lào Cai">Lào Cai</option>
                            <option value="Long An">Long An</option>
                            <option value="Nam Định">Nam Định</option>
                            <option value="Nghệ An">Nghệ An</option>
                            <option value="Ninh Bình">Ninh Bình</option>
                            <option value="Ninh Thuận">Ninh Thuận</option>
                            <option value="Phú Thọ">Phú Thọ</option>
                            <option value="Phú Yên">Phú Yên</option>
                            <option value="Quảng Bình">Quảng Bình</option>
                            <option value="Quảng Nam">Quảng Nam</option>
                            <option value="Quảng Ngãi">Quảng Ngãi</option>
                            <option value="Quảng Ninh">Quảng Ninh</option>
                            <option value="Quảng Trị">Quảng Trị</option>
                            <option value="Sóc Trăng">Sóc Trăng</option>
                            <option value="Sơn La">Sơn La</option>
                            <option value="Tây Ninh">Tây Ninh</option>
                            <option value="Thái Bình">Thái Bình</option>
                            <option value="Thái Nguyên">Thái Nguyên</option>
                            <option value="Thanh Hóa">Thanh Hóa</option>
                            <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                            <option value="Tiền Giang">Tiền Giang</option>
                            <option value="Trà Vinh">Trà Vinh</option>
                            <option value="Tuyên Quang">Tuyên Quang</option>
                            <option value="Vĩnh Long">Vĩnh Long</option>
                            <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                            <option value="Yên Bái">Yên Bái</option>
                        </select>
                    </div>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                        TÌM KIẾM NGAY
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main">
        <div class="content-section">
            <div class="section-subtitle">Khách sạn hàng đầu</div>
            <div class="rotating-title">
                <div class="title-item">Đặt phòng dễ dàng, trải nghiệm hoàn hảo.</div>
                <div class="title-item">Nơi kỳ nghỉ của bạn bắt đầu.</div>
                <div class="title-item">Chọn phòng chuẩn – nghỉ dưỡng chuẩn.</div>
                <div class="title-item">Giá tốt mỗi ngày, đặt ngay hôm nay.</div>
                <div class="title-item">Hành trình của bạn, phòng nghỉ của chúng tôi.</div>
            </div>
            <div class="divider"></div>
        </div>

        <!-- Travel Partners Section -->
        <section class="partners-section">
            <div class="container">
                <div class="hotels-grid" id="featuredHotels">
                    <!-- Hotels will be loaded here -->
                </div>
            </div>
        </section>

        <!-- All-Inclusive Hotels Section -->
        <section class="all-inclusive-section">
            <div class="container">
                <h2 class="section-title">KHÁCH SẠN TRỌN GÓI</h2>
                <div class="hotels-grid-large" id="allHotels">
                    <!-- Hotels will be loaded here -->
                </div>
            </div>
        </section>

        <!-- Destinations Section -->
        <section class="destinations-section">
            <div class="container">
                <h2 class="section-title">ĐIỂM ĐẾN HẤP DẪN</h2>
                <div class="destinations-grid">
                    <div class="destination-card large" onclick="searchByLocation('Đà Nẵng')">
                        <img src="img/Danang.jpg" alt="Destination">
                        <div class="destination-overlay">
                            <span class="destination-name">ĐÀ NẴNG</span>
                        </div>
                    </div>
                    <div class="destination-card medium" onclick="searchByLocation('Kiên Giang')">
                        <img src="img/phuquoc1.jpg" alt="Destination">
                        <div class="destination-overlay">
                            <span class="destination-name">PHÚ QUỐC</span>
                        </div>
                    </div>
                    <div class="destination-card medium" onclick="searchByLocation('Khánh Hòa')">
                        <img src="img/nhatrang.jpg" alt="Destination">
                        <div class="destination-overlay">
                            <span class="destination-name">NHA TRANG</span>
                        </div>
                    </div>
                    <div class="destination-card small" onclick="searchByLocation('Hồ Chí Minh')">
                        <img src="img/saigon.jpg" alt="Destination">
                        <div class="destination-overlay">
                            <span class="destination-name">SÀI GÒN</span>
                        </div>
                    </div>
                    <div class="destination-card small" onclick="searchByLocation('Hội An')">
                        <img src="img/hoian.jpg" alt="Destination">
                        <div class="destination-overlay">
                            <span class="destination-name">HỘI AN</span>
                        </div>
                    </div>
                    <div class="destination-card small" onclick="searchByLocation('Đà Lạt')">
                        <img src="img/dalat.jpg" alt="Destination">
                        <div class="destination-overlay">
                            <span class="destination-name">ĐÀ LẠT</span>
                        </div>
                    </div>
                    <div class="destination-card small" onclick="searchByLocation('Hạ Long')">
                        <img src="img/halong.jpg" alt="Destination">
                        <div class="destination-overlay">
                            <span class="destination-name">HẠ LONG</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Company Info Section -->
        <section class="company-info-section">
            <div class="container">
                <div class="company-info-content">
                    <div class="company-images">
                        <div class="image-group">
                            <img src="img/dulich1.jpg" alt="Resort" class="company-img-1">
                            <img src="img/dulich2.jpg" alt="Beach" class="company-img-2">
                        </div>
                    </div>
                    <div class="company-text">
                        <p class="company-subtitle">KINH NGHIỆM KHÁCH SẠN</p>
                        <h2 class="company-title">NHANH CHÓNG - UY TÍN - TIỆN LỢI</h2>
                        <p class="company-description">
                            Với sứ mệnh mang những trải nghiệm du lịch tuyệt vời đến với mọi người, chúng tôi luôn nỗ lực để mang đến những dịch vụ tốt nhất. Từ việc tìm kiếm khách sạn phù hợp đến việc đặt phòng nhanh chóng, chúng tôi cam kết mang đến sự hài lòng cho khách hàng.
                        </p>
                        <div class="company-stats">
                            <div class="stat-item">
                                <div class="stat-number">100+</div>
                                <div class="stat-label">Khách sạn</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">172</div>
                                <div class="stat-label">Khách hàng</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">88</div>
                                <div class="stat-label">Phương tiện</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">32M+</div>
                                <div class="stat-label">Khách hàng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>

    <script>
        // Rotating titles functionality
        let currentTitleIndex = 0;
        const titleItems = document.querySelectorAll('.title-item');
        
        function rotateTitle() {
            // Remove active class from all items
            titleItems.forEach(item => item.classList.remove('active'));
            
            // Add active class to current item
            titleItems[currentTitleIndex].classList.add('active');
            
            // Move to next title
            currentTitleIndex = (currentTitleIndex + 1) % titleItems.length;
            
            // Schedule next rotation
            setTimeout(rotateTitle, 4500);
        }

        // Start rotation when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            rotateTitle();
        });

        // Add scroll animation observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.8s ease-out forwards';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Add animations to slideshow
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }

            @keyframes pulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
            }

            .pulse-animation {
                animation: pulse 2s ease-in-out infinite;
            }
        `;
        document.head.appendChild(style);
     
        // Rotating title slogan
        let currentSlogan = 0;
        const slogans = document.querySelectorAll('.title-item');
        const sloganInterval = 4500; // Thời gian hiển thị mỗi slogan (ms)

        function showSlogan(index) {
            slogans.forEach((slogan, i) => {
                slogan.style.opacity = '0';
                slogan.style.animation = 'none';
            });
            
            setTimeout(() => {
                slogans[index].style.animation = 'slideDownFadeOut 4.5s ease-in-out forwards';
            }, 50);
        }

        function rotateSlogan() {
            currentSlogan = (currentSlogan + 1) % slogans.length;
            showSlogan(currentSlogan);
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadFeaturedHotels();
            loadAllHotels();

            // Initialize first slogan
            if (slogans.length > 0) {
                showSlogan(0);
                setInterval(rotateSlogan, sloganInterval);
            }

            // Observe all hotel cards and destination cards
            document.querySelectorAll('.hotel-card, .destination-card').forEach(el => {
                observer.observe(el);
            });
        });

        
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const khuvuc = this.khuvuc.value;
            if (khuvuc) {
                window.location.href = `khachsan2.php?khuvuc=${encodeURIComponent(khuvuc)}`;
            } else {
                alert('Vui lòng chọn điểm đến');
            }
        });

        
        function searchByLocation(location) {
            window.location.href = `khachsan2.php?khuvuc=${encodeURIComponent(location)}`;
        }

        
        function loadFeaturedHotels() {
            fetch('search_hotels.php?limit=3')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('featuredHotels');
                        container.innerHTML = data.hotels.slice(0, 3).map(hotel => createHotelCard(hotel)).join('');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Load all hotels
        function loadAllHotels() {
            fetch('search_hotels.php?limit=6')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('allHotels');
                        container.innerHTML = data.hotels.slice(0, 6).map(hotel => createHotelCard(hotel)).join('');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Create hotel card HTML
        function createHotelCard(hotel) {
            const stars = generateStars(hotel.diemdg);
            return `
                <div class="hotel-card">
                    <div class="hotel-image">
                        <img src="${hotel.anhmain || '/placeholder.svg?height=200&width=300'}" alt="${hotel.Ten}">
                        <span class="sale-badge">HOT</span>
                    </div>
                    <div class="hotel-info">
                        <h3 class="hotel-name">${hotel.Ten}</h3>
                        <div class="hotel-price">
                           <span class="price">Từ ${hotel.price ? Number(hotel.price).toLocaleString('vi-VN') + ' VNĐ' : 'Liên hệ'}</span>
                            <span class="duration">/ 3N2 Đêm</span>
                        </div>
                        <div class="hotel-rating">
                            <div class="stars">${stars}</div>
                            <span class="rating">${hotel.diemdg}</span>
                        </div>
                        <button class="view-more-btn" onclick="window.location.href='chitietkhachsan.php?id=${hotel.MaKS}'">Xem thêm</button>
                    </div>
                </div>
            `;
        }

        function generateStars(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            
            return '<i class="fas fa-star"></i>'.repeat(fullStars) +
                   (hasHalfStar ? '<i class="fas fa-star-half-alt"></i>' : '') +
                   '<i class="far fa-star"></i>'.repeat(emptyStars);
        }
    </script>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>