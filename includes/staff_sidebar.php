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
            <a href="#" data-url="/cssc/views/staff/dashboard.php" class="sidebar-link menu-link">
                <i class="lni lni-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Entries (Verified and Unverified) -->
        <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#entries" aria-expanded="false" aria-controls="entries">
                <i class="lni lni-files"></i>
                <span>Entries</span>
            </a>
            <ul id="entries" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/staff/unverified_entries.php" class="sidebar-link menu-link">Unverified Entries</a>
                </li>
                <li class="sidebar-item">
                    <a href="#" data-url="/cssc/views/staff/verified_entries.php" class="sidebar-link menu-link">Verified Entries</a>
                </li>
            </ul>
        </li>

        <!-- Profile -->
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/admin-profile.php" class="sidebar-link menu-link">
                <i class="lni lni-user"></i>
                <span>Profile</span>
            </a>
        </li>

        <!-- Settings -->
        <li class="sidebar-item">
            <a href="#" data-url="/cssc/views/admin/settings.php" class="sidebar-link menu-link">
                <i class="lni lni-cog"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <a href="#" class="sidebar-link">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
