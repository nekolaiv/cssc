$(document).ready(function () {
    let students = []; // Store all students
    let allStudents = []; // Store original data for search reset
    const rowsPerPage = 5; // Rows per page
    let currentPage = 1; // Current page

  loadStudents();

  const studentForm = document.getElementById('studentForm');

  // Bootstrap validation styles
  studentForm.addEventListener('submit', function (e) {
      if (!studentForm.checkValidity()) {
          e.preventDefault(); // Prevent form submission
          e.stopPropagation(); // Stop event bubbling
      }
      studentForm.classList.add('was-validated'); // Add validation classes
  });

  // Load all students into the table
  function loadStudents() {
    $.ajax({
      url: "/cssc/server/studentServer.php",
      type: "POST",
      data: { action: "read" },
      success: function (response) {
        students = JSON.parse(response);
        allStudents = [...students]; // Keep original data
        displayTable(currentPage); // Display the first page
        setupPagination(); // Set up pagination
      },
    });
  }

  function displayTable(page) {
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const visibleStudents = students.slice(startIndex, endIndex);
  
    const tableBody = $("#studentsTable tbody");
    tableBody.empty();

    if (visibleStudents.length === 0) {
      tableBody.append(`
          <tr>
              <td colspan="8" class="text-center">No data found.</td>
          </tr>
      `);
      return; // Exit the function early since there's no data to process
  }
  
    visibleStudents.forEach((student) => {
      tableBody.append(`
        <tr>
          <td>${student.student_id}</td>
          <td>${student.first_name} ${student.middle_name ?? ''} ${student.last_name}</td>
          <td>${student.email}</td>
          <td>
            <span class="masked-password">••••••••</span>
            <button class="btn btn-sm btn-secondary reveal-password" data-password="${student.password}">Reveal</button>
          </td>
          <td>${student.course}</td>
          <td>${student.year_level}</td>
          <td>${student.section}</td>
          <td>
            <button class="btn btn-sm btn-warning edit-btn" data-id="${student.user_id}">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${student.user_id}">Delete</button>
          </td>
        </tr>
      `);
    });
  
    attachEventListeners(); // Reattach event listeners
  }
  
  function setupPagination() {
    const pageCount = Math.ceil(students.length / rowsPerPage);
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
  

  // Search functionality
  $("#searchStudent").on("keyup", function () {
    const value = $(this).val().toLowerCase();
  
    if (value === "") {
      students = [...allStudents]; // Reset to original data
      currentPage = 1; // Reset to first page
      displayTable(currentPage);
      setupPagination();
    } else {
      students = allStudents.filter((student) => {
        return (
          student.student_id.toLowerCase().includes(value) ||
          `${student.first_name} ${student.middle_name ?? ''} ${student.last_name}`.toLowerCase().includes(value)
        );
      });
      currentPage = 1; // Reset to first page
      displayTable(currentPage);
      setupPagination();
    }
  });
  

  // Open "Add Student" modal
  $("#addStudentBtn").click(function () {
    console.log("Add Student Button Clicked"); // Debug log
    $("#studentForm")[0].reset();
    $("#user_id").val(""); // Clear hidden user_id field
    $("#studentModalLabel").text("Add Student");
    $("#studentModal").modal("show");
  });

// Handle form submission for Add/Edit
$("#studentForm").submit(function (e) {
  e.preventDefault();

  const action = $("#user_id").val() ? "update" : "create";
  let formData = $(this).serialize();

  if (!$("#password").val()) {
      formData = formData.replace(/&password=[^&]*/, ""); // Remove password if empty
  }

  formData += `&action=${action}`;
  console.log("Submitting Form Data:", formData);

  $.ajax({
      url: "/cssc/server/studentServer.php",
      type: "POST",
      data: formData,
      success: function (response) {
          console.log("Raw Response:", response);
          const result = JSON.parse(response);

          // Clear previous error messages
          $(".form-control").removeClass("is-invalid");
          $(".invalid-feedback").text("");
          $("#studentModal .modal-content").removeClass("border-danger");

          if (result.success) {
              alert("Student saved successfully!");
              $("#studentModal").modal("hide");
              loadStudents();
          } else if (result.errors) {
              // Display error messages
              $("#studentModal .modal-content").addClass("border-danger");
              Object.keys(result.errors).forEach(function (field) {
                  const errorMessage = result.errors[field];
                  const fieldElement = $(`[name="${field}"]`);
                  fieldElement.addClass("is-invalid");
                  fieldElement
                      .next(".invalid-feedback")
                      .text(errorMessage)
                      .show();
              });
          } else {
              alert("An unexpected error occurred.");
          }
      },
      error: function (xhr, status, error) {
          console.error("Failed to submit form:", error);
      }
  });
});




      // Toggle password visibility
      $("#togglePassword").click(function () {
        const passwordField = $("#password");
        const type = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        $(this).text(type === "password" ? "Show" : "Hide");
    });

    $(document).on("click", ".reveal-password", function () {
        const password = $(this).data("password");
        alert(`Password: ${password}`);
    });
    
  // Attach event listeners for Edit and Delete buttons
  function attachEventListeners() {
    $(".edit-btn").click(function () {
      const user_id = $(this).data("id");
      console.log("Triggering edit for User ID:", user_id);
  
      $.ajax({
          url: "/cssc/server/studentServer.php",
          type: "POST",
          data: { action: "get", user_id: user_id },
          success: function (response) {
              console.log("Edit response:", response);
              const student = JSON.parse(response);
  
              if (student.error) {
                  console.error("Error fetching student:", student.error);
                  alert("Error: " + student.error);
                  return;
              }
  
              // Populate the modal with data
              $("#user_id").val(student.user_id);
              $("#student_id").val(student.student_id);
              $("#first_name").val(student.first_name);
              $("#middle_name").val(student.middle_name ?? "");
              $("#last_name").val(student.last_name);
              $("#email").val(student.email);
              $("#password").val(""); // Leave password blank
              $("#course").val(student.course);
              $("#year_level").val(student.year_level);
              $("#section").val(student.section);
  
              // Reset error messages and modal styles
              $(".form-control").removeClass("is-invalid");
              $(".invalid-feedback").text("");
              $("#studentModal .modal-content").removeClass("border-danger");
  
              $("#studentModalLabel").text("Edit Student");
              $("#studentModal").modal("show");
          },
          error: function (xhr, status, error) {
              console.error("Failed to fetch student data:", error);
          }
      });
  });
  
  

    $(".delete-btn").click(function () {
      const user_id = $(this).data("id");
      if (confirm("Are you sure you want to delete this student?")) {
        $.ajax({
          url: "/cssc/server/studentServer.php",
          type: "POST",
          data: { action: "delete", user_id },
          success: function (response) {
            const result = JSON.parse(response);
            alert(
              result.success ? "Deleted successfully!" : "Failed to delete."
            );
            loadStudents();
          },
        });
      }
    });
  }
});
