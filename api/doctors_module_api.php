<?php
// doctors_module_api.php - Handle doctor module data operations

require_once '../connection.php';

header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'add_availability':
        addAvailability();
        break;
    case 'get_availability':
        getAvailability();
        break;
    case 'add_material':
        addMaterial();
        break;
    case 'get_materials':
        getMaterials();
        break;
    case 'add_service':
        addService();
        break;
    case 'get_services':
        getServices();
        break;
    case 'approve_appointment':
        approveAppointment();
        break;
    case 'reject_appointment':
        rejectAppointment();
        break;
    case 'get_appointments':
        getAppointments();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

/**
 * Add doctor availability
 */
function addAvailability() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    $doctor_id = isset($data['doctor_id']) ? intval($data['doctor_id']) : 0;
    $date = isset($data['date']) ? $data['date'] : '';
    $start_time = isset($data['start_time']) ? $data['start_time'] : '';
    $end_time = isset($data['end_time']) ? $data['end_time'] : '';
    
    if(!$doctor_id || !$date || !$start_time || !$end_time) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    // Verify doctor exists in account_tbl
    $check_sql = "SELECT account_id FROM account_tbl WHERE account_id = ?";
    if($check_stmt = $conn->prepare($check_sql)) {
        $check_stmt->bind_param("i", $doctor_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        if($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Doctor not found']);
            $check_stmt->close();
            return;
        }
        $check_stmt->close();
    }
    
    $sql = "INSERT INTO doctor_availability (doctor_id, date, start_time, end_time, created_at) 
            VALUES (?, ?, ?, ?, NOW())";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isss", $doctor_id, $date, $start_time, $end_time);
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Availability added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}

/**
 * Get doctor availability
 */
function getAvailability() {
    global $conn;
    
    $doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
    
    if(!$doctor_id) {
        echo json_encode(['success' => false, 'message' => 'Doctor ID required']);
        return;
    }
    
    $sql = "SELECT id, doctor_id, date, start_time, end_time, created_at 
            FROM doctor_availability 
            WHERE doctor_id = ? 
            ORDER BY date DESC, start_time ASC";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $availabilities = [];
        while($row = $result->fetch_assoc()) {
            $availabilities[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $availabilities]);
        $stmt->close();
    }
}

/**
 * Add material used in visit
 */
function addMaterial() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    $visit_id = isset($data['visit_id']) ? intval($data['visit_id']) : 0;
    $patient_id = isset($data['patient_id']) ? intval($data['patient_id']) : 0;
    $material_name = isset($data['material_name']) ? $data['material_name'] : '';
    $quantity = isset($data['quantity']) ? intval($data['quantity']) : 0;
    $notes = isset($data['notes']) ? $data['notes'] : '';
    
    if(!$patient_id || !$material_name || !$quantity) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    $sql = "INSERT INTO materials_used (visit_id, patient_id, material_name, quantity, notes, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iisis", $visit_id, $patient_id, $material_name, $quantity, $notes);
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Material logged successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}

/**
 * Get materials used for a patient
 */
function getMaterials() {
    global $conn;
    
    $patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
    
    if(!$patient_id) {
        echo json_encode(['success' => false, 'message' => 'Patient ID required']);
        return;
    }
    
    $sql = "SELECT id, visit_id, patient_id, material_name, quantity, notes, created_at 
            FROM materials_used 
            WHERE patient_id = ? 
            ORDER BY created_at DESC";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $materials = [];
        while($row = $result->fetch_assoc()) {
            $materials[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $materials]);
        $stmt->close();
    }
}

/**
 * Add service rendered to patient
 */
function addService() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    $visit_id = isset($data['visit_id']) ? intval($data['visit_id']) : 0;
    $patient_id = isset($data['patient_id']) ? intval($data['patient_id']) : 0;
    $service_name = isset($data['service_name']) ? $data['service_name'] : '';
    $description = isset($data['description']) ? $data['description'] : '';
    $doctor_id = isset($data['doctor_id']) ? intval($data['doctor_id']) : 0;
    
    if(!$patient_id || !$service_name || !$doctor_id) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    $sql = "INSERT INTO services_rendered (visit_id, patient_id, doctor_id, service_name, description, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iisis", $visit_id, $patient_id, $doctor_id, $service_name, $description);
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Service logged successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}

/**
 * Get services rendered to a patient
 */
function getServices() {
    global $conn;
    
    $patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
    
    if(!$patient_id) {
        echo json_encode(['success' => false, 'message' => 'Patient ID required']);
        return;
    }
    
    $sql = "SELECT id, visit_id, patient_id, doctor_id, service_name, description, created_at 
            FROM services_rendered 
            WHERE patient_id = ? 
            ORDER BY created_at DESC";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $services = [];
        while($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $services]);
        $stmt->close();
    }
}

/**
 * Approve appointment
 */
function approveAppointment() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    $appointment_id = isset($data['appointment_id']) ? intval($data['appointment_id']) : 0;
    
    if(!$appointment_id) {
        echo json_encode(['success' => false, 'message' => 'Appointment ID required']);
        return;
    }
    
    $sql = "UPDATE appointments SET status = 'approved', updated_at = NOW() WHERE id = ?";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $appointment_id);
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Appointment approved']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}

/**
 * Reject appointment
 */
function rejectAppointment() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    $appointment_id = isset($data['appointment_id']) ? intval($data['appointment_id']) : 0;
    
    if(!$appointment_id) {
        echo json_encode(['success' => false, 'message' => 'Appointment ID required']);
        return;
    }
    
    $sql = "UPDATE appointments SET status = 'rejected', updated_at = NOW() WHERE id = ?";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $appointment_id);
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Appointment rejected']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}

/**
 * Get pending appointments for doctor
 */
function getAppointments() {
    global $conn;
    
    $doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
    
    if(!$doctor_id) {
        echo json_encode(['success' => false, 'message' => 'Doctor ID required']);
        return;
    }
    
    $sql = "SELECT id, patient_id, doctor_id, appointment_date, appointment_time, status, created_at 
            FROM appointments 
            WHERE doctor_id = ? AND status = 'pending' 
            ORDER BY appointment_date ASC, appointment_time ASC";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $appointments = [];
        while($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $appointments]);
        $stmt->close();
    }
}

?>
