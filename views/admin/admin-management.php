<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/cssc/js/admin-management.js"></script>
</head>
<body>
<div class="container mt-4">
    <h1>Admin Management</h1>

    <!-- Button to trigger the "Add Admin" modal -->
    <button class="btn btn-primary mb-3" id="addAdminBtn">Create Admin</button>

    <!-- Table to display all admins -->
    <table class="table table-bordered" id="adminsTable">
        <thead>
            <tr>
                <th>Admin ID</th>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Populated dynamically via AJAX -->
        </tbody>
    </table>

    <!-- Include Modals -->
    <?php include 'addedit-admin-modal.php'; ?>
</div>
</body>
</html>
