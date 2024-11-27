<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once("database.class.php");

class Admin {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    // Create a new admin account
    public function createAdmin($email, $password) {
        $query = "INSERT INTO Admin_Accounts (email, password) VALUES (:email, :password)";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

    // Read all admins
    public function getAllAdmins() {
        $query = "SELECT * FROM Admin_Accounts";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get admin by ID
    public function getAdminById($id) {
        $query = "SELECT * FROM Admin_Accounts WHERE admin_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update admin profile
    public function updateAdmin($id, $email) {
        $query = "UPDATE Admin_Accounts SET email = :email WHERE admin_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // Delete an admin account
    public function deleteAdmin($id) {
        $query = "DELETE FROM Admin_Accounts WHERE admin_id = :id";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

 // Check if staff email exists (for both create and update)
 public function staffEmailExists($email, $excludeId = null) {
    $query = "SELECT COUNT(*) FROM staff_accounts WHERE email = :email";
    $params = [':email' => $email];

    if ($excludeId !== null) {
        $query .= " AND staff_id != :staff_id";
        $params[':staff_id'] = $excludeId;
    }

    return $this->database->fetchColumn($query, $params) > 0;
}

// Create a new staff account
public function createStaff($data) {
    $query = "INSERT INTO staff_accounts (email, password) VALUES (:email, :password)";
    $params = [
        ':email' => $data['email'],
        ':password' => $data['password']
    ];

    return $this->database->execute($query, $params);
}

// Get all staff accounts
public function getAllStaff() {
    $query = "SELECT staff_id, email, password FROM staff_accounts";
    return $this->database->fetchAll($query);
}

// Get a specific staff account by ID
public function getStaffById($staff_id) {
    $query = "SELECT staff_id, email, password FROM staff_accounts WHERE staff_id = :staff_id";
    $params = [':staff_id' => $staff_id];
    return $this->database->fetch($query, $params);
}

// Update an existing staff account
public function updateStaff($data) {
    $query = "UPDATE staff_accounts SET email = :email";

    $params = [
        ':email' => $data['email'],
        ':staff_id' => $data['staff_id']
    ];

    if (isset($data['password']) && !empty($data['password'])) {
        $query .= ", password = :password";
        $params[':password'] = $data['password'];
    }

    $query .= " WHERE staff_id = :staff_id";

    return $this->database->execute($query, $params);
}

// Delete a staff account
public function deleteStaff($staff_id) {
    $query = "DELETE FROM staff_accounts WHERE staff_id = :staff_id";
    $params = [':staff_id' => $staff_id];
    return $this->database->execute($query, $params);
}

    public function createStudent($data) {
        $query = "INSERT INTO Registered_Students (student_id, email, password, first_name, last_name, middle_name, course, year_level, section, role)
                  VALUES (:student_id, :email, :password, :first_name, :last_name, :middle_name, :course, :year_level, :section, 'student')";
        return $this->database->execute($query, $data);
    }

    public function getAllStudents() {
        $query = "SELECT * FROM Registered_Students";
        return $this->database->fetchAll($query);
    }

    public function updateStudent($data)
    {
        $query = "UPDATE registered_students SET 
            student_id = :student_id, 
            email = :email, 
            first_name = :first_name, 
            middle_name = :middle_name, 
            last_name = :last_name, 
            course = :course, 
            year_level = :year_level, 
            section = :section";
    
        // Include password update only if provided
        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }
    
        $query .= " WHERE user_id = :user_id";
    
        $stmt = $this->database->connect()->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':student_id', $data['student_id']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':middle_name', $data['middle_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':course', $data['course']);
        $stmt->bindParam(':year_level', $data['year_level']);
        $stmt->bindParam(':section', $data['section']);
        $stmt->bindParam(':user_id', $data['user_id']);
    
        // Bind password if provided
        if (!empty($data['password'])) {
            $stmt->bindParam(':password', $data['password']);
        }
    
        return $stmt->execute();
    }
    
    

    public function deleteStudent($user_id) {
        $query = "DELETE FROM Registered_Students WHERE user_id = :user_id";
        return $this->database->execute($query, ['user_id' => $user_id]);
    }

    public function getStudentById($user_id) {
        $query = "SELECT * FROM Registered_Students WHERE user_id = :user_id LIMIT 1";
    
        // Execute the query with the user_id as a parameter
        return $this->database->fetchOne($query, ['user_id' => $user_id]);
    }

    public function studentIdExists($student_id, $exclude_user_id = null) {
        $query = "SELECT COUNT(*) FROM registered_students WHERE student_id = :student_id";
        if ($exclude_user_id) {
            $query .= " AND user_id != :exclude_user_id";
        }
    
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindValue(':student_id', $student_id, PDO::PARAM_STR);
        if ($exclude_user_id) {
            $stmt->bindValue(':exclude_user_id', $exclude_user_id, PDO::PARAM_INT);
        }
        $stmt->execute();
    
        return $stmt->fetchColumn() > 0;
    }
    

    public function studentIdExistsForOther($student_id, $user_id)
{
    $query = "SELECT COUNT(*) FROM Registered_Students WHERE student_id = :student_id AND user_id != :user_id";
    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}

}
?>