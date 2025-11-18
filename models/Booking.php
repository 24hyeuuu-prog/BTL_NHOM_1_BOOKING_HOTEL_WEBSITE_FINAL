<?php

require_once __DIR__ . '/../config/db.php';

class Booking {
    private $conn;
    
    public $booking_id;
    public $user_id;
    public $hotel_id;
    public $room_id;
    public $room_numbers;      // NEW: for multiple rooms
    public $room_quantity;     // NEW: count of rooms
    public $check_in_date;
    public $check_out_date;
    public $num_guests;
    public $total_price;
    public $status;
    public $notes;             // NEW: for booking notes
    public $created_at;
    public $updated_at;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Create a new booking
     */
    public function create() {
        error_log("=== BOOKING.create() ===");
        error_log("User ID: " . $this->user_id);
        error_log("Hotel ID: " . $this->hotel_id);
        error_log("Room ID: " . $this->room_id);
        error_log("Check-in: " . $this->check_in_date);
        error_log("Check-out: " . $this->check_out_date);
        error_log("Guests: " . $this->num_guests);
        error_log("Price: " . $this->total_price);
        
        // Validate dates
        if (strtotime($this->check_in_date) >= strtotime($this->check_out_date)) {
            error_log("ERROR: Invalid date range");
            return ['success' => false, 'message' => 'Ngày trả phòng phải sau ngày nhận phòng'];
        }
        
        // Calculate number of nights
        $nights = (strtotime($this->check_out_date) - strtotime($this->check_in_date)) / (60 * 60 * 24);
        
        if ($nights <= 0) {
            error_log("ERROR: Invalid nights count: $nights");
            return ['success' => false, 'message' => 'Thời gian đặt phòng không hợp lệ'];
        }

        // Check room maintenance status
        $room_check = "SELECT status FROM rooms WHERE room_id = ?";
        $stmt_check = $this->conn->prepare($room_check);
        $stmt_check->bind_param("i", $this->room_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['status'] === 'Bảo trì') {
                error_log("ERROR: Room under maintenance");
                $stmt_check->close();
                return ['success' => false, 'message' => 'Phòng đang bảo trì, không thể đặt'];
            }
        }
        $stmt_check->close();
        
        // Check room availability
        $available = $this->checkRoomAvailability($this->room_id, $this->check_in_date, $this->check_out_date);
        if (!$available) {
            error_log("ERROR: Room not available for these dates");
            return ['success' => false, 'message' => 'Phòng không còn trống trong khoảng thời gian này'];
        }

        $sql = "INSERT INTO bookings (user_id, hotel_id, room_id, room_numbers, room_quantity, check_in_date, check_out_date, num_guests, total_price, status, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Chưa xác nhận', ?)";
        
        error_log("SQL: $sql");
        error_log("Inserting values:");
        error_log("  - user_id: " . $this->user_id);
        error_log("  - hotel_id: " . $this->hotel_id);
        error_log("  - room_id: " . $this->room_id);
        error_log("  - room_numbers (tên phòng): " . ($this->room_numbers ?? 'NULL'));
        error_log("  - room_quantity: " . ($this->room_quantity ?? 'NULL'));
        error_log("  - check_in_date: " . $this->check_in_date);
        error_log("  - check_out_date: " . $this->check_out_date);
        error_log("  - num_guests: " . $this->num_guests);
        error_log("  - total_price: " . $this->total_price);
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("ERROR: Prepare failed - " . $this->conn->error);
            return ['success' => false, 'message' => 'Lỗi chuẩn bị câu lệnh: ' . $this->conn->error];
        }
        
        $stmt->bind_param("iiisissids", $this->user_id, $this->hotel_id, $this->room_id, 
                         $this->room_numbers, $this->room_quantity,
                         $this->check_in_date, $this->check_out_date, $this->num_guests, $this->total_price, $this->notes);
        
        if ($stmt->execute()) {
            $this->booking_id = $this->conn->insert_id;
            error_log("SUCCESS: Booking created with ID: " . $this->booking_id);
            $stmt->close();
            return ['success' => true, 'message' => 'Đặt phòng thành công', 'booking_id' => $this->booking_id];
        } else {
            $error = $stmt->error;
            error_log("ERROR: Execute failed - $error");
            $stmt->close();
            return ['success' => false, 'message' => 'Lỗi khi tạo đặt phòng: ' . $error];
        }
    }    /**
     * Get booking by ID
     */
    public static function getById($conn, $booking_id) {
        $sql = "SELECT b.*, h.Ten as hotel_name, u.Tendangnhap as username 
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN nguoidung u ON b.user_id = u.Mauser 
                WHERE b.booking_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $booking = $result->fetch_assoc();
            $stmt->close();
            return $booking;
        }
        $stmt->close();
        return null;
    }
    
    /**
     * Get bookings by user
     */
    public static function getByUser($conn, $user_id) {
        $sql = "SELECT b.*, h.Ten as hotel_name, h.anhmain as hotel_image 
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                WHERE b.user_id = ? 
                ORDER BY b.check_in_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        
        $stmt->close();
        return $bookings;
    }
    
    /**
     * Get bookings by hotel
     */
    public static function getByHotel($conn, $hotel_id) {
        $sql = "SELECT b.*, u.Tendangnhap as username, u.Email as user_email 
                FROM bookings b 
                JOIN nguoidung u ON b.user_id = u.Mauser 
                WHERE b.hotel_id = ? 
                ORDER BY b.check_in_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        
        $stmt->close();
        return $bookings;
    }
    
    /**
     * Update booking status
     */
    public function updateStatus($new_status) {
        $allowed_statuses = ['Chưa xác nhận', 'Đã xác nhận', 'Đã hoàn thành', 'Đã hủy'];
        
        if (!in_array($new_status, $allowed_statuses)) {
            return false;
        }
        
        $sql = "UPDATE bookings SET status = ?, updated_at = NOW() WHERE booking_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $this->booking_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Update booking notes
     */
    public function updateNotes($new_notes) {
        $sql = "UPDATE bookings SET notes = ?, updated_at = NOW() WHERE booking_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $new_notes, $this->booking_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Get pending/active bookings for a user (not completed, not cancelled)
     */
    public static function getUserPending($conn, $user_id) {
        $sql = "SELECT b.*, h.Ten as hotel_name, h.anhmain as hotel_image, r.room_type, r.room_number
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
                WHERE b.user_id = ? 
                AND b.status IN ('Chưa xác nhận', 'Đã xác nhận')
                AND b.check_out_date >= CURDATE()
                ORDER BY b.check_in_date ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        
        $stmt->close();
        return $bookings;
    }
    
    /**
     * Get completed bookings for a user
     */
    public static function getUserCompleted($conn, $user_id) {
        $sql = "SELECT b.*, h.Ten as hotel_name, h.anhmain as hotel_image, r.room_type, r.room_number
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
                WHERE b.user_id = ? 
                AND b.status = 'Đã hoàn thành'
                ORDER BY b.check_in_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        
        $stmt->close();
        return $bookings;
    }
    
    /**
     * Get cancelled bookings for a user
     */
    public static function getUserCancelled($conn, $user_id) {
        $sql = "SELECT b.*, h.Ten as hotel_name, h.anhmain as hotel_image, r.room_type, r.room_number
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
                WHERE b.user_id = ? 
                AND b.status = 'Đã hủy'
                ORDER BY b.check_in_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        
        $stmt->close();
        return $bookings;
    }
    
    /**
     * Cancel booking
     */
    public function cancel() {
        return $this->updateStatus('Đã hủy');
    }
    
    /**
     * Check room availability - Detects all overlap patterns
     */
    private function checkRoomAvailability($room_id, $check_in, $check_out) {
        $sql = "SELECT COUNT(*) as conflict_count FROM bookings 
                WHERE room_id = ? 
                AND status IN ('Đã xác nhận', 'Chưa xác nhận') 
                AND check_in_date < ?
                AND check_out_date > ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("ERROR in checkRoomAvailability prepare: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("iss", $room_id, $check_out, $check_in);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['conflict_count'] == 0;
    }
}
