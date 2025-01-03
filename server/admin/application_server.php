<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    switch ($action) {
        /**
         * Fetch All Applications
         */
        case 'read':
            $filters = [
                'search' => cleanInput($_POST['search'] ?? ''),
                'curriculum_id' => cleanInput($_POST['curriculum_id'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? ''),
                'submission_date' => cleanInput($_POST['submission_date'] ?? ''),
                'school_year' => cleanInput($_POST['school_year'] ?? ''),
                'semester' => cleanInput($_POST['semester'] ?? '')
            ];
        
            $applications = $admin->getAllApplications($filters);
            echo json_encode($applications);
            break;
        

        /**
         * Get Application Details
         */
        case 'get':
            $applicationId = intval(cleanInput($_POST['id'] ?? 0));

            if (!$applicationId) {
                echo json_encode(['error' => 'Invalid application ID.']);
                break;
            }

            $application = $admin->getApplicationById($applicationId);

            if ($application) {
                echo json_encode($application);
            } else {
                echo json_encode(['error' => 'Application not found.']);
            }
            break;

        /**
         * Fetch Curriculums
         */
        case 'fetch_curriculums':
            try {
                $curriculums = $admin->getCurriculums();
                echo json_encode($curriculums);
            } catch (Exception $e) {
                echo json_encode(['error' => 'Failed to fetch curriculums.']);
            }
            break;

        /**
         * Compare Grades
         */
        case 'compare_grades':
            $applicationId = intval(cleanInput($_POST['application_id'] ?? 0));
            $userId = intval(cleanInput($_POST['user_id'] ?? 0));
        
            if (!$applicationId || !$userId) {
                echo json_encode(['error' => 'Invalid application ID or user ID.']);
                break;
            }
        
            try {
                // Fetch grades and proof image using Admin methods
                $grades = $admin->getGradesByApplication($applicationId, $userId);
                $image = $admin->getProofImageByApplication($applicationId);
        
                // Log data for debugging
                error_log('Grades: ' . print_r($grades, true));
                error_log('Image: ' . ($image ? 'Image retrieved' : 'No image found'));
        
                echo json_encode([
                    'grades' => $grades,
                    'image' => $image
                ]);
            } catch (Exception $e) {
                error_log('Error in compare_grades: ' . $e->getMessage());
                echo json_encode(['error' => 'Failed to fetch grades and proof image.']);
            }
            break;
        

            case 'change_status':
                $applicationId = intval(cleanInput($_POST['application_id'] ?? 0));
                $currentStatus = cleanInput($_POST['current_status'] ?? '');
            
                if (!$applicationId || !$currentStatus) {
                    echo json_encode(['error' => 'Invalid application ID or status.']);
                    break;
                }
            
                // Determine the new status based on the current status
                $newStatus = '';
                if ($currentStatus === 'Pending') {
                    $newStatus = 'Approved';
                } elseif ($currentStatus === 'Approved') {
                    $newStatus = 'Rejected';
                } elseif ($currentStatus === 'Rejected') {
                    $newStatus = 'Approved';
                } else {
                    echo json_encode(['error' => 'Invalid status transition.']);
                    break;
                }
            
                try {
                    // Call the Admin class method to update the status
                    $isUpdated = $admin->updateApplicationStatus($applicationId, $newStatus);
            
                    if ($isUpdated) {
                        echo json_encode(['success' => true, 'new_status' => $newStatus]);
                    } else {
                        echo json_encode(['error' => 'Failed to update application status.']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['error' => 'An error occurred while updating status.']);
                }
                break;            
            

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
