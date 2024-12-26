<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');

    $student = new Student();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $subject_names = $_SESSION['course-fields']['subject-name'];
        $subject_codes = $_SESSION['course-fields']['subject-code'];
        $grades = $_SESSION['course-fields']['grade'];
        $units = $_SESSION['course-fields']['unit'];

        for ($i = 0; $i < count($subject_codes); $i++) {
            if ($subject_codes[$i] !== NULL && $grades[$i] !== NULL && $units[$i] !== NULL) {
                $subject_names[$i] = cleanInput($subject_names[$i]);
                $subject_codes[$i] = cleanInput($subject_codes[$i]);
                $grades[$i] = cleanNumericInput($grades[$i]);
                $units[$i] = cleanNumericInput($units[$i]);
            }
        }
        $gwa_result = $student->calculateGWA($subject_codes, $grades, $units);

        if ($gwa_result >= 1 && $gwa_result <= 2) {
            $_SESSION['GWA'] = ['message-1' => 'Congratulations!', 'message-2' => 'You are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result];
        } else if ($gwa_result > 2 && $gwa_result <= 5) {
            $_SESSION['GWA'] = ['message-1' => "We're sorry", 'message-2' => 'You not are qualified for:', 'message-3' => "Dean's Lister", 'gwa-score' => $gwa_result];
        } else {
            $_SESSION['GWA'] = ['message-1' => "Invalid Grade", 'message-2' => 'There must be a mistake with your inputs', 'message-3' => "Edit Inputs to Double Check", 'gwa-score' => $gwa_result];
        }
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => "success calculating"]);
?>
