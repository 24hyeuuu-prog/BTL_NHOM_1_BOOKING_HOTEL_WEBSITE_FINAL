<?php

require_once __DIR__ . '/../config/db.php';

class Hotel {
    private $conn;
    
    public $MaKS;
    public $Ten;
    public $hangkhachsan;
    public $diemdg;
    public $xemhang;
    public $vitri;
    public $khuvuc;
    public $mota;
    public $giatri1;
    public $giatri2;
    public $giatri3;
    public $giatri4;
    public $anhmain;
    public $anh1;
    public $anh2;
    public $anh3;
    public $anh4;
    public $motachitiet;
    public $price;
    public $created_at;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all hotels
     */
    public static function getAll($conn) {
        $sql = "SELECT * FROM khachsan ORDER BY diemdg DESC";
        $result = $conn->query($sql);
        
        $hotels = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['xemhang'] = self::getRankingFromScore($row['diemdg']);
                $hotels[] = $row;
            }
        }
        return $hotels;
    }
    
    /**
     * Get hotel by ID
     */
    public static function getById($conn, $id) {
        $sql = "SELECT * FROM khachsan WHERE MaKS = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $hotel = $result->fetch_assoc();
            $hotel['xemhang'] = self::getRankingFromScore($hotel['diemdg']);
            $stmt->close();
            return $hotel;
        }
        $stmt->close();
        return null;
    }
    
    /**
     * Search hotels by filters
     */
    public static function search($conn, $filters = []) {
        $sql = "SELECT k.*, COUNT(r.Mareview) as total_reviews FROM khachsan k 
                LEFT JOIN review r ON k.MaKS = r.MaKS WHERE 1=1";
        
        $where_conditions = [];
        $params = [];
        $types = '';
        
        if (!empty($filters['khuvuc'])) {
            $where_conditions[] = "k.khuvuc = ?";
            $params[] = $filters['khuvuc'];
            $types .= 's';
        }
        
        if (!empty($filters['hangkhachsan'])) {
            $where_conditions[] = "k.hangkhachsan = ?";
            $params[] = $filters['hangkhachsan'];
            $types .= 's';
        }
        if (!empty($filters['xemhang'])) {
            $where_conditions[] = "k.xemhang = ?";
            $params[] = $filters['xemhang'];
            $types .= 's';
        }  
        // if (isset($filters['xemhang']) && $filters['xemhang'] !== '') {
        //     $minRating = self::getRatingFromRanking($filters['xemhang']);
        //     $where_conditions[] = "k.diemdg >= ?";
        //     $params[] = $minRating;
        //     $types .= 'd';
        // }
        
        if (!empty($where_conditions)) {
            $sql .= " AND " . implode(" AND ", $where_conditions);
        }
        
        $sql .= " GROUP BY k.MaKS ORDER BY k.diemdg DESC, total_reviews DESC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT ?";
            $params[] = intval($filters['limit']);
            $types .= 'i';
        }
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $hotels = [];
        while ($row = $result->fetch_assoc()) {
            $row['xemhang'] = self::getRankingFromScore($row['diemdg']);
            $hotels[] = $row;
        }
        
        $stmt->close();
        return $hotels;
    }
    
    /**
     * Search hotel by exact name
     */
    public static function searchByName($conn, $hotelName) {
        $sql = "SELECT * FROM khachsan WHERE Ten = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $hotelName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $hotels = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['xemhang'] = self::getRankingFromScore($row['diemdg']);
                $hotels[] = $row;
            }
        }
        
        $stmt->close();
        return $hotels;
    }
    
    /**
     * Create a new hotel
     */
    public function create() {
        $sql = "INSERT INTO khachsan (Ten, hangkhachsan,  vitri, khuvuc, mota, 
                giatri1, giatri2, giatri3, giatri4, anhmain, anh1, anh2, anh3, anh4, motachitiet, price) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $this->xemhang = self::getRankingFromScore($this->diemdg);
        
        $stmt->bind_param("sssssssssssssssd", 
            $this->Ten, $this->hangkhachsan, 
            $this->vitri, $this->khuvuc, $this->mota, $this->giatri1, $this->giatri2,
            $this->giatri3, $this->giatri4, $this->anhmain, $this->anh1, $this->anh2,
            $this->anh3, $this->anh4, $this->motachitiet, $this->price
        );
        
        if ($stmt->execute()) {
            $this->MaKS = $this->conn->insert_id;
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Update hotel
     */
    public function update() {
        $this->xemhang = self::getRankingFromScore($this->diemdg);
        
        $sql = "UPDATE khachsan SET Ten = ?, hangkhachsan = ?, vitri = ?, 
                 khuvuc = ?, mota = ?, giatri1 = ?, giatri2 = ?, giatri3 = ?, 
                giatri4 = ?, anhmain = ?, anh1 = ?, anh2 = ?, anh3 = ?, anh4 = ?, 
                motachitiet = ?, price = ? WHERE MaKS = ?";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bind_param("sssssssssssssssdi",
            $this->Ten, $this->hangkhachsan, $this->vitri,
            $this->khuvuc, $this->mota, $this->giatri1, $this->giatri2,
            $this->giatri3, $this->giatri4, $this->anhmain, $this->anh1, $this->anh2,
            $this->anh3, $this->anh4, $this->motachitiet, $this->price, $this->MaKS
        );
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Delete hotel
     */
    public static function delete($conn, $id) {
        $sql = "DELETE FROM khachsan WHERE MaKS = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Get hotel recommendations
     */
    public static function getRecommendations($conn, $limit = 4, $exclude_id = 0) {
        $sql = "SELECT k.*, COUNT(r.Mareview) as total_reviews FROM khachsan k 
                LEFT JOIN review r ON k.MaKS = r.MaKS 
                WHERE k.diemdg >= 4.0";
        
        if ($exclude_id > 0) {
            $sql .= " AND k.MaKS != " . intval($exclude_id);
        }
        
        $sql .= " GROUP BY k.MaKS ORDER BY k.diemdg DESC, total_reviews DESC LIMIT " . intval($limit);
        
        $result = $conn->query($sql);
        
        $hotels = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['xemhang'] = self::getRankingFromScore($row['diemdg']);
                $hotels[] = $row;
            }
        }
        return $hotels;
    }
    
    /**
     * Get rating from score
     */
    public static function getRankingFromScore($score) {
        $score = floatval($score);
        if  ( $score >= 4.5) return 'xuất sắc';
        elseif (  $score >= 4.0) return 'tốt';
        elseif ($score >= 2.5) return 'bình thường';
        elseif ($score >= 1.5) return 'kém';
        else return 'rất tệ';
    }
    
    public static function getRatingFromRanking($ranking) {
        switch($ranking) {
            case 'xuất sắc': return 4.5;
            case 'tốt': return 4.0;
            case 'bình thường': return 2.5;
            case 'kém': return 1.5;
            case 'rất tệ': return 0;
            default: return 0;
        }
    }
}
