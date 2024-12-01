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
        <h1>Staff Management</h1>
        
        <!-- Button to trigger the "Add Staff" modal -->
        <button class="btn btn-primary mb-3" id="addStaffBtn">Add Staff</button>
        
        <!-- Table to display staff -->
        <table class="table table-bordered" id="staffTable">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated dynamically via AJAX -->
            </tbody>
        </table>

        <!-- Pagination -->
        <nav id="pagination" aria-label="Page navigation">
            <ul class="pagination justify-content-center"></ul>
        </nav>
    </div>

    <!-- Include Add/Edit Modal -->
    <?php include 'addedit-staff-modal.php'; ?>
</body>
</html>
