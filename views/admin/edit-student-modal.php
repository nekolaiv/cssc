<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editStudentForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <!-- Student ID -->
                    <div class="mb-3">
                        <label for="edit_student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="edit_student_id" name="student_id">
                        <div class="invalid-feedback">Student ID is required.</div>
                    </div>
                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="edit_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name">
                        <div class="invalid-feedback">First Name is required.</div>
                    </div>
                    <!-- Middle Name -->
                    <div class="mb-3">
                        <label for="edit_middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="edit_middle_name" name="middle_name">
                    </div>
                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name">
                        <div class="invalid-feedback">Last Name is required.</div>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                        <div class="invalid-feedback">Valid Email is required.</div>
                    </div>
                    <!-- Course -->
                    <div class="mb-3">
                        <label for="edit_course" class="form-label">Course</label>
                        <select class="form-control course-dropdown" id="edit_course" name="course_id">
                            <option value="">Select a course</option>
                            <!-- Options will be dynamically populated -->
                        </select>
                        <div class="invalid-feedback">Course is required.</div>
                    </div>
                    <!-- Year Level -->
                    <div class="mb-3">
                        <label for="edit_year_level" class="form-label">Year Level</label>
                        <input type="number" class="form-control" id="edit_year_level" name="year_level" min="1" max="10">
                        <div class="invalid-feedback">Year Level is required.</div>
                    </div>
                    <!-- Section -->
                    <div class="mb-3">
                        <label for="edit_section" class="form-label">Section</label>
                        <input type="text" class="form-control" id="edit_section" name="section">
                        <div class="invalid-feedback">Section is required.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
