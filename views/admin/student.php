<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <link rel="stylesheet" href="../assets/css/style.css"> -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="/cssc/js/student.js"></script>
</head>
<body>
    <h1>Student Management</h1>
    <button id="addStudentBtn">Add Student</button>
    <table id="studentsTable">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Populated dynamically via AJAX -->
        </tbody>
    </table>
</body>
</html>
