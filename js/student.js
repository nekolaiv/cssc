$(document).ready(function () {
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
        let students = JSON.parse(response);
        let tableBody = $("#studentsTable tbody");
        tableBody.empty();

        students.forEach((student) => {
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

        attachEventListeners();
      },
    });
  }

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
    const formData = $(this).serialize() + `&action=${action}`;

    console.log("Submitting Form Data:", formData);

    $.ajax({
      url: "/cssc/server/studentServer.php",
      type: "POST",
      data: formData,
      success: function (response) {
        console.log("Raw Response:", response); // Debugging log
        const result = JSON.parse(response);
        alert(result.success ? "Success!" : "Failed.");
        $("#studentModal").modal("hide");
        loadStudents();
      },
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
        console.log("Triggering edit for User ID:", user_id); // Confirm this is outputting correctly
    
        $.ajax({
            url: "/cssc/server/studentServer.php",
            type: "POST",
            data: { action: "get", user_id: user_id },
            success: function (response) {
                console.log("Edit response:", response);
                const student = JSON.parse(response);
                if(student.error) {
                    console.error("Error fetching student:", student.error);
                    alert("Error: " + student.error);
                    return;
                }
    
                // Populate the modal with data
                $("#user_id").val(student.user_id);
                $("#student_id").val(student.student_id);
                $("#first_name").val(student.first_name);
                $("#last_name").val(student.last_name);
                $("#email").val(student.email);
                $("#course").val(student.course);
                $("#year_level").val(student.year_level);
                $("#section").val(student.section);
    
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
