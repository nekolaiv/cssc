<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container-fluid px-4">
        <!-- Greeting Section -->
        <div class="row my-4">
            <div class="col-12">
                <h4 class="text-muted">Hello, Admin</h4>
                <p class="text-secondary" id="currentDate">Loading date...</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <h6 class="text-muted">ACCOUNTS OVERVIEW</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">STUDENTS</h6>
                        <h2 class="fw-bold" id="studentCount">0</h2>
                        <i class="lni lni-users fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">STAFF</h6>
                        <h2 class="fw-bold" id="staffCount">0</h2>
                        <i class="lni lni-user fs-2 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">ADMINS</h6>
                        <h2 class="fw-bold" id="adminCount">0</h2>
                        <i class="lni lni-crown fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Advisers Table -->
        <div class="row my-4">
        <div class="col-12">
            <h6 class="text-muted">ASSIGNED ADVISERS</h6>
            <table class="table table-hover table-striped" id="advisersTable">
                <thead>
                    <tr class="text-muted">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


        <!-- Audit Logs Table -->
        <div class="row my-4">
            <div class="col-12">
                <h6 class="text-muted">AUDIT LOGS</h6>
                <table class="table table-hover table-striped" id="auditLogsTable">
                    <thead>
                        <tr class="text-muted">
                            <th>Date/Time</th>
                            <th>Role</th>
                            <th>Action</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/cssc/js/admin/admin_dashboard.js"></script>
</body>
</html>
