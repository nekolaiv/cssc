$(document).ready(function () {
  const sidebar = $("#sidebar");
  const hamBurger = $(".toggle-btn");

  // Toggle sidebar with hamburger button
  hamBurger.on("click", function () {
    sidebar.toggleClass("expand");
    closeAllSubMenus(); // Close all submenus when toggling the sidebar
  });

  // Load initial content (dashboard) when the document is ready
  loadContent("/cssc/views/staff/dashboard.php");

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

        // Remove all delegated event handlers to avoid duplication
        content.off();

        // Dynamically load specific JavaScript for each page
        if (url.includes("unverified_entries.php")) {
          $.getScript("/cssc/js/staff/unverified_entries.js");
        }
        if (url.includes("verified_entries.php")) {
          $.getScript("/cssc/js/staff/verified_entries.js");
        }
        if (url.includes("profile.php")) {
          $.getScript("/cssc/js/profile.js");
        }
      },
      error: function () {
        content.html("<p>Error loading content. Please try again later.</p>");
      },
    });
  }
});
