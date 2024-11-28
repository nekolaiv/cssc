<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="sidebar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="wrapper">
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
                    <a href="#" data-url="dashboard.html" class="sidebar-link menu-link">
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
                    <a href="#" data-url="profile.html" class="sidebar-link menu-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" data-url="settings.html" class="sidebar-link menu-link">
                        <i class="lni lni-cog"></i>
                        <span>Setting</span>
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
        <div class="main p-3">
            <div id="content">
                <h1>
                    Sidebar Bootstrap 5
                </h1>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script>
        // Select the sidebar and hamburger toggle button
        const sidebar = document.querySelector("#sidebar");
        const hamBurger = document.querySelector(".toggle-btn");

        // Toggle sidebar with hamburger
        hamBurger.addEventListener("click", function () {
            sidebar.classList.toggle("expand");
            closeAllSubMenus(); // Close submenus when toggling the sidebar
        });

        // Dynamic content loading with AJAX
        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("menu-link")) {
                e.preventDefault();
                const url = e.target.getAttribute("data-url");

                // Fetch content dynamically
                fetch(url)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.text();
                    })
                    .then((data) => {
                        document.getElementById("content").innerHTML = data;

                        // Dynamically load the student-management.js script if it's student management page
                        if (url.includes("student-management.php")) {
                            $.getScript("/cssc/js/student-management.js");
                        }
                        if (url.includes("staff-management.php")) {
                            $.getScript("/cssc/js/staff-management.js");
                        }
                        if (url.includes("admin-management.php")) {
                            $.getScript("/cssc/js/admin-management.js");
                        }
                    })
                    .catch(() => {
                        document.getElementById("content").innerHTML = "<p>Error loading content.</p>";
                    });

                // Highlight active menu item
                document.querySelectorAll(".menu-link").forEach((link) => {
                    link.parentElement.classList.remove("active");
                });
                e.target.parentElement.classList.add("active");
            }
        });

        // Handle submenu toggle
        function toggleSubMenu(button) {
            if (!button.nextElementSibling.classList.contains("show")) {
                closeAllSubMenus();
            }
            button.nextElementSibling.classList.toggle("show");
            button.classList.toggle("rotate");

            // Ensure the sidebar is fully visible when expanding a submenu
            if (sidebar.classList.contains("expand")) {
                sidebar.classList.remove("expand");
            }
        }

        // Close all submenus
        function closeAllSubMenus() {
            document.querySelectorAll(".sidebar-dropdown").forEach((dropdown) => {
                dropdown.classList.remove("show");
            });
            document.querySelectorAll(".sidebar-item .rotate").forEach((button) => {
                button.classList.remove("rotate");
            });
        }
    </script>
</body>

</html>
