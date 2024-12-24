<?php
require_once '../../classes/database.class.php';
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    switch ($action) {
        /**
         * Fetch All Subjects
         */
        case 'read':
            $filters = [
                'course_id' => cleanInput($_POST['course_id'] ?? ''),
                'curriculum_id' => cleanInput($_POST['curriculum_id'] ?? ''),
                'year_level' => cleanInput($_POST['year_level'] ?? ''),
                'semester' => cleanInput($_POST['semester'] ?? '')
            ];
        
            try {
                $subjects = $admin->getAllSubjects($filters);
                error_log("Subjects Response: " . print_r($subjects, true)); // Debugging
                echo json_encode($subjects);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
              

        /**
         * Fetch All Courses
         */
        case 'fetch_courses':
            try {
                $courses = $admin->getAllCourses();
                echo json_encode($courses);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch courses.']);
            }
            break;

        /**
         * Fetch All Curriculums
         */
        case 'fetch_curriculums':
            try {
                $curriculums = $admin->getAllCurriculums();
                echo json_encode($curriculums);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch curriculums.']);
            }
            break;

        /**
         * Create or Update Subject
         */
        case 'save':
            $errors = [];
            $id = intval(cleanInput($_POST['id'] ?? 0));
            $data = [
                'subject_code' => cleanInput($_POST['subject_code'] ?? ''),
                'descriptive_title' => cleanInput($_POST['descriptive_title'] ?? ''),
                'prerequisite' => cleanInput($_POST['prerequisite'] ?? ''),
                'lec_units' => cleanInput($_POST['lec_units'] ?? ''),
                'lab_units' => cleanInput($_POST['lab_units'] ?? ''),
                'total_units' => cleanInput($_POST['total_units'] ?? ''),
                'year_level' => cleanInput($_POST['year_level'] ?? ''),
                'semester' => cleanInput($_POST['semester'] ?? ''),
                'curriculum_id' => cleanInput($_POST['curriculum_id'] ?? '')
            ];

                    // Debugging Logs
        error_log("Raw POST Data: " . print_r($_POST, true));
        error_log("Cleaned Data: " . print_r($data, true));
            
            // Validation
            if (empty($data['subject_code'])) {
                $errors['subject_code'] = 'Subject code is required.';
            }

            if (empty($data['descriptive_title'])) {
                $errors['descriptive_title'] = 'Descriptive title is required.';
            }

            if (empty($data['lec_units']) || !is_numeric($data['lec_units'])) {
                $errors['lec_units'] = 'Lecture units are required and must be numeric.';
            }

            if (empty($data['lab_units']) || !is_numeric($data['lab_units'])) {
                $errors['lab_units'] = 'Lab units are required and must be numeric.';
            }

            if (empty($data['total_units']) || !is_numeric($data['total_units'])) {
                $errors['total_units'] = 'Total units are required and must be numeric.';
            }

            if (empty($data['year_level']) || !is_numeric($data['year_level'])) {
                $errors['year_level'] = 'Year level is required and must be numeric.';
            }

            if (empty($data['semester'])) {
                $errors['semester'] = 'Semester is required.';
            }

            if (empty($data['curriculum_id']) || !is_numeric($data['curriculum_id'])) {
                $errors['curriculum_id'] = 'Curriculum is required.';
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }

            try {
                if ($id > 0) {
                    $result = $admin->updateSubject($id, $data);
                } else {
                    $result = $admin->createSubject($data);
                }

                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to save the subject.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'An error occurred while saving the subject.']);
            }
            break;

        /**
         * Fetch Subject by ID
         */
        case 'get':
            $id = intval(cleanInput($_POST['id'] ?? 0));

            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'Invalid Subject ID.']);
                exit;
            }

            try {
                $subject = $admin->getSubjectById($id);
                if ($subject) {
                    echo json_encode(['success' => true, 'data' => $subject]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Subject not found.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch subject details.']);
            }
            break;

        /**
         * Delete Subject
         */
        case 'delete':
            $id = intval(cleanInput($_POST['id'] ?? 0));

            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'Invalid Subject ID.']);
                exit;
            }

            try {
                $response = $admin->deleteSubject($id);

                if ($response) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to delete subject.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'An error occurred while deleting the subject.']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
}
