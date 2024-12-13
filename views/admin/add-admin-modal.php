<div id="addAdminModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addAdminForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="add_first_name" name="first_name"d>
                        <div class="invalid-feedback">First name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="add_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="add_last_name" name="last_name" >
                        <div class="invalid-feedback">Last name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="add_middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="add_middle_name" name="middle_name">
                        <div class="invalid-feedback">Middle name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="add_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add_email" name="email">
                        <div class="invalid-feedback">Please provide a valid email.</div>
                    </div>

                    <div class="mb-3">
                        <label for="add_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="add_password" name="password">
                        <div class="invalid-feedback">Password is required.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
