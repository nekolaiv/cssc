$(document).ready(function () {
  let students = []; // Stores filtered students
  let allStudents = []; // Original data for resetting search
  const rowsPerPage = 5; // Pagination rows per page
  let currentPage = 1; // Tracks current page

  // Initial page load
  loadInitialData();

  // Load initial data (students, courses, curriculums)
  function loadInitialData() {
    loadStudents();
    loadCurriculums();
    loadStatusOptions();
    setupFilters();
    setupSearch();
  }

  // Fetch all students with optional filters
  function loadStudents(filters = {}) {
    $.post(
      "/cssc/server/admin/student_server.php",
      { action: "read", ...filters },
      function (response) {
        try {
          students = JSON.parse(response);
          allStudents = [...students];
          displayTable(currentPage);
          setupPagination();
        } catch (error) {
          console.error("Error parsing student data:", error);
        }
      }
    ).fail(function () {
      console.error("Failed to load students.");
    });
  }

  // Fetch curriculums for dropdown
  function loadCurriculums() {
    $.post("/cssc/server/admin/student_server.php", { action: "fetch_curriculums" }, function (response) {
      const curriculums = JSON.parse(response);
      const dropdowns = $(".curriculum-dropdown, #filterCurriculum");
      dropdowns.empty().append('<option value="">All Curriculums</option>');
      curriculums.forEach(({ id, remarks }) => {
        dropdowns.append(`<option value="${id}">${remarks}</option>`);
      });
    }).fail(function () {
      console.error("Failed to load curriculums.");
    });
  }

  // Fetch status options
  function loadStatusOptions() {
    $.post("/cssc/server/admin/student_server.php", { action: "fetch_status_options" }, function (response) {
      const statuses = JSON.parse(response);
      const statusDropdown = $("#filterStatus");
      statusDropdown.empty().append('<option value="">All Statuses</option>');
      statuses.forEach((status) => {
        statusDropdown.append(`<option value="${status}">${status}</option>`);
      });
    }).fail(function () {
      console.error("Failed to load statuses.");
    });
  }

  // Set up filters
  function setupFilters() {
    $("#filterCurriculum, #filterStatus").on("change", function () {
      const filters = {
        curriculum_id: $("#filterCurriculum").val(),
        status: $("#filterStatus").val(),
      };
      loadStudents(filters);
    });
  }

  // Set up search functionality
  function setupSearch() {
    $("#searchStudent").on("keyup", function () {
      const query = $(this).val().toLowerCase();
      const filtered = allStudents.filter((student) => {
        return (
          student.identifier.toLowerCase().includes(query) ||
          student.full_name.toLowerCase().includes(query)
        );
      });
      students = filtered;
      currentPage = 1;
      displayTable(currentPage);
      setupPagination();
    });
  }

  // Display data in the table
  function displayTable(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const visible = students.slice(start, end);

    const tableBody = $("#studentsTable tbody");
    tableBody.empty();

    if (visible.length === 0) {
      tableBody.append(`<tr><td colspan="6" class="text-center">No data found.</td></tr>`);
      return;
    }

    visible.forEach((student) => {
      tableBody.append(`
        <tr>
          <td>${student.identifier}</td>
          <td>${student.full_name}</td>
          <td>${student.email}</td>
          <td>${student.curriculum}</td>
          <td>${student.status}</td>
          <td>
            <button class="btn btn-warning btn-sm edit-btn" data-id="${student.id}">Edit</button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="${student.id}">Delete</button>
          </td>
        </tr>
      `);
    });
  }

  // Set up pagination
  function setupPagination() {
    const totalPages = Math.ceil(students.length / rowsPerPage);
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

  // Open "Edit User" modal
  $(document).on("click", ".edit-btn", function () {
    const userId = $(this).data("id");
    $.post(
      "/cssc/server/admin/student_server.php",
      { action: "get", id: userId },
      function (response) {
        const user = JSON.parse(response);

        if (user.error) return alert("Error fetching user data.");

        $("#edit_user_id").val(user.id);
        $("#edit_identifier").val(user.identifier);
        $("#edit_first_name").val(user.firstname);
        $("#edit_middle_name").val(user.middlename ?? "");
        $("#edit_last_name").val(user.lastname);
        $("#edit_email").val(user.email);
        $("#edit_curriculum").val(user.curriculum_id);
        $("#edit_status").val(user.status);

        $("#editUserModal").modal("show");
      }
    ).fail(function () {
      alert("Failed to fetch user data.");
    });
  });

  // Delete User
  $(document).on("click", ".delete-btn", function () {
    const userId = $(this).data("id");
    if (!confirm("Are you sure you want to delete this user?")) return;

    $.post(
      "/cssc/server/admin/student_server.php",
      { action: "delete", id: userId },
      function (response) {
        const result = JSON.parse(response);
        alert(result.success ? "User deleted successfully!" : "Failed to delete user.");
        loadStudents();
      }
    ).fail(function () {
      alert("Failed to delete user.");
    });
  });

  // Submit "Add User" form
  $("#addUserForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=create";

    $.post("/cssc/server/admin/student_server.php", formData, function (response) {
      const result = JSON.parse(response);
      if (result.success) {
        alert("User created successfully!");
        $(".modal-backdrop").remove();
        $("#addUserModal").modal("hide");
        loadStudents();
      } else {
        displayFormErrors("#addUserForm", result.errors);
      }
    }).fail(function () {
      alert("Failed to create user.");
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
});
