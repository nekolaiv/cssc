<?php
namespace Src\Middlewares;

class AuthMiddleware {
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
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
    }

    private function _verifyPassword($password){
        if(strlen($password) < 8){
            return 'minimum 8 characters';
        }
    }

    public function verifyCredentials($email, $password){
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);

        $email_err = $this->_verifyEmail($email) ?? NULL;
        $password_err = $this->_verifyPasword($password) ?? NULL;

        if($email_err === NULL && $password_err === NULL){
            return NULL;
        } else {
            [$email_err, $password_err];
        }
    }
    
}
?>