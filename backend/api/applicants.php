<?php
/**
 * Applicants API Endpoints
 */

require_once '../config/config.php';
require_once '../classes/Applicant.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$applicant = new Applicant();

// Parse JSON body
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        // Register new applicant
        if (isset($input['first_name']) && isset($input['last_name'])) {
            $result = $applicant->registerApplicant(
                $input['first_name'],
                $input['last_name'],
                $input['date_of_birth'] ?? '',
                $input['gender'] ?? '',
                $input['nationality'] ?? '',
                $input['email'] ?? '',
                $input['phone'] ?? '',
                $input['address'] ?? '',
                $input['city'] ?? '',
                $input['country'] ?? '',
                $input['postal_code'] ?? ''
            );
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
        }
        break;
        
    case 'GET':
        // Get applicant by ID or search
        if (isset($_GET['id'])) {
            $result = $applicant->getApplicantById($_GET['id']);
            if ($result) {
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Applicant not found']);
            }
        } elseif (isset($_GET['search'])) {
            $results = $applicant->searchApplicants($_GET['search']);
            echo json_encode(['success' => true, 'data' => $results]);
        } elseif (isset($_GET['all'])) {
            $limit = $_GET['limit'] ?? ITEMS_PER_PAGE;
            $offset = $_GET['offset'] ?? 0;
            $results = $applicant->getAllApplicants($limit, $offset);
            echo json_encode(['success' => true, 'data' => $results]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
        break;
        
    case 'PUT':
        // Update applicant
        if (isset($input['applicant_id'])) {
            $result = $applicant->updateApplicant($input['applicant_id'], $input);
            echo json_encode(['success' => true, 'message' => 'Applicant updated', 'data' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Applicant ID required']);
        }
        break;
        
    case 'DELETE':
        // Delete applicant
        if (isset($_GET['id'])) {
            $result = $applicant->deleteApplicant($_GET['id']);
            echo json_encode(['success' => $result, 'message' => $result ? 'Applicant deleted' : 'Failed to delete']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Applicant ID required']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}

?>