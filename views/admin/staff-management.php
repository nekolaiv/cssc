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
        <h1>Staff Management</h1>

        <!-- Error/Success Message Section -->
        <div id="feedbackMessage" class="alert d-none" role="alert"></div>

        <!-- Button to trigger the "Add Staff" modal -->
        <button class="btn btn-primary mb-3" id="addStaffBtn" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add Staff</button>
        
        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchStaff" class="form-control" placeholder="Search by Identifier, Name, Username, or Email">
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Filter by Department -->
            <div class="col-md-6">
                <label for="filterDepartment" class="form-label">Filter by Department</label>
                <select class="form-control" id="filterDepartment">
                    <option value="">All Departments</option>
                </select>
            </div>
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

        <!-- Table to display staff -->
        <table class="table table-bordered" id="staffTable">
            <thead>
                <tr>
                    <th>Identifier</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                     <th>Department</th>
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

    <!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStaffModalLabel">Add Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStaffForm">
                    <!-- Identifier -->
                    <div class="mb-3">
                        <label for="staffIdentifier" class="form-label">Identifier</label>
                        <input type="text" class="form-control" id="staffIdentifier" name="identifier">
                        <div class="invalid-feedback">Identifier is required.</div>
                    </div>
                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="staffFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="staffFirstName" name="first_name">
                        <div class="invalid-feedback">First name is required.</div>
                    </div>
                    <!-- Middle Name -->
                    <div class="mb-3">
                        <label for="staffMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="staffMiddleName" name="middle_name">
                        <div class="invalid-feedback">Middle name is optional.</div>
                    </div>
                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="staffLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="staffLastName" name="last_name">
                        <div class="invalid-feedback">Last name is required.</div>
                    </div>
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="staffUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="staffUsername" name="username">
                        <div class="invalid-feedback">Username is required.</div>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="staffEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="staffEmail" name="email">
                        <div class="invalid-feedback">Valid email is required.</div>
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="staffPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="staffPassword" name="password">
                        <div class="invalid-feedback">Password is required.</div>
                    </div>
                    <!-- Department -->
                    <div class="mb-3">
                        <label for="staffDepartment" class="form-label">Department</label>
                        <select class="form-control" id="staffDepartment" name="department_id">
                            <option value="">Select Department</option>
                            <!-- Options dynamically populated -->
                        </select>
                        <div class="invalid-feedback">Please select a department.</div>
                    </div>
                    <!-- Status -->
                    <div class="mb-3">
                        <label for="staffStatus" class="form-label">Status</label>
                        <select class="form-control" id="staffStatus" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveStaffBtn">Save Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStaffModalLabel">Edit Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStaffForm">
                    <input type="hidden" id="editStaffId" name="id">

                    <!-- Identifier -->
                    <div class="mb-3">
                        <label for="editStaffIdentifier" class="form-label">Identifier</label>
                        <input type="text" class="form-control" id="editStaffIdentifier" name="identifier" readonly>
                        <div class="invalid-feedback">Identifier is required.</div>
                    </div>
                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="editStaffFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editStaffFirstName" name="first_name">
                        <div class="invalid-feedback">First name is required.</div>
                    </div>
                    <!-- Middle Name -->
                    <div class="mb-3">
                        <label for="editStaffMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editStaffMiddleName" name="middle_name">
                        <div class="invalid-feedback">Middle name is optional.</div>
                    </div>
                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="editStaffLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editStaffLastName" name="last_name">
                        <div class="invalid-feedback">Last name is required.</div>
                    </div>
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="editStaffUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editStaffUsername" name="username">
                        <div class="invalid-feedback">Username is required.</div>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="editStaffEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editStaffEmail" name="email">
                        <div class="invalid-feedback">Valid email is required.</div>
                    </div>
                    <!-- Department -->
                    <div class="mb-3">
                        <label for="editStaffDepartment" class="form-label">Department</label>
                        <select class="form-control" id="editStaffDepartment" name="department_id">
                            <!-- Options dynamically populated -->
                        </select>
                        <div class="invalid-feedback">Please select a department.</div>
                    </div>
                    <!-- Status -->
                    <div class="mb-3">
                        <label for="editStaffStatus" class="form-label">Status</label>
                        <select class="form-control" id="editStaffStatus" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateStaffBtn">Update Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



</body>
</html>
