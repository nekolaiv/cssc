<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="/cssc/js/admin/student_management.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Student Management</h1>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add New Student</button>
        </div>
        <table class="table table-bordered table-striped" id="studentTable">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course ID</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be dynamically populated via AJAX -->
            </tbody>
        </table>
        <nav aria-label="Student Table Pagination">
    <ul class="pagination justify-content-center" id="pagination">
        <!-- Pagination links will be dynamically generated -->
    </ul>
</nav>

    </div>

    <!-- Include modals for add/edit -->
    <?php include 'addedit-student-modal.html'; ?>

</body>
</html>
