<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
?>
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
        <h1>Admin Management</h1>

        <!-- Feedback Message -->
        <div id="feedbackMessage" class="alert d-none" role="alert"></div>

        <!-- Add Admin Button -->
        <button class="btn btn-primary mb-3" id="addAdminBtn" data-bs-toggle="modal" data-bs-target="#addAdminModal">Add Admin</button>

        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchAdmin" class="form-control" placeholder="Search by Identifier or Name">
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Filter by Status -->
            <div class="col-md-6">
                <label for="filterStatus" class="form-label">Filter by Status</label>
                <select class="form-control" id="filterStatus">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Admin Table -->
        <table class="table table-bordered" id="adminTable">
            <thead>
                <tr>
                    <th>Identifier</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
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

    <!-- Add Admin Modal -->
    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdminModalLabel">Add Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAdminForm">
                        <!-- Identifier -->
                        <div class="mb-3">
                            <label for="identifier" class="form-label">Identifier</label>
                            <input type="text" class="form-control" id="identifier" name="identifier">
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
                            <input type="text" class="form-control" id="first_name" name="first_name">
                            <div class="invalid-feedback">First name is required.</div>
                        </div>
                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name">
                            <div class="invalid-feedback">Last name is required.</div>
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="invalid-feedback">Password is required.</div>
                        </div>
                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveAdminBtn">Save Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAdminForm">
                        <input type="hidden" id="editAdminId" name="id">
                        <!-- Identifier -->
                        <div class="mb-3">
                            <label for="editIdentifier" class="form-label">Identifier</label>
                            <input type="text" class="form-control" id="editIdentifier" name="identifier" readonly>
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
                        <!-- Status -->
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-control" id="editStatus" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback">Please select a valid status.</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="updateAdminBtn">Update Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
