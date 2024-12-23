$(document).ready(function () {
    let applications = [];
    let allApplications = [];
    const rowsPerPage = 5;
    let currentPage = 1;

    // Initial Load
    loadApplications();
    loadFilters();
    setupFilters();
    setupSearch();

    // Fetch Applications
    function loadApplications(filters = {}) {
        $.post(
            "/cssc/server/admin/application_server.php",
            { action: "read", ...filters },
            function (response) {
                try {
                    applications = JSON.parse(response);
                    allApplications = [...applications];
                    displayTable(currentPage);
                    setupPagination();
                } catch (error) {
                    console.error("Error parsing application data:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to load applications.");
        });
    }

    // Dynamically Load Filters
    function loadFilters() {
        loadSchoolYears();
        loadSemesters();
    }

    // Dynamically Populate School Year Filter
    function loadSchoolYears() {
        const schoolYears = ["2024-2025", "2023-2024", "2022-2023"]; // Example years; replace with dynamic fetching if needed
        const dropdown = $("#filterSchoolYear");
        dropdown.empty().append('<option value="">All Years</option>');

        schoolYears.forEach((year) => {
            dropdown.append(`<option value="${year}">${year}</option>`);
        });
    }

    // Dynamically Populate Semester Filter
    function loadSemesters() {
        const semesters = ["1st", "2nd", "Summer"];
        const dropdown = $("#filterSemester");
        dropdown.empty().append('<option value="">All Semesters</option>');

        semesters.forEach((semester) => {
            dropdown.append(`<option value="${semester}">${semester}</option>`);
        });
    }

    // Filters
    function setupFilters() {
        $("#filterCurriculum, #filterStatus, #filterDate, #filterSchoolYear, #filterSemester").on("change", function () {
            const filters = {
                curriculum_id: $("#filterCurriculum").val(),
                status: $("#filterStatus").val(),
                submission_date: $("#filterDate").val(),
                school_year: $("#filterSchoolYear").val(),
                semester: $("#filterSemester").val(),
            };
            loadApplications(filters);
        });
    }

    // Search
    function setupSearch() {
        $("#searchApplication").on("keyup", function () {
            const query = $(this).val().toLowerCase();

            const filtered = allApplications.filter((app) => {
                const identifier = app.identifier ? app.identifier.toLowerCase() : '';
                const fullName = app.full_name ? app.full_name.toLowerCase() : '';

                return identifier.includes(query) || fullName.includes(query);
            });

            applications = filtered;
            currentPage = 1;
            displayTable(currentPage);
            setupPagination();
        });
    }

    // Display Table
    function displayTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const visible = applications.slice(start, end);
    
        const tableBody = $("#applicationsTable tbody");
        tableBody.empty();
    
        if (visible.length === 0) {
            tableBody.append('<tr><td colspan="9" class="text-center">No data found.</td></tr>');
            return;
        }
    
        visible.forEach((app) => {
            tableBody.append(`
                <tr>
                    <td>${app.identifier}</td>
                    <td>${app.full_name}</td>
                    <td>${app.curriculum}</td>
                    <td>${app.status}</td>
                    <td>${app.school_year}</td>
                    <td>${app.semester}</td>
                    <td>${app.submission_date}</td>
                    <td>${app.total_rating}</td>
                    <td>
                        <button class="btn btn-info btn-sm view-details-btn" data-id="${app.id}">View Details</button>
                        <button class="btn btn-warning btn-sm compare-grades-btn" data-id="${app.id}" data-user-id="${app.user_id}">Compare Grades</button>
                        <button class="btn btn-success btn-sm change-status-btn" data-id="${app.id}" data-status="${app.status}">Change Status</button>
                    </td>
                </tr>
            `);
        });
    }
    

    // Pagination
    function setupPagination() {
        const totalPages = Math.ceil(applications.length / rowsPerPage);
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
    
  // View Application Details
  $(document).on("click", ".view-details-btn", function () {
      const applicationId = $(this).data("id");

      $.post(
          "/cssc/server/admin/application_server.php",
          { action: "get", id: applicationId },
          function (response) {
              try {
                  const app = JSON.parse(response);

                  if (app.error) {
                      alert(app.error);
                      return;
                  }

                  // Populate modal with application details
                  const detailsHtml = `
                      <p><strong>Identifier:</strong> ${app.identifier}</p>
                      <p><strong>Full Name:</strong> ${app.full_name}</p>
                      <p><strong>Email:</strong> ${app.email}</p>
                      <p><strong>Curriculum:</strong> ${app.curriculum}</p>
                      <p><strong>Status:</strong> ${app.status}</p>
                      <p><strong>Total Rating:</strong> ${app.total_rating}</p>
                      <p><strong>Submission Date:</strong> ${app.submission_date}</p>
                      <p><strong>Last Updated:</strong> ${app.last_updated}</p>
                      ${app.rejection_reason ? `<p><strong>Rejection Reason:</strong> ${app.rejection_reason}</p>` : ''}
                  `;

                  $("#applicationDetails").html(detailsHtml);
                  $("#viewDetailsModal").modal("show");
              } catch (error) {
                  console.error("Error parsing application details:", error);
              }
          }
      ).fail(function () {
          alert("Failed to fetch application details.");
      });
  });

  // Compare Grades
  $(document).on("click", ".compare-grades-btn", function () {
      const applicationId = $(this).data("id");
      const userId = $(this).data("user-id");

      $.post(
          "/cssc/server/admin/application_server.php",
          { action: "compare_grades", application_id: applicationId, user_id: userId },
          function (response) {
              try {
                  const data = JSON.parse(response);

                  if (data.error) {
                      alert(data.error);
                      return;
                  }

                  // Populate Grades Table
                  const gradesTableBody = $("#gradesTableBody");
                  gradesTableBody.empty();
                  data.grades.forEach(grade => {
                      gradesTableBody.append(`
                          <tr>
                              <td>${grade.subject_code}</td>
                              <td>${grade.descriptive_title}</td>
                              <td>${grade.rating}</td>
                          </tr>
                      `);
                  });

                  // Populate Image Proof
                  const imageProofContainer = $("#imageProofContainer");
                  imageProofContainer.html(`
                      <img src="${data.image}" class="img-fluid" alt="Proof Image">
                  `);

                  $("#compareGradesModal").modal("show");
              } catch (error) {
                  console.error("Error parsing compare grades data:", error);
              }
          }
      ).fail(function () {
          alert("Failed to fetch grades for comparison.");
      });
  });

  $(document).on("click", ".change-status-btn", function () {
    const applicationId = $(this).data("id");
    const currentStatus = $(this).data("status");

    // Determine the new status based on the current status
    let confirmMessage = "";
    let nextStatus = "";
    if (currentStatus === "Pending") {
        confirmMessage = "Are you sure you want to approve this application?";
        nextStatus = "Approved";
    } else if (currentStatus === "Approved") {
        confirmMessage = "Are you sure you want to reject this application?";
        nextStatus = "Rejected";
    } else if (currentStatus === "Rejected") {
        confirmMessage = "Are you sure you want to approve this application again?";
        nextStatus = "Approved";
    } else {
        alert("Invalid status transition.");
        return;
    }

    // Confirm the action
    if (!confirm(confirmMessage)) {
        return;
    }

    // Send AJAX request to change the status
    $.post(
        "/cssc/server/admin/application_server.php",
        {
            action: "change_status",
            application_id: applicationId,
            current_status: currentStatus
        },
        function (response) {
            try {
                const data = JSON.parse(response);

                if (data.error) {
                    alert(data.error);
                    return;
                }

                if (data.success) {
                    // Update the status in the UI
                    alert(`Application status updated to ${data.new_status}`);
                    loadApplications(); // Reload applications to reflect the change
                }
            } catch (error) {
                console.error("Error parsing change status response:", error);
            }
        }
    ).fail(function () {
        alert("Failed to change application status.");
    });
});

});
