<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/cssc/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container-fluid px-4">
        <!-- Greeting Section -->
        <div class="row my-4">
            <div class="col-12">
                <h4 class="text-muted">Good Morning, Khofer</h4>
                <p class="text-secondary">December 25, 2024</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">PENDING</h6>
                        <h2 class="fw-bold">150</h2>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">CS | 30</span>
                            <span class="text-muted">IT | 23</span>
                            <span class="text-muted">ACT | 12</span>
                        </div>
                        <canvas id="pendingChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">FOR APPROVAL</h6>
                        <h2 class="fw-bold">45</h2>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">CS | 30</span>
                            <span class="text-muted">IT | 23</span>
                            <span class="text-muted">ACT | 12</span>
                        </div>
                        <canvas id="approvalChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">APPROVED</h6>
                        <h2 class="fw-bold">28</h2>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">CS | 30</span>
                            <span class="text-muted">IT | 23</span>
                            <span class="text-muted">ACT | 12</span>
                        </div>
                        <canvas id="approvedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Advisers -->
        <div class="row my-4">
            <div class="col-12">
                <h6 class="text-muted">ASSIGNED ADVISERS</h6>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr class="text-muted">
                            <th>Name</th>
                            <th>Program</th>
                            <th>Section</th>
                            <th>Pending</th>
                            <th>Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Engr. Fatima Sheena Abubakar</td>
                            <td>BSCS</td>
                            <td>1-MAIN</td>
                            <td>15</td>
                            <td>8</td>
                        </tr>
                        <tr>
                            <td>Engr. Ahmad Yahya</td>
                            <td>BSIT</td>
                            <td>4-C</td>
                            <td>10</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Engr. Emmah Imrana</td>
                            <td>BSCS</td>
                            <td>3-A</td>
                            <td>9</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Engr. Jeliane Macias</td>
                            <td>BSIT</td>
                            <td>3-C</td>
                            <td>9</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Engr. Khoffershine Javar</td>
                            <td>BSCS</td>
                            <td>2-B</td>
                            <td>9</td>
                            <td>5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Audit Logs -->
        <div class="row my-4">
            <div class="col-12">
                <h6 class="text-muted">AUDIT LOGS</h6>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr class="text-muted">
                            <th>Date/Time</th>
                            <th>Admin/Staff</th>
                            <th>Action</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-11-24 | 10:15 AM</td>
                            <td>Admin - Jelaine</td>
                            <td>Updated Eligibility Rules</td>
                            <td>Set minimum GPA to 1.75</td>
                        </tr>
                        <tr>
                            <td>2024-11-23 | 03:45 PM</td>
                            <td>Staff - Khofer</td>
                            <td>Verified Application</td>
                            <td>Verified application for Student ID: 20230534</td>
                        </tr>
                        <tr>
                            <td>2024-11-23 | 01:30 PM</td>
                            <td>Staff - Fatima</td>
                            <td>Rejected Application</td>
                            <td>Rejected Student ID: 20230532</td>
                        </tr>
                        <tr>
                            <td>2024-11-22 | 05:00 PM</td>
                            <td>Admin - Niko</td>
                            <td>Assigned Tasks</td>
                            <td>Assigned 20 applications to Staff - Ahmad</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example Charts for Pending, For Approval, Approved
        const pendingCtx = document.getElementById('pendingChart').getContext('2d');
        const approvalCtx = document.getElementById('approvalChart').getContext('2d');
        const approvedCtx = document.getElementById('approvedChart').getContext('2d');

        const chartConfig = {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr'],
                datasets: [{
                    label: 'Applications',
                    data: [30, 50, 70, 90],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        };

        new Chart(pendingCtx, chartConfig);
        new Chart(approvalCtx, chartConfig);
        new Chart(approvedCtx, chartConfig);
    </script>
</body>

</html>
