$(document).ready(function () {
  loadUnverifiedEntries();

  // Populate the unverified entries table
  function loadUnverifiedEntries() {
    $.ajax({
      url: "/cssc/server/staff/unverified_entries_server.php",
      type: "POST",
      data: { action: "read" },
      success: function (response) {
        const entries = JSON.parse(response);
        const tableBody = $("#unverifiedEntriesTable tbody");
        tableBody.empty();

        if (entries.length === 0) {
          tableBody.append(
            `<tr><td colspan="6" class="text-center">No unverified entries found.</td></tr>`
          );
        } else {
          entries.forEach((entry) => {
            tableBody.append(`
              <tr>
                  <td>${entry.student_id}</td>
                  <td>${entry.fullname}</td>
                  <td>${entry.course_details}</td>
                  <td>${entry.created_at}</td>
                  <td>${entry.status}</td>
                  <td>
                      <button class="btn btn-primary btn-sm view-details-btn" data-id="${entry.id}">View Details</button>
                      <button class="btn btn-secondary btn-sm compare-grades-btn" data-student-id="${entry.student_id}">Compare Grades</button>
                  </td>
              </tr>
            `);
          });
        }
      },
      error: function () {
        alert("Failed to load unverified entries.");
      },
    });
  }

  // Event delegation for View Details button
  $(document).on("click", ".view-details-btn", function () {
    const entryId = $(this).data("id");
    // Fetch the specific entry details via AJAX
    $.ajax({
      url: "/cssc/server/staff/unverified_entries_server.php",
      type: "POST",
      data: { action: "get", id: entryId },
      success: function (response) {
        const entry = JSON.parse(response);

        // Populate modal with entry details
        $("#modalStatus").text(entry.status);
        $("#modalStudentId").text(entry.student_id);
        $("#modalFullName").text(entry.fullname);
        $("#modalEmail").text(entry.email);
        $("#modalCourse").text(entry.course);
        $("#modalYearLevel").text(entry.year_level);
        $("#modalSection").text(entry.section);
        $("#modalAdviserName").text(entry.adviser_name);
        $("#modalGWA").text(entry.gwa);
        if (entry.image_proof) {
          $("#modalImageProof")
            .attr("src", "data:image/jpeg;base64," + entry.image_proof)
            .show();
        } else {
          $("#modalImageProof").hide();
        }

        // Set approve and reject button actions
        $("#approveBtn").data("id", entry.id);
        $("#rejectBtn").data("id", entry.id);

        // Show the modal
        $("#detailsModal").modal("show");
      },
      error: function () {
        alert("Failed to fetch entry details.");
      },
    });
  });

  // Event delegation for Approve button
  $("#approveBtn")
    .off("click")
    .on("click", function () {
      const entryId = $(this).data("id");
      if (confirm("Are you sure you want to approve this entry?")) {
        $.ajax({
          url: "/cssc/server/staff/unverified_entries_server.php",
          type: "POST",
          data: { action: "verify", id: entryId },
          success: function () {
            alert("Entry approved successfully!");
            $("#detailsModal").modal("hide");
            loadUnverifiedEntries();
          },
          error: function () {
            alert("Failed to approve the entry.");
          },
        });
      }
    });

  // Event delegation for Reject button
  $("#rejectBtn")
    .off("click")
    .on("click", function () {
      const entryId = $(this).data("id");
      if (confirm("Are you sure you want to reject this entry?")) {
        $.ajax({
          url: "/cssc/server/staff/unverified_entries_server.php",
          type: "POST",
          data: { action: "reject", id: entryId },
          success: function () {
            alert("Entry rejected successfully!");
            $("#detailsModal").modal("hide");
            loadUnverifiedEntries();
          },
          error: function () {
            alert("Failed to reject the entry.");
          },
        });
      }
    });

  // Event delegation for Compare Grades button
  $(document).on("click", ".compare-grades-btn", function () {
    const studentId = $(this).data("student-id");
    // Fetch subject fields via AJAX
    $.ajax({
      url: "/cssc/server/staff/unverified_entries_server.php",
      type: "POST",
      data: { action: "get_subject_fields", student_id: studentId },
      success: function (response) {
        const subjects = JSON.parse(response);
        const tableBody = $("#subjectFieldsTable tbody");
        tableBody.empty();

        if (subjects.length === 0) {
          tableBody.append(
            `<tr><td colspan="5" class="text-center">No subject details available.</td></tr>`
          );
        } else {
          subjects.forEach((subject) => {
            tableBody.append(`
              <tr>
                  <td>${subject.subject_code}</td>
                  <td>${subject.units}</td>
                  <td>${subject.grade}</td>
                  <td>${subject.academic_year}</td>
                  <td>${subject.semester}</td>
              </tr>
            `);
          });
        }

        // Show the modal
        $("#subjectFieldsModal").modal("show");
      },
      error: function () {
        alert("Failed to fetch subject details.");
      },
    });
  });
});
