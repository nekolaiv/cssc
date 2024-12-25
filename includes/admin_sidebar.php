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
        <!-- Dashboard -->
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/admin-dashboard.php" class="sidebar-link menu-link">
                <i class="lni lni-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- Account Management -->
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
        <!-- Applications Management -->
        <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#applications" aria-expanded="false" aria-controls="applications">
                <i class="lni lni-graduation"></i>
                <span>Applications</span>
            </a>
            <ul id="applications" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/admin/view-applications.php" class="sidebar-link menu-link">View Applications</a>
                </li>
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/admin/deans-period.php" class="sidebar-link menu-link">Dean's Lister Periods</a>
                </li>
            </ul>
        </li>
        <!-- Curriculum Management -->
        <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#curriculum" aria-expanded="false" aria-controls="curriculum">
                <i class="lni lni-book"></i>
                <span>Curriculum Management</span>
            </a>
            <ul id="curriculum" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/admin/curriculum-list.php" class="sidebar-link menu-link">Curriculum List</a>
                </li>
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/admin/subjects-management.php" class="sidebar-link menu-link">Subjects Management</a>
                </li>
            </ul>
        </li>
        <!-- Audit Logs -->
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/audit-logs.php" class="sidebar-link menu-link">
                <i class="lni lni-timer"></i>
                <span>Audit Logs</span>
            </a>
        </li>
        <!-- Profile -->
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/admin-profile.php" class="sidebar-link menu-link">
                <i class="lni lni-user"></i>
                <span>Profile</span>
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
