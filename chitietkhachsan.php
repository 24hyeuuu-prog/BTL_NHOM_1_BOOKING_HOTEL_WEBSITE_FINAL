<?php 
require_once 'config/config.php';

$hotelId = intval($_GET['id'] ?? 0);
if ($hotelId <= 0) {
    header('Location: khachsan2.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết khách sạn - LaValle</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> 
    <link rel="stylesheet" href="CSS/chitietkhachsan3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hotel Header Section -->
        <section class="hotel-header">
            <div class="container">
                <div class="hotel-header-content" id="hotelHeader">
                    <!-- Hotel header will be loaded here -->
                </div>
            </div>
        </section>

        <!-- Location Section -->
        <section class="location-section">
            <div class="container">
                <h2 class="section-title">VỊ TRÍ</h2>
                <div class="map-container" id="mapContainer">
                    <!-- Map will be loaded here -->
                </div>
            </div>
        </section>

        <!-- Introduction Section -->
        <section class="introduction-section">
            <div class="container">
                <h2 class="section-title">GIỚI THIỆU</h2>
                <div class="intro-content" id="introContent">
                </div>
            </div>
        </section>

        <section class="reservation-section">
            <div class="container">
                <h2 class="section-title" id="phongtrong">PHÒNG TRỐNG</h2>
                    <div class="room-search">
                        <span> Ngày bắt đầu: <input type="date" id="checkInDate" class="date-input" required>  --   Ngày kết thúc: <input type="date" id="checkOutDate" class="date-input" required> </span>
                        <span> Số lượng khách: <input type="number" id="adultsCount" class="number-input" min="1" value="1" required> Người lớn <input type="number" id="childrenCount" class="number-input" min="0" value="0"> Trẻ em </span>
                        <button type="button" id="searchRoomsBtn" class="search-btn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Tìm kiếm
                        </button>
                    </div>
                <div id="roomsListContainer">
                <p class="search-title">Tất cả phòng còn trống</p>
                <table>
                    <tr>
                        <th width="18%">LOẠI PHÒNG</th>
                        <th width="12%">SỐ LƯỢNG KHÁCH</th>
                        <th width="12%">ĐƠN GIÁ</th>
                        <th width="20%">CÁC LỰA CHỌN</th>
                        <th width="10%">GHI CHÚ</th>
                        <th width="28%">CHỌN PHÒNG</th>
                    </tr>
                    <tbody id="roomsTableBody">
                        
                    </tbody>
                </table>
                </div>
            </div>
        </section>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">PHÒNG ĐÔI</h5>
                                     <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                            <div class="offcanvas-body">
                                <div>
                                    <div class="room-gallery">
                    <div class="main-room-image">
                        <img src="img/vungtau.jpg" alt="..." class="main-hotel-image">
                    </div>
                    <div class="small-imagine">
                    <div class="room-thumbnail-images">
                       <img src="img/vungtau.jpg" alt="Hotel Image" class="thumbnail" >
                    </div>
                    <div class="room-thumbnail-images">
                       <img src="img/vungtau.jpg" alt="Hotel Image" class="thumbnail" >
                    </div>
                    <div class="room-thumbnail-images">
                       <img src="img/vungtau.jpg" alt="Hotel Image" class="thumbnail" >
                    </div>
                    <div class="room-thumbnail-images">
                       <img src="img/vungtau.jpg" alt="Hotel Image" class="thumbnail" >
                    </div>
                </div>
                </div>
                                </div>
                            <div class="room-introduction">
                        <p class="room-size"><b>Kích thước phòng:</b> 23m vuông</p>
                        <br>
                        <p class="room-detail">Phòng được trang bị máy điều hòa và có tầm nhìn tuyệt đẹp ra thành phố. Không gian yên tĩnh nhờ cửa sổ cách âm, cùng với TV màn hình phẳng truyền hình vệ tinh, ghế sofa thoải mái và minibar tiện nghi. Phòng tắm riêng hiện đại có vòi sen, máy sấy tóc và đồ vệ sinh cá nhân miễn phí. Khách còn được phục vụ trà, cà phê, hoa tươi và trái cây miễn phí trong phòng.</p>
                        <br>
                        <p class="room-advantage"><b>Tiện nghi:</b>
                            <ul>
                                <li>Điều hòa không khí</li>
                                <li>Ghế sofa</li>
                                <li>Ga trải giường</li>
                                <li>Bàn làm việc</li>
                                <li>Hệ thống cách âm</li>
                                <li>TV</li>
                                <li>Tủ lạnh</li>
                                <li>Điện thoại</li>
                                <li>TV màn hình phẳng</li>
                                <li>Minibar</li>
                                <li>Ấm đun nước điện</li>
                                <li>Dịch vụ báo thức</li>
                            </ul>
                        </p>
                        </div>
                    </div>
                            </div>
        <!-- Reviews Section -->
        <section class="reviews-section">
            <div class="container">
                <div class="reviews-header">
                    <h2 class="section-title">ĐÁNH GIÁ</h2>
                    <div class="reviews-summary">
                        <span id="reviewsCount">Tất cả đánh giá (0)</span>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <button class="write-review-btn" onclick="window.location.href='danhgia.php?id=<?php echo $hotelId; ?>'">
                                <i class="fas fa-pen"></i>
                                VIẾT ĐÁNH GIÁ
                            </button>
                        <?php else: ?>
                            <button class="write-review-btn" onclick="alert('Vui lòng đăng nhập để viết đánh giá')">
                                <i class="fas fa-pen"></i>
                                VIẾT ĐÁNH GIÁ
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="reviews-content" id="reviewsContent">
                    <!-- Reviews content will be loaded here -->
                </div>
            </div>
        </section>

        <!-- Recommendations Section -->
        <section class="recommendations-section">
            <div class="container">
                <h2 class="section-title">BẠN CÓ THỂ THÍCH</h2>
                <div class="recommendations-grid" id="recommendationsGrid">
                    <!-- Recommendations will be loaded here -->
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        const hotelId = <?php echo $hotelId; ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            loadHotelDetails();
            loadRecommendations();
        });

        function loadHotelDetails() {
            fetch(`get_hotel_details.php?id=${hotelId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('API Response:', data);
                    if (data.success) {
                        displayHotelHeader(data.hotel);
                        displayMap(data.hotel);
                        displayIntroduction(data.hotel);
                        displayReviews(data.reviews, data.rating_stats, data.total_reviews);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi tải dữ liệu');
                });
        }

        function displayHotelHeader(hotel) {
            const stars = generateStars(hotel.diemdg);
            const images = [hotel.anh1, hotel.anh2, hotel.anh3, hotel.anh4].filter(img => img);
            
            document.getElementById('hotelHeader').innerHTML = `
                <div class="hotel-gallery">
                    <div class="main-image">
                        <img src="${hotel.anhmain || '/placeholder.svg?height=400&width=600'}" alt="${hotel.Ten}" class="main-hotel-image">
                    </div>
                    <div class="thumbnail-images">
                        ${images.map(img => `<img src="${img}" alt="Hotel Image" class="thumbnail" onclick="changeMainImage('${img}')">`).join('')}
                    </div>
                </div>
                <div class="hotel-info-header">
                    <h1 class="hotel-title">${hotel.Ten}</h1>
                    <div class="hotel-rating-header">
                        <div class="stars-header">${stars}</div>
                        <span class="rating-number-header">${hotel.diemdg}</span>
                    </div>
                    <div class="hotel-price-header">
                        <span class="price-label">Từ</span>
                         <span class="price-amount-header">${hotel.price ? Number(hotel.price).toLocaleString('vi-VN') + ' VND' : 'Liên hệ'}</span>
                    </div>
                    <div class="hotel-location-header">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${hotel.khuvuc}</span>
                    </div>
                    <p class="hotel-description-header" id="hotelMotaContent"></p>
                    <a href="#phongtrong"><button class="check-rooms-btn" >ĐẶT PHÒNG </button> </a>
                </div>
            `;
            // Set HTML content for mota after the template
            document.getElementById('hotelMotaContent').innerHTML = hotel.mota || '';
        } //Nút xem phòng trống ở trên

        function displayMap(hotel) {
            const mapContainer = document.getElementById('mapContainer');
            
            // Kiểm tra nếu khách sạn có dữ liệu bản đồ trong database
            if (hotel.vitri && hotel.vitri.trim() !== '') { //phương thức trim để loại bỏ khoảng trắng
                // Hiển thị iframe từ database 
                mapContainer.innerHTML = hotel.vitri;
            } else {
                // Nếu chưa có dữ liệu bản đồ, hiển thị thông báo
                mapContainer.innerHTML = `
                    <div style="
                        height: 450px; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center; 
                        background: #f8f9fa; 
                        border-radius: 15px; 
                        color: #666;
                        text-align: center;
                        flex-direction: column;
                        gap: 15px;
                    ">
                        <i class="fas fa-map-marker-alt" style="font-size: 48px; color: #2196F3;"></i>
                        <div>
                            <h4 style="margin: 0 0 10px 0; color: #333;">Đang cập nhật vị trí</h4>
                            <p style="margin: 0; font-size: 14px;">
                                Vị trí: <strong>${hotel.Ten}</strong><br>
                                Khu vực: <strong>${hotel.khuvuc}</strong>
                            </p>
                        </div>
                    </div>
                `;
            }
        }

        function displayIntroduction(hotel) {
            const amenities = hotel.motachitiet ? hotel.motachitiet.split(',') : [];
            const hotelAmenities = amenities.slice(0, 6); // Hiển trhij 6 cái đầu tiên là tiện nghi khách sạn
            const roomAmenities = amenities.slice(6, 12); // Hiển thị 6 cái tiếp theo là tiện nghi phòng
            const roomFeatures = amenities.slice(12); // Hiển thị các tiện ích khác
            
            document.getElementById('introContent').innerHTML = `
                <div class="rating-overview">
                    <div class="overall-rating">
                        <div class="rating-number-large">${hotel.diemdg}</div>
                        <div class="rating-label">${hotel.xemhang ? hotel.xemhang.toUpperCase() : getRankingFromScore(hotel.diemdg).toUpperCase()}</div>  
                        <div class="stars-large">${generateStars(hotel.diemdg)}</div>
                        <div class="total-reviews" id="totalReviewsText">(0) đánh giá</div>
                    </div>
                    <div class="rating-categories" id="ratingCategories">
                        <!-- Rating categories will be populated -->
                    </div>
                </div>
                <div class="hotel-features">
                    <div class="features-column">
                        <h4>Tiện nghi khách sạn</h4>
                        <ul class="features-list">
                            ${hotelAmenities.map(amenity => `<li><i class="fas fa-check"></i> ${amenity.trim()}</li>`).join('')} 
                        </ul>
                    </div>
                    <div class="features-column">
                        <h4>Tiện nghi phòng</h4>
                        <ul class="features-list">
                            ${roomAmenities.map(amenity => `<li><i class="fas fa-check"></i> ${amenity.trim()}</li>`).join('')}
                        </ul>
                    </div>
                    <div class="features-column">
                        <h4>Tiện ích khác</h4>
                        <ul class="features-list">
                            ${[hotel.giatri1, hotel.giatri2, hotel.giatri3, hotel.giatri4].filter(v => v).map(value => `<li><i class="fas fa-check"></i> ${value}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
        }

        function displayReviews(reviews, ratingStats, totalReviews) {
            document.getElementById('reviewsCount').textContent = `Tất cả đánh giá (${totalReviews})`;
            document.getElementById('totalReviewsText').textContent = `(${totalReviews}) đánh giá`;
            
            const avgRating = reviews.length > 0 ? (reviews.reduce((sum, r) => sum + parseFloat(r.diemreview), 0) / reviews.length).toFixed(1) : 0;
            
            const reviewsHTML = `
                <div class="rating-breakdown">
                    <div class="rating-stats">
                        <div class="rating-number-big">${avgRating}</div>
                        <div class="rating-details">
                            ${Object.entries(ratingStats).map(([rating, count]) => {
                                const percentage = totalReviews > 0 ? (count / totalReviews * 100) : 0;
                                return `
                                    <div class="rating-row">
                                        <span>${rating}</span>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: ${percentage}%"></div>
                                        </div>
                                        <span>(${count})</span>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                </div>
                
                <div class="reviews-list">
                    ${reviews.slice(0, 3).map(review => `
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        <img src="${review.linkavatar}" alt="${review.Tendangnhap}">
                                    </div>
                                    <div class="reviewer-details">
                                        <div class="reviewer-name">${review.Tendangnhap}</div>
                                        <div class="reviewer-location">Loại chuyến đi: ${review.mucdich || 'Không xác định'}</div>
                                    </div>
                                </div>
                                <div class="review-meta">
                                    <div class="review-rating">
                                        ${generateStars(review.diemreview)}
                                        <span class="review-score">${getScoreText(review.diemreview)}</span>
                                    </div>
                                    <div class="review-date">${formatDate(review.thoigian)}</div>
                                </div>
                            </div>
                            <div class="review-content">
                                <h4 class="review-title">${review.tieude}</h4>
                                <p>${review.noidung}</p>
                            </div>
                        </div>
                    `).join('')}
                    
                    ${reviews.length > 3 ? `
                        <div class="view-all-reviews-container">
                            <button class="view-all-reviews-btn" onclick="showAllReviews()">XEM TẤT CẢ ĐÁNH GIÁ</button>
                        </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('reviewsContent').innerHTML = reviewsHTML;
        }

        function loadRecommendations() {
            fetch(`get_recommendations.php?exclude=${hotelId}&limit=3`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const recommendationsHTML = data.recommendations.map(hotel => `
                            <div class="recommendation-card">
                                <div class="recommendation-image">
                                    <img src="${hotel.anhmain || '/placeholder.svg?height=200&width=300'}" alt="${hotel.Ten}">
                                    <span class="sale-badge">HOT</span>
                                </div>
                                <div class="recommendation-info">
                                    <h3 class="recommendation-name">${hotel.Ten}</h3>
                                    <div class="recommendation-price">
                                        <span class="price">Từ ${hotel.price}</span>
                                        <span class="duration">/ 3N2 Đêm</span>
                                    </div>
                                    <div class="recommendation-rating">
                                        <div class="stars">${generateStars(hotel.diemdg)}</div>
                                    </div>
                                    <button class="view-more-btn" onclick="window.location.href='chitietkhachsan.php?id=${hotel.MaKS}'">Xem thêm</button>
                                </div>
                            </div>
                        `).join('');
                        
                        document.getElementById('recommendationsGrid').innerHTML = recommendationsHTML;
                    }
                })
                .catch(error => console.error('Error loading recommendations:', error));
        }

        function generateStars(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            
            return '<i class="fas fa-star"></i>'.repeat(fullStars) +
                   (hasHalfStar ? '<i class="fas fa-star-half-alt"></i>' : '') +
                   '<i class="far fa-star"></i>'.repeat(emptyStars);
        }
        function getRankingFromScore(score) {
    if (score >= 4.5) return 'xuất sắc';
    else if (score >= 4.0) return 'tốt';
    else if (score >= 2.5) return 'bình thường';
    else if (score >= 1.5) return 'kém';
    else return 'rất tệ';
    }

        function getScoreText(score) {
            if (score >= 4.5) return 'Xuất sắc';
            if (score >= 4.0) return 'Tốt';
            if (score >= 2.5) return 'Bình thường';
            if (score >= 1.5) return 'Kém';
            return 'Rất tệ';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN');
        }

        function changeMainImage(src) {
            document.querySelector('.main-hotel-image').src = src;
        }

        function showAllReviews() {
            // Implementation for showing all reviews
            alert('Chức năng xem tất cả đánh giá sẽ được triển khai');
        }

        // Room booking search functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today for check-in
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('checkInDate').min = today;
            document.getElementById('checkOutDate').min = today;

            // Handle search rooms button click
            document.getElementById('searchRoomsBtn').addEventListener('click', function(e) {
                e.preventDefault();
                searchAvailableRooms();
            });
        });

        function searchAvailableRooms() {
            const checkIn = document.getElementById('checkInDate').value;
            const checkOut = document.getElementById('checkOutDate').value;
            const adults = parseInt(document.getElementById('adultsCount').value) || 0;
            const children = parseInt(document.getElementById('childrenCount').value) || 0;
            const totalGuests = adults + children;

            // Validation 1: Check if dates are entered
            if (!checkIn || !checkOut) {
                alert('Vui lòng nhập ngày bắt đầu và ngày kết thúc');
                return;
            }
            if (totalGuests <1) {
                alert('Phải bao gồm ít nhất 1 người lớn');
                return;
            }

            // Validation 2: Check if check-in date is not in the past
            const today = new Date().toISOString().split('T')[0];
            if (checkIn < today) {
                alert('Ngày bắt đầu không được nhỏ hơn ngày hôm nay');
                return;
            }

            // Validation 3: Check if check-out date is after check-in date
            if (checkIn >= checkOut) {
                alert('Ngày kết thúc phải sau ngày bắt đầu');
                return;
            }

            // Call API to get available rooms (filters out booked rooms automatically)
            fetch(`api/rooms.php?action=getAvailable&hotel_id=${hotelId}&check_in=${checkIn}&check_out=${checkOut}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data && data.data.length > 0) {
                        displayAvailableRooms(data.data, checkIn, checkOut, totalGuests);
                    } else if (data.success && (!data.data || data.data.length === 0)) {
                        document.getElementById('roomsTableBody').innerHTML = 
                            '<tr><td colspan="7" style="text-align: center; padding: 20px; color: #999; font-weight: bold;">Hết phòng trống trong khoảng thời gian này. Vui lòng chọn ngày khác!</td></tr>';
                    } else {
                        document.getElementById('roomsTableBody').innerHTML = 
                            '<tr><td colspan="7" style="text-align: center; padding: 20px; color: #d9534f;">Lỗi: ' + (data.message || 'Không thể tải danh sách phòng') + '</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    document.getElementById('roomsTableBody').innerHTML = 
                        '<tr><td colspan="7" style="text-align: center; padding: 20px; color: #d9534f;">Có lỗi xảy ra khi tải danh sách phòng. Vui lòng thử lại!</td></tr>';
                });
        }

        function displayAvailableRooms(rooms, checkIn, checkOut, totalGuests) {
            const roomsTableBody = document.getElementById('roomsTableBody');
            roomsTableBody.innerHTML = '';

            // Group rooms by type
            const groupedRooms = {};
            rooms.forEach(room => {
                if (!groupedRooms[room.room_type]) {
                    groupedRooms[room.room_type] = [];
                }
                groupedRooms[room.room_type].push(room);
            });

            let rowIndex = 0;
            // Display grouped rooms
            Object.keys(groupedRooms).forEach(roomType => {
                const roomList = groupedRooms[roomType];
                const firstRoom = roomList[0];
                
                // Generate capacity icons
                let icons = '';
                for (let i = 0; i < firstRoom.capacity; i++) {
                    icons += '<i class="fa-solid fa-user"></i>';
                }

                // Format price
                const priceFormatted = Number(firstRoom.price_per_night).toLocaleString('vi-VN');

                // Build amenities list
                const amenitiesList = firstRoom.amenities 
                    ? firstRoom.amenities.split(',').slice(0, 3).map(a => `<li>${a.trim()}</li>`).join('') 
                    : '<li>-</li>';

                // Generate checkboxes for each room number in this type
                const roomNumberCheckboxes = roomList.map(room => 
                    `<label class="room-checkbox-label">
                        <input type="checkbox" class="room-checkbox" 
                               data-room-id="${room.room_id}" 
                               data-room-number="${room.room_number}" 
                               data-room-type="${room.room_type}"
                               data-price="${room.price_per_night}">
                        <span>${room.room_number}</span>
                    </label>`
                ).join('');

                // Create table row
                const row = `
                    <tr>
                        <td>${roomType}</td>
                        <td><span>${icons}</span></td>
                        <td><p>${priceFormatted} VND</p></td>
                        <td>
                            <ul class="room-option">
                                ${amenitiesList}
                            </ul>
                        </td>
                        <td><p>Còn ${roomList.length} phòng</p></td>
                        <td class="room-numbers-container">
                            <div class="room-numbers">
                                ${roomNumberCheckboxes}
                            </div>
                        </td>
                    </tr>
                `;
                roomsTableBody.innerHTML += row;
                rowIndex++;
            });

            // Add footer row with booking button
            const footerRow = `
                <tr class="booking-footer">
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        <button class="submit-btn-1" type="button" onclick="proceedToMultipleBooking('${checkIn}', '${checkOut}', ${totalGuests})">
                            ĐẶT PHÒNG
                        </button>
                    </td>
                </tr>
            `;
            roomsTableBody.innerHTML += footerRow;
        }

        function proceedToMultipleBooking(checkIn, checkOut, guests) {
            // Check if user is logged in
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            
            if (!isLoggedIn) {
                alert('Vui lòng đăng nhập để đặt phòng');
                window.location.href = 'login.php';
                return;
            }

            // Collect all selected rooms
            const checkboxes = document.querySelectorAll('.room-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Vui lòng chọn ít nhất một phòng để đặt');
                return;
            }

            // Group selected rooms by room type and collect details
            const selectedRooms = {};
            const allRoomIds = [];
            
            checkboxes.forEach(checkbox => {
                const roomId = checkbox.getAttribute('data-room-id');
                const roomNumber = checkbox.getAttribute('data-room-number');
                const roomType = checkbox.getAttribute('data-room-type');
                const price = checkbox.getAttribute('data-price');
                
                if (!selectedRooms[roomType]) {
                    selectedRooms[roomType] = {
                        count: 0,
                        rooms: [],
                        firstRoomId: roomId,
                        price: price
                    };
                }
                
                selectedRooms[roomType].count++;
                selectedRooms[roomType].rooms.push({
                    room_id: roomId,
                    room_number: roomNumber
                });
                
                allRoomIds.push(roomId);
            });

            // Display summary
            let summary = 'Bạn đã chọn:\n';
            Object.keys(selectedRooms).forEach(type => {
                summary += `\n${type}: ${selectedRooms[type].count} phòng (${selectedRooms[type].rooms.map(r => r.room_number).join(', ')})`;
            });
            
            console.log('Selected rooms:', selectedRooms);
            console.log(summary);

            // Validate check-in date is not in the past (final check before redirect)
            const today = new Date().toISOString().split('T')[0];
            if (checkIn < today) {
                alert('Không thể đặt phòng cho ngày trong quá khứ');
                return;
            }

            // Collect all room numbers across all types
            const allRoomNumbers = [];
            Object.keys(selectedRooms).forEach(type => {
                selectedRooms[type].rooms.forEach(room => {
                    allRoomNumbers.push(room.room_number);
                });
            });
            
            const roomNumbers = allRoomNumbers.join(',');
            const roomQty = allRoomNumbers.length;
            const firstRoomId = allRoomIds[0];
            
            // Redirect to datphong.php with ALL selected rooms (including multiple types)
            window.location.href = `datphong.php?hotel_id=${hotelId}&room_id=${firstRoomId}&room_numbers=${encodeURIComponent(roomNumbers)}&check_in=${checkIn}&check_out=${checkOut}&guests=${guests}&room_qty=${roomQty}`;
        }
    </script>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>

</body>
</html>