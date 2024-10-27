<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('base-controller.class.php');
require_once('../src/classes/auth.class.php');
require_once('../src/middlewares/auth-middleware.class.php');

use Src\Classes\Auth;
use Src\Middlewares\AuthMiddleware;

class AuthController {

    private $auth;
    private $middleware;
    private $root_directory;

    public function __construct(){
        $this->middleware = new AuthMiddleware(); 
        $this->root_directory = dirname(__FILE__, 3);
        $this->auth = new Auth();
    }

    public function login() {
        $required = '*';
        $email = $password = '';
        $email_err = $password_err = ' ';
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form-action'])) {
            if($_POST['form-action'] === 'attempt-login') {
                // echo 'attempt login';
                $email = $this->middleware->cleanInput($_POST['email']);
                $password = $this->middleware->cleanInput($_POST['password']);
                $credentials_status = $this->middleware->verifyLoginCredentials($email, $password);
                if($credentials_status === true) {
                    $login_status = $this->auth->login($email, $password);
                    if($login_status === true) {
                        $_SESSION['is-logged-in'] = true;
                        header('Location: ' . FRONT_DIR);
                        exit;
                    } else {
                        $email_err = $login_status[0] ?? NULL;
                        $password_err = $login_status[1] ?? NULL;
                        require_once($this->root_directory . '/resources/views/auth/login.php');
                    }
                } else {
                    $email_err = $credentials_status[0] ?? NULL;
                    $password_err = $credentials_status[1] ?? NULL;
                    require_once($this->root_directory . '/resources/views/auth/login.php');
                }
            } else if($_POST['form-action'] === 'switch-to-register') {
                $_SESSION['action'] = 'register';
                header('Location: ' . FRONT_DIR);
                exit;
            } else if($_POST['form-action'] === 'forgot-password') {
                $_SESSION['action'] = 'forgot-password';
                header('Location: ' . FRONT_DIR, 'Refresh: 0');
                exit;
            }  
        } else {
            require_once($this->root_directory . '/resources/views/auth/login.php');
        }
    }

    public function register() {
        $required = '*';
        $email = $password = $confirm_password = '';
        $email_err = $password_err = $confirm_password_err = ' ';
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form-action'])) {
            if($_POST['form-action'] === 'attempt-register') {
                $email = $this->middleware->cleanInput($_POST['email']);
                $password = $this->middleware->cleanInput($_POST['password']);
                $confirm_password = $this->middleware->cleanInput($_POST['confirm-password']);
                $credentials_status = $this->middleware->verifyRegisterCredentials($email, $password, $confirm_password);
                if($credentials_status === true) {
                    $register_status = $this->auth->register($email, $password);
                    if($register_status === true) {
                        $_SESSION['action'] = 'login';
                        $_SESSION['feedback'] = 'account registered successfully';
                        header('Location: ' . FRONT_DIR, 'Refresh: 0');
                        exit;
                    } else {
                        $email_err = $register_status ?? NULL;
                        require_once($this->root_directory . '/resources/views/auth/register.php');
                    }
                } else {
                    $email_err = $credentials_status[0] ?? NULL;
                    $password_err = $credentials_status[1] ?? NULL;
                    $confirm_password_err = $credentials_status[2] ?? NULL;
                    require_once($this->root_directory . '/resources/views/auth/register.php');
                }
            } else if($_POST['form-action'] == 'switch-to-login'){
                $_SESSION['action'] = 'login';
                header('Location: ' . FRONT_DIR, 'Refresh: 0');
                exit;
            }  
        } else {
            require_once($this->root_directory . '/resources/views/auth/register.php');
        }
    }

    public function forgotPassword() {
        $required = '*';
        $email = $new_password = $confirm_password = '';
        $email_err = $new_password_err = $confirm_password_err = ' ';
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form-action'])) {
            if($_POST['form-action'] === 'attempt-reset-password') {
                $email = $this->middleware->cleanInput($_POST['email']);
                $new_password = $this->middleware->cleanInput($_POST['new-password']);
                $confirm_password = $this->middleware->cleanInput($_POST['confirm-password']);
                $credentials_status = $this->middleware->verifyRegisterCredentials($email, $new_password, $confirm_password);
                if($credentials_status === true) {
                    $reset_password_status = $this->auth->resetPassword($email, $new_password);
                    if($reset_password_status === true) {
                        $_SESSION['action'] = 'login';
                        $_SESSION['feedback'] = 'password reset successfully';
                        header('Location: ' . FRONT_DIR, 'Refresh: 0');
                        exit;
                    } else {
                        $email_err = $reset_password_status[0] ?? NULL;
                        $new_password_err = $reset_password_status[1] ?? NULL;
                        require_once($this->root_directory . '/resources/views/auth/forgot-password.php');
                    }
                } else {
                    $email_err = $credentials_status[0] ?? NULL;
                    $new_password_err = $credentials_status[1] ?? NULL;
                    $confirm_password_err = $credentials_status[2] ?? NULL;
                    require_once($this->root_directory . '/resources/views/auth/forgot-password.php');
                }
            } else if($_POST['form-action'] == 'switch-to-login'){
                $_SESSION['action'] = 'login';
                header('Location: ' . FRONT_DIR, 'Refresh: 0');
                exit;
            }  
        } else {
            require_once($this->root_directory . '/resources/views/auth/forgot-password.php');
        }
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . FRONT_DIR);
        exit;
    }

    // =================== DUMPS BUT MIGHT BE USEFUL =================== 
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
}
?>