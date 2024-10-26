<?php
namespace Src\Middlewares;

class AuthMiddleware {
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../resources/views/student/home.php');
            exit;
        }
    }

    public function cleanInput($input){
        if($input !== NULL){
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
        }
        return $input;
    }

    public function hashPassword($password){

    }

    private function _verifyEmail($email){
        if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')){
            return '';
        } else {
            return '';
        }
    }


    private function _verifyPassword($password){
        if(strlen($password) < 8){
            return 'minimum 8 characters';
        }
        return '';
    }

    private function _isEmpty($email, $password){
        if($email == '' && $password == ''){
            return true;
        } else {
            return false;
        }
    }

    public function verifyLoginCredentials($email, $password){
        $email_err = $password_err = '';
        $clean_email = cleanInput($email);
        $clean_password = cleanInput($password);

        if($this->_isEmpty($email, $password)){
            $email_err = 'email is required';
            $password_err = 'password is required';
        }

        if($this->_verifyEmail($clean_email)){
            $email_err = 'invalid email - use @wmsu.edu.ph';
        }

        if($email_err === '' && $password_err === ''){
            return true;
        } else {
            return [$email_err, $password_err];
        }
    }
}
?>