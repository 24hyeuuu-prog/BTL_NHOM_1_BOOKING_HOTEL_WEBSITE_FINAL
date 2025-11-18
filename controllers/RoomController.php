<?php

require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/../config/db.php';

class RoomController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all rooms for a hotel
     */
    public function getByHotel($hotel_id) {
        return Room::getByHotel($this->conn, $hotel_id);
    }
    
    /**
     * Search room by exact room number
     */
    public function searchByRoomNumber($hotel_id, $room_number) {
        return Room::searchByRoomNumber($this->conn, $hotel_id, $room_number);
    }
    
    /**
     * Get rooms grouped by type with statistics
     */
    public function getGroupedByType($hotel_id) {
        return Room::getByHotelGrouped($this->conn, $hotel_id);
    }
    
    /**
     * Get available rooms for dates
     */
    public function getAvailable($hotel_id, $check_in, $check_out) {
        return Room::getAvailable($this->conn, $hotel_id, $check_in, $check_out);
    }
    
    /**
     * Check if specific room is available
     */
    public function isAvailable($room_id, $check_in, $check_out) {
        return Room::isAvailable($this->conn, $room_id, $check_in, $check_out);
    }
    
    /**
     * Get room details
     */
    public function getById($room_id) {
        return Room::getById($this->conn, $room_id);
    }
    
    /**
     * Create new room (admin)
     */
    public function create($data) {
        $room = new Room($this->conn);
        $room->hotel_id = intval($data['hotel_id'] ?? 0);
        $room->room_type = sanitizeInput($data['room_type'] ?? '');
        $room->room_number = sanitizeInput($data['room_number'] ?? '');
        $room->capacity = intval($data['capacity'] ?? 1);
        $room->price_per_night = floatval($data['price_per_night'] ?? 0);
        // IMPORTANT: Use trim() instead of sanitizeInput() for HTML-rich fields to preserve formatting
        $room->amenities = trim($data['amenities'] ?? '');
        $room->status = sanitizeInput($data['status'] ?? 'Có sẵn');
        $room->description = trim($data['description'] ?? '');
        
        // Validate required fields
        if ($room->hotel_id <= 0 || empty($room->room_type) || empty($room->room_number) || $room->price_per_night <= 0) {
            return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc'];
        }
        
        if ($room->create()) {
            return ['success' => true, 'message' => 'Phòng được thêm thành công', 'room_id' => $room->room_id];
        }
        return ['success' => false, 'message' => 'Lỗi khi tạo phòng'];
    }
    
    /**
     * Update room (admin)
     */
    public function update($room_id, $data) {
        $room_data = Room::getById($this->conn, $room_id);
        
        if (!$room_data) {
            return ['success' => false, 'message' => 'Phòng không tìm thấy'];
        }
        
        $room = new Room($this->conn);
        $room->room_id = $room_id;
        $room->room_number = sanitizeInput($data['room_number'] ?? $room_data['room_number']);
        $room->room_type = sanitizeInput($data['room_type'] ?? $room_data['room_type']);
        $room->capacity = intval($data['capacity'] ?? $room_data['capacity']);
        $room->price_per_night = floatval($data['price_per_night'] ?? $room_data['price_per_night']);
        // Use new value if key exists in data (even if empty string), otherwise keep old value
        // IMPORTANT: Use trim() instead of sanitizeInput() for HTML-rich fields to preserve formatting
        $room->amenities = array_key_exists('amenities', $data) ? trim($data['amenities'] ?? '') : $room_data['amenities'];
        $room->status = sanitizeInput($data['status'] ?? $room_data['status']);
        $room->description = array_key_exists('description', $data) ? trim($data['description'] ?? '') : $room_data['description'];
        
        if ($room->update()) {
            return ['success' => true, 'message' => 'Cập nhật phòng thành công'];
        }
        return ['success' => false, 'message' => 'Lỗi khi cập nhật phòng'];
    }
    
    /**
     * Delete room (admin)
     */
    public function delete($room_id) {
        if (Room::delete($this->conn, $room_id)) {
            return ['success' => true, 'message' => 'Xóa phòng thành công'];
        }
        return ['success' => false, 'message' => 'Lỗi khi xóa phòng'];
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>