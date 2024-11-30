<div id="adminModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="adminForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="admin_id" name="admin_id">

                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                        <div class="invalid-feedback">First name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                        <div class="invalid-feedback">Last name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name">
                        <div class="invalid-feedback">Middle name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                        <div class="invalid-feedback">Please provide a valid email.</div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show</button>
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
