<?php
namespace Src\Middlewares;

class StudentMiddleware {
    public function cleanInput($input){
        if($input === '' || $input === NULL){
            return false;
        } else { 
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
            return $input;
        }
    }

    public function cleanNumericInput($input) {
        if ($input === '' || $input === NULL) {
            return false;
        } else { 
            // Clean the input (trim, stripslashes, htmlspecialchars)
            $input = trim($input);           // Remove leading/trailing whitespace
            $input = stripslashes($input);    // Remove slashes (if any)
            $input = htmlspecialchars($input); // Convert special characters to HTML entities

            // Validate if the input is a valid number (integer or float)
            if (is_numeric($input)) {
                return $input;  // Return the cleaned input
            } else {
                return false;  // Invalid input, return false
            }
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
        $clean_email = $this->cleanInput($email);
        $clean_password = $this->cleanInput($password);
        $email_err = $password_err = ' ';

        if($this->_isEmpty($clean_email)){
            $email_err = 'email is required';
        } else if(!$this->_verifyEmail($clean_email)){
            $email_err = 'invalid email - use @wmsu.edu.ph';
        }

        if($this->_isEmpty($clean_password)){
            $password_err = 'password is required';
        }

        if($email_err === ' ' && $password_err === ' '){
            return true;
        } else {
            return [$email_err, $password_err];
        }
    }

    public function verifyRegisterCredentials($email, $password, $confirm_password){
        $clean_email = $this->cleanInput($email);
        $clean_password = $this->cleanInput($password);
        $clean_confirm_password = $this->cleanInput($confirm_password);
        $email_err = $password_err = $confirm_password_err = ' ';
        

        if($this->_isEmpty($clean_email)){
            $email_err = 'email is required';
        } else if(!$this->_verifyEmail($clean_email)){
            $email_err = 'invalid email - use @wmsu.edu.ph';
        }

        if($this->_isEmpty($clean_password)){
            $password_err = 'password is required';
        } else if(!$this->_verifyPasswordLength($clean_password)){
            $password_err = 'minimum 8 characters';
        } else if(!$this->_verifyPasswordMatch($clean_password, $clean_confirm_password)){
            $password_err = 'passwords do not match';
        }

        if($this->_isEmpty($clean_confirm_password)){
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
        $clean_email = $this->cleanInput($email);
        $clean_new_password = $this->cleanInput($new_password);
        $clean_confirm_password = $this->cleanInput($confirm_password);
        $email_err = $new_password_err = $confirm_password_err = ' ';
        

        if($this->_isEmpty($clean_email)){
            $email_err = 'email is required';
        } else if(!$this->_verifyEmail($clean_email)){
            $email_err = 'invalid email - use @wmsu.edu.ph';
        }

        if($this->_isEmpty($clean_new_password)){
            $new_password_err = 'password is required';
        } else if(!$this->_verifyPasswordLength($clean_new_password)){
            $new_password_err = 'minimum 8 characters';
        } else if(!$this->_verifyPasswordMatch($clean_new_password, $clean_confirm_password)){
            $new_password_err = 'passwords do not match';
        }

        if($this->_isEmpty($clean_confirm_password)){
            $confirm_password_err = 'confirm password is required';
        } else if(!$this->_verifyPasswordLength($clean_confirm_password)){
            $confirm_password_err = 'minimum 8 characters';
        } else if(!$this->_verifyPasswordMatch($clean_new_password, $clean_confirm_password)){
            $confirm_password_err = 'passwords do not match';
        }
        
        if($email_err === ' ' && $new_password_err === ' ' && $confirm_password_err === ' '){
            return true;
        } else {
            return [$email_err, $new_password_err, $confirm_password_err];
        }
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
}
?>