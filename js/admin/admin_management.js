$(document).ready(function () {
  let admins = [];
  let filteredAdmins = [];
  const rowsPerPage = 5; // Rows per page
  let currentPage = 1;

  loadAdmins();

  // Load admins into the table
  function loadAdmins() {
    $.ajax({
      url: "/cssc/server/admin/admin_management_server.php",
      type: "POST",
      data: { action: "read" },
      success: function (response) {
        admins = JSON.parse(response);
        filteredAdmins = admins; // Initially show all admins
        displayTable(currentPage);
        setupPagination(filteredAdmins);
      },
      error: function (xhr, status, error) {
        console.error("Failed to load admins:", error);
      },
    });
  }

  // Display admins in the table
  function displayTable(page) {
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const visibleAdmins = filteredAdmins.slice(startIndex, endIndex);

    const tableBody = $("#adminsTable tbody");
    tableBody.empty(); // Clear the table before appending new rows

    if (visibleAdmins.length === 0) {
      tableBody.append(`
        <tr>
          <td colspan="4" class="text-center">No data found.</td>
        </tr>
      `);
    } else {
      visibleAdmins.forEach((admin) => {
        const fullName = `${admin.first_name} ${
          admin.middle_name ? admin.middle_name + " " : ""
        }${admin.last_name}`;
        tableBody.append(`
          <tr>
            <td>${admin.admin_id}</td>
            <td>${fullName}</td>
            <td>${admin.email}</td>
            <td>
              <button class="btn btn-sm btn-warning edit-btn" data-id="${admin.admin_id}">Edit</button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="${admin.admin_id}">Delete</button>
            </td>
          </tr>
        `);
      });
    }

  }

  // Set up pagination
  function setupPagination(data) {
    const pageCount = Math.ceil(data.length / rowsPerPage);
    const pagination = $("#pagination ul");
    pagination.empty();

    for (let i = 1; i <= pageCount; i++) {
      const activeClass = i === currentPage ? "active" : "";
      pagination.append(`
        <li class="page-item ${activeClass}">
          <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>
      `);
    }

    pagination.find(".page-link").on("click", function (e) {
      e.preventDefault();
      currentPage = parseInt($(this).data("page"));
      displayTable(currentPage);

      pagination.find(".page-item").removeClass("active");
      $(this).parent().addClass("active");
    });
  }

  // Search functionality
  $("#searchAdmin").on("input", function () {
    const query = $(this).val().toLowerCase();
    filteredAdmins = admins.filter(
      (admin) =>
        admin.admin_id.toString().toLowerCase().includes(query) ||
        `${admin.first_name} ${admin.middle_name || ""} ${admin.last_name}`
          .toLowerCase()
          .includes(query)
    );
    currentPage = 1; // Reset to the first page when searching
    displayTable(currentPage);
    setupPagination(filteredAdmins);
  });

  // Add Admin Button
  $("#addAdminBtn").click(function () {
    $("#addAdminForm")[0].reset();
    $("#addAdminModal").modal("show");
  });

  // Edit Admin Button
  $(document).on("click", ".edit-btn", function () {
    const adminId = $(this).data("id");

    $.ajax({
      url: "/cssc/server/admin/admin_management_server.php",
      type: "POST",
      data: { action: "get", admin_id: adminId },
      success: function (response) {
        const admin = JSON.parse(response);

        if (admin.error) {
          alert("Error fetching admin details: " + admin.error);
          return;
        }

        $("#edit_admin_id").val(admin.admin_id);
        $("#edit_email").val(admin.email);
        $("#edit_first_name").val(admin.first_name);
        $("#edit_middle_name").val(admin.middle_name);
        $("#edit_last_name").val(admin.last_name);

        $("#editAdminModal").modal("show");
      },
      error: function () {
        alert("Failed to fetch admin details.");
      },
    });
  });

  // Delete Admin Button
  $(document).on("click", ".delete-btn", function () {
    const adminId = $(this).data("id");

    if (confirm("Are you sure you want to delete this admin?")) {
      $.ajax({
        url: "/cssc/server/admin/admin_management_server.php",
        type: "POST",
        data: { action: "delete", admin_id: adminId },
        success: function (response) {
          const result = JSON.parse(response);
          alert(result.success ? "Admin deleted successfully!" : "Failed to delete admin.");
          loadAdmins();
        },
      });
    }
  });

  // Submit Add Admin Form
  $("#addAdminForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=create";

    $.ajax({
      url: "/cssc/server/admin/admin_management_server.php",
      type: "POST",
      data: formData,
      success: function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          alert("Admin added successfully!");
          $("#addAdminModal").modal("hide");
          loadAdmins();
        } else {
          handleFormErrors("#addAdminForm", result.errors);
        }
      },
    });
  });

  // Submit Edit Admin Form
  $("#editAdminForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=update";

    $.ajax({
      url: "/cssc/server/admin/admin_management_server.php",
      type: "POST",
      data: formData,
      success: function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          alert("Admin updated successfully!");
          $("#editAdminModal").modal("hide");
          loadAdmins();
        } else {
          handleFormErrors("#editAdminForm", result.errors);
        }
      },
    });
  });

  // Handle Form Errors
  function handleFormErrors(formSelector, errors) {
    $(formSelector)
      .find(".form-control")
      .removeClass("is-invalid");
    $(formSelector)
      .find(".invalid-feedback")
      .text("");

    if (errors) {
      Object.keys(errors).forEach((field) => {
        const errorMessage = errors[field];
        const fieldElement = $(formSelector + " [name='" + field + "']");
        fieldElement.addClass("is-invalid");
        fieldElement.next(".invalid-feedback").text(errorMessage);
      });
    }
  }
});
