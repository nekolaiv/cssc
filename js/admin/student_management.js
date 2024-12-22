$(document).ready(function () {
  let students = []; // Stores filtered students
  let allStudents = []; // Original data for resetting search
  const rowsPerPage = 5; // Pagination rows per page
  let currentPage = 1; // Tracks current page

  // Initial page load
  loadInitialData();

  // Load initial data (students, curriculums, statuses)
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
          console.log("Server Response:", response);
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
        const username = student.username ? student.username.toLowerCase() : ''; // Handle null username
        const fullName = student.full_name ? student.full_name.toLowerCase() : '';
        const identifier = student.identifier ? student.identifier.toLowerCase() : '';
  
        return (
          identifier.includes(query) ||
          fullName.includes(query) ||
          username.includes(query)
        );
      });
  
      students = filtered; // Update filtered data
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
      tableBody.append(`<tr><td colspan="7" class="text-center">No data found.</td></tr>`);
      return;
    }
  
    visible.forEach((student) => {
      const statusButtonLabel = student.status === "active" ? "Deactivate" : "Activate";
      const statusButtonClass = student.status === "active" ? "btn-outline-danger" : "btn-outline-success";
  
      tableBody.append(`
        <tr>
          <td>${student.identifier}</td>
          <td>${student.full_name}</td>
          <td>${student.username}</td>
          <td>${student.email}</td>
          <td>${student.curriculum}</td>
          <td>${student.status}</td>
          <td>
            <button class="btn btn-warning btn-sm edit-btn" data-id="${student.id}">Edit</button>
            <button class="btn ${statusButtonClass} btn-sm toggle-status-btn" data-id="${student.id}" data-status="${student.status}">
              ${statusButtonLabel}
            </button>
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
    $.post("/cssc/server/admin/student_server.php", { action: "get", id: userId }, function (response) {
      const user = JSON.parse(response);

      if (user.error) return alert("Error fetching user data.");

      $("#editUserId").val(user.id);
      $("#editIdentifier").val(user.identifier);
      $("#editUsername").val(user.username);
      $("#edit_first_name").val(user.firstname);
      $("#edit_middle_name").val(user.middlename ?? "");
      $("#edit_last_name").val(user.lastname);
      $("#editEmail").val(user.email);
      $("#editCurriculum").val(user.curriculum_id);
      $("#editStatus").val(user.status);

      $("#editUserModal").modal("show");
    }).fail(function () {
      alert("Failed to fetch user data.");
    });
  });

  // Submit "Add User" form
  $("#addUserForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=create";

    $.post("/cssc/server/admin/student_server.php", formData, function (response) {
      const result = JSON.parse(response);
      console.log("Server Response:", result);
      if (result.success) {
        alert("User created successfully!");
        $("#addUserModal").modal("hide");
        loadStudents();
      } else {
        displayFormErrors("#addUserForm", result.errors);
      }
    }).fail(function () {
      alert("Failed to create user.");
    });
  });

  // Submit "Edit User" form
  $("#editUserForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=update";

    $.post("/cssc/server/admin/student_server.php", formData, function (response) {
      const result = JSON.parse(response);
      if (result.success) {
        alert("User updated successfully!");
        $("#editUserModal").modal("hide");
        loadStudents();
      } else {
        displayFormErrors("#editUserForm", result.errors);
      }
    }).fail(function () {
      alert("Failed to update user.");
    });
  });

  $(document).on("click", ".toggle-status-btn", function () {
    const userId = $(this).data("id");
    const currentStatus = $(this).data("status");
    const newStatus = currentStatus === "active" ? "inactive" : "active";
  
    // Confirm the action
    if (!confirm(`Are you sure you want to ${newStatus} this account?`)) {
      return;
    }
  
    // Send AJAX request to toggle account status
    $.post(
      "/cssc/server/admin/student_server.php",
      { action: "toggle_status", id: userId, status: newStatus },
      function (response) {
        try {
          const result = JSON.parse(response);
          if (result.success) {
            alert(`Account ${newStatus}d successfully!`);
            loadStudents(); // Reload the table
          } else {
            alert(result.error || `Failed to ${newStatus} the account.`);
          }
        } catch (error) {
          console.error("Error parsing toggle status response:", error);
          alert("An error occurred while updating the account status.");
        }
      }
    ).fail(function (xhr, status, error) {
      console.error("AJAX Error:", xhr.responseText || error);
      alert("Failed to communicate with the server. Please try again later.");
    });
  });

  $(document).on("click", ".delete-btn", function () {
    const userId = $(this).data("id");
  
    // Confirm before deletion
    if (!confirm("Are you sure you want to permanently delete this user? This action cannot be undone.")) {
      return;
    }
  
    // Send AJAX request to delete the user
    $.post(
      "/cssc/server/admin/student_server.php",
      { action: "delete_permanent", id: userId },
      function (response) {
        try {
          const result = JSON.parse(response);
          if (result.success) {
            alert("User permanently deleted successfully!");
            loadStudents(); // Reload the table
          } else {
            alert(result.error || "Failed to delete user permanently. Please try again.");
          }
        } catch (error) {
          console.error("Error parsing delete response:", error);
          alert("An unexpected error occurred while deleting the user.");
        }
      }
    ).fail(function (xhr, status, error) {
      console.error("AJAX Error:", xhr.responseText || error);
      alert("Failed to communicate with the server. Please try again later.");
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

//   // Delete User
// $(document).on("click", ".delete-btn", function () {
//   const userId = $(this).data("id");

//   // Confirm before deleting
//   if (!confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
//     return;
//   }

//   // Send delete request to the server
//   $.post("/cssc/server/admin/student_server.php", { action: "delete", id: userId }, function (response) {
//     try {
//       const result = JSON.parse(response);

//       if (result.success) {
//         alert("User deleted successfully!");
//         loadStudents(); // Reload the table to reflect changes
//       } else {
//         alert(result.error || "Failed to delete user. Please try again.");
//       }
//     } catch (error) {
//       console.error("Error parsing delete response:", error);
//       alert("An error occurred while deleting the user.");
//     }
//   }).fail(function (xhr, status, error) {
//     console.error("AJAX Error:", xhr.responseText || error);
//     alert("Failed to communicate with the server. Please try again later.");
//   });
// });


  // Event: Cleanup modal backdrop after the modal is closed
$(".modal").on("hidden.bs.modal", function () {
  // Remove any lingering modal-backdrop elements
  $(".modal-backdrop").remove();

  // Remove the 'modal-open' class from the body to fix scrolling
  $("body").removeClass("modal-open");

  // Reset the form inside the modal
  $(this).find("form")[0].reset();
  $(this).find(".form-control").removeClass("is-invalid");
  $(this).find(".invalid-feedback").text("");
});

});
