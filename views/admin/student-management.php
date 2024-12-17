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
        <h1>User Management</h1>

        <!-- Error/Success Message Section -->
        <div id="feedbackMessage" class="alert d-none" role="alert"></div>

        <!-- Button to trigger the "Add User" modal -->
        <button class="btn btn-primary mb-3" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        
        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchStudent" class="form-control" placeholder="Search by Identifier or Name">
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Filter by Curriculum -->
            <div class="col-md-6">
                <label for="filterCurriculum" class="form-label">Filter by Curriculum</label>
                <select class="form-control" id="filterCurriculum">
                    <option value="">All Curriculums</option>
                </select>
            </div>
            <!-- Filter by Status -->
            <div class="col-md-6">
                <label for="filterStatus" class="form-label">Filter by Status</label>
                <select class="form-control" id="filterStatus">
                    <option value="">All Statuses</option>
                </select>
            </div>
        </div>

        <!-- Table to display users -->
        <table class="table table-bordered" id="studentsTable">
            <thead>
                <tr>
                    <th>Identifier</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Curriculum</th>
                    <th>Status</th>
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <!-- Identifier -->
                        <div class="mb-3">
                            <label for="identifier" class="form-label">Identifier</label>
                            <input type="text" class="form-control" id="identifier" name="identifier" >
                            <div class="invalid-feedback">Identifier is required.</div>
                        </div>
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                            <div class="invalid-feedback">Username is required.</div>
                        </div>
                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" >
                            <div class="invalid-feedback">First name is required.</div>
                        </div>
                        <!-- Middle Name -->
                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name">
                        </div>
                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" >
                            <div class="invalid-feedback">Last name is required.</div>
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" >
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>
                        <!-- Curriculum -->
                        <div class="mb-3">
                            <label for="curriculum" class="form-label">Curriculum</label>
                            <select class="form-control curriculum-dropdown" id="curriculum" name="curriculum_id" >
                                <option value="">Select Curriculum</option>
                            </select>
                            <div class="invalid-feedback">Please select a curriculum.</div>
                        </div>
                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" >
                            <div class="invalid-feedback">Password is required.</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveUserBtn">Save User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="id">

                    <!-- Identifier -->
                    <div class="mb-3">
                        <label for="editIdentifier" class="form-label">Identifier</label>
                        <input type="text" class="form-control" id="editIdentifier" name="identifier" readonly>
                        <div class="invalid-feedback">Identifier is required.</div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" name="username">
                        <div class="invalid-feedback">Username is required.</div>
                    </div>

                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="edit_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name">
                        <div class="invalid-feedback">First name is required.</div>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name">
                        <div class="invalid-feedback">Last name is required.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                        <div class="invalid-feedback">Valid email is required.</div>
                    </div>

                    <!-- Curriculum -->
                    <div class="mb-3">
                        <label for="editCurriculum" class="form-label">Curriculum</label>
                        <select class="form-control curriculum-dropdown" id="editCurriculum" name="curriculum_id">
                            <!-- Dynamically populated -->
                        </select>
                        <div class="invalid-feedback">Please select a curriculum.</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-control" id="editStatus" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateUserBtn">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
