<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Src\Classes\Auth;
use Src\Middlewares\AuthMiddleware;

class RouteController {

    protected $middleware;

    public function __construct(){
        $this->middleware = new AuthMiddleware(); 
    }

    public function studentMainView(){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
            unset($_SESSION['is-logged-in']);
            $_SESSION['action'] = 'logout';
            header('Location: ' . FRONT_DIR);
            exit;
        }
        require_once('../resources/views/student/home.php');
    }

    public function staffMainView(){
        
    }

    public function adminMainView(){
        
    }
}
?>