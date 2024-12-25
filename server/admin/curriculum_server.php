<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    switch ($action) {
        case 'read':
            $filters = [
                'course_id' => cleanInput($_POST['course_id'] ?? ''),
                'year' => cleanInput($_POST['year'] ?? ''),
                'remarks' => cleanInput($_POST['remarks'] ?? ''),
                'version' => cleanInput($_POST['version'] ?? '')
            ];

            try {
                $curricula = $admin->getAllCurricula($filters);
                echo json_encode($curricula);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;

        case 'fetch_courses':
            try {
                $courses = $admin->getAllCourses();
                echo json_encode($courses);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch courses.']);
            }
            break;

            case 'save':
                $errors = [];
            
                // Log raw POST data
                error_log("Raw POST Data: " . print_r($_POST, true));
            
                $id = cleanNumericInput($_POST['id'] ?? null);
                $data = [
                    'effective_year' => cleanInput($_POST['effective_year'] ?? null),
                    'version' => cleanInput($_POST['version'] ?? null),
                    'remarks' => cleanInput($_POST['remarks'] ?? null),
                    'course_id' => cleanNumericInput($_POST['course_id'] ?? null)
                ];
            
                // Log cleaned data
                error_log("Cleaned Data: " . print_r($data, true));
            
                // Validation
                if (empty($data['effective_year']) || !preg_match('/^\d{4}-\d{4}$/', $data['effective_year'])) {
                    $errors['effective_year'] = 'Effective year must be in the format YYYY-YYYY.';
                }
            
                if (empty($data['version'])) {
                    $errors['version'] = 'Version is required.';
                }
            
                if (empty($data['course_id'])) {
                    $errors['course_id'] = 'A valid course must be selected.';
                }
            
                // Log validation errors
                error_log("Validation Errors: " . print_r($errors, true));
            
                if (!empty($errors)) {
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    exit;
                }
            
                try {
                    if ($id) {
                        $result = $admin->updateCurriculum($id, $data);
                    } else {
                        $result = $admin->createCurriculum($data);
                    }
                    echo json_encode(['success' => $result]);
                } catch (Exception $e) {
                    error_log("Exception: " . $e->getMessage());
                    echo json_encode(['success' => false, 'error' => 'An error occurred while saving the curriculum.']);
                }
                break;
            
            

        case 'get':
            $id = cleanNumericInput($_POST['id'] ?? null);

            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'Invalid Curriculum ID.']);
                exit;
            }

            try {
                $curriculum = $admin->getCurriculumById($id);
                if ($curriculum) {
                    echo json_encode(['success' => true, 'data' => $curriculum]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Curriculum not found.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch curriculum details.']);
            }
            break;

        case 'delete':
            $id = cleanNumericInput($_POST['id'] ?? null);

            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'Invalid Curriculum ID.']);
                exit;
            }

            try {
                $response = $admin->deleteCurriculum($id);
                echo json_encode(['success' => $response]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'An error occurred while deleting the curriculum.']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action.']);
            break;
    }
}
