        // Select the sidebar and hamburger toggle button
        const sidebar = document.querySelector("#sidebar");
        const hamBurger = document.querySelector(".toggle-btn");

        // Toggle sidebar with hamburger
        hamBurger.addEventListener("click", function () {
            sidebar.classList.toggle("expand");
            closeAllSubMenus(); // Close submenus when toggling the sidebar
        });

        
            document.addEventListener("DOMContentLoaded", function () {
            const content = document.getElementById("content");

            // Fetch the dashboard view
            fetch("/cssc/views/admin/dashboard.php")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`Failed to fetch dashboard content. Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then((data) => {
                    content.innerHTML = data;
                    
                    // Initialize any dashboard-specific scripts (like charts)
                    if (typeof initializeCharts === "function") {
                        initializeCharts();
                    }
                })
                .catch((error) => {
                    console.error(error);
                    content.innerHTML = `<p class="text-danger">Error loading dashboard content. Please try again later.</p>`;
                });
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