<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="/cssc/vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="/cssc/js/admin/student_management.js"></script> -->
</head>
<body>
    <div class="container mt-4">
        <h1>Student Management</h1>

        <!-- Button to trigger the "Add Student" modal -->
        <button class="btn btn-primary mb-3" id="addStudentBtn">Create Student</button>
        
        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchStudent" class="form-control" placeholder="Search by Student ID or Name">
        </div>
        
        <!-- Table to display all students -->
        <table class="table table-bordered" id="studentsTable">
            <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Section</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated dynamically via AJAX -->
            </tbody>
        </table>
        <nav id="pagination" aria-label="Page navigation">
        <ul class="pagination justify-content-center"></ul>
        </nav>
    </div>

    <!-- Include Modals -->
    <?php include 'addedit-student-modal.php'; ?>
</body>
</html>
