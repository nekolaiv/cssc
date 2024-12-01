<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="/cssc/js/admin/staff_management.js"></script> -->
</head>
<body>
<div class="container mt-4">
    <h2>Staff Management</h2>
    <button id="addStaffBtn" class="btn btn-primary mb-3">Add Staff</button>
    <!-- Search Bar -->
    <div class="mb-3">
        <input type="text" id="searchStaff" class="form-control" placeholder="Search by Student ID or Name">
    </div>
    <table id="staffTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Populated by JavaScript -->
        </tbody>
    </table>
    <nav>
        <ul id="pagination" class="pagination justify-content-center">
            <!-- Populated by JavaScript -->
        </ul>
    </nav>
</div>

    <!-- Include Add/Edit Modal -->
    <?php include 'addedit-staff-modal.php'; ?>
</body>
</html>
