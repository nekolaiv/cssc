<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("database.class.php");
require_once('../tools/session.function.php');

class Auth {
    protected $database;

    public function __construct() {
        $this->database = new Database();
    }

    // LOGIN FUNCTION
    public function login($email, $password) {
        try {
            // Query to get user credentials and role
            $sql = "
                SELECT a.id AS account_id, a.password, a.status, r.name AS role, 
                       u.firstname, u.middlename, u.lastname, u.email
                FROM account a
                JOIN user u ON a.user_id = u.id
                JOIN role r ON a.role_id = r.id
                WHERE u.email = :email
            ";

            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':email', $email);
            $query->execute();

            if ($query->rowCount() == 0) {
                return ['Email does not exist', ''];
            }

            $user = $query->fetch(PDO::FETCH_ASSOC);

            // Check if account is inactive
            if ($user['status'] !== 'active') {
                return ['Account is inactive.', ''];
            }

            // Verify password
            if (!password_verify($password, $user['password'])) {
                return ['', 'Incorrect password'];
            }

            // Set session variables
            regenerateSession();
            $_SESSION['user-id'] = $user['account_id'];
            $_SESSION['user-name'] = $user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename'];
            $_SESSION['user-email'] = $user['email'];
            $_SESSION['user-role'] = $user['role'];
            $_SESSION['is-logged-in'] = true;

            return true;
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return ['Something went wrong', ''];
        }
    }

    // RESET PASSWORD FUNCTION
    public function resetPassword($email, $new_password) {
        try {
            // Check if email exists
            $user = $this->_getUserByEmail($email);
            if (!$user) {
                return ['Email does not exist', ''];
            }

            // Check if new password is the same as the old password
            if (password_verify($new_password, $user['password'])) {
                return ['', 'New password cannot be the same as the old password'];
            }

            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE account SET password = :password WHERE id = :account_id";
            $query = $this->database->connect()->prepare($sql);
            $query->bindParam(':password', $hashed_password);
            $query->bindParam(':account_id', $user['account_id']);

            if ($query->execute()) {
                return true;
            } else {
                return ['Failed to reset password', ''];
            }
        } catch (PDOException $e) {
            error_log("Password Reset Error: " . $e->getMessage());
            return ['Something went wrong', ''];
        }
    }

    // HELPER FUNCTION: Get user by email
    private function _getUserByEmail($email) {
        $sql = "
            SELECT a.id AS account_id, a.password
            FROM account a
            JOIN user u ON a.user_id = u.id
            WHERE u.email = :email
        ";

        $query = $this->database->connect()->prepare($sql);
        $query->bindParam(':email', $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            return $query->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
}
?>
