<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);


class RouteController {

    public function studentMainView(){
        require_once('../resources/views/student/home.php');
    }

    public function staffMainView(){
        
    }

    public function adminMainView(){
        
    }
}
?>