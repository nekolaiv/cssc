<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/cssc/vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Curriculum List</h1>

        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filterCourse" class="form-label">Filter by Course</label>
                <select class="form-control" id="filterCourse">
                    <option value="">All Courses</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="searchYear" class="form-label">Search by Effective Year</label>
                <input type="text" id="searchYear" class="form-control" placeholder="e.g., 2024-2025">
            </div>
            <div class="col-md-3">
                <label for="searchRemarks" class="form-label">Search by Remarks</label>
                <input type="text" id="searchRemarks" class="form-control" placeholder="Optional notes">
            </div>
            <div class="col-md-3">
                <label for="searchVersion" class="form-label">Search by Version</label>
                <input type="text" id="searchVersion" class="form-control" placeholder="e.g., v2025">
            </div>
        </div>

        <!-- Create New Curriculum -->
        <button class="btn btn-primary mb-3" id="createCurriculumBtn">Create New Curriculum</button>

        <!-- Curriculum Table -->
        <table class="table table-bordered" id="curriculumTable">
            <thead>
                <tr>
                    <th>Effective Year</th>
                    <th>Version</th>
                    <th>Remarks</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Pagination -->
        <nav id="pagination" aria-label="Page navigation">
            <ul class="pagination justify-content-center"></ul>
        </nav>

        <!-- Create/Update Modal -->
        <div class="modal fade" id="curriculumModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Manage Curriculum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="curriculumForm">
                            <input type="hidden" id="curriculumId" name="id">
                            <div class="mb-3">
                                <label for="effectiveYear" class="form-label">Effective Year</label>
                                <input type="text" class="form-control" id="effectiveYear" name="effective_year" >
                            </div>
                            <div class="mb-3">
                                <label for="version" class="form-label">Version</label>
                                <input type="text" class="form-control" id="version" name="version" >
                            </div>
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-control" id="course" name="course_id" >
                                    <option value="">Select Course</option>
                                </select>
                            </div>
                            <div id="formError" class="text-danger d-none"></div>
                            <button type="submit" class="btn btn-primary">Save Curriculum</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
