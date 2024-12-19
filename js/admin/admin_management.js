$(document).ready(function () {
  let admins = [];
  let allAdmins = [];
  const rowsPerPage = 5;
  let currentPage = 1;

  // Initial Load
  loadInitialData();

  function loadInitialData() {
    loadAdmins();
    setupFilters();
    setupSearch();
  }

  // Fetch All Admins
  function loadAdmins(filters = {}) {
    $.post(
      "/cssc/server/admin/admin_management_server.php",
      { action: "read", role: "admin", ...filters },
      function (response) {
        try {
          console.log("Server Response:", response);
          admins = JSON.parse(response);
          allAdmins = [...admins];
          displayTable(currentPage);
          setupPagination();
        } catch (error) {
          console.error("Error parsing admin data:", error);
        }
      }
    ).fail(function () {
      console.error("Failed to load admins.");
    });
  }

  // Set up Filters
  function setupFilters() {
    $("#filterStatus").on("change", function () {
      const filters = { status: $("#filterStatus").val() };
      loadAdmins(filters);
    });
  }

  // Set up Search
  function setupSearch() {
    $("#searchAdmin").on("keyup", function () {
      const query = $(this).val().toLowerCase();

      const filtered = allAdmins.filter((admin) => {
        const username = admin.username ? admin.username.toLowerCase() : '';
        const fullName = admin.full_name ? admin.full_name.toLowerCase() : '';
        const identifier = admin.identifier ? admin.identifier.toLowerCase() : '';

        return (
          identifier.includes(query) ||
          fullName.includes(query) ||
          username.includes(query)
        );
      });

      admins = filtered;
      currentPage = 1;
      displayTable(currentPage);
      setupPagination();
    });
  }

  // Display Admin Data in the Table
  function displayTable(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const visible = admins.slice(start, end);

    const tableBody = $("#adminTable tbody");
    tableBody.empty();

    if (visible.length === 0) {
      tableBody.append('<tr><td colspan="6" class="text-center">No data found.</td></tr>');
      return;
    }

    visible.forEach((admin) => {
      const statusButtonLabel = admin.status === "active" ? "Deactivate" : "Activate";
      const statusButtonClass = admin.status === "active" ? "btn-outline-danger" : "btn-outline-success";

      tableBody.append(`
        <tr>
          <td>${admin.identifier}</td>
          <td>${admin.full_name}</td>
          <td>${admin.username}</td>
          <td>${admin.email}</td>
          <td>${admin.status}</td>
          <td>
            <button class="btn btn-warning btn-sm edit-btn" data-id="${admin.id}">Edit</button>
            <button class="btn ${statusButtonClass} btn-sm toggle-status-btn" data-id="${admin.id}" data-status="${admin.status}">
              ${statusButtonLabel}
            </button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="${admin.id}">Delete</button>
          </td>
        </tr>
      `);
    });
  }

  // Pagination
  function setupPagination() {
    const totalPages = Math.ceil(admins.length / rowsPerPage);
    const pagination = $("#pagination ul");
    pagination.empty();

    for (let i = 1; i <= totalPages; i++) {
      pagination.append(
        `<li class="page-item ${i === currentPage ? "active" : ""}">
          <a href="#" class="page-link" data-page="${i}">${i}</a>
        </li>`
      );
    }

    $(".page-link").on("click", function (e) {
      e.preventDefault();
      currentPage = parseInt($(this).data("page"));
      displayTable(currentPage);
      setupPagination();
    });
  }

  // Open "Edit Admin" Modal
  // Open "Edit Admin" Modal
  $(document).on("click", ".edit-btn", function () {
    const adminId = $(this).data("id");
    $.post(
        "/cssc/server/admin/admin_management_server.php",
        { action: "get", id: adminId },
        function (response) {
            console.log("Server Response:", response); // Debugging

            try {
                const result = JSON.parse(response);

                if (!result.success || result.error) {
                    alert(result.error || "Error fetching admin data.");
                    return;
                }

                const admin = result.data;

                // Populate the form fields with admin data
                $("#editAdminId").val(admin.id);
                $("#editIdentifier").val(admin.identifier);
                $("#editUsername").val(admin.username);
                $("#edit_first_name").val(admin.firstname);
                $("#edit_middle_name").val(admin.middlename ?? ""); // Handle null middlename
                $("#edit_last_name").val(admin.lastname);
                $("#editEmail").val(admin.email);
                $("#editStatus").val(admin.status);

                $("#editAdminModal").modal("show");
            } catch (error) {
                console.error("Error parsing admin data:", error);
                alert("An error occurred while loading the admin details.");
            }
        }
    ).fail(function () {
        alert("Failed to fetch admin data.");
    });
  });


  // Submit "Add Admin" Form
  $("#addAdminForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=create";

    $.post("/cssc/server/admin/admin_management_server.php", formData, function (response) {
      console.log("Server Response:", response);
      const result = JSON.parse(response);
      if (result.success) {
        alert("Admin added successfully!");
        $("#addAdminModal").modal("hide");
        loadAdmins();
      } else {
        displayFormErrors("#addAdminForm", result.errors);
      }
    }).fail(function () {
      alert("Failed to add admin.");
    });
  });

  // Submit "Edit Admin" Form
  $("#editAdminForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=update";

    $.post("/cssc/server/admin/admin_management_server.php", formData, function (response) {
      const result = JSON.parse(response);
      if (result.success) {
        alert("Admin updated successfully!");
        $("#editAdminModal").modal("hide");
        loadAdmins();
      } else {
        displayFormErrors("#editAdminForm", result.errors);
      }
    }).fail(function () {
      alert("Failed to update admin.");
    });
  });

  // Toggle Admin Status
  $(document).on("click", ".toggle-status-btn", function () {
    const adminId = $(this).data("id");
    const currentStatus = $(this).data("status");
    const newStatus = currentStatus === "active" ? "inactive" : "active";

    if (!confirm(`Are you sure you want to ${newStatus} this admin?`)) return;

    $.post(
      "/cssc/server/admin/admin_management_server.php",
      { action: "toggle_status", id: adminId, status: newStatus },
      function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          alert(`Admin ${newStatus}d successfully!`);
          loadAdmins();
        } else {
          alert(result.error || "Failed to toggle admin status.");
        }
      }
    ).fail(function () {
      alert("Failed to toggle admin status.");
    });
  });

  // Delete Admin
  $(document).on("click", ".delete-btn", function () {
    const adminId = $(this).data("id");

    if (!confirm("Are you sure you want to permanently delete this admin? This action cannot be undone.")) return;

    $.post(
      "/cssc/server/admin/admin_management_server.php",
      { action: "delete", id: adminId },
      function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          alert("Admin permanently deleted successfully!");
          loadAdmins();
        } else {
          alert(result.error || "Failed to delete admin.");
        }
      }
    ).fail(function () {
      alert("Failed to delete admin.");
    });
  });

  // Display Form Errors
  function displayFormErrors(formSelector, errors) {
    $(formSelector + " .form-control").removeClass("is-invalid");
    Object.keys(errors).forEach((field) => {
      const fieldElement = $(formSelector + " [name='" + field + "']");
      fieldElement.addClass("is-invalid");
      fieldElement.next(".invalid-feedback").text(errors[field]);
    });
  }

  // Cleanup Modal Backdrop
  $(".modal").on("hidden.bs.modal", function () {
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
    $(this).find("form")[0].reset();
    $(this).find(".form-control").removeClass("is-invalid");
    $(this).find(".invalid-feedback").text("");
  });
});
