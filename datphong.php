<?php 
require_once 'config/config.php';
require_once 'controllers/RoomController.php';
require_once 'models/User.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get parameters
$hotelId = intval($_GET['hotel_id'] ?? 0);
$roomId = intval($_GET['room_id'] ?? 0);
$checkIn = $_GET['check_in'] ?? '';
$checkOut = $_GET['check_out'] ?? '';
$guests = intval($_GET['guests'] ?? 1);
$roomQuantity = intval($_GET['room_qty'] ?? 1);  // Number of rooms to book
$roomNumbers = $_GET['room_numbers'] ?? '';  // Comma-separated room numbers selected

if ($hotelId <= 0 || $roomId <= 0 || empty($checkIn) || empty($checkOut)) {
    header('Location: khachsan2.php');
    exit;
}

// Get hotel info
$hotel_sql = "SELECT * FROM khachsan WHERE MaKS = ?";
$stmt = $conn->prepare($hotel_sql);
$stmt->bind_param("i", $hotelId);
$stmt->execute();
$hotel_result = $stmt->get_result();
if ($hotel_result->num_rows == 0) {
    header('Location: khachsan2.php');
    exit;
}
$hotel = $hotel_result->fetch_assoc();
$stmt->close();

// Calculate number of nights
$checkInDate = new DateTime($checkIn);
$checkOutDate = new DateTime($checkOut);
$nights = $checkInDate->diff($checkOutDate)->days;

// Parse room_numbers to get all room_ids and their types
$selectedRooms = [];
$roomIds = [];
if (!empty($roomNumbers)) {
    $roomNumbersList = array_map('trim', explode(',', $roomNumbers));
    
    // Get all room info from database for the selected room numbers
    $placeholders = implode(',', array_fill(0, count($roomNumbersList), '?'));
    $room_sql = "SELECT room_id, room_number, room_type, price_per_night, capacity, amenities 
                 FROM rooms 
                 WHERE hotel_id = ? AND room_number IN ($placeholders)";
    
    $stmt = $conn->prepare($room_sql);
    
    // Build bind params
    $bindParams = array_merge([$hotelId], $roomNumbersList);
    $bindTypes = 'i' . str_repeat('s', count($roomNumbersList));
    
    $stmt->bind_param($bindTypes, ...$bindParams);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $selectedRooms[] = $row;
        $roomIds[] = $row['room_id'];
    }
    $stmt->close();
}

// Fallback: if room_numbers is empty, get the primary room
if (empty($selectedRooms)) {
    $room_sql = "SELECT * FROM rooms WHERE room_id = ? AND hotel_id = ?";
    $stmt = $conn->prepare($room_sql);
    $stmt->bind_param("ii", $roomId, $hotelId);
    $stmt->execute();
    $room_result = $stmt->get_result();
    if ($room_result->num_rows == 0) {
        header('Location: khachsan2.php');
        exit;
    }
    $room = $room_result->fetch_assoc();
    $selectedRooms = [$room];
    $stmt->close();
}

// Group rooms by type and calculate prices
$roomsByType = [];
$totalPrice = 0;

foreach ($selectedRooms as $room) {
    $roomType = $room['room_type'];
    if (!isset($roomsByType[$roomType])) {
        $roomsByType[$roomType] = [
            'type' => $roomType,
            'price_per_night' => $room['price_per_night'],
            'capacity' => $room['capacity'],
            'amenities' => $room['amenities'],
            'rooms' => [],
            'count' => 0,
            'subtotal' => 0
        ];
    }
    
    $roomsByType[$roomType]['rooms'][] = $room['room_number'];
    $roomsByType[$roomType]['count']++;
    $roomsByType[$roomType]['subtotal'] = $room['price_per_night'] * $nights * $roomsByType[$roomType]['count'];
    
    // Add to total
    $totalPrice += $room['price_per_night'] * $nights;
}

// Use first room for display if needed
$room = $selectedRooms[0];

// Get current user info
$user = User::getById($conn, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt phòng - LaValle</title>
    <link rel="stylesheet" href="CSS/datphong.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php include 'chatbot.php'; ?>
    
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>
    
    <main class="main">
        <div class="reservation-header">
            <a href="chitietkhachsan.php?id=<?php echo $hotelId; ?>"><i class="fa-solid fa-arrow-left"></i></a> 
            <h2>THÔNG TIN ĐẶT PHÒNG</h2>
        </div>

        <div class="reservation-content">
            <!-- Left Section: Booking Details -->
            <div class="left">
                <div class="reservation-detail">
                    <h2><?php echo htmlspecialchars($hotel['Ten']); ?></h2>
                    <div class="star">
                        <?php 
                        $rating = round($hotel['diemdg']);
                        for ($i = 0; $i < 5; $i++): 
                        ?>
                            <i class="fa-solid fa-star <?php echo $i < $rating ? '' : ''; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($hotel['khuvuc']); ?></span>
                </div>

                <div class="reservation-total-detail">
                    <center><h2>Chi tiết đặt phòng</h2></center>
                    
                    <div>
                        <h3>Nhận phòng</h3>
                        <div class="reservation-time">
                            <i class="fa-regular fa-calendar"></i>
                            <div>
                                <b><p><?php echo date('l, d/m/Y', strtotime($checkIn)); ?></p></b>
                                <p>Từ 14:00</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3>Trả phòng</h3>
                        <div class="reservation-time">
                            <i class="fa-regular fa-calendar"></i>
                            <div>
                                <b><p><?php echo date('l, d/m/Y', strtotime($checkOut)); ?></p></b>
                                <p>Từ 12:00</p>
                            </div>
                        </div>
                    </div>

                    <b><h3>Tổng thời gian lưu trú:</h3></b>
                    <p><?php echo $nights; ?> đêm</p>
                    <hr>

                    <div class="total-room">
                        <b><h3>Phòng đã đặt</h3></b>
                        <div>
                            <?php foreach ($roomsByType as $roomType => $typeInfo): ?>
                                <div style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e0e0e0;">
                                    <p><strong><?php echo htmlspecialchars($roomType); ?></strong></p>
                                    <p>- Sức chứa: <?php echo $typeInfo['capacity']; ?> người</p>
                                    <p><strong>Các phòng được chọn (<?php echo $typeInfo['count']; ?> phòng):</strong></p>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;">
                                        <?php foreach ($typeInfo['rooms'] as $roomNum): ?>
                                            <span style="background-color: #C1DDFF; padding: 4px 8px; border-radius: 4px; border: 1px solid #073fff;">
                                                <?php echo htmlspecialchars($roomNum); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <b><h3>Số lượng khách:</h3></b>
                    <div class="total-customer">
                        <i class="fa-solid fa-users"></i>
                        <p><?php echo $guests; ?> người</p>
                    </div>

                    <div class="amenities">
                        <b><h3>Tiện nghi:</h3></b>
                        <p><?php echo htmlspecialchars($room['amenities']); ?></p>
                    </div>
                </div>

                <div class="reservation-price-detail">
                    <center><h2>Tổng cộng</h2></center>
                    <div class="total">
                        <center><b><p><?php echo number_format($totalPrice, 0, ',', '.'); ?> VNĐ</p></b></center>
                    </div>

                    <div>
                        <h3>Thông tin giá</h3>
                        <table>
                            <?php foreach ($roomsByType as $roomType => $typeInfo): ?>
                                <tr>
                                    <td width="20%"><strong><?php echo htmlspecialchars($typeInfo['type']); ?></strong></td>
                                    <td width="10%">x</td>
                                    <td width="10%"><?php echo $nights; ?> đêm</td>
                                    <td width="10%">x</td>
                                    <td width="10%"><?php echo $typeInfo['count']; ?> phòng</td>
                                    <td width="30%"><strong><?php echo number_format($typeInfo['subtotal'], 0, ',', '.'); ?> VNĐ</strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Section: Booking Form -->
            <div class="right">
                <div class="reservation-user-detail">
                    <span><i class="fa-solid fa-user"></i> Người dùng "<?php echo htmlspecialchars($user['Tendangnhap']); ?>" đã đăng nhập</span>
                </div>

                <div class="customer-info">
                    <center><h2>THÔNG TIN CHI TIẾT</h2></center>
                    <form id="bookingForm" method="POST">
                        <input type="hidden" name="hotel_id" value="<?php echo $hotelId; ?>">
                        <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
                        <input type="hidden" name="check_in" value="<?php echo $checkIn; ?>">
                        <input type="hidden" name="check_out" value="<?php echo $checkOut; ?>">
                        <input type="hidden" name="num_guests" value="<?php echo $guests; ?>">
                        <input type="hidden" name="room_quantity" value="<?php echo sizeof($selectedRooms); ?>">
                        <input type="hidden" name="room_numbers" value="<?php echo htmlspecialchars($roomNumbers); ?>">
                        <input type="hidden" name="room_ids" value="<?php echo htmlspecialchars(implode(',', $roomIds)); ?>">
                        <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">

                        <div class="form-name-input">
                            <div>
                                <p>Họ</p>
                                <input type="text" class="customer-name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['Tendangnhap'] ?? ''); ?>" required>
                            </div>
                            <div>
                                <p>Tên</p>
                                <input type="text" class="customer-name" name="last_name" placeholder="" required>
                            </div>
                        </div>

                        <p>Địa chỉ email</p>
                        <input type="email" class="customer-email" name="email" 
                               value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>" required>

                        <div class="form-location-input">
                            <div class="location">
                                <p>Vùng/quốc gia</p>
                                <input type="text" class="customer-name" name="country" placeholder="Việt Nam" required>
                            </div>
                            <div class="tel">
                                <p>Số điện thoại</p>
                                <div class="telephone">
                                    <select name="area_code" class="" required>
                                        <option value="+84" selected>+84</option>
                                    </select>
                                    <input type="tel" class="customer-phone" name="phone" 
                                           value="<?php echo htmlspecialchars($user['Sdt'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <p>Yêu cầu chi tiết</p>
                        <textarea name="notes" class="special-request" placeholder="Nhập yêu cầu đặc biệt (nếu có)"></textarea>

                        <div class="submit-button">
                            <button type="submit" class="submit-btn">ĐẶT PHÒNG</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>

    <script>
        // Debug logging
        const debugForm = document.getElementById('bookingForm');
        console.log('Form found:', debugForm ? 'YES' : 'NO');
        console.log('hotel_id:', debugForm.hotel_id.value);
        console.log('room_id:', debugForm.room_id.value);
        console.log('check_in:', debugForm.check_in.value);
        console.log('check_out:', debugForm.check_out.value);
        console.log('num_guests:', debugForm.num_guests.value);
        console.log('total_price:', debugForm.total_price.value);
        
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get all form values with debugging
            const hotel_id = this.hotel_id.value;
            const room_id = this.room_id.value;
            const check_in = this.check_in.value;
            const check_out = this.check_out.value;
            const num_guests = this.num_guests.value;
            const total_price = this.total_price.value;
            const notes = this.notes.value;
            const room_numbers = this.room_numbers.value;        // ← NEW
            const room_quantity = this.room_quantity.value;      // ← NEW
            
            console.log('=== FORM VALUES ===');
            console.log('hotel_id:', hotel_id, 'type:', typeof hotel_id);
            console.log('room_id:', room_id, 'type:', typeof room_id);
            console.log('check_in:', check_in, 'type:', typeof check_in);
            console.log('check_out:', check_out, 'type:', typeof check_out);
            console.log('num_guests:', num_guests, 'type:', typeof num_guests);
            console.log('total_price:', total_price, 'type:', typeof total_price);
            console.log('notes:', notes, 'type :', typeof notes);
            console.log('room_numbers:', room_numbers, 'type:', typeof room_numbers);    // ← NEW
            console.log('room_quantity:', room_quantity, 'type:', typeof room_quantity); // ← NEW

            const formData = {
                hotel_id: parseInt(hotel_id),
                room_id: parseInt(room_id),
                check_in_date: check_in,
                check_out_date: check_out,
                num_guests: parseInt(num_guests),
                total_price: parseFloat(total_price),
                notes: notes,
                room_numbers: room_numbers,              // ← NEW: Gửi danh sách số phòng
                room_quantity: parseInt(room_quantity)   // ← NEW: Gửi số lượng phòng
            };
            
            console.log('Form data to send:', formData);
            console.log('room_numbers value:', formData.room_numbers);
            console.log('room_quantity value:', formData.room_quantity);

            fetch('api/bookings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include',  // 👈 IMPORTANT: Send session cookies
                body: JSON.stringify(formData)
            })
            .then(response => {
                // Log response for debugging
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                return response.text().then(text => {
                    console.log('Raw response text:', text);
                    
                    // Try to parse JSON
                    try {
                        const json = JSON.parse(text);
                        return { ok: response.ok, data: json, status: response.status };
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        return { ok: false, data: null, status: response.status, error: text };
                    }
                });
            })
            .then(result => {
                console.log('Result:', result);
                
                // HTTP 201 is also success! (Created)
                // Don't check result.ok, check the HTTP status code instead
                if (result.status >= 400) {
                    throw new Error(`HTTP ${result.status}: ${result.error || 'Unknown error'}`);
                }
                
                console.log('Parsed data:', result.data);
                if (result.data && result.data.success) {
                    showNotification('success', 'Đặt phòng thành công!');
                    setTimeout(() => {
                        window.location.href = 'phongdadat_new.php';
                    }, 2000);
                } else {
                    const errorMsg = result.data?.message || 'Lỗi khi đặt phòng';
                    console.error('API error:', errorMsg);
                    showNotification('error', errorMsg);
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                showNotification('error', 'Lỗi: ' + error.message);
            });
        });

        function showNotification(type, message) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type} show`;
            notification.innerHTML = `
                <div class="notification-content">
                    <div class="notification-icon"></div>
                    <div class="notification-message">${escapeHtml(message)}</div>
                    <button class="notification-close" aria-label="Close notification">×</button>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Close button handler
            const closeBtn = notification.querySelector('.notification-close');
            closeBtn.addEventListener('click', () => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            });
            
            // Auto hide after 5 seconds
            const timeoutId = setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>