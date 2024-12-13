<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addStaffForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel">Add Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="add_first_name" name="first_name" required>
                        <div class="invalid-feedback">First name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="add_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="add_last_name" name="last_name" required>
                        <div class="invalid-feedback">Last name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="add_middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="add_middle_name" name="middle_name">
                    </div>

                    <div class="mb-3">
                        <label for="add_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add_email" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="add_password" name="password" placeholder="Enter password" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleAddPassword">Show</button>
                        <div class="invalid-feedback">Password is required.</div>
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
