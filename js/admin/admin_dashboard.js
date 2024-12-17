$(document).ready(function () {
  // Set Current Date
  $("#currentDate").text(new Date().toLocaleDateString());

  // Fetch and display counts
  function loadCounts() {
    $.ajax({
      url: "/cssc/server/admin/admin_dashboard.php",
      type: "POST",
      data: { action: "getCounts" },
      dataType: "json", // Tell jQuery to expect JSON directly
      success: function (response) {
        console.log("Server Response:", response); // Debugging

        if (response.success) {
          $("#studentCount").text(response.students);
          $("#staffCount").text(response.staff);
          $("#adminCount").text(response.admins);
        } else {
          alert(response.error || "Failed to load counts.");
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
      dataType: "json", // Expect JSON response
      success: function (response) {
        const tableBody = $("#advisersTable tbody");
        tableBody.empty();
  
        if (response.success && response.advisers.length > 0) {
          response.advisers.forEach((adviser) => {
            tableBody.append(`
              <tr>
                <td>${adviser.name}</td>
                <td>${adviser.email}</td>
                <td>${adviser.department}</td>
              </tr>
            `);
          });
        } else {
          tableBody.append(
            `<tr><td colspan="3" class="text-center">No advisers found.</td></tr>`
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
      dataType: "json", // Expect JSON response
      success: function (response) {
        const tableBody = $("#auditLogsTable tbody");
        tableBody.empty();

        if (response.success && response.logs.length > 0) {
          response.logs.forEach((log) => {
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
          tableBody.append(`<tr><td colspan="4" class="text-center">No audit logs found.</td></tr>`);
        }
      },
      error: function () {
        alert("Failed to fetch audit logs.");
      },
    });
  }

  // Initialize dashboard
  loadCounts();
  loadAdvisers();
  loadAuditLogs();
});
