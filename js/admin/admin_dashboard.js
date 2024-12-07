$(document).ready(function () {
  // Set Current Date
  $("#currentDate").text(new Date().toLocaleDateString());

  // Fetch and display counts
  function loadCounts() {
    $.ajax({
      url: "/cssc/server/admin/admin_dashboard.php",
      type: "POST",
      data: { action: "getCounts" },
      success: function (response) {
        const result = JSON.parse(response);
        if (result.success) {
          $("#studentCount").text(result.students);
          $("#staffCount").text(result.staff);
          $("#adminCount").text(result.admins);
        } else {
          alert(result.error || "Failed to load counts.");
        }
      },
      error: function () {
        alert("Failed to fetch counts.");
      },
    });
  }

  // Fetch and display advisers table
  function loadAdvisers() {
    $.ajax({
      url: "/cssc/server/admin/admin_dashboard.php",
      type: "POST",
      data: { action: "getAdvisers" },
      success: function (response) {
        const result = JSON.parse(response);
        const tableBody = $("#advisersTable tbody");
        tableBody.empty();

        if (result.success && result.advisers.length > 0) {
          result.advisers.forEach((adviser) => {
            tableBody.append(`
              <tr>
                <td>${adviser.name}</td>
                <td>${adviser.email}</td>
                <td>${adviser.course}</td>
                <td>${adviser.year_level}</td>
              </tr>
            `);
          });
        } else {
          tableBody.append(
            `<tr><td colspan="4" class="text-center">No advisers found.</td></tr>`
          );
        }
      },
      error: function () {
        alert("Failed to fetch advisers data.");
      },
    });
  }

  // Fetch and display audit logs
  function loadAuditLogs() {
    $.ajax({
      url: "/cssc/server/admin/admin_dashboard.php",
      type: "POST",
      data: { action: "getAuditLogs" },
      success: function (response) {
        const result = JSON.parse(response);
        const tableBody = $("#auditLogsTable tbody");
        tableBody.empty();

        if (result.success && result.logs.length > 0) {
          result.logs.forEach((log) => {
            tableBody.append(`
              <tr>
                <td>${new Date(log.timestamp).toLocaleString()}</td>
                <td>${log.role}</td>
                <td>${log.action}</td>
                <td>${log.details}</td>
              </tr>
            `);
          });
        } else {
          tableBody.append(
            `<tr><td colspan="4" class="text-center">No audit logs found.</td></tr>`
          );
        }
      },
      error: function () {
        alert("Failed to fetch audit logs.");
      },
    });
  }

  // Initialize the dashboard
  loadCounts();
  loadAdvisers();
  loadAuditLogs(); // Call this to load audit logs on page load
});
