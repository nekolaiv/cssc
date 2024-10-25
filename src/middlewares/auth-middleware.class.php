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
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public function hashPassword($password){

    }

    private function _verifyEmail($email){
        if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')){
            return 'invalid email - use @wmsu.edu.ph';
        } else if(!($auth->emailExists($email))){
            return 'email does not exist';
        }
        return NULL;
    }


    private function _verifyPassword($password){
        if(strlen($password) < 8){
            return 'minimum 8 characters';
        }
        return NULL;
    }

    public function verifyCredentials($email, $password, $password2=NULL){
        $email_err = $password_err = $password2_err = NULL;

        $clean_email = cleanInput($email);
        $email_err = $this->_verifyEmail($clean_email);

        $clean_password = cleanInput($password);
        $password_err = $this->_verifyPassword($clean_password);

        if($password2 !== NULL){
            $clean_password2 = cleanInput($password2);
            $password2_err = $this->_verifyPassword($clean_password);
        }

        if($email_err === NULL && $password_err === NULL && $password2_err === NULL){
            return true;
        } else {
            [$email_err, $password_err, $password2_err];
        }
    }
}
?>