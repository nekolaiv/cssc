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
        <h1>Audit Logs</h1>

        <!-- Feedback Message -->
        <div id="feedbackMessage" class="alert d-none" role="alert"></div>

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Filter by Role -->
            <div class="col-md-4">
                <label for="filterRole" class="form-label">Filter by Role</label>
                <select class="form-control" id="filterRole">
                    <option value="">All Roles</option>
                    <!-- Dynamically populated -->
                </select>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <table class="table table-bordered" id="auditLogsTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Role</th>
                    <th>Action Type</th>
                    <th>Action Details</th>
                    <th>Timestamp</th>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Audit Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this audit log entry?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
