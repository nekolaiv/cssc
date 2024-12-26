<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year_level = $_POST['year_level'];
    $student = new Student();

    $cs_topnotcher = $student->getStudentTopNotcher($year_level, 1);
    $it_topnotcher = $student->getStudentTopNotcher($year_level, 2);
    if($year_level <= 2){
        $act_topnotcher = $student->getStudentTopNotcher($year_level, 3);
    } else {
        $act_topnotcher = 'None';
    }

    header('Content-Type: application/json');
    echo json_encode([
        'cs_topnotcher' => $cs_topnotcher,
        'it_topnotcher' => $it_topnotcher,
        'act_topnotcher' => $act_topnotcher
    ]);

}
