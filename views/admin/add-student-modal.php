<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addStudentForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Student ID -->
                    <div class="mb-3">
                        <label for="add_student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="add_student_id" name="student_id" required>
                        <div class="invalid-feedback">Student ID is required.</div>
                    </div>
                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="add_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="add_first_name" name="first_name" required>
                        <div class="invalid-feedback">First Name is required.</div>
                    </div>
                    <!-- Middle Name -->
                    <div class="mb-3">
                        <label for="add_middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="add_middle_name" name="middle_name">
                    </div>
                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="add_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="add_last_name" name="last_name" required>
                        <div class="invalid-feedback">Last Name is required.</div>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="add_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add_email" name="email" required>
                        <div class="invalid-feedback">Valid Email is required.</div>
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="add_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="add_password" name="password" required>
                        <div class="invalid-feedback">Password is required.</div>
                    </div>
                    <!-- Course -->
                    <div class="mb-3">
                        <label for="add_course" class="form-label">Course</label>
                        <input type="text" class="form-control" id="add_course" name="course" required>
                        <div class="invalid-feedback">Course is required.</div>
                    </div>
                    <!-- Year Level -->
                    <div class="mb-3">
                        <label for="add_year_level" class="form-label">Year Level</label>
                        <input type="number" class="form-control" id="add_year_level" name="year_level" min="1" max="10" required>
                        <div class="invalid-feedback">Year Level is required.</div>
                    </div>
                    <!-- Section -->
                    <div class="mb-3">
                        <label for="add_section" class="form-label">Section</label>
                        <input type="text" class="form-control" id="add_section" name="section" required>
                        <div class="invalid-feedback">Section is required.</div>
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