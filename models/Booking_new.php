<?php

require_once __DIR__ . '/../config/db.php';

class Booking {
    private $conn;
    
    public $booking_id;
    public $user_id;
    public $hotel_id;
    public $room_id;
    public $check_in_date;
    public $check_out_date;
    public $num_guests;
    public $total_price;
    public $status;
    public $notes;
    public $created_at;
    public $updated_at;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Create a new booking with validation
     */
    public function create() {
        // Validate dates
        if (strtotime($this->check_in_date) >= strtotime($this->check_out_date)) {
            return ['success' => false, 'message' => 'Ngày trả phòng phải sau ngày nhận phòng'];
        }
        
        // Check if room is under maintenance
        $room = $this->getRoom($this->room_id);
        if ($room && $room['status'] === 'Bảo trì') {
            return ['success' => false, 'message' => 'Phòng hiện đang bảo trì, không thể đặt'];
        }
        
        // Check room availability - không được đặt chồng lập
        if (!$this->checkAvailability($this->room_id, $this->check_in_date, $this->check_out_date)) {
            return ['success' => false, 'message' => 'Phòng không khả dụng cho khoảng thời gian này'];
        }
        
        $sql = "INSERT INTO bookings (user_id, hotel_id, room_id, check_in_date, check_out_date, num_guests, total_price, status, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiissidss", 
            $this->user_id, $this->hotel_id, $this->room_id, 
            $this->check_in_date, $this->check_out_date, $this->num_guests, 
            $this->total_price, $this->status, $this->notes);
        
        if ($stmt->execute()) {
            $this->booking_id = $this->conn->insert_id;
            $stmt->close();
            return ['success' => true, 'message' => 'Đặt phòng thành công', 'booking_id' => $this->booking_id];
        }
        $stmt->close();
        return ['success' => false, 'message' => 'Lỗi khi tạo đặt phòng'];
    }
    
    /**
     * Get room details
     *
     * @param int $room_id
     * @return array|null
     */
    private function getRoom($room_id): ?array {
        $sql = "SELECT * FROM rooms WHERE room_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $room = $result->fetch_assoc();
            $stmt->close();
            return $room;
        }
        $stmt->close();
        return null;
    }
    
    /**
     * Check room availability - 2 khách hàng không thể đặt cùng 1 phòng trong khoảng thời gian lồng nhau
     */
    private function checkAvailability($room_id, $check_in, $check_out) {
        $sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE room_id = ? 
                AND status IN ('Đã xác nhận', 'Chưa xác nhận')
                AND (
                    (check_in_date < ? AND check_out_date > ?) OR
                    (check_in_date < ? AND check_out_date > ?) OR
                    (check_in_date >= ? AND check_out_date <= ?)
                )";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssss", $room_id, $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        return $row['count'] == 0;
    }
    
    /**
     * Get booking by ID with full details
     */
    public static function getById($conn, $booking_id) {
        $sql = "SELECT b.*, u.Tendangnhap, u.Email, u.Sdt, 
                h.Ten as hotel_name, h.khuvuc as hotel_location,
                r.room_type, r.room_number, r.price_per_night
                FROM bookings b 
                JOIN nguoidung u ON b.user_id = u.Mauser 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
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
     * Get all bookings for a user
     */
    public static function getByUser($conn, $user_id) {
        $sql = "SELECT b.*, h.Ten as hotel_name, h.khuvuc as hotel_location, r.room_type, r.room_number
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
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
     * Get all bookings for a hotel
     */
    public static function getByHotel($conn, $hotel_id) {
        $sql = "SELECT b.*, u.Tendangnhap, u.Email, r.room_type, r.room_number
                FROM bookings b 
                JOIN nguoidung u ON b.user_id = u.Mauser 
                JOIN rooms r ON b.room_id = r.room_id
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
     * Get pending bookings for a user
     */
    public static function getUserPending($conn, $user_id) {
        $sql = "SELECT b.*, h.Ten as hotel_name, h.khuvuc, r.room_type, r.room_number
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
                WHERE b.user_id = ? AND b.status IN ('Chưa xác nhận', 'Đã xác nhận')
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
        $sql = "SELECT b.*, h.Ten as hotel_name, h.khuvuc, r.room_type, r.room_number
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
                WHERE b.user_id = ? AND b.status = 'Đã hoàn thành'
                ORDER BY b.check_out_date DESC";
        
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
        $sql = "SELECT b.*, h.Ten as hotel_name, h.khuvuc, r.room_type, r.room_number
                FROM bookings b 
                JOIN khachsan h ON b.hotel_id = h.MaKS 
                JOIN rooms r ON b.room_id = r.room_id
                WHERE b.user_id = ? AND b.status = 'Đã hủy'
                ORDER BY b.updated_at DESC";
        
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
     * Update booking
     */
    public function update() {
        $sql = "UPDATE bookings SET check_in_date = ?, check_out_date = ?, num_guests = ?, 
                total_price = ?, status = ?, notes = ?, updated_at = NOW() 
                WHERE booking_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiissi", 
            $this->check_in_date, $this->check_out_date, $this->num_guests, 
            $this->total_price, $this->status, $this->notes, $this->booking_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Cancel booking
     */
    public function cancel() {
        $sql = "UPDATE bookings SET status = 'Đã hủy', updated_at = NOW() WHERE booking_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->booking_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Delete booking
     */
    public static function delete($conn, $booking_id) {
        $sql = "DELETE FROM bookings WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
}
