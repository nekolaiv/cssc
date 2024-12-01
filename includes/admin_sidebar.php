<!-- _sidebar.php -->
<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt"></i>
        </button>
        <div class="sidebar-logo">
            <a href="#">CSSC</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/admin-dashboard.php" class="sidebar-link menu-link">
                <i class="lni lni-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                <i class="lni lni-protection"></i>
                <span>Account Management</span>
            </a>
            <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/admin/student-management.php" class="sidebar-link menu-link">Student</a>
                </li>
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/admin/staff-management.php" class="sidebar-link menu-link">Staff</a>
                </li>
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/admin/admin-management.php" class="sidebar-link menu-link">Admin</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/admin-profile.php" class="sidebar-link menu-link">
                <i class="lni lni-user"></i>
                <span>Profile</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/admin-settings.php" class="sidebar-link menu-link">
                <i class="lni lni-cog"></i>
                <span>Setting</span>
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <a href="#" class="sidebar-link log-out">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
