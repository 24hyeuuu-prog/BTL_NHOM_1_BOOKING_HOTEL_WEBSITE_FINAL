<?php

require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../config/db.php';

class HotelController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all hotels
     */
    public function getAll() {
        return Hotel::getAll($this->conn);
    }
    
    /**
     * Get hotel by ID with reviews
     */
    public function getById($hotel_id) {
        $hotel = Hotel::getById($this->conn, $hotel_id);
        
        if ($hotel) {
            // Get reviews
            $review_sql = "SELECT r.*, n.Tendangnhap FROM review r 
                          JOIN nguoidung n ON r.Mauser = n.Mauser 
                          WHERE r.MaKS = ? 
                          ORDER BY r.thoigian DESC";
            $review_stmt = $this->conn->prepare($review_sql);
            $review_stmt->bind_param("i", $hotel_id);
            $review_stmt->execute();
            $review_result = $review_stmt->get_result();
            
            $reviews = [];
            while ($row = $review_result->fetch_assoc()) {
                $reviews[] = $row;
            }
            $review_stmt->close();
            
            $hotel['reviews'] = $reviews;
            
            // Calculate statistics
            $stats_sql = "SELECT 
                         COUNT(*) as total_reviews,
                         AVG(diemreview) as avg_rating,
                         SUM(CASE WHEN diemreview >= 4.5 THEN 1 ELSE 0 END) as excellent,
                         SUM(CASE WHEN diemreview >= 4.0 AND diemreview < 4.5 THEN 1 ELSE 0 END) as good,
                         SUM(CASE WHEN diemreview >= 2.5 AND diemreview < 4.0 THEN 1 ELSE 0 END) as average,
                         SUM(CASE WHEN diemreview < 2.5 THEN 1 ELSE 0 END) as poor
                         FROM review WHERE MaKS = ?";
            
            $stats_stmt = $this->conn->prepare($stats_sql);
            $stats_stmt->bind_param("i", $hotel_id);
            $stats_stmt->execute();
            $stats_result = $stats_stmt->get_result();
            
            if ($stats_result->num_rows > 0) {
                $hotel['stats'] = $stats_result->fetch_assoc();
            }
            $stats_stmt->close();
        }
        
        return $hotel;
    }
    
    /**
     * Search hotels with filters
     */
    public function search($filters = []) {
        return Hotel::search($this->conn, $filters);
    }
    
    /**
     * Search hotel by exact name
     */
    public function searchByName($hotelName) {
        return Hotel::searchByName($this->conn, $hotelName);
    }
    
    /**
     * Get hotel recommendations
     */
    public function getRecommendations($limit = 4, $exclude_id = 0) {
        return Hotel::getRecommendations($this->conn, $limit, $exclude_id);
    }
    
    /**
     * Create new hotel (admin)
     */
    public function create($data) {
        $hotel = new Hotel($this->conn);
        $hotel->Ten = $data['Ten'] ?? '';
        $hotel->hangkhachsan = $data['hangkhachsan'] ?? '';
        // $hotel->diemdg = floatval($data['diemdg'] ?? 0);
        $hotel->vitri = $data['vitri'] ?? '';
        $hotel->khuvuc = $data['khuvuc'] ?? '';
        $hotel->mota = $data['mota'] ?? '';
        $hotel->giatri1 = $data['giatri1'] ?? '';
        $hotel->giatri2 = $data['giatri2'] ?? '';
        $hotel->giatri3 = $data['giatri3'] ?? '';
        $hotel->giatri4 = $data['giatri4'] ?? '';
        $hotel->anhmain = $data['anhmain'] ?? '';
        $hotel->anh1 = $data['anh1'] ?? '';
        $hotel->anh2 = $data['anh2'] ?? '';
        $hotel->anh3 = $data['anh3'] ?? '';
        $hotel->anh4 = $data['anh4'] ?? '';
        $hotel->motachitiet = $data['motachitiet'] ?? '';
        $hotel->price = floatval($data['price'] ?? 0);
        
        if ($hotel->create()) {
            return ['success' => true, 'message' => 'Hotel created successfully', 'hotel_id' => $hotel->MaKS];
        }
        return ['success' => false, 'message' => 'Failed to create hotel'];
    }
    
    /**
     * Update hotel (admin)
     */
    public function update($hotel_id, $data) {
        $hotel = Hotel::getById($this->conn, $hotel_id);
        
        if (!$hotel) {
            return ['success' => false, 'message' => 'Hotel not found'];
        }
        
        $hotel_obj = new Hotel($this->conn);
        $hotel_obj->MaKS = $hotel_id;
        $hotel_obj->Ten = $data['Ten'] ?? $hotel['Ten'];
        $hotel_obj->hangkhachsan = $data['hangkhachsan'] ?? $hotel['hangkhachsan'];
        $hotel_obj->diemdg = floatval($data['diemdg'] ?? $hotel['diemdg']);
        $hotel_obj->vitri = $data['vitri'] ?? $hotel['vitri'];
        $hotel_obj->khuvuc = $data['khuvuc'] ?? $hotel['khuvuc'];
        $hotel_obj->mota = $data['mota'] ?? $hotel['mota'];
        $hotel_obj->giatri1 = $data['giatri1'] ?? $hotel['giatri1'];
        $hotel_obj->giatri2 = $data['giatri2'] ?? $hotel['giatri2'];
        $hotel_obj->giatri3 = $data['giatri3'] ?? $hotel['giatri3'];
        $hotel_obj->giatri4 = $data['giatri4'] ?? $hotel['giatri4'];
        $hotel_obj->anhmain = $data['anhmain'] ?? $hotel['anhmain'];
        $hotel_obj->anh1 = $data['anh1'] ?? $hotel['anh1'];
        $hotel_obj->anh2 = $data['anh2'] ?? $hotel['anh2'];
        $hotel_obj->anh3 = $data['anh3'] ?? $hotel['anh3'];
        $hotel_obj->anh4 = $data['anh4'] ?? $hotel['anh4'];
        $hotel_obj->motachitiet = $data['motachitiet'] ?? $hotel['motachitiet'];
        $hotel_obj->price = floatval($data['price'] ?? $hotel['price']);
        
        if ($hotel_obj->update()) {
            return ['success' => true, 'message' => 'Hotel updated successfully'];
        }
        return ['success' => false, 'message' => 'Failed to update hotel'];
    }
    
    /**
     * Delete hotel (admin)
     */
    public function delete($hotel_id) {
        if (Hotel::delete($this->conn, $hotel_id)) {
            return ['success' => true, 'message' => 'Hotel deleted successfully'];
        }
        return ['success' => false, 'message' => 'Failed to delete hotel'];
    }
}
