$(document).ready(function () {
  const sidebar = $("#sidebar");
  const hamBurger = $(".toggle-btn");
  const logout = $(".log-out");

  logout.on("click", function () {
    sidebar.toggleClass("expand");
    logOut(); // Close all submenus when toggling the sidebar
  });


  function logOut(){
    $.ajax({
        url: '/cssc/server/logout.php',    // The PHP file handling the logout
        type: 'POST',         // POST request method
             // Expecting a JSON response
        success: function(response) {
            location.reload();
        },
        error: function() {
            alert('Error occurred while logging out');
        }
    });
  }

  // Toggle sidebar with hamburger button
  hamBurger.on("click", function () {
    sidebar.toggleClass("expand");
    closeAllSubMenus(); // Close all submenus when toggling the sidebar
  });

  // Load initial content (dashboard) when the document is ready
  loadContent("/cssc/views/admin/admin-dashboard.php");

  // Handle menu link clicks to dynamically load content
  $(document).on("click", ".menu-link", function (e) {
    e.preventDefault();
    const url = $(this).data("url");
    loadContent(url);

    // Highlight the clicked menu item
    $(".menu-link").parent().removeClass("active");
    $(this).parent().addClass("active");
  });

  // Submenu toggle handling
  $(document).on("click", ".has-dropdown", function (e) {
    e.preventDefault();
    const button = $(this);
    const dropdown = button.next(".sidebar-dropdown");

    if (!dropdown.hasClass("show")) {
      closeAllSubMenus(); // Close other submenus before toggling the clicked one
    }
    dropdown.toggleClass("show");
    button.toggleClass("rotate");
  });

  // Function to close all submenus
  function closeAllSubMenus() {
    $(".sidebar-dropdown").removeClass("show");
    $(".has-dropdown").removeClass("rotate");
  }

  // Function to dynamically load content into the main container
  function loadContent(url) {
    const content = $("#content");

    // Fetch content via AJAX
    $.ajax({
        url: url,
        method: "GET",
        success: function (data) {
            content.html(data);

            // Dynamically load specific JavaScript for each page
            if (url.includes("admin-dashboard.php")) {
                $.getScript("/cssc/js/admin/admin_dashboard.js");
            }
            if (url.includes("student-management.php")) {
                $.getScript("/cssc/js/admin/student_management.js");
            }
            if (url.includes("staff-management.php")) {
                $.getScript("/cssc/js/admin/staff_management.js");
            }
            if (url.includes("admin-management.php")) {
                $.getScript("/cssc/js/admin/admin_management.js");
            }
            if (url.includes("view-applications.php")) {
                $.getScript("/cssc/js/admin/view_applications.js");
            }
            if (url.includes("deans-period.php")) {
                $.getScript("/cssc/js/admin/deans_period.js");
            }
            if (url.includes("curriculum-management.php")) {
                $.getScript("/cssc/js/admin/curriculum_management.js");
            }
            if (url.includes("profile.php")) {
                $.getScript("/cssc/js/admin/admin_profile.js");
            }
            if (url.includes("settings.php")) {
                $.getScript("/cssc/js/admin/admin_settings.js");
            }
        },
        error: function () {
            content.html("<p>Error loading content. Please try again later.</p>");
        },
    });
}

});
