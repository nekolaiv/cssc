$(document).ready(function () {
  let staff = [];
  let filteredStaff = [];
  const rowsPerPage = 5; // Rows per page
  let currentPage = 1;

  // Load staff data on page load
  loadStaff();

  // Search functionality
  $("#searchStaff").on("input", function () {
    const query = $(this).val().toLowerCase();
    filteredStaff = staff.filter(
      (s) =>
        s.staff_id.toString().toLowerCase().includes(query) || // Convert to string
        `${s.first_name} ${s.middle_name || ""} ${s.last_name}`
          .toLowerCase()
          .includes(query)
    );
    currentPage = 1; // Reset to the first page when searching
    displayTable(currentPage);
    setupPagination(filteredStaff); // Pass filtered data for pagination
  });

  function loadStaff() {
    $.ajax({
      url: "/cssc/server/admin/staff_server.php",
      type: "POST",
      data: { action: "read" },
      success: function (response) {
        staff = JSON.parse(response);
        filteredStaff = staff; // Initially show all staff
        displayTable(currentPage);
        setupPagination(filteredStaff); // Pass all data initially
      },
      error: function (xhr, status, error) {
        console.error("Failed to load staff:", error);
      },
    });
  }

  function displayTable(page) {
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const visibleStaff = filteredStaff.slice(startIndex, endIndex);

    const tableBody = $("#staffTable tbody");
    tableBody.empty(); // Clear the table before appending new rows

    if (visibleStaff.length === 0) {
      tableBody.append(`
        <tr>
          <td colspan="4" class="text-center">No data found.</td>
        </tr>
      `);
    } else {
      visibleStaff.forEach((staffMember) => {
        const fullName = `${staffMember.first_name} ${
          staffMember.middle_name ? staffMember.middle_name + " " : ""
        }${staffMember.last_name}`;
        tableBody.append(`
          <tr>
            <td>${staffMember.staff_id}</td>
            <td>${fullName}</td>
            <td>${staffMember.email}</td>
            <td>
              <button class="btn btn-sm btn-warning edit-btn" data-id="${staffMember.staff_id}">Edit</button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="${staffMember.staff_id}">Delete</button>
            </td>
          </tr>
        `);
      });
    }

    attachEventListeners();
  }

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

    $(".page-link").on("click", function (e) {
      e.preventDefault();
      currentPage = parseInt($(this).data("page"));
      displayTable(currentPage);

      $(".page-item").removeClass("active");
      $(this).parent().addClass("active");
    });
  }

  // Open "Add Staff" modal
  $("#addStaffBtn").click(function () {
    $("#addStaffForm")[0].reset();
    $(".form-control").removeClass("is-invalid");
    $(".invalid-feedback").text("");
    $("#addStaffModal").modal("show");
  });

  // Open "Edit Staff" modal
  $(document).on("click", ".edit-btn", function () {
    const staff_id = $(this).data("id");

    $.ajax({
      url: "/cssc/server/admin/staff_server.php",
      type: "POST",
      data: { action: "get", staff_id },
      success: function (response) {
        const staffMember = JSON.parse(response);

        if (staffMember.error) {
          alert("Error: " + staffMember.error);
          return;
        }

        $("#edit_staff_id").val(staffMember.staff_id);
        $("#edit_email").val(staffMember.email);
        $("#edit_first_name").val(staffMember.first_name);
        $("#edit_last_name").val(staffMember.last_name);
        $("#edit_middle_name").val(staffMember.middle_name);

        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").text("");
        $("#editStaffModal").modal("show");
      },
      error: function () {
        alert("Failed to fetch staff details.");
      },
    });
  });

  // Handle form submission for "Add Staff"
  $("#addStaffForm").submit(function (e) {
    e.preventDefault();

    const formData = $(this).serialize() + "&action=create";

    $.ajax({
      url: "/cssc/server/admin/staff_server.php",
      type: "POST",
      data: formData,
      success: function (response) {
        const result = JSON.parse(response);

        if (result.success) {
          alert("Staff added successfully!");
          $("#addStaffModal").modal("hide");
          loadStaff();
        } else {
          displayFormErrors("#addStaffForm", result.errors);
        }
      },
      error: function (xhr, status, error) {
        console.error("Failed to add staff:", error);
      },
    });
  });

  // Handle form submission for "Edit Staff"
  $("#editStaffForm").submit(function (e) {
    e.preventDefault();

    const formData = $(this).serialize() + "&action=update";

    $.ajax({
      url: "/cssc/server/admin/staff_server.php",
      type: "POST",
      data: formData,
      success: function (response) {
        const result = JSON.parse(response);

        if (result.success) {
          alert("Staff updated successfully!");
          $("#editStaffModal").modal("hide");
          loadStaff();
        } else {
          displayFormErrors("#editStaffForm", result.errors);
        }
      },
      error: function (xhr, status, error) {
        console.error("Failed to update staff:", error);
      },
    });
  });

  function displayFormErrors(formSelector, errors) {
    $(".form-control").removeClass("is-invalid");
    $(".invalid-feedback").text("");

    Object.keys(errors).forEach(function (field) {
      const errorMessage = errors[field];
      const fieldElement = $(formSelector + " [name='" + field + "']");
      fieldElement.addClass("is-invalid");
      fieldElement.next(".invalid-feedback").text(errorMessage).show();
    });
  }

  // Delete staff
  $(document).on("click", ".delete-btn", function () {
    const staff_id = $(this).data("id");

    if (confirm("Are you sure you want to delete this staff?")) {
      $.ajax({
        url: "/cssc/server/admin/staff_server.php",
        type: "POST",
        data: { action: "delete", staff_id },
        success: function (response) {
          const result = JSON.parse(response);
          alert(result.success ? "Staff deleted successfully!" : "Failed to delete staff.");
          loadStaff();
        },
        error: function () {
          alert("Failed to delete staff.");
        },
      });
    }
  });
});
