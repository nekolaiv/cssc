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
                            <th>Course</th>
                            <th>Year Level</th>
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

        <!-- Audit Logs Table -->
        <div class="row my-4">
            <div class="col-12">
                <h6 class="text-muted">AUDIT LOGS</h6>
                <table class="table table-hover table-striped">
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
                        <td>2024-11-20 | 09:30 AM</td>
                        <td>STAFF - EMILY</td>
                        <td>Verified Application</td>
                        <td>Verified application for Student ID: 20235412.</td>
                    </tr>
                    <tr>
                        <td>2024-11-22 | 02:45 PM</td>
                        <td>ADMIN - JASON</td>
                        <td>Updated Eligibility Rules</td>
                        <td>Set minimum GPA to 1.85.</td>
                    </tr>
                    <tr>
                        <td>2024-11-23 | 11:15 AM</td>
                        <td>STAFF - OLIVER</td>
                        <td>Rejected Application</td>
                        <td>Rejected Student ID: 20231234.</td>
                    </tr>
                    <tr>
                        <td>2024-11-25 | 05:00 PM</td>
                        <td>ADMIN - SOPHIA</td>
                        <td>Assigned Tasks</td>
                        <td>Assigned 10 applications to Staff - Liam.</td>
                    </tr>
                    <tr>
                        <td>2024-11-26 | 08:20 AM</td>
                        <td>STAFF - LIAM</td>
                        <td>Verified Application</td>
                        <td>Verified application for Student ID: 20234123.</td>
                    </tr>
                    <tr>
                        <td>2024-11-28 | 03:10 PM</td>
                        <td>ADMIN - ISABELLA</td>
                        <td>Updated Eligibility Rules</td>
                        <td>Set maximum allowed absences to 5.</td>
                    </tr>
                    <tr>
                        <td>2024-11-29 | 01:45 PM</td>
                        <td>STAFF - NOAH</td>
                        <td>Rejected Application</td>
                        <td>Rejected Student ID: 20232256.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="/cssc/js/admin/admin_dashboard.js"></script>
</body>

</html>
