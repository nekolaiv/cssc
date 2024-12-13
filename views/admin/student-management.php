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

        <div class="row mb-3">
        <!-- Filter by Course -->
        <div class="col-md-4">
            <label for="filterCourse" class="form-label">Filter by Course</label>
            <select class="form-control" id="filterCourse">
            <option value="">All Courses</option>
            </select>
        </div>
        <!-- Filter by Year Level -->
        <div class="col-md-4">
            <label for="filterYear" class="form-label">Filter by Year Level</label>
            <select class="form-control" id="filterYear">
            <option value="">All Year Levels</option>
            <option value="1">1st Year</option>
            <option value="2">2nd Year</option>
            <option value="3">3rd Year</option>
            <option value="4">4th Year</option>
            </select>
        </div>
        <!-- Filter by Section -->
        <div class="col-md-4">
            <label for="filterSection" class="form-label">Filter by Section</label>
            <select class="form-control" id="filterSection">
            <option value="">All Sections</option>
            <option value="A">Section A</option>
            <option value="B">Section B</option>
            <option value="C">Section C</option>
            </select>
        </div>
        </div>  
        <!-- Table to display all students -->
        <table class="table table-bordered" id="studentsTable">
            <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Curriculum Code</th>
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
    <?php include 'add-student-modal.php'; ?>
    <?php include 'edit-student-modal.php'; ?>
</body>
</html>
