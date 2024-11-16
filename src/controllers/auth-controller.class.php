<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        $email_err = $_SESSION['email-err'] ?? ' ';
        $password_err = $_SESSION['password-err'] ?? ' ';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form-action'])) {
			if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
				die("Invalid CSRF token.");
			}
            if ($_POST['form-action'] === 'attempt-login') {
                // Clean inputs
                $email = $this->middleware->cleanInput($_POST['email']) ?? NULL;
                $password = $this->middleware->cleanInput($_POST['password']) ?? NULL;

                // Verify credentials
                $credentials_status = $this->middleware->verifyLoginCredentials($email, $password) ?? NULL;

                if ($credentials_status === true) {
                    // Attempt login
                    $login_status = $this->auth->login($email, $password);
                    if ($login_status === true) {
                        unset($_SESSION['email-err']);
                        unset($_SESSION['password-err']);
						unset($_SESSION['csrf_token']);
                        header('Location: ' . FRONT_DIR);
                        exit;
                    } else {
                        // Handle login errors
                        $_SESSION['email-err'] = $login_status[0] ?? NULL;
                        $_SESSION['password-err'] = $login_status[1] ?? NULL;
                    }
                } else {
                    // Handle credential verification errors
                    $_SESSION['email-err'] = $credentials_status[0] ?? NULL;
                    $_SESSION['password-err'] = $credentials_status[1] ?? NULL;
                }

                // Redirect to the login page to prevent resubmission
                $_SESSION['action'] = 'login';
				unset($_SESSION['csrf_token']);
                header('Location: ' . FRONT_DIR);

            } else if ($_POST['form-action'] === 'switch-to-register') {
                $_SESSION['action'] = 'register';
				unset($_SESSION['email-err']);
				unset($_SESSION['password-err']);
				unset($_SESSION['csrf_token']);
                header('Location: ' . FRONT_DIR);
                exit;

            } else if ($_POST['form-action'] === 'forgot-password') {
                $_SESSION['action'] = 'forgot-password';
				unset($_SESSION['email-err']);
				unset($_SESSION['password-err']);
				unset($_SESSION['csrf_token']);
                header('Location: ' . FRONT_DIR);
                exit;
            }
        }

        // Render the login view if no form submission
        include_once($this->root_directory . '/resources/views/auth/login.php');
    }

    public function register() {
        $required = '*';
        $email = $password = $confirm_password = '';
        $email_err = $password_err = $confirm_password_err = ' ';
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form-action'])) {
            if($_POST['form-action'] === 'attempt-register') {
                $email = $this->middleware->cleanInput($_POST['email']) ?? NULL;
                $password = $this->middleware->cleanInput($_POST['password']) ?? NULL;
                $confirm_password = $this->middleware->cleanInput($_POST['confirm-password']) ?? NULL;
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
                $email = $this->middleware->cleanInput($_POST['email']) ?? NULL;
                $new_password = $this->middleware->cleanInput($_POST['new-password']) ?? NULL;
                $confirm_password = $this->middleware->cleanInput($_POST['confirm-password']) ?? NULL;
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