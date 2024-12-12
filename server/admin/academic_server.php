<?php
require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    try {
        switch ($action) {
            case 'get_all_terms':
                $terms = $admin->getAllAcademicTerms();
                echo json_encode(['success' => true, 'data' => $terms]);
                break;

            case 'add_academic_term':
                $academic_year = cleanInput($_POST['academic_year'] ?? '');
                $semester = cleanInput($_POST['semester'] ?? '');
                $start_date = cleanInput($_POST['start_date'] ?? '');
                $end_date = cleanInput($_POST['end_date'] ?? '');

                if (!$academic_year || !$semester || !$start_date || !$end_date) {
                    throw new Exception("All fields are required.");
                }

                $newTermId = $admin->addAcademicTerm($academic_year, $semester, $start_date, $end_date);
                echo json_encode(['success' => true, 'message' => "New term added with ID $newTermId."]);
                break;

            case 'update_academic_term':
                $term_id = intval(cleanInput($_POST['term_id'] ?? 0));
                $academic_year = cleanInput($_POST['academic_year'] ?? '');
                $semester = cleanInput($_POST['semester'] ?? '');
                $start_date = cleanInput($_POST['start_date'] ?? '');
                $end_date = cleanInput($_POST['end_date'] ?? '');

                if (!$term_id || !$academic_year || !$semester || !$start_date || !$end_date) {
                    throw new Exception("All fields are required.");
                }

                $updatedRows = $admin->updateAcademicTerm($term_id, $academic_year, $semester, $start_date, $end_date);
                echo json_encode(['success' => true, 'message' => "$updatedRows row(s) updated."]);
                break;

            case 'toggle_active_term':
                $term_id = intval(cleanInput($_POST['term_id'] ?? 0));
                if (!$term_id) {
                    throw new Exception("Invalid term ID.");
                }
                $updatedRows = $admin->toggleActiveTerm($term_id);
                echo json_encode(['success' => true, 'message' => "$updatedRows term activated."]);
                break;

            case 'get_all_gwa_schedules':
                $schedules = $admin->getAllGwaSchedules();
                echo json_encode(['success' => true, 'data' => $schedules]);
                break;

            case 'add_gwa_schedule':
                $term_id = intval(cleanInput($_POST['term_id'] ?? 0));
                $gwa_submission_start = cleanInput($_POST['gwa_submission_start'] ?? '');
                $gwa_submission_end = cleanInput($_POST['gwa_submission_end'] ?? '');

                if (!$term_id || !$gwa_submission_start || !$gwa_submission_end) {
                    throw new Exception("All fields are required.");
                }

                $newScheduleId = $admin->addGwaSchedule($term_id, $gwa_submission_start, $gwa_submission_end);
                echo json_encode(['success' => true, 'message' => "New GWA schedule added with ID $newScheduleId."]);
                break;

            case 'toggle_active_gwa_schedule':
                $submission_id = intval(cleanInput($_POST['submission_id'] ?? 0));
                if (!$submission_id) {
                    throw new Exception("Invalid submission ID.");
                }
                $updatedRows = $admin->toggleActiveGwaSchedule($submission_id);
                echo json_encode(['success' => true, 'message' => "$updatedRows schedule activated."]);
                break;

            default:
                throw new Exception("Invalid action.");
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
