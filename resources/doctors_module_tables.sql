-- ============================================================================
-- DOCTOR'S MODULE DATABASE TABLES
-- ============================================================================

-- ============================================================================
-- 1. DOCTOR AVAILABILITY TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS doctor_availability (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_doctor_id (doctor_id),
    INDEX idx_date (date),
    UNIQUE KEY unique_availability (doctor_id, date, start_time)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- 2. MATERIALS USED TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS materials_used (
    id INT PRIMARY KEY AUTO_INCREMENT,
    visit_id INT,
    patient_id INT NOT NULL,
    material_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_patient_id (patient_id),
    INDEX idx_visit_id (visit_id),
    INDEX idx_created_at (created_at)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- 3. SERVICES RENDERED TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS services_rendered (
    id INT PRIMARY KEY AUTO_INCREMENT,
    visit_id INT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    service_name VARCHAR(150) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_patient_id (patient_id),
    INDEX idx_doctor_id (doctor_id),
    INDEX idx_visit_id (visit_id),
    INDEX idx_created_at (created_at)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- 4. APPOINTMENTS TABLE (Modified if doesn't exist)
-- ============================================================================
CREATE TABLE IF NOT EXISTS appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_patient_id (patient_id),
    INDEX idx_doctor_id (doctor_id),
    INDEX idx_appointment_date (appointment_date),
    INDEX idx_status (status)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- SAMPLE QUERIES FOR TESTING
-- ============================================================================

-- Add doctor availability
-- INSERT INTO doctor_availability (doctor_id, date, start_time, end_time)
-- VALUES (1, '2025-01-15', '08:00:00', '17:00:00');

-- Get doctor's availability for a specific date
-- SELECT * FROM doctor_availability 
-- WHERE doctor_id = 1 AND date = '2025-01-15' 
-- ORDER BY start_time ASC;

-- Log material used during patient visit
-- INSERT INTO materials_used (visit_id, patient_id, material_name, quantity, notes)
-- VALUES (NULL, 2, 'Anesthetic', 2, 'Used for tooth extraction');

-- Get all materials used for a patient
-- SELECT * FROM materials_used 
-- WHERE patient_id = 2 
-- ORDER BY created_at DESC;

-- Log service rendered to patient
-- INSERT INTO services_rendered (visit_id, patient_id, doctor_id, service_name, description)
-- VALUES (NULL, 2, 1, 'Tooth Extraction', 'Upper left molar extracted due to decay');

-- Get all services rendered to a patient
-- SELECT sr.id, sr.service_name, sr.description, a.firstname, a.surname as doctor_name, sr.created_at
-- FROM services_rendered sr
-- JOIN account_tbl a ON sr.doctor_id = a.account_id
-- WHERE sr.patient_id = 2
-- ORDER BY sr.created_at DESC;

-- Get pending appointments for a doctor
-- SELECT a.*, p.firstname, p.surname as patient_name 
-- FROM appointments a
-- JOIN account_tbl p ON a.patient_id = p.account_id
-- WHERE a.doctor_id = 1 AND a.status = 'pending'
-- ORDER BY a.appointment_date ASC, a.appointment_time ASC;

-- Approve an appointment
-- UPDATE appointments 
-- SET status = 'approved', updated_at = NOW() 
-- WHERE id = 1;
