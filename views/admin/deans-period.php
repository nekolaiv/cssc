<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/cssc/vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Dean's Lister Application Periods</h1>

        <!-- Feedback Message -->
        <div id="feedbackMessage" class="alert d-none" role="alert"></div>

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Filter by Status -->
            <div class="col-md-4">
                <label for="filterStatus" class="form-label">Filter by Status</label>
                <select class="form-control" id="filterStatus">
                    <option value="">All Periods</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <!-- Search Bar -->
            <div class="col-md-8">
                <label for="searchPeriod" class="form-label">Search by Year</label>
                <input type="text" id="searchPeriod" class="form-control" placeholder="Search by Year">
            </div>
        </div>

        <!-- Create New Period -->
        <button class="btn btn-primary mb-3" id="createPeriodBtn">Create New Period</button>

        <!-- Periods Table -->
        <table class="table table-bordered" id="periodsTable">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Semester</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated dynamically via AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav id="pagination" aria-label="Page navigation">
        <ul class="pagination justify-content-center"></ul>
    </nav>

    <!-- Create/Update Period Modal -->
    <div class="modal fade" id="periodModal" tabindex="-1" aria-labelledby="periodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="periodModalLabel">Manage Application Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="periodForm">
                        <input type="hidden" id="periodId" name="id">
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" class="form-control" id="year" name="year" placeholder="e.g., 2024-2025" required>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-control" id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                                <option value="1st">1st</option>
                                <option value="2nd">2nd</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" id="startDate" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" id="endDate" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div id="formError" class="text-danger d-none"></div>
                        <button type="submit" class="btn btn-primary">Save Period</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
