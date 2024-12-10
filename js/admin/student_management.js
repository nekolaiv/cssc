$(document).ready(function () {
  // Function to toggle show/hide password
  $("#togglePassword").click(function () {
      const passwordField = $("#password");
      const type = passwordField.attr("type") === "password" ? "text" : "password";
      passwordField.attr("type", type);

      // Change the icon based on visibility
      $(this).toggleClass("fa-eye fa-eye-slash");
  });

  // Function to fetch and display students
  function fetchStudents(filters = {}) {
      console.log("Fetching students with filters:", filters);
      $.ajax({
          url: "../../server/admin/student_management_server.php",
          type: "POST",
          data: { action: "fetch", ...filters },
          dataType: "json",
          success: function (response) {
              console.log("Fetch Students Response:", response);
              if (response.success) {
                  const students = response.data;
                  let tableRows = "";

                  students.forEach(student => {
                      tableRows += `
                          <tr>
                              <td>${student.student_id}</td>
                              <td>${student.first_name} ${student.middle_name ? student.middle_name : ""} ${student.last_name}</td>
                              <td>${student.email}</td>
                              <td>${student.course_code}</td> <!-- Use course_code -->
                              <td>${student.year_level_name}</td> <!-- Use year_level_name -->
                              <td>${student.section_code}</td> <!-- Use section_code -->
                              <td>
                                  <button class="btn btn-primary btn-sm edit-student" data-id="${student.student_id}">Edit</button>
                                  <button class="btn btn-danger btn-sm delete-student" data-id="${student.student_id}">Delete</button>
                              </td>
                          </tr>
                      `;
                  });

                  $("#studentTable tbody").html(tableRows);
              } else {
                  console.error("Error Message:", response.message);
                  alert(response.message);
              }
          },
          error: function (xhr, status, error) {
              console.error("AJAX Fetch Error:", xhr.responseText);
              alert("An error occurred while fetching students.");
          }
      });
  }

  // Function to populate dropdowns in edit modal
  function populateDropdowns(selectedCourseId, selectedYearLevelId, selectedSectionId) {
      console.log("Populating dropdowns for edit modal.");
      $.ajax({
          url: "../../server/admin/student_management_server.php",
          type: "POST",
          data: { action: "getDropdownData" },
          dataType: "json",
          success: function (response) {
              console.log("Dropdown Data Response:", response);
              if (response.success) {
                  const { courses, year_levels, sections } = response.data;

                  // Populate courses dropdown
                  const courseDropdown = $("#editCourseId");
                  courseDropdown.empty();
                  courses.forEach(course => {
                      const selected = course.course_id == selectedCourseId ? "selected" : "";
                      courseDropdown.append(`<option value="${course.course_id}" ${selected}>${course.course_code}</option>`);
                  });

                  // Populate year levels dropdown
                  const yearLevelDropdown = $("#editYearLevelId");
                  yearLevelDropdown.empty();
                  year_levels.forEach(yearLevel => {
                      const selected = yearLevel.year_level_id == selectedYearLevelId ? "selected" : "";
                      yearLevelDropdown.append(`<option value="${yearLevel.year_level_id}" ${selected}>${yearLevel.year_level_name}</option>`);
                  });

                  // Populate sections dropdown
                  const sectionDropdown = $("#editSectionId");
                  sectionDropdown.empty();
                  sections.forEach(section => {
                      const selected = section.section_id == selectedSectionId ? "selected" : "";
                      sectionDropdown.append(`<option value="${section.section_id}" ${selected}>${section.section_code}</option>`);
                  });
              } else {
                  console.error("Error Message:", response.message);
                  alert(response.message);
              }
          },
          error: function (xhr, status, error) {
              console.error("AJAX Dropdown Fetch Error:", xhr.responseText);
              alert("An error occurred while fetching dropdown data.");
          }
      });
  }

  // Function to pre-fill and show student data for editing
  $(document).on("click", ".edit-student", function () {
      const studentId = $(this).data("id");

      console.log("Fetching student data for editing with ID:", studentId);

      $.ajax({
          url: "../../server/admin/student_management_server.php",
          type: "POST",
          data: { action: "fetch", student_id: studentId },
          dataType: "json",
          success: function (response) {
              console.log("Edit Fetch Response:", response);
              if (response.success) {
                  const student = response.data[0]; // Assuming single student data

                  $("#editStudentId").val(student.student_id);
                  $("#editFirstName").val(student.first_name);
                  $("#editMiddleName").val(student.middle_name);
                  $("#editLastName").val(student.last_name);
                  $("#editEmail").val(student.email);

                  // Populate dropdowns with selected values
                  populateDropdowns(student.course_id, student.year_level_id, student.section_id);

                  $("#editStudentModal").modal("show");
              } else {
                  console.error("Error Message:", response.message);
                  alert(response.message);
              }
          },
          error: function (xhr, status, error) {
              console.error("AJAX Edit Fetch Error:", xhr.responseText);
              alert("An error occurred while fetching student data.");
          }
      });
  });

  // Function to update a student's information
  $("#editStudentForm").submit(function (e) {
      e.preventDefault();

      const formData = {
          action: "update",
          student_id: $("#editStudentId").val(),
          first_name: $("#editFirstName").val(),
          middle_name: $("#editMiddleName").val(),
          last_name: $("#editLastName").val(),
          email: $("#editEmail").val(),
          course_id: $("#editCourseId").val(),
          year_level_id: $("#editYearLevelId").val(),
          section_id: $("#editSectionId").val()
      };

      console.log("Updating student with data:", formData);

      $.ajax({
          url: "../../server/admin/student_management_server.php",
          type: "POST",
          data: formData,
          dataType: "json",
          success: function (response) {
              console.log("Update Student Response:", response);
              if (response.success) {
                  alert(response.message);
                  fetchStudents(); // Refresh student list
                  $("#editStudentModal").modal("hide"); // Close modal
              } else {
                  console.error("Error Message:", response.message);
                  alert(response.message);
              }
          },
          error: function (xhr, status, error) {
              console.error("AJAX Update Error:", xhr.responseText);
              alert("An error occurred while updating the student.");
          }
      });
  });

  // Initial fetch of students
  fetchStudents();
});
