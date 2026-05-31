-- Immigration Management System Database Schema
-- Created: 2026-05-31

-- Users Table (Admin, Immigration Officers, Border Control Officers)
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'immigration_officer', 'border_control_officer') NOT NULL,
    department VARCHAR(100),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Applicants/Travelers Table
CREATE TABLE applicants (
    applicant_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    country VARCHAR(50),
    postal_code VARCHAR(20),
    status ENUM('registered', 'verified', 'blocked') DEFAULT 'registered',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Passports Table
CREATE TABLE passports (
    passport_id INT PRIMARY KEY AUTO_INCREMENT,
    applicant_id INT NOT NULL,
    passport_number VARCHAR(50) UNIQUE NOT NULL,
    issue_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    issuing_country VARCHAR(100) NOT NULL,
    issuing_authority VARCHAR(100),
    status ENUM('valid', 'expired', 'revoked', 'lost') DEFAULT 'valid',
    verified BOOLEAN DEFAULT FALSE,
    verified_by INT,
    verified_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(user_id)
);

-- Visa Applications Table
CREATE TABLE visa_applications (
    visa_id INT PRIMARY KEY AUTO_INCREMENT,
    applicant_id INT NOT NULL,
    visa_type VARCHAR(50) NOT NULL,
    purpose_of_visit VARCHAR(100),
    destination_country VARCHAR(100) NOT NULL,
    application_date DATE NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'expired', 'cancelled') DEFAULT 'pending',
    processing_officer INT,
    decision_date TIMESTAMP NULL,
    rejection_reason TEXT,
    validity_start_date DATE,
    validity_end_date DATE,
    number_of_entries INT,
    duration_of_stay_days INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    FOREIGN KEY (processing_officer) REFERENCES users(user_id)
);

-- Border Entry/Exit Records Table
CREATE TABLE border_records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    applicant_id INT NOT NULL,
    passport_id INT NOT NULL,
    visa_id INT,
    record_type ENUM('entry', 'exit') NOT NULL,
    border_checkpoint VARCHAR(100) NOT NULL,
    entry_exit_date TIMESTAMP NOT NULL,
    entry_exit_time TIME,
    transport_mode ENUM('air', 'land', 'sea', 'rail') NOT NULL,
    flight_train_number VARCHAR(50),
    officer_id INT NOT NULL,
    status ENUM('cleared', 'flagged', 'rejected') DEFAULT 'cleared',
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    FOREIGN KEY (passport_id) REFERENCES passports(passport_id),
    FOREIGN KEY (visa_id) REFERENCES visa_applications(visa_id),
    FOREIGN KEY (officer_id) REFERENCES users(user_id)
);

-- Audit Logs Table (for security and tracking)
CREATE TABLE audit_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Reports Table
CREATE TABLE reports (
    report_id INT PRIMARY KEY AUTO_INCREMENT,
    report_type VARCHAR(100) NOT NULL,
    created_by INT NOT NULL,
    report_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    report_period_start DATE,
    report_period_end DATE,
    total_applications INT,
    approved_applications INT,
    rejected_applications INT,
    pending_applications INT,
    total_entries INT,
    total_exits INT,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id)
);

-- Create Indexes for better performance
CREATE INDEX idx_applicant_email ON applicants(email);
CREATE INDEX idx_passport_number ON passports(passport_number);
CREATE INDEX idx_visa_applicant ON visa_applications(applicant_id);
CREATE INDEX idx_visa_status ON visa_applications(status);
CREATE INDEX idx_border_applicant ON border_records(applicant_id);
CREATE INDEX idx_border_date ON border_records(entry_exit_date);
CREATE INDEX idx_audit_user ON audit_logs(user_id);
CREATE INDEX idx_audit_timestamp ON audit_logs(timestamp);