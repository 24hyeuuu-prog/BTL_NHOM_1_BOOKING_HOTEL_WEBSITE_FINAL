<?php

require_once __DIR__ . '/../config/db.php';

class Review {
    private $conn;
    
    public $Mareview;
    public $Mauser;
    public $MaKS;
    public $diemreview;
    public $mucdich;
    public $dicung;
    public $noidung;
    public $tieude;
    public $thoigian;
    public $linkavatar;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Create a new review
     */
    public function create() {
        // Check if user already reviewed this hotel
        $check_sql = "SELECT Mareview FROM review WHERE Mauser = ? AND MaKS = ?";
        $check_stmt = $this->conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $this->Mauser, $this->MaKS);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $check_stmt->close();
            return ['success' => false, 'message' => 'Bạn đã đánh giá khách sạn này rồi'];
        }
        $check_stmt->close();
        
        $sql = "INSERT INTO review (Mauser, MaKS, diemreview, mucdich, dicung, noidung, tieude, linkavatar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iidsssss", $this->Mauser, $this->MaKS, $this->diemreview, 
                         $this->mucdich, $this->dicung, $this->noidung, $this->tieude, $this->linkavatar);
        
        if ($stmt->execute()) {
            $this->Mareview = $this->conn->insert_id;
            $stmt->close();
            
            // Update hotel rating
            $this->updateHotelRating();
            
            return ['success' => true, 'message' => 'Review submitted successfully', 'review_id' => $this->Mareview];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to submit review: ' . $error];
        }
    }
    
    /**
     * Get all reviews for a hotel
     */
    public static function getByHotel($conn, $hotel_id, $limit = 10, $offset = 0) {
        $sql = "SELECT r.*, n.Tendangnhap FROM review r 
                JOIN nguoidung n ON r.Mauser = n.Mauser 
                WHERE r.MaKS = ? 
                ORDER BY r.thoigian DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $hotel_id, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        $stmt->close();
        return $reviews;
    }
    
    /**
     * Get reviews by user
     */
    public static function getByUser($conn, $user_id) {
        $sql = "SELECT r.*, k.Ten as hotel_name, k.anhmain as hotel_image 
                FROM review r 
                JOIN khachsan k ON r.MaKS = k.MaKS 
                WHERE r.Mauser = ? 
                ORDER BY r.thoigian DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        $stmt->close();
        return $reviews;
    }
    
    /**
     * Get review by ID
     */
    public static function getById($conn, $review_id) {
        $sql = "SELECT r.*, n.Tendangnhap FROM review r 
                JOIN nguoidung n ON r.Mauser = n.Mauser 
                WHERE r.Mareview = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $review = $result->fetch_assoc();
            $stmt->close();
            return $review;
        }
        $stmt->close();
        return null;
    }
    
    /**
     * Update review
     */
    public function update() {
        $sql = "UPDATE review SET diemreview = ?, mucdich = ?, dicung = ?, noidung = ?, tieude = ? 
                WHERE Mareview = ? AND Mauser = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("dsssii", $this->diemreview, $this->mucdich, $this->dicung, 
                         $this->noidung, $this->tieude, $this->Mareview, $this->Mauser);
        
        if ($stmt->execute()) {
            $stmt->close();
            // Update hotel rating
            $this->updateHotelRating();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Delete review
     */
    public static function delete($conn, $review_id, $user_id, $hotel_id) {
        $sql = "DELETE FROM review WHERE Mareview = ? AND Mauser = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $review_id, $user_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            // Update hotel rating after deletion
            self::updateHotelRatingStatic($conn, $hotel_id);
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Get review statistics for a hotel
     */
    public static function getStats($conn, $hotel_id) {
        $sql = "SELECT 
                COUNT(*) as total_reviews,
                AVG(diemreview) as avg_rating,
                SUM(CASE WHEN diemreview >= 4.5 THEN 1 ELSE 0 END) as excellent,
                SUM(CASE WHEN diemreview >= 4.0 AND diemreview < 4.5 THEN 1 ELSE 0 END) as good,
                SUM(CASE WHEN diemreview >= 2.5 AND diemreview < 4.0 THEN 1 ELSE 0 END) as average,
                SUM(CASE WHEN diemreview < 2.5 THEN 1 ELSE 0 END) as poor
                FROM review WHERE MaKS = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stats = $result->fetch_assoc();
            $stmt->close();
            return $stats;
        }
        $stmt->close();
        return null;
    }
    
    /**
     * Update hotel rating
     */
    private function updateHotelRating() {
        self::updateHotelRatingStatic($this->conn, $this->MaKS);
    }
    
    /**
     * Static method to update hotel rating
     */
    public static function updateHotelRatingStatic($conn, $hotel_id) {
        $sql = "SELECT AVG(diemreview) as avg_rating FROM review WHERE MaKS = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $avg_rating = round($row['avg_rating'], 1);
            
            $update_sql = "UPDATE khachsan SET diemdg = ? WHERE MaKS = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("di", $avg_rating, $hotel_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
        $stmt->close();
    }
}
