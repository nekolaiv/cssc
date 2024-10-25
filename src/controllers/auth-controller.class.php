<?php
namespace Src\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../src/classes/auth.class.php');
require_once('base-controller.class.php');
require_once("../src/utils/clean.function.php");

use Src\Classes\Auth;
use Src\Middlewares\AuthMiddleware;

class AuthController extends BaseController {

    protected $middleware;

    public function __construct(){
        $this->$middleware = new AuthMiddleware(); 
    }

    public function login() {
        $required = '*';
        $email = $password = '';
        $email_err = $password_err = ' ';
        $auth = new Auth();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = $this->middleware->cleanInput($_POST['email']);
            $password = $this->middleware->cleanInput($_POST['password']);

            $credentials = $this->middleware->verifyCredentials($email, $password);

            if($credentials !== NULL){
                
            }

            if($this->middleware->verifyCredentials($email, $password)){
                $_SESSION['is-logged-in'] = true;
            }
            

            if($email_err == ' ' && $password_err == ' '){
                echo 'login';
                if($auth->login($email, $password)){
                    $_SESSION['is-logged-in'] = true;
                    header('Location: ../../public/index.php');
                    exit;
                } else {
                    $password_err = "incorrect password"; // Temporary, verify later with hashed.
                }
            } else {
                require_once('../resources/views/auth/login.php');
            }
            
            // if ($user->login($_POST['email'], $_POST['password'])) {
            //     $_SESSION['user-id'] = $_POST['username']; // Assuming username is user ID
            //     $_SESSION['is-logged-in'] = true;
            //     header('Location: /dashboard');
            //     exit;
            // } else {
            //     echo "Invalid credentials.";
            // }
        } else {
            require_once('../resources/views/auth/login.php');
        }
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }


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