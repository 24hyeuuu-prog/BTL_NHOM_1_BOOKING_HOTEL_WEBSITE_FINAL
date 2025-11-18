<?php require_once 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaValle - Khách sạn</title>
    <link rel="stylesheet" href="CSS/khachsan2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-image">
            <img src="img/banner2.jpg" alt="Resort Pool" class="hero-bg">
        </div>
        <div class="hero-content">
            <div class="search-container">
                <form id="searchForm" class="search-box">
                    <div class="search-input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="khuvuc" class="search-select">
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
                    <div class="search-input-group">
                        <i class="fas fa-tag"></i>
                        <select name="hangkhachsan" class="search-select">
                            <option value="">Phân khúc</option>
                            <option value="sang trọng">Sang trọng</option>
                            <option value="cao cấp">Cao cấp</option>
                            <option value="bình dân">Bình dân</option>
                        </select>
                    </div>
                    <div class="search-input-group">
                        <i class="fas fa-star"></i>
                        <select name="xemhang" class="search-select">
                            <option value="">Đánh giá của du khách</option>
                            <option value="xuất sắc">Xuất sắc</option>
                            <option value="tốt">Tốt</option>
                            <option value="bình thường">Bình thường</option>
                            <option value="kém">Kém</option>
                            <option value="rất tệ">Rất tệ</option>
                        </select>
                    </div>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                        TÌM KIẾM
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Service Icons Section -->
    <section class="services-section">
        <div class="container">
            <div class="services-grid">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="service-text">
                        <h4>BOOK PHÒNG NHANH</h4>
                        <p>Hệ thống đặt phòng nhanh chóng, thuận tiện cho khách hàng</p>
                    </div>
                </div>
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="service-text">
                        <h4>NỘI DUNG UY TÍN</h4>
                        <p>Đảm bảo thông tin chính xác và đánh giá khách quan</p>
                    </div>
                </div>
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="service-text">
                        <h4>CHI PHÍ HỢP LÝ</h4>
                        <p>Giá cả hợp lý, minh bạch, không phát sinh chi phí ẩn</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Section -->
    <section class="results-section">
        <div class="container">
            <h2 class="section-title">KẾT QUẢ CHO BẠN</h2>
            <div id="loadingMessage" style="text-align: center; padding: 20px;">Đang tải...</div>
            <div class="hotels-list" id="hotelsList">
                <!-- Default message when page loads -->
                <div class="no-results-container">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="no-results-title">Không có khách sạn yêu cầu</div>
                    <div class="no-results-message">
                        Vui lòng chọn tiêu chí tìm kiếm và nhấn nút "TÌM KIẾM" để bắt đầu.
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-dots" id="pagination">
                    <!-- Pagination will be generated here -->
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>

    <script>
        let currentPage = 1;
        let allHotels = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Get URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            
            // Set form values from URL parameters
            if (urlParams.get('khuvuc')) {
                document.querySelector('select[name="khuvuc"]').value = urlParams.get('khuvuc');
            }
            if (urlParams.get('hangkhachsan')) {
                document.querySelector('select[name="hangkhachsan"]').value = urlParams.get('hangkhachsan');
            }
            if (urlParams.get('xemhang')) {
                document.querySelector('select[name="xemhang"]').value = urlParams.get('xemhang');
            }
            
            // Load hotels based on parameters
            searchHotels();
        });

        // Handle search form submission
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            searchHotels();
        });

        function searchHotels() {
            const formData = new FormData(document.getElementById('searchForm'));
            const params = new URLSearchParams(formData).toString();
            
            document.getElementById('loadingMessage').style.display = 'block'; // Hiển thị thông báo đang tải
            
            fetch('search_hotels.php?' + params)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingMessage').style.display = 'none';
                    if (data.success) {
                        allHotels = data.hotels;
                        document.getElementById('hotelsList').innerHTML = '';  // Xóa nội dung cũ khi có kết quả
                        displayHotels(1);
                        createPagination();
                    } else {
                        document.getElementById('hotelsList').innerHTML = '<div class="no-results-container"><div class="no-results-icon"><i class="fas fa-search"></i></div><div class="no-results-title">Không có khách sạn yêu cầu</div><div class="no-results-message">Không tìm thấy khách sạn nào phù hợp với tiêu chí tìm kiếm của bạn.</div></div>';
                    }
                })
                .catch(error => {
                    document.getElementById('loadingMessage').style.display = 'none';
                    console.error('Error:', error);
                    document.getElementById('hotelsList').innerHTML = '<p style="text-align: center;">Có lỗi xảy ra khi tải dữ liệu.</p>';
                });
        }

        function displayHotels(page) {
            const hotelsPerPage = 6;
            const start = (page - 1) * hotelsPerPage;
            const end = start + hotelsPerPage;
            const hotelsToShow = allHotels.slice(start, end);
            
            const container = document.getElementById('hotelsList');
            container.innerHTML = hotelsToShow.map(hotel => createHotelCard(hotel)).join('');
            currentPage = page;
        }

        function createHotelCard(hotel) {
            const stars = generateStars(hotel.diemdg);
            const amenities = hotel.motachitiet ? hotel.motachitiet.split(',').slice(0, 4) : [];
            
            return `
                <div class="hotel-card-new">
                    <div class="hotel-image-container">
                        <img src="${hotel.anhmain || '/placeholder.svg?height=280&width=400'}" alt="${hotel.Ten}" class="hotel-image-new">
                        <div class="hotel-badges">
                            <span class="category-badge">${hotel.hangkhachsan}</span>
                            <span class="availability-badge">Còn Phòng</span>
                        </div>
                    </div>
                    <div class="hotel-content">
                        <div class="hotel-main-info">
                            <h3 class="hotel-name-new">${hotel.Ten}</h3>
                            <div class="hotel-rating-new">
                                <div class="stars-new">${stars}</div>
                                <span class="rating-number">${hotel.diemdg}</span>
                            </div>
                            <div class="hotel-price-new">
                                <i class="fas fa-tag"></i>
                                 <span class="price-amount">${hotel.price ? Number(hotel.price).toLocaleString('vi-VN') + ' VND' : '2.300.000 VND'}</span>
                            </div>
                            <div class="hotel-location-new">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${hotel.khuvuc}</span>
                            </div>
                            <p class="hotel-description-new">${hotel.mota || ''}</p>
                            <div class="hotel-amenities-new">
                                ${[hotel.giatri1, hotel.giatri2, hotel.giatri3, hotel.giatri4].filter(v => v).map(value => `
                                    <div class="amenity-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>${value}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="hotel-action">
                            <button class="detail-btn-new" onclick="window.location.href='chitietkhachsan.php?id=${hotel.MaKS}'">Xem thêm</button>
                        </div>
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

        function createPagination() {
            const totalPages = Math.ceil(allHotels.length / 6);
            const paginationContainer = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }
            
            let paginationHTML = '';
            for (let i = 1; i <= Math.min(totalPages, 5); i++) {
                paginationHTML += `<span class="dot ${i === currentPage ? 'active' : ''}" onclick="displayHotels(${i})"></span>`;
            }
            
            paginationContainer.innerHTML = paginationHTML;
        }
    </script>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>