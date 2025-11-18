<?php

require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../config/db.php';

class BookingController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Create a new booking
     */
    public function create($user_id, $hotel_id, $room_id, $data) {
        // Log received data
        error_log("=== BOOKING CREATE ===");
        error_log("User ID: $user_id");
        error_log("Hotel ID: $hotel_id");
        error_log("Room ID: $room_id");
        error_log("Data: " . print_r($data, true));
        
        // Validate inputs
        if (empty($data['check_in_date']) || empty($data['check_out_date'])) {
            error_log("ERROR: Missing dates - check_in_date: " . ($data['check_in_date'] ?? 'NULL') . ", check_out_date: " . ($data['check_out_date'] ?? 'NULL'));
            return ['success' => false, 'message' => 'Vui lòng chọn ngày nhận và trả phòng'];
        }
        
        if (empty($data['num_guests']) || intval($data['num_guests']) < 1) {
            error_log("ERROR: Missing or invalid num_guests - " . ($data['num_guests'] ?? 'NULL'));
            return ['success' => false, 'message' => 'Vui lòng nhập số khách'];
        }
        
        // Create booking
        $booking = new Booking($this->conn);
        $booking->user_id = $user_id;
        $booking->hotel_id = $hotel_id;
        $booking->room_id = $room_id;
        $booking->check_in_date = $data['check_in_date'];
        $booking->check_out_date = $data['check_out_date'];
        $booking->num_guests = intval($data['num_guests']);
        $booking->total_price = floatval($data['total_price'] ?? 0);
        $booking->status = 'Chưa xác nhận';  // Default status
        $booking->notes = $data['notes'] ?? '';  // Optional notes
        
        // Add support for room_numbers and room_quantity
        // Always set room_numbers (even if empty), MySQL expects string value
        $booking->room_numbers = $data['room_numbers'] ?? '';
        $booking->room_quantity = !empty($data['room_quantity']) ? intval($data['room_quantity']) : 1;
        
        error_log("Setting room_numbers: '" . $booking->room_numbers . "'");
        error_log("Setting room_quantity: " . $booking->room_quantity);
        
        error_log("Creating booking: User=$user_id, Hotel=$hotel_id, Room=$room_id, CheckIn=" . $booking->check_in_date . ", CheckOut=" . $booking->check_out_date . ", Guests=" . $booking->num_guests . ", Price=" . $booking->total_price . ", RoomNumbers=" . ($booking->room_numbers ?? 'N/A') . ", RoomQuantity=" . ($booking->room_quantity ?? 'N/A'));
        
        $result = $booking->create();
        error_log("Booking result: " . print_r($result, true));
        return $result;
    }
    
    /**
     * Get booking by ID
     */
    public function getById($booking_id) {
        return Booking::getById($this->conn, $booking_id);
    }
    
    /**
     * Get user bookings
     */
    public function getUserBookings($user_id) {
        return Booking::getByUser($this->conn, $user_id);
    }
    
    /**
     * Get pending/active bookings
     */
    public function getUserPendingBookings($user_id) {
        return Booking::getUserPending($this->conn, $user_id);
    }
    
    /**
     * Get completed bookings
     */
    public function getUserCompletedBookings($user_id) {
        return Booking::getUserCompleted($this->conn, $user_id);
    }
    
    /**
     * Get cancelled bookings
     */
    public function getUserCancelledBookings($user_id) {
        return Booking::getUserCancelled($this->conn, $user_id);
    }
    
    /**
     * Get hotel bookings (admin)
     */
    public function getHotelBookings($hotel_id) {
        return Booking::getByHotel($this->conn, $hotel_id);
    }
    
    /**
     * Update booking status (admin)
     */
    public function updateStatus($booking_id, $status) {
        $booking = Booking::getById($this->conn, $booking_id);
        
        if (!$booking) {
            return ['success' => false, 'message' => 'Không tìm thấy đặt phòng'];
        }
        
        $booking_obj = new Booking($this->conn);
        $booking_obj->booking_id = $booking_id;
        
        if ($booking_obj->updateStatus($status)) {
            return ['success' => true, 'message' => 'Cập nhật trạng thái đặt phòng thành công'];
        }
        
        return ['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái đặt phòng'];
    }
    
    /**
     * Update booking with status and notes (admin)
     */
    public function updateBooking($booking_id, $status, $notes) {
        $booking = Booking::getById($this->conn, $booking_id);
        
        if (!$booking) {
            return ['success' => false, 'message' => 'Không tìm thấy đặt phòng'];
        }
        
        $booking_obj = new Booking($this->conn);
        $booking_obj->booking_id = $booking_id;
        
        // Update status if provided
        if (!empty($status)) {
            if (!$booking_obj->updateStatus($status)) {
                return ['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái đặt phòng'];
            }
        }
        
        // Update notes if provided
        if ($notes !== null && $notes !== '') {
            if (!$booking_obj->updateNotes($notes)) {
                return ['success' => false, 'message' => 'Lỗi khi cập nhật ghi chú'];
            }
        }
        
        return ['success' => true, 'message' => 'Cập nhật đặt phòng thành công'];
    }
    
    /**
     * Cancel booking
     */
    public function cancel($booking_id, $user_id) {
        $booking = Booking::getById($this->conn, $booking_id);
        
        if (!$booking) {
            return ['success' => false, 'message' => 'Không tìm thấy đặt phòng'];
        }
        
        if ($booking['user_id'] != $user_id) {
            return ['success' => false, 'message' => 'Không có quyền hủy đặt phòng này'];
        }
        
        $booking_obj = new Booking($this->conn);
        $booking_obj->booking_id = $booking_id;
        
        if ($booking_obj->cancel()) {
            return ['success' => true, 'message' => 'Hủy đặt phòng thành công'];
        }
        
        return ['success' => false, 'message' => 'Lỗi khi hủy đặt phòng'];
    }
}
