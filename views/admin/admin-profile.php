<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .profile-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <h1 class="mb-4">Admin Profile</h1>

        <!-- Basic Information Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Basic Information</h5>
                <p><strong>Identifier:</strong> <span id="admin-identifier">[Loading...]</span></p>
                <p><strong>First Name:</strong> <span id="admin-firstname">[Loading...]</span></p>
                <p><strong>Middle Name:</strong> <span id="admin-middlename">[Loading...]</span></p>
                <p><strong>Last Name:</strong> <span id="admin-lastname">[Loading...]</span></p>
                <p><strong>Email:</strong> <span id="admin-email">[Loading...]</span></p>
                <p><strong>Username:</strong> <span id="admin-username">[Loading...]</span></p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
            </div>
        </div>

        <!-- Security Section -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Account Security</h5>
                <p><strong>Password:</strong> ******** <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button></p>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="mb-3">
                            <label for="editIdentifier" class="form-label">Identifier</label>
                            <input type="text" class="form-control" id="editIdentifier" name="identifier" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editFirstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstname" name="firstname">
                        </div>
                        <div class="mb-3">
                            <label for="editMiddlename" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="editMiddlename" name="middlename">
                        </div>
                        <div class="mb-3">
                            <label for="editLastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastname" name="lastname">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username">
                        </div>
                        <button type="button" class="btn btn-primary" id="saveProfileChanges">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                        </div>
                        <button type="button" class="btn btn-primary" id="savePasswordChanges">Save Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="/cssc/js/admin-profile.js"></script> -->
</body>
</html>
