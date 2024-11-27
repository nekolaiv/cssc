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
  // Create Admin
  public function createAdmin($data) {
    $sql = "INSERT INTO admin_accounts (email, password) VALUES (:email, :password)";
    $stmt = $this->database->connect()->prepare($sql);
    $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
    $stmt->bindValue(':password', $data['password'], PDO::PARAM_STR);

    return $stmt->execute();
}

// Get All Admins
public function getAllAdmins() {
    $sql = "SELECT admin_id, email, password FROM admin_accounts";
    return $this->database->fetchAll($sql);
}

// Get Admin By ID
public function getAdminById($admin_id) {
    $sql = "SELECT admin_id, email FROM admin_accounts WHERE admin_id = :admin_id";
    $stmt = $this->database->connect()->prepare($sql);
    $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update Admin
public function updateAdmin($data) {
    $query = "UPDATE admin_accounts SET email = :email";
    
    if (!empty($data['password'])) {
        $query .= ", password = :password";
    }
    
    $query .= " WHERE admin_id = :admin_id";

    $stmt = $this->database->connect()->prepare($query);
    $stmt->bindValue(':email', $data['email']);
    if (!empty($data['password'])) {
        $stmt->bindValue(':password', $data['password']);
    }
    $stmt->bindValue(':admin_id', $data['admin_id']);
    return $stmt->execute();
}


// Delete Admin
public function deleteAdmin($admin_id) {
    $sql = "DELETE FROM admin_accounts WHERE admin_id = :admin_id";
    $stmt = $this->database->connect()->prepare($sql);
    $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);

    return $stmt->execute();
}

// Check if Email Exists
public function emailExists($email, $exclude_admin_id = null) {
    $sql = "SELECT admin_id FROM admin_accounts WHERE email = :email";
    if ($exclude_admin_id) {
        $sql .= " AND admin_id != :admin_id";
    }

    $stmt = $this->database->connect()->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    if ($exclude_admin_id) {
        $stmt->bindValue(':admin_id', $exclude_admin_id, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchColumn() ? true : false;
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

public function verifyPassword($plainPassword, $hashedPassword) {
    return password_verify($plainPassword, $hashedPassword);
}


}
?>