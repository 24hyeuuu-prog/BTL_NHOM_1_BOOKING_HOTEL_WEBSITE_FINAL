<?php
/**
 * ReviewController
 * 
 * Handles all review-related operations
 * - Submit new reviews
 * - Get reviews for hotels
 * - Update/Delete reviews
 * - Calculate review statistics
 */

require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../config/db.php';

class ReviewController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Submit a new review
     * 
     * @param int $user_id User ID
     * @param int $hotel_id Hotel ID
     * @param array $data Review data (rating, title, content, purpose, companion)
     * @return array Result array with success status and message
     */
    public function submit($user_id, $hotel_id, $data) {
        // Validate user exists
        if (!$this->userExists($user_id)) {
            return ['success' => false, 'message' => 'Người dùng không tồn tại'];
        }
        
        // Validate hotel exists
        if (!$this->hotelExists($hotel_id)) {
            return ['success' => false, 'message' => 'Khách sạn không tồn tại'];
        }
        
        // Validate inputs
        $validation = $this->validateReviewData($data);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }
        
        // Check if user already reviewed this hotel
        if ($this->userAlreadyReviewed($user_id, $hotel_id)) {
            return ['success' => false, 'message' => 'Bạn đã đánh giá khách sạn này rồi'];
        }
        
        // Get user avatar
        $avatar = $this->getUserAvatar($user_id);
        
        // Create review object
        $review = new Review($this->conn);
        $review->Mauser = $user_id;
        $review->MaKS = $hotel_id;
        $review->diemreview = floatval($data['rating']);
        $review->mucdich = $this->sanitizeInput($data['purpose'] ?? '');
        $review->dicung = $this->sanitizeInput($data['companion'] ?? '');
        $review->tieude = $this->sanitizeInput($data['title']);
        $review->noidung = $this->sanitizeInput($data['content']);
        $review->linkavatar = $avatar;
        
        return $review->create();
    }
    
    /**
     * Get reviews for a hotel
     * 
     * @param int $hotel_id Hotel ID
     * @param int $limit Number of reviews to fetch
     * @param int $offset Pagination offset
     * @return array Array of reviews
     */
    public function getHotelReviews($hotel_id, $limit = 10, $offset = 0) {
        return Review::getByHotel($this->conn, $hotel_id, $limit, $offset);
    }
    
    /**
     * Get user reviews
     * 
     * @param int $user_id User ID
     * @return array Array of reviews by the user
     */
    public function getUserReviews($user_id) {
        return Review::getByUser($this->conn, $user_id);
    }
    
    /**
     * Get review by ID
     * 
     * @param int $review_id Review ID
     * @return array|null Review data or null if not found
     */
    public function getById($review_id) {
        return Review::getById($this->conn, $review_id);
    }
    
    /**
     * Update a review
     * 
     * @param int $review_id Review ID
     * @param int $user_id User ID (for authorization)
     * @param array $data Updated review data
     * @return array Result array with success status and message
     */
    public function update($review_id, $user_id, $data) {
        // Validate inputs
        $validation = $this->validateReviewData($data);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }
        
        // Get existing review
        $review = Review::getById($this->conn, $review_id);
        if (!$review) {
            return ['success' => false, 'message' => 'Đánh giá không tồn tại'];
        }
        
        // Check authorization
        if ($review['Mauser'] != $user_id) {
            return ['success' => false, 'message' => 'Bạn không có quyền chỉnh sửa đánh giá này'];
        }
        
        // Update review
        $review_obj = new Review($this->conn);
        $review_obj->Mareview = $review_id;
        $review_obj->Mauser = $user_id;
        $review_obj->MaKS = $review['MaKS'];
        $review_obj->diemreview = floatval($data['rating']);
        $review_obj->mucdich = $this->sanitizeInput($data['purpose'] ?? '');
        $review_obj->dicung = $this->sanitizeInput($data['companion'] ?? '');
        $review_obj->tieude = $this->sanitizeInput($data['title']);
        $review_obj->noidung = $this->sanitizeInput($data['content']);
        
        if ($review_obj->update()) {
            return ['success' => true, 'message' => 'Đánh giá đã được cập nhật thành công'];
        }
        
        return ['success' => false, 'message' => 'Lỗi khi cập nhật đánh giá'];
    }
    
    /**
     * Delete a review
     * 
     * @param int $review_id Review ID
     * @param int $user_id User ID (for authorization)
     * @return array Result array with success status and message
     */
    public function delete($review_id, $user_id) {
        $review = Review::getById($this->conn, $review_id);
        
        if (!$review) {
            return ['success' => false, 'message' => 'Đánh giá không tồn tại'];
        }
        
        if ($review['Mauser'] != $user_id) {
            return ['success' => false, 'message' => 'Bạn không có quyền xóa đánh giá này'];
        }
        
        if (Review::delete($this->conn, $review_id, $user_id, $review['MaKS'])) {
            return ['success' => true, 'message' => 'Đánh giá đã được xóa thành công'];
        }
        
        return ['success' => false, 'message' => 'Lỗi khi xóa đánh giá'];
    }
    
    /**
     * Get review statistics for a hotel
     * 
     * @param int $hotel_id Hotel ID
     * @return array|null Statistics data
     */
    public function getStats($hotel_id) {
        return Review::getStats($this->conn, $hotel_id);
    }
    
    /**
     * ===== PRIVATE HELPER METHODS =====
     */
    
    /**
     * Validate review data
     * 
     * @param array $data Review data to validate
     * @return array Validation result with 'valid' and 'message' keys
     */
    private function validateReviewData($data) {
        // Check required fields
        if (empty($data['rating'])) {
            return ['valid' => false, 'message' => 'Vui lòng chọn xếp hạng sao'];
        }
        
        if (empty($data['title'])) {
            return ['valid' => false, 'message' => 'Vui lòng nhập tiêu đề đánh giá'];
        }
        
        if (empty($data['content'])) {
            return ['valid' => false, 'message' => 'Vui lòng nhập nội dung đánh giá'];
        }
        
        // Validate rating value
        $rating = floatval($data['rating']);
        if ($rating < 1 || $rating > 5) {
            return ['valid' => false, 'message' => 'Xếp hạng phải từ 1 đến 5 sao'];
        }
        
        // Validate field lengths
        $title = trim($data['title']);
        $content = trim($data['content']);
        
        if (strlen($title) > 50) {
            return ['valid' => false, 'message' => 'Tiêu đề không được vượt quá 50 ký tự'];
        }
        
        if (strlen($content) > 1000) {
            return ['valid' => false, 'message' => 'Nội dung đánh giá không được vượt quá 1000 ký tự'];
        }
        
        if (strlen($content) < 10) {
            return ['valid' => false, 'message' => 'Nội dung đánh giá phải ít nhất 10 ký tự'];
        }
        
        return ['valid' => true, 'message' => ''];
    }
    
    /**
     * Check if user already reviewed this hotel
     * 
     * @param int $user_id User ID
     * @param int $hotel_id Hotel ID
     * @return bool True if user already reviewed, false otherwise
     */
    private function userAlreadyReviewed($user_id, $hotel_id) {
        $sql = "SELECT COUNT(*) as count FROM review WHERE Mauser = ? AND MaKS = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] > 0;
    }
    
    /**
     * Get user avatar
     * 
     * @param int $user_id User ID
     * @return string|null User avatar URL or null
     */
    private function getUserAvatar($user_id) {
        $sql = "SELECT linkavatar FROM nguoidung WHERE Mauser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $avatar = null;
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $avatar = $user['linkavatar'];
        }
        $stmt->close();
        
        return $avatar;
    }
    
    /**
     * Check if user exists
     * 
     * @param int $user_id User ID
     * @return bool True if user exists
     */
    private function userExists($user_id) {
        $sql = "SELECT COUNT(*) as count FROM nguoidung WHERE Mauser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] > 0;
    }
    
    /**
     * Check if hotel exists
     * 
     * @param int $hotel_id Hotel ID
     * @return bool True if hotel exists
     */
    private function hotelExists($hotel_id) {
        $sql = "SELECT COUNT(*) as count FROM khachsan WHERE MaKS = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] > 0;
    }
    
    /**
     * Sanitize input data
     * 
     * @param string $data Input to sanitize
     * @return string Sanitized input
     */
    private function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}
?>
