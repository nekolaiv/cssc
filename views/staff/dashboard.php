<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/cssc/js/staff/staff_dashboard.js"></script>
</head>

<body>
    <div class="container-fluid px-4">
        <!-- Greeting Section -->
        <div class="row my-4">
            <div class="col-12">
                <h4 class="text-muted">Hello, Staff</h4>
                <p class="text-secondary" id="currentDate">Loading date...</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <h6 class="text-muted">OVERVIEW</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">UNVERIFIED ENTRIES</h6>
                        <h2 class="fw-bold" id="unverifiedCount">0</h2>
                        <i class="lni lni-users fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">VERIFIED ENTRIES</h6>
                        <h2 class="fw-bold" id="verifiedCount">0</h2>
                        <i class="lni lni-checkmark fs-2 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">PENDING SUBMISSIONS</h6>
                        <h2 class="fw-bold" id="pendingCount">0</h2>
                        <i class="lni lni-timer fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">NEED REVISION</h6>
                        <h2 class="fw-bold" id="revisionCount">0</h2>
                        <i class="lni lni-reload fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Verified Entries -->
        <div class="row my-4">
            <div class="col-12">
                <h6 class="text-muted">RECENTLY VERIFIED ENTRIES</h6>
                <table class="table table-hover table-striped" id="recentVerifiedTable">
                    <thead>
                        <tr class="text-muted">
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>GWA</th>
                            <th>Date Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Audit Logs -->
        <div class="row my-4">
            <div class="col-12">
                <h6 class="text-muted">AUDIT LOGS</h6>
                <table class="table table-hover table-striped" id="auditLogTable">
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
</body>

</html>
