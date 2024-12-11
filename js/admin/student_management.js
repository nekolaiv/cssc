$(document).ready(function () {
  let students = []; // Store all students
  let allStudents = []; // Store original data for search reset
  const rowsPerPage = 5; // Rows per page
  let currentPage = 1; // Current page

  // Load students and courses on page load
  loadStudents();
  loadCourses();

  // Load all students into the table
  function loadStudents() {
    $.ajax({
      url: "/cssc/server/admin/student_server.php",
      type: "POST",
      data: { action: "read" },
      success: function (response) {
        console.log("Load Students Response:", response); // Log the response
        students = JSON.parse(response);
        allStudents = [...students]; // Keep original data
        displayTable(currentPage); // Display the first page
        setupPagination(); // Set up pagination
      },
      error: function (xhr, status, error) {
        console.error("Failed to load students:", error);
      },
    });
  }

  // Load courses into the dropdowns
  function loadCourses() {
    $.ajax({
      url: "/cssc/server/admin/student_server.php",
      type: "POST",
      data: { action: "get_courses" },
      success: function (response) {
        console.log("Load Courses Response:", response);
        const courses = JSON.parse(response);
        const courseDropdowns = $(".course-dropdown");
        courseDropdowns.empty();
        courseDropdowns.append('<option value="">Select a course</option>');

        courses.forEach((course) => {
          courseDropdowns.append(
            `<option value="${course.course_id}">${course.course_code}</option>`
          );
        });
      },
      error: function (xhr, status, error) {
        console.error("Failed to load courses:", error);
      },
    });
  }

  function displayTable(page) {
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const visibleStudents = students.slice(startIndex, endIndex);

    const tableBody = $("#studentsTable tbody");
    tableBody.empty(); // Clear table before appending new rows

    if (visibleStudents.length === 0) {
      tableBody.append(
        `<tr>
          <td colspan="7" class="text-center">No data found.</td>
        </tr>`
      );
      return; // Exit the function early since there's no data to process
    }

    visibleStudents.forEach((student) => {
      tableBody.append(
        `<tr>
          <td>${student.student_id}</td>
          <td>${student.first_name} ${student.middle_name ?? ""} ${
        student.last_name
      }</td>
          <td>${student.email}</td>
          <td>${student.course_code}</td>
          <td>${student.year_level}</td>
          <td>${student.section}</td>
          <td>
              <button class="btn btn-sm btn-warning edit-btn" data-id="${
                student.user_id
              }">Edit</button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="${
                student.user_id
              }">Delete</button>
          </td>
        </tr>`
      );
    });
  }

  function setupPagination() {
    const pageCount = Math.ceil(students.length / rowsPerPage);
    const pagination = $("#pagination ul");
    pagination.empty();

    for (let i = 1; i <= pageCount; i++) {
      const activeClass = i === currentPage ? "active" : "";
      pagination.append(
        `<li class="page-item ${activeClass}">
          <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>`
      );
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
          `${student.first_name} ${student.middle_name ?? ""} ${
            student.last_name
          }`
            .toLowerCase()
            .includes(value)
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
    $("#addStudentForm")[0].reset();
    $("#addStudentModal").modal("show");
  });

  // Open "Edit Student" modal
  $(document).on("click", ".edit-btn", function () {
    const user_id = $(this).data("id");
    console.log("Edit Button Clicked for User ID:", user_id);

    $.ajax({
      url: "/cssc/server/admin/student_server.php",
      type: "POST",
      data: { action: "get", user_id: user_id },
      success: function (response) {
        console.log("Edit Response:", response);
        const student = JSON.parse(response);

        if (student.error) {
          alert("Error fetching student data.");
          return;
        }

        $("#edit_user_id").val(student.user_id);
        $("#edit_student_id").val(student.student_id);
        $("#edit_first_name").val(student.first_name);
        $("#edit_middle_name").val(student.middle_name ?? "");
        $("#edit_last_name").val(student.last_name);
        $("#edit_email").val(student.email);
        $("#edit_course").val(student.course_id); // Update course dropdown
        $("#edit_year_level").val(student.year_level);
        $("#edit_section").val(student.section);

        $("#editStudentModal").modal("show");
      },
      error: function () {
        alert("Error fetching student details.");
      },
    });
  });

  // Handle form submission for "Add Student"
  $("#addStudentForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=create";
    console.log("Submitting Add Student Form Data:", formData);

    $.ajax({
      url: "/cssc/server/admin/student_server.php",
      type: "POST",
      data: formData,
      success: function (response) {
        console.log("Add Student Response:", response);
        const result = JSON.parse(response);

        if (result.success) {
          alert("Student added successfully!");
          $("#addStudentModal").modal("hide");
          loadStudents();
        } else {
          displayFormErrors("#addStudentForm", result.errors);
        }
      },
      error: function (xhr, status, error) {
        console.error("Failed to add student:", error);
      },
    });
  });

  // Handle form submission for "Edit Student"
  $("#editStudentForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + "&action=update";
    console.log("Submitting Edit Student Form Data:", formData);

    $.ajax({
      url: "/cssc/server/admin/student_server.php",
      type: "POST",
      data: formData,
      success: function (response) {
        console.log("Edit Student Response:", response);
        const result = JSON.parse(response);

        if (result.success) {
          alert("Student updated successfully!");
          $("#editStudentModal").modal("hide");
          loadStudents();
        } else {
          displayFormErrors("#editStudentForm", result.errors);
        }
      },
      error: function (xhr, status, error) {
        console.error("Failed to update student:", error);
      },
    });
  });

  // Display form errors
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

  // Attach event listeners for Delete buttons
  $(document).on("click", ".delete-btn", function () {
    const user_id = $(this).data("id");
    console.log("Delete Button Clicked for User ID:", user_id);

    if (confirm("Are you sure you want to delete this student?")) {
      $.ajax({
        url: "/cssc/server/admin/student_server.php",
        type: "POST",
        data: { action: "delete", user_id },
        success: function (response) {
          console.log("Delete Student Response:", response);
          const result = JSON.parse(response);
          alert(
            result.success
              ? "Student deleted successfully!"
              : "Failed to delete student."
          );
          loadStudents();
        },
        error: function (xhr, status, error) {
          console.error("Failed to delete student:", error);
        },
      });
    }
  });
});
