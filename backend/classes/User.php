<?php
/**
 * User Class
 * Handles user authentication and management
 */

require_once 'Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Register New User
     */
    public function register($username, $email, $password, $fullName, $role) {
        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($fullName)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        // Check if user exists
        $existing = $this->db->getRow("SELECT user_id FROM users WHERE email = '" . $this->db->escape($email) . "'");
        if ($existing) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert user
        $data = [
            'username' => $this->db->escape($username),
            'email' => $this->db->escape($email),
            'password_hash' => $passwordHash,
            'full_name' => $this->db->escape($fullName),
            'role' => $this->db->escape($role),
            'status' => 'active'
        ];
        
        $userId = $this->db->insert('users', $data);
        
        if ($userId) {
            return ['success' => true, 'message' => 'User registered successfully', 'user_id' => $userId];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }
    
    /**
     * Login User
     */
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password required'];
        }
        
        $user = $this->db->getRow("SELECT * FROM users WHERE email = '" . $this->db->escape($email) . "'");
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        if ($user['status'] === 'suspended' || $user['status'] === 'inactive') {
            return ['success' => false, 'message' => 'Account is not active'];
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid password'];
        }
        
        // Update last login
        $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], "user_id = {$user['user_id']}");
        
        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role']
            ]
        ];
    }
    
    /**
     * Get User by ID
     */
    public function getUserById($userId) {
        $user = $this->db->getRow("SELECT user_id, username, email, full_name, role, status, created_at FROM users WHERE user_id = $userId");
        return $user ?: null;
    }
    
    /**
     * Update User
     */
    public function updateUser($userId, $data) {
        $this->db->update('users', $data, "user_id = $userId");
        return $this->getUserById($userId);
    }
    
    /**
     * Delete User
     */
    public function deleteUser($userId) {
        return $this->db->delete('users', "user_id = $userId");
    }
    
    /**
     * Get All Users
     */
    public function getAllUsers($limit = 10, $offset = 0) {
        $sql = "SELECT user_id, username, email, full_name, role, status, created_at FROM users LIMIT $limit OFFSET $offset";
        return $this->db->getAll($sql);
    }
    
    /**
     * Change Password
     */
    public function changePassword($userId, $oldPassword, $newPassword) {
        $user = $this->db->getRow("SELECT password_hash FROM users WHERE user_id = $userId");
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        if (!password_verify($oldPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Old password is incorrect'];
        }
        
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->db->update('users', ['password_hash' => $newHash], "user_id = $userId");
        
        return ['success' => true, 'message' => 'Password changed successfully'];
    }
}

?>