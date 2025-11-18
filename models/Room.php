<?php

require_once __DIR__ . '/../config/db.php';

class Room {
    private $conn;
    
    public $room_id;
    public $hotel_id;
    public $room_type;
    public $room_number;
    public $capacity;
    public $price_per_night;
    public $amenities;
    public $quantity;
    public $status;
    public $description;
    public $created_at;
    public $updated_at;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Create a new room
     */
    public function create() {
        $sql = "INSERT INTO rooms (hotel_id, room_type, room_number, capacity, price_per_night, amenities, status, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issidsis", 
            $this->hotel_id, $this->room_type, $this->room_number, 
            $this->capacity, $this->price_per_night, $this->amenities, 
            $this->status, $this->description);
        
        if ($stmt->execute()) {
            $this->room_id = $this->conn->insert_id;
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Get room by ID
     */
    public static function getById($conn, $room_id) {
        $sql = "SELECT * FROM rooms WHERE room_id = ?";
        $stmt = $conn->prepare($sql);
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
     * Get all rooms for a hotel
     */
    public static function getByHotel($conn, $hotel_id) {
        $sql = "SELECT * FROM rooms WHERE hotel_id = ? ORDER BY room_type, room_number";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        $stmt->close();
        return $rooms;
    }
    
    /**
     * Search room by exact room number for a hotel
     */
    public static function searchByRoomNumber($conn, $hotel_id, $room_number) {
        $sql = "SELECT * FROM rooms WHERE hotel_id = ? AND room_number = ? ORDER BY room_type, room_number";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $hotel_id, $room_number);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        $stmt->close();
        return $rooms;
    }
    
    /**
     * Get available rooms for a hotel and date range
     * Lấy các phòng khả dụng (không bảo trì và không có lịch đặt chồng lên nhau)
     */
    public static function getAvailable($conn, $hotel_id, $check_in, $check_out) {
        $sql = "SELECT DISTINCT r.* 
                FROM rooms r 
                WHERE r.hotel_id = ? 
                AND r.status NOT IN ('Bảo trì', 'Đang sử dụng')
                AND r.room_id NOT IN (
                    SELECT b.room_id 
                    FROM bookings b 
                    WHERE b.hotel_id = ? 
                    AND b.status IN ('Đã xác nhận', 'Chưa xác nhận')
                    AND (
                        (b.check_in_date <= ? AND b.check_out_date > ?) OR
                        (b.check_in_date < ? AND b.check_out_date >= ?) OR
                        (b.check_in_date >= ? AND b.check_out_date <= ?)
                    )
                )
                ORDER BY r.room_type, r.room_number";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssss", $hotel_id, $hotel_id, $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        $stmt->close();
        return $rooms;
    }
    
    /**
     * Get rooms grouped by type for a hotel
     */
    public static function getByHotelGrouped($conn, $hotel_id) {
        $sql = "SELECT room_type, COUNT(*) as total, 
                SUM(CASE WHEN status = 'Có sẵn' THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN status = 'Bảo trì' THEN 1 ELSE 0 END) as maintenance,
                MIN(price_per_night) as min_price
                FROM rooms 
                WHERE hotel_id = ? 
                GROUP BY room_type";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        $stmt->close();
        return $rooms;
    }
    
    /**
     * Check if a specific room is available for a date range
     */
    public static function isAvailable($conn, $room_id, $check_in, $check_out) {
        $room = self::getById($conn, $room_id);
        
        if (!$room) {
            return false;
        }
        
        // Không được đặt nếu phòng đang bảo trì
        if ($room['status'] === 'Bảo trì') {
            return false;
        }
        
        // Kiểm tra xem có booking trùng lập không
        $sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE room_id = ? 
                AND status IN ('Đã xác nhận', 'Chưa xác nhận')
                AND (
                    (check_in_date <= ? AND check_out_date > ?) OR
                    (check_in_date < ? AND check_out_date >= ?) OR
                    (check_in_date >= ? AND check_out_date <= ?)
                )";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $room_id, $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        return $row['count'] == 0;
    }
    
    /**
     * Update room status
     */
    public function update() {
        $sql = "UPDATE rooms SET room_number = ?, room_type = ?, capacity = ?, price_per_night = ?, 
                amenities = ?, status = ?, description = ?, updated_at = NOW() 
                WHERE room_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssidsssi", 
            $this->room_number, $this->room_type, $this->capacity, $this->price_per_night, 
            $this->amenities, $this->status, 
            $this->description, $this->room_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Delete room
     */
    public static function delete($conn, $room_id) {
        $sql = "DELETE FROM rooms WHERE room_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $room_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
}
