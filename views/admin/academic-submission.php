<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Term and GWA Submission Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Academic Term and GWA Submission Management</h1>

    <!-- Buttons to Add New Records -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addAcademicTermModal">Add Academic Term</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGwaSubmissionModal">Add GWA Submission Schedule</button>
    </div>

    <!-- Table for Current Academic Term -->
    <h2>Current Academic Terms</h2>
    <table class="table table-bordered" id="academicTermTable">
        <thead>
        <tr>
            <th>Term ID</th>
            <th>Academic Year</th>
            <th>Semester</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <!-- Rows populated dynamically via AJAX -->
        </tbody>
    </table>

    <!-- Table for GWA Submission Schedule -->
    <h2>GWA Submission Schedules</h2>
    <table class="table table-bordered" id="gwaSubmissionTable">
        <thead>
        <tr>
            <th>Submission ID</th>
            <th>Term ID</th>
            <th>Submission Start</th>
            <th>Submission End</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <!-- Rows populated dynamically via AJAX -->
        </tbody>
    </table>
</div>

<!-- Add Academic Term Modal -->
<div class="modal fade" id="addAcademicTermModal" tabindex="-1" aria-labelledby="addAcademicTermModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addAcademicTermForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAcademicTermModalLabel">Add Academic Term</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="academicYear" class="form-label">Academic Year</label>
                        <input type="text" class="form-control" id="academicYear" name="academic_year" required>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                            <option value="summer">Summer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add GWA Submission Schedule Modal -->
<div class="modal fade" id="addGwaSubmissionModal" tabindex="-1" aria-labelledby="addGwaSubmissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addGwaSubmissionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGwaSubmissionModalLabel">Add GWA Submission Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="gwaTermId" class="form-label">Term ID</label>
                        <select class="form-control" id="gwaTermId" name="term_id" required>
                            <!-- Options populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="gwaSubmissionStart" class="form-label">Submission Start</label>
                        <input type="date" class="form-control" id="gwaSubmissionStart" name="gwa_submission_start" required>
                    </div>
                    <div class="mb-3">
                        <label for="gwaSubmissionEnd" class="form-label">Submission End</label>
                        <input type="date" class="form-control" id="gwaSubmissionEnd" name="gwa_submission_end" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/cssc/js/admin/academic_term_management.js"></script>
</body>
</html>
