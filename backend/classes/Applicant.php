<?php
/**
 * Applicant Class
 * Handles applicant registration and management
 */

require_once 'Database.php';

class Applicant {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Register New Applicant
     */
    public function registerApplicant($firstName, $lastName, $dob, $gender, $nationality, $email, $phone, $address, $city, $country, $postalCode) {
        if (empty($firstName) || empty($lastName) || empty($dob) || empty($nationality)) {
            return ['success' => false, 'message' => 'Required fields are missing'];
        }
        
        // Check if applicant already exists
        $existing = $this->db->getRow("SELECT applicant_id FROM applicants WHERE email = '" . $this->db->escape($email) . "'");
        if ($existing) {
            return ['success' => false, 'message' => 'Applicant already registered'];
        }
        
        $data = [
            'first_name' => $this->db->escape($firstName),
            'last_name' => $this->db->escape($lastName),
            'date_of_birth' => $dob,
            'gender' => $gender,
            'nationality' => $this->db->escape($nationality),
            'email' => $this->db->escape($email),
            'phone' => $phone,
            'address' => $this->db->escape($address),
            'city' => $this->db->escape($city),
            'country' => $this->db->escape($country),
            'postal_code' => $postalCode,
            'status' => 'registered'
        ];
        
        $applicantId = $this->db->insert('applicants', $data);
        
        if ($applicantId) {
            return ['success' => true, 'message' => 'Applicant registered successfully', 'applicant_id' => $applicantId];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }
    
    /**
     * Get Applicant by ID
     */
    public function getApplicantById($applicantId) {
        return $this->db->getRow("SELECT * FROM applicants WHERE applicant_id = $applicantId");
    }
    
    /**
     * Get Applicant by Email
     */
    public function getApplicantByEmail($email) {
        return $this->db->getRow("SELECT * FROM applicants WHERE email = '" . $this->db->escape($email) . "'");
    }
    
    /**
     * Update Applicant
     */
    public function updateApplicant($applicantId, $data) {
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $this->db->escape($value);
            }
        }
        $this->db->update('applicants', $data, "applicant_id = $applicantId");
        return $this->getApplicantById($applicantId);
    }
    
    /**
     * Search Applicants
     */
    public function searchApplicants($searchTerm) {
        $term = $this->db->escape($searchTerm);
        $sql = "SELECT * FROM applicants WHERE 
                first_name LIKE '%$term%' OR 
                last_name LIKE '%$term%' OR 
                email LIKE '%$term%' OR 
                phone LIKE '%$term%'";
        return $this->db->getAll($sql);
    }
    
    /**
     * Get All Applicants with Pagination
     */
    public function getAllApplicants($limit = 10, $offset = 0) {
        $sql = "SELECT * FROM applicants LIMIT $limit OFFSET $offset";
        return $this->db->getAll($sql);
    }
    
    /**
     * Verify Applicant
     */
    public function verifyApplicant($applicantId) {
        $this->db->update('applicants', ['status' => 'verified'], "applicant_id = $applicantId");
        return $this->getApplicantById($applicantId);
    }
    
    /**
     * Block Applicant
     */
    public function blockApplicant($applicantId) {
        $this->db->update('applicants', ['status' => 'blocked'], "applicant_id = $applicantId");
        return $this->getApplicantById($applicantId);
    }
    
    /**
     * Delete Applicant
     */
    public function deleteApplicant($applicantId) {
        return $this->db->delete('applicants', "applicant_id = $applicantId");
    }
}

?>