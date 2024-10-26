<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../src/classes/auth.class.php');
require_once('base-controller.class.php');
require_once('../src/middlewares/auth-middleware.class.php');

use Src\Classes\Auth;
use Src\Middlewares\AuthMiddleware;

class AuthController {

    private $root_directory;
    private $middleware;
    private $auth;

    public function __construct(){
        $this->middleware = new AuthMiddleware(); 
        $this->root_directory = dirname(__FILE__, 3);
        $this->auth = new Auth();
    }

    // ORIGINAL LOGIN VERSION
    public function login() {
        $required = '*';
        $email = $password = '';
        $email_err = $password_err = ' ';
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form-action'])){
            if($_POST['form-action'] == 'attempt-login'){
                $email = $this->middleware->cleanInput($_POST['email']);
                $password = $this->middleware->cleanInput($_POST['password']);
                $credentials_status = $this->middleware->verifyLoginCredentials($email, $password);
                if($credentials_status === true){
                    $login_status = $this->auth->login($email, $password);
                    if($login_status === true){
                        $_SESSION['is-logged-in'] = true;
                        header('Location: ' . FRONT_DIR);
                        exit;
                    } else {
                        $email_err = $login_status[0];
                        $password_err = $login_status[1];
                        require_once($this->root_directory . '/resources/views/auth/login.php');
                    }
                } else {
                    $email_err = $credentials_status[0];
                    $password_err = $credentials_status[1];
                    require_once($this->root_directory . '/resources/views/auth/login.php');
                }
            } else if($_POST['form-action'] == 'switch-to-register'){
                $_SESSION['action'] = 'register';
                header('Location: ' . FRONT_DIR);
                exit;
            }
            
        } else {
            require_once($this->root_directory . '/resources/views/auth/login.php');
        }
    }

    // ENHANCED LOGIN VERSION
    // public function login() {

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $this->processLogin();
    //     } else {
    //         $this->showLoginForm();
    //     }
    // }

    // private function processLogin() {
    //     if (isset($_POST['email']) && isset($_POST['password'])) {
    //         $email = $this->middleware->cleanInput($_POST['email']);
    //         $password = $this->middleware->cleanInput($_POST['password']);

    //         if ($this->middleware->verifyLoginCredentials($email) === true) {
    //             $this->attemptLogin($email, $password);
    //         } else {
    //             $this->handleLoginError('invalid email - use @wmsu.edu.ph');
    //         }
    //     } else {
    //         $this->showLoginForm();
    //     }
    // }

    // private function attemptLogin($email, $password) {
    //     $login_status = $this->auth->login($email, $password);
    //     if ($login_status === true) {
    //         $_SESSION['is-logged-in'] = true;
    //         header('Location: ' . FRONT_DIR);
    //         exit;
    //     } else {
    //         $this->handleLoginError($login_status);
    //     }
    // }

    // private function handleLoginError($error) {
    //     $required = '*';
    //     $email = $password = '';
    //     $email_err = $password_err = ' ';
    //     require_once($this->root_directory . '/resources/views/auth/login.php');
    // }

    // private function showLoginForm() {
    //     $required = '*';
    //     $email = $password = '';
    //     require_once($this->root_directory . '/resources/views/auth/login.php');
    // }


    // public function logout() {
    //     $_SESSION = [];
    //     session_destroy();
    //     header('Location: ' . FRONT_DIR);
    //     exit;
    // }


    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle user registration logic (e.g., saving user to the database)
            $username = $_POST['username'];
            $password = $_POST['password'];
            // Assume User model has a register method
            $user = new User();
            if ($user->register($username, $password)) {
                header('Location: /login');
                exit;
            } else {
                echo "Registration failed.";
            }
        } else {
            $this->render('auth/register');
        }
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle forgot password logic (e.g., sending reset email)
            $email = $_POST['email'];
            // Assume User model has a method to send reset email
            $user = new User();
            if ($user->sendResetEmail($email)) {
                echo "Reset email sent.";
            } else {
                echo "Email not found.";
            }
        } else {
            $this->render('auth/forgot-password');
        }
    }
}
?>