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
        <h1>View Applications</h1>

        <!-- Feedback Message -->
        <div id="feedbackMessage" class="alert d-none" role="alert"></div>

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Filter by Curriculum -->
            <div class="col-md-4">
                <label for="filterCurriculum" class="form-label">Filter by Curriculum</label>
                <select class="form-control" id="filterCurriculum">
                    <option value="">All Curriculums</option>
                    <!-- Dynamically populated -->
                </select>
            </div>
            <!-- Filter by Status -->
            <div class="col-md-4">
                <label for="filterStatus" class="form-label">Filter by Status</label>
                <select class="form-control" id="filterStatus">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <!-- Filter by Submission Date -->
            <div class="col-md-4">
                <label for="filterDate" class="form-label">Filter by Submission Date</label>
                <input type="date" class="form-control" id="filterDate">
            </div>
            <div class="row mb-3">
            <!-- Filter by School Year -->
            <div class="col-md-3">
                <label for="filterSchoolYear" class="form-label">Filter by School Year</label>
                <select class="form-control" id="filterSchoolYear">
                    <option value="">All Years</option>
                    <!-- Dynamically populated -->
                </select>
            </div>
            <!-- Filter by Semester -->
            <div class="col-md-3">
                <label for="filterSemester" class="form-label">Filter by Semester</label>
                <select class="form-control" id="filterSemester">
                    <option value="">All Semesters</option>
                    <option value="1st">1st</option>
                    <option value="2nd">2nd</option>
                    <option value="Summer">Summer</option>
                </select>
            </div>
        </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchApplication" class="form-control" placeholder="Search by Identifier or Name">
        </div>

        <!-- Applications Table -->
        <table class="table table-bordered" id="applicationsTable">
            <thead>
                <tr>
                    <th>Student Identifier</th>
                    <th>Full Name</th>
                    <th>Curriculum</th>
                    <th>Status</th>
                    <th>School Year</th>
                    <th>Semester</th>
                    <th>Submission Date</th>
                    <th>Total Rating</th>
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

    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsModalLabel">Application Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="applicationDetails">
                        <!-- Dynamically populated -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Compare Grades Modal -->
<div class="modal fade" id="compareGradesModal" tabindex="-1" aria-labelledby="compareGradesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="compareGradesModalLabel">Compare Grades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Grades Submitted by User</h6>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Descriptive Title</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody id="gradesTableBody">
                        <!-- Dynamically populated -->
                    </tbody>
                </table>
                <h6>Image Proof</h6>
                <div id="imageProofContainer" class="text-center">
                    <!-- Image will be dynamically loaded -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
