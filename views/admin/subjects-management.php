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
        <h1>Subjects Management</h1>

        <!-- Feedback Message -->
        <div id="feedbackMessage" class="alert d-none" role="alert"></div>

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Filter by Course -->
            <div class="col-md-3">
                <label for="filterCourse" class="form-label">Filter by Course</label>
                <select class="form-control" id="filterCourse">
                    <option value="">All Courses</option>
                    <!-- Dynamically populated -->
                </select>
            </div>
            <!-- Filter by Curriculum -->
            <div class="col-md-3">
                <label for="filterCurriculum" id="curriculum" class="form-label">Filter by Curriculum</label>
                <select class="form-control" id="filterCurriculum">
                    <option value="">All Curriculums</option>
                    <!-- Dynamically populated -->
                </select>
            </div>
            <!-- Filter by Year Level -->
            <div class="col-md-3">
                <label for="filterYearLevel" class="form-label">Filter by Year Level</label>
                <select class="form-control" id="filterYearLevel">
                    <option value="">All Year Levels</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>
            <!-- Filter by Semester -->
            <div class="col-md-3">
                <label for="filterSemester" class="form-label">Filter by Semester</label>
                <select class="form-control" id="filterSemester">
                    <option value="">All Semesters</option>
                    <option value="1st">1st Semester</option>
                    <option value="2nd">2nd Semester</option>
                    <option value="Summer">Summer</option>
                </select>
            </div>
        </div>

        <!-- Create New Subject -->
        <button class="btn btn-primary mb-3" id="createSubjectBtn">Create New Subject</button>

        <!-- Subjects Table -->
        <table class="table table-bordered" id="subjectsTable">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Descriptive Title</th>
                    <th>Prerequisite</th>
                    <th>Lecture Units</th>
                    <th>Lab Units</th>
                    <th>Total Units</th>
                    <th>Year Level</th>
                    <th>Semester</th>
                    <th>Curriculum</th>
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

    <!-- Create/Update Subject Modal -->
    <div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subjectModalLabel">Manage Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="subjectForm">
                        <input type="hidden" id="subjectId" name="id">
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subjectCode" name="subject_code" placeholder="e.g., IT101">
                        </div>
                        <div class="mb-3">
                            <label for="descriptiveTitle" class="form-label">Descriptive Title</label>
                            <input type="text" class="form-control" id="descriptiveTitle" name="descriptive_title" placeholder="e.g., Introduction to IT">
                        </div>
                        <div class="mb-3">
                            <label for="prerequisite" class="form-label">Prerequisite</label>
                            <input type="text" class="form-control" id="prerequisite" name="prerequisite" placeholder="e.g., None or IT100">
                        </div>
                        <div class="mb-3">
                            <label for="lecUnits" class="form-label">Lecture Units</label>
                            <input type="number" class="form-control" id="lecUnits" name="lec_units" placeholder="e.g., 3">
                        </div>
                        <div class="mb-3">
                            <label for="labUnits" class="form-label">Lab Units</label>
                            <input type="number" class="form-control" id="labUnits" name="lab_units" placeholder="e.g., 1">
                        </div>
                        <div class="mb-3">
                            <label for="totalUnits" class="form-label">Total Units</label>
                            <input type="number" class="form-control" id="totalUnits" name="total_units">
                        </div>
                        <div class="mb-3">
                            <label for="yearLevel" class="form-label">Year Level</label>
                            <select class="form-control" id="yearLevel" name="year_level">
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-control" id="semester" name="semester">
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="curriculum" class="form-label">Curriculum</label>
                            <select class="form-control" id="curriculumModal" name="curriculum_id">
                                <option value="">Select Curriculum</option>
                                <!-- Dynamically populated -->
                            </select>
                        </div>
                        <div id="formError" class="text-danger d-none"></div>
                        <button type="submit" class="btn btn-primary">Save Subject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
