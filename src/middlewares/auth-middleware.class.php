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
        if(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph'){
            return true;
        } else {
            return false;
        }
    }


    private function _verifyPasswordLength($password){
        if(strlen($password) >= 8){
            return true;
        } else {
            return false;
        }
    }

    private function _verifyPasswordMatch($password, $confirm_password){
        if($password === $confirm_password){
            return true;
        } else {
            return false;
        }
    }

    public function _isEmpty($input){
        if($input === '' || $input === NULL){
            return true;
        } else {
            return false;
        }
    }

    public function verifyLoginCredentials($email, $password){
        $clean_email = cleanInput($email);
        $clean_password = cleanInput($password);
        $email_err = $password_err = ' ';

        if($this->_isEmpty($email)){
            $email_err = 'email is required';
        }

        if($this->_isEmpty($password)){
            $password_err = 'password is required';
        }

        if(!$this->_verifyEmail($clean_email)){
            $email_err = 'invalid email - use @wmsu.edu.ph';
        }

        if($email_err === ' ' && $password_err === ' '){
            return true;
        } else {
            return [$email_err, $password_err];
        }
    }

    public function verifyRegisterCredentials($email, $password, $confirm_password){
        $clean_email = cleanInput($email);
        $clean_password = cleanInput($password);
        $clean_confirm_password = cleanInput($confirm_password);
        $email_err = $password_err = $confirm_password_err = ' ';
        

        if($this->_isEmpty($email)){
            $email_err = 'email is required';
        } else if(!$this->_verifyEmail($clean_email)){
            $email_err = 'invalid email - use @wmsu.edu.ph';
        }

        if($this->_isEmpty($password)){
            $password_err = 'password is required';
        } else if(!$this->_verifyPasswordLength($clean_password)){
            $password_err = 'minimum 8 characters';
        } else if(!$this->_verifyPasswordMatch($clean_password, $clean_confirm_password)){
            $password_err = 'passwords do not match';
        }

        if($this->_isEmpty($confirm_password)){
            $confirm_password_err = 'confirm password is required';
        } else if(!$this->_verifyPasswordLength($clean_confirm_password)){
            $confirm_password_err = 'minimum 8 characters';
        } else if(!$this->_verifyPasswordMatch($clean_password, $clean_confirm_password)){
            $confirm_password_err = 'passwords do not match';
        }

        if($email_err === ' ' && $password_err === ' ' && $confirm_password_err === ' '){
            return true;
        } else {
            return [$email_err, $password_err, $confirm_password_err];
        }
    }

    public function verifyResetPasswordCredentials($email, $new_password, $confirm_password){
        $clean_email = cleanInput($email);
        $clean_new_password = cleanInput($new_password);
        $clean_confirm_password = cleanInput($confirm_password);
        $email_err = $new_password_err = $confirm_password_err = ' ';
        

        if($this->_isEmpty($email)){
            $email_err = 'email is required';
        } else if(!$this->_verifyEmail($clean_email)){
            $email_err = 'invalid email - use @wmsu.edu.ph';
        }

        if($this->_isEmpty($new_password)){
            $new_password_err = 'password is required';
        } else if(!$this->_verifyPasswordLength($clean_new_password)){
            $new_password_err = 'minimum 8 characters';
        } else if(!$this->_verifyPasswordMatch($clean_new_password, $clean_confirm_password)){
            $new_password_err = 'passwords do not match';
        }

        if($this->_isEmpty($confirm_password)){
            $confirm_password_err = 'confirm password is required';
        } else if(!$this->_verifyPasswordLength($clean_confirm_password)){
            $confirm_password_err = 'minimum 8 characters';
        } else if(!$this->_verifyPasswordMatch($clean_new_password, $clean_confirm_password)){
            $confirm_password_err = 'passwords do not match';
        }
        
        if($email_err === ' ' && $password_err === ' ' && $confirm_password_err === ' '){
            return true;
        } else {
            return [$email_err, $password_err, $confirm_password_err];
        }
    }
}
?>