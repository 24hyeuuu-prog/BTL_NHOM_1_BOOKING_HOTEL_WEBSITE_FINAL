<?php

require_once __DIR__ . '/../config/db.php';

class User {
    private $conn;
    
    public $Mauser;
    public $Tendangnhap;
    public $Email;
    public $Sdt;
    public $matkhau;
    public $linkavatar;
    public $admin;
    public $created_at;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Register a new user
     */
    public function register() {
        $sql = "INSERT INTO nguoidung (Tendangnhap, Email, Sdt, matkhau, linkavatar, admin) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Prepare failed: ' . $this->conn->error];
        }
        
        $hashedPassword = password_hash($this->matkhau, PASSWORD_DEFAULT);
        $admin = 0; // Mặc định là người dùng bình thường
        
        $stmt->bind_param("sssssi", $this->Tendangnhap, $this->Email, $this->Sdt, $hashedPassword, $this->linkavatar, $admin);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Registration successful', 'user_id' => $this->conn->insert_id];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => 'Registration failed: ' . $error];
        }
    }
    
    /**
     * Check if username already exists
     */
    public function usernameExists() {
        $sql = "SELECT Mauser FROM nguoidung WHERE Tendangnhap = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->Tendangnhap);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    
    /**
     * Check if email already exists
     */
    public function emailExists() {
        $sql = "SELECT Mauser FROM nguoidung WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->Email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    
    /**
     * Get user by ID
     */
    public static function getById($conn, $user_id) {
        $sql = "SELECT * FROM nguoidung WHERE Mauser = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        }
        $stmt->close();
        return null;
    }
    
    /**
     * Authenticate user with username and password
     */
    public static function authenticate($conn, $username, $password) {
        $sql = "SELECT * FROM nguoidung WHERE Tendangnhap = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['matkhau'])) {
                $stmt->close();
                return $user;
            }
        }
        $stmt->close();
        return null;
    }
    
    /**
     * Update user profile
     */
    public function update() {
        $sql = "UPDATE nguoidung SET Tendangnhap = ?, Email = ?, Sdt = ?, linkavatar = ? WHERE Mauser = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $this->Tendangnhap, $this->Email, $this->Sdt, $this->linkavatar, $this->Mauser);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Change password
     */
    public function changePassword($newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE nguoidung SET matkhau = ? WHERE Mauser = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $this->Mauser);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
}
