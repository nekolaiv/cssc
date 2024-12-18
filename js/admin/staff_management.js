$(document).ready(function () {
  let staff = []; // Stores filtered staff
  let allStaff = []; // Original data for resetting search
  const rowsPerPage = 5; // Pagination rows per page
  let currentPage = 1; // Tracks current page

  // Initial page load
  loadInitialData();

  // Load initial data (staff, departments, statuses)
  function loadInitialData() {
    loadStaff();
    loadDepartments();
    loadStatusOptions();
    setupFilters();
    setupSearch();
  }

  // Fetch all staff with optional filters
  function loadStaff(filters = {}) {
    $.post(
      "/cssc/server/admin/staff_server.php",
      { action: "read", ...filters },
      function (response) {
        try {
          console.log("Server Response:", response);
          staff = JSON.parse(response);
          allStaff = [...staff];
          displayTable(currentPage);
          setupPagination();
        } catch (error) {
          console.error("Error parsing staff data:", error);
        }
      }
    ).fail(function () {
      console.error("Failed to load staff.");
    });
  }

  // Fetch departments for dropdown
  function loadDepartments() {
    $.post(
      "/cssc/server/admin/staff_server.php",
      { action: "fetch_departments" },
      function (response) {
        const departments = JSON.parse(response);
        const dropdowns = $(".department-dropdown, #filterDepartment, #staffDepartment, #editStaffDepartment");
        dropdowns.empty().append('<option value="">All Departments</option>');
        departments.forEach(({ id, department_name }) => {
          dropdowns.append(
            `<option value="${id}">${department_name}</option>`
          );
        });
      }
    ).fail(function () {
      console.error("Failed to load departments.");
    });
  }

  // Fetch status options
  function loadStatusOptions() {
    $.post(
      "/cssc/server/admin/staff_server.php",
      { action: "fetch_status_options" },
      function (response) {
        const statuses = JSON.parse(response);
        const statusDropdown = $("#filterStatus");
        statusDropdown.empty().append('<option value="">All Statuses</option>');
        statuses.forEach((status) => {
          statusDropdown.append(
            `<option value="${status}">${status}</option>`
          );
        });
      }
    ).fail(function () {
      console.error("Failed to load statuses.");
    });
  }

  // Set up filters
  function setupFilters() {
    $("#filterDepartment, #filterStatus").on("change", function () {
      const filters = {
        department_id: $("#filterDepartment").val(),
        status: $("#filterStatus").val(),
      };
      loadStaff(filters);
    });
  }

  // Set up search functionality
  function setupSearch() {
    $("#searchStaff").on("keyup", function () {
      const query = $(this).val().toLowerCase();

      const filtered = allStaff.filter((member) => {
        const username = member.username ? member.username.toLowerCase() : "";
        const fullName = member.full_name
          ? member.full_name.toLowerCase()
          : "";
        const identifier = member.identifier
          ? member.identifier.toLowerCase()
          : "";

        return (
          identifier.includes(query) ||
          fullName.includes(query) ||
          username.includes(query)
        );
      });

      staff = filtered; // Update filtered data
      currentPage = 1;
      displayTable(currentPage);
      setupPagination();
    });
  }

  // Display data in the table
  function displayTable(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const visible = staff.slice(start, end);

    const tableBody = $("#staffTable tbody");
    tableBody.empty();

    if (visible.length === 0) {
      tableBody.append(
        `<tr><td colspan="7" class="text-center">No data found.</td></tr>`
      );
      return;
    }

    visible.forEach((member) => {
      const statusButtonLabel =
        member.status === "active" ? "Deactivate" : "Activate";
      const statusButtonClass =
        member.status === "active" ? "btn-outline-danger" : "btn-outline-success";

      tableBody.append(`  
        <tr>
          <td>${member.identifier}</td>
          <td>${member.full_name}</td>
          <td>${member.username}</td>
          <td>${member.email}</td>
          <td>${member.department}</td>
          <td>${member.status}</td>
          <td>
            <button class="btn btn-warning btn-sm edit-btn" data-id="${member.id}">Edit</button>
            <button class="btn ${statusButtonClass} btn-sm toggle-status-btn" data-id="${member.id}" data-status="${member.status}">
              ${statusButtonLabel}
            </button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="${member.id}">Delete</button>
          </td>
        </tr>
      `);
    });
  }

  // Set up pagination
  function setupPagination() {
    const totalPages = Math.ceil(staff.length / rowsPerPage);
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

  // Open "Edit Staff" modal
  $(document).on("click", ".edit-btn", function () {
    const staffId = $(this).data("id");
    console.log("Fetching Staff ID:", staffId); // Debug ID being sent
    $.post(
      "/cssc/server/admin/staff_server.php",
      { action: "get", id: staffId },
      function (response) {
        const staffMember = JSON.parse(response);
        console.log("Staff Data:", staffMember);
        if (staffMember.error) return alert("Error fetching staff data.");

        $("#editStaffId").val(staffMember.id);
        $("#editStaffIdentifier").val(staffMember.identifier);
        $("#editStaffFirstName").val(staffMember.firstname);
        $("#editStaffMiddleName").val(staffMember.middlename ?? "");
        $("#editStaffLastName").val(staffMember.lastname);
        $("#editStaffUsername").val(staffMember.username);
        $("#editStaffEmail").val(staffMember.email);
        $("#editStaffDepartment").val(staffMember.department_id);
        $("#editStaffStatus").val(staffMember.status);

        $("#editStaffModal").modal("show");
      }
    ).fail(function () {
      alert("Failed to fetch staff data.");
    });
  });

  // Submit "Add Staff" form
  $("#addStaffForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=create";

    $.post("/cssc/server/admin/staff_server.php", formData, function (response) {
      const result = JSON.parse(response);
      if (result.success) {
        alert("Staff created successfully!");
        $("#addStaffModal").modal("hide");
        loadStaff();
      } else {
        displayFormErrors("#addStaffForm", result.errors);
      }
    }).fail(function () {
      alert("Failed to create staff.");
    });
  });

  // Submit "Edit Staff" form
  $("#editStaffForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=update";

    $.post("/cssc/server/admin/staff_server.php", formData, function (response) {
      const result = JSON.parse(response);
      if (result.success) {
        alert("Staff updated successfully!");
        $("#editStaffModal").modal("hide");
        loadStaff();
      } else {
        displayFormErrors("#editStaffForm", result.errors);
      }
    }).fail(function () {
      alert("Failed to update staff.");
    });
  });

  // Toggle staff status
  $(document).on("click", ".toggle-status-btn", function () {
    const staffId = $(this).data("id");
    const currentStatus = $(this).data("status");
    const newStatus = currentStatus === "active" ? "inactive" : "active";

    if (!confirm(`Are you sure you want to ${newStatus} this account?`)) {
      return;
    }

    $.post(
      "/cssc/server/admin/staff_server.php",
      { action: "toggle_status", id: staffId, status: newStatus },
      function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          alert(`Account ${newStatus}d successfully!`);
          loadStaff();
        } else {
          alert(result.error || `Failed to ${newStatus} the account.`);
        }
      }
    ).fail(function () {
      alert("Failed to toggle staff status.");
    });
  });

  // Delete staff
  $(document).on("click", ".delete-btn", function () {
    const staffId = $(this).data("id");

    if (!confirm("Are you sure you want to permanently delete this staff?")) {
      return;
    }

    $.post(
      "/cssc/server/admin/staff_server.php",
      { action: "delete", id: staffId },
      function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          alert("Staff permanently deleted successfully!");
          loadStaff();
        } else {
          alert(result.error || "Failed to delete staff.");
        }
      }
    ).fail(function () {
      alert("Failed to delete staff.");
    });
  });

  // Display form errors
  function displayFormErrors(formSelector, errors) {
    $(formSelector + " .form-control").removeClass("is-invalid");
    Object.keys(errors).forEach((field) => {
      const fieldElement = $(formSelector + " [name='" + field + "']");
      fieldElement.addClass("is-invalid");
      fieldElement.next(".invalid-feedback").text(errors[field]);
    });
  }

  // Cleanup modal when closed
  $(".modal").on("hidden.bs.modal", function () {
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
    $(this).find("form")[0].reset();
    $(this).find(".form-control").removeClass("is-invalid");
    $(this).find(".invalid-feedback").text("");
  });
});
