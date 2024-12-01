$(document).ready(function () {
  // Set Current Date
  $("#currentDate").text(new Date().toLocaleDateString());

  // Load Overview Counts
  function loadCounts() {
    $.ajax({
      url: "/cssc/server/staff/staff_dashboard_server.php",
      type: "POST",
      data: { action: "getCounts" },
      success: function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          $("#unverifiedCount").text(result.data.unverifiedCount);
          $("#verifiedCount").text(result.data.verifiedCount);
          $("#pendingCount").text(result.data.pendingCount);
          $("#revisionCount").text(result.data.revisionCount);
        } else {
          console.error(result.error || "Failed to fetch counts.");
        }
      },
      error: function () {
        alert("Failed to load counts. Please try again later.");
      },
    });
  }

  // Load Recently Verified Entries
  function loadRecentVerified() {
    $.ajax({
      url: "/cssc/server/staff/staff_dashboard_server.php",
      type: "POST",
      data: { action: "recentVerified" },
      success: function (response) {
        const result = JSON.parse(response);
        const tableBody = $("#recentVerifiedTable tbody");
        tableBody.empty();

        if (result.success && result.entries && result.entries.length > 0) {
          result.entries.forEach((entry) => {
            tableBody.append(`
                            <tr>
                                <td>${entry.student_id}</td>
                                <td>${entry.fullname}</td>
                                <td>${entry.course}</td>
                                <td>${entry.gwa}</td>
                                <td>${entry.date_verified}</td>
                            </tr>
                        `);
          });
        } else {
          tableBody.append(
            `<tr><td colspan="5" class="text-center">No entries found.</td></tr>`
          );
        }
      },
      error: function () {
        alert("Failed to load recently verified entries.");
      },
    });
  }

  // Load Audit Log
  function loadAuditLog() {
    $.ajax({
      url: "/cssc/server/staff/staff_dashboard_server.php",
      type: "POST",
      data: { action: "auditLog" },
      success: function (response) {
        const result = JSON.parse(response);
        const tableBody = $("#auditLogTable tbody");
        tableBody.empty();

        if (result.success && result.log && result.log.length > 0) {
          result.log.forEach((log) => {
            tableBody.append(`
                            <tr>
                                <td>${log.action_date}</td>
                                <td>${log.action}</td>
                                <td>${log.details}</td>
                            </tr>
                        `);
          });
        } else {
          tableBody.append(
            `<tr><td colspan="3" class="text-center">No audit logs found.</td></tr>`
          );
        }
      },
      error: function () {
        alert("Failed to load audit log.");
      },
    });
  }

  // Redirect to Verified Entries Page
  $("#viewAllVerifiedBtn").click(function () {
    window.location.href = "/cssc/views/staff/verified_entries.php";
  });

  // Initialize Dashboard
  loadCounts();
  loadRecentVerified();
  loadAuditLog();
});
