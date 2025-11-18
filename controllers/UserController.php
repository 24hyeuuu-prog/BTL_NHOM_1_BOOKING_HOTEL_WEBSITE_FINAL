<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/db.php';

class UserController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Register a new user
     */
    public function register($username, $email, $phone, $password, $password_confirm) {
        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Please fill in all required fields'];
        }
        
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }
        
        if ($password !== $password_confirm) {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        // Create user object
        $user = new User($this->conn);
        $user->Tendangnhap = $this->sanitizeInput($username);
        $user->Email = $this->sanitizeInput($email);
        $user->Sdt = $this->sanitizeInput($phone);
        $user->matkhau = $password;
        
        // Check if username already exists
        if ($user->usernameExists()) {
            return ['success' => false, 'message' => 'Username already exists'];
        }
        
        // Check if email already exists
        if ($user->emailExists()) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Register user
        return $user->register();
    }
    
    /**
     * Authenticate user
     */
    public function login($username, $password) {
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Please enter username and password'];
        }
        
        $user = User::authenticate($this->conn, $username, $password);
        
        if ($user) {
            return ['success' => true, 'message' => 'Login successful', 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    /**
     * Get user profile
     */
    public function getProfile($user_id) {
        $user = User::getById($this->conn, $user_id);
        
        if ($user) {
            unset($user['matkhau']); // Remove password from response
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'User not found'];
    }
    
    /**
     * Update user profile
     */
    public function updateProfile($user_id, $data) {
        $user = User::getById($this->conn, $user_id);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        $user_obj = new User($this->conn);
        $user_obj->Mauser = $user_id;
        $user_obj->Tendangnhap = $data['Tendangnhap'] ?? $user['Tendangnhap'];
        $user_obj->Email = $data['Email'] ?? $user['Email'];
        $user_obj->Sdt = $data['Sdt'] ?? $user['Sdt'];
        $user_obj->linkavatar = $data['linkavatar'] ?? $user['linkavatar'];
        
        if ($user_obj->update()) {
            return ['success' => true, 'message' => 'Profile updated successfully'];
        }
        
        return ['success' => false, 'message' => 'Failed to update profile'];
    }
    
    /**
     * Change password
     */
    public function changePassword($user_id, $old_password, $new_password, $new_password_confirm) {
        if (empty($old_password) || empty($new_password)) {
            return ['success' => false, 'message' => 'Please fill in all fields'];
        }
        
        if ($new_password !== $new_password_confirm) {
            return ['success' => false, 'message' => 'New passwords do not match'];
        }
        
        if (strlen($new_password) < 8) {
            return ['success' => false, 'message' => 'New password must be at least 8 characters'];
        }
        
        $user = User::getById($this->conn, $user_id);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        // Verify old password
        if (!password_verify($old_password, $user['matkhau'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Change password
        $user_obj = new User($this->conn);
        $user_obj->Mauser = $user_id;
        
        if ($user_obj->changePassword($new_password)) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        }
        
        return ['success' => false, 'message' => 'Failed to change password'];
    }
    
    /**
     * Sanitize input
     */
    private function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
