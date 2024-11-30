<div class="container">
    <h4 class="mb-4">Profile</h4>
    <!-- Personal Information -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Personal Information</h5>
            <div class="row">
                <div class="col-md-4">
                    <img src="profile-pic.jpg" alt="Profile Picture" class="img-fluid rounded-circle" />
                    <input type="file" class="form-control mt-2" />
                </div>
                <div class="col-md-8">
                    <p><strong>Name:</strong> John Doe</p>
                    <p><strong>Email:</strong> admin@example.com</p>
                    <p><strong>Phone:</strong> +123456789</p>
                    <p><strong>Password:</strong> idk</p>

                    <button class="btn btn-primary">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Security Settings</h5>
            <button class="btn btn-secondary">Change Password</button>
        </div>
    </div>

    <!-- Notifications -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Notification Settings</h5>
            <form>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="emailNotifications">
                    <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="systemAlerts">
                    <label class="form-check-label" for="systemAlerts">System Alerts</label>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Activity Logs</h5>
            <ul>
                <li>2024-11-23 10:00 AM - Approved application for Student ID: 20230534</li>
                <li>2024-11-22 05:00 PM - Updated eligibility rules</li>
            </ul>
            <button class="btn btn-primary">View More</button>
        </div>
    </div>
</div>
