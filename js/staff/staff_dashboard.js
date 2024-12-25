$(document).ready(function () {
    // Load statistics, audit logs, and recently verified entries
    loadStatistics();
    loadAuditLogs();
    loadRecentVerifiedEntries();

    // Function to fetch and display statistics
    function loadStatistics() {
        $.post(
            "/cssc/server/staff/staff_dashboard_server.php",
            { action: "fetch_statistics" },
            function (response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        const stats = data.data;
                        $("#pendingApplicationsCount").text(stats.pending || 0);
                        $("#approvedApplicationsCount").text(stats.approved || 0);
                        $("#rejectedApplicationsCount").text(stats.rejected || 0);
                    } else {
                        console.error("Failed to fetch statistics:", data.error);
                    }
                } catch (error) {
                    console.error("Error parsing statistics response:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to fetch statistics.");
        });
    }

    // Function to fetch and display recently verified entries
    function loadRecentVerifiedEntries() {
        $.post(
            "/cssc/server/staff/staff_dashboard_server.php",
            { action: "fetch_recent_verified" },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.success) {
                        const entries = data.data;
                        const tableBody = $("#recentVerifiedTable tbody");
                        tableBody.empty();

                        if (entries.length === 0) {
                            tableBody.append('<tr><td colspan="5" class="text-center">No recently verified entries.</td></tr>');
                        } else {
                            entries.forEach((entry) => {
                                tableBody.append(`
                                    <tr>
                                        <td>${entry.student_identifier}</td>
                                        <td>${entry.full_name}</td>
                                        <td>${entry.course_name}</td>
                                        <td>${entry.gwa}</td>
                                        <td>${new Date(entry.date_verified).toLocaleString()}</td>
                                    </tr>
                                `);
                            });
                        }
                    } else {
                        console.error("Failed to fetch recently verified entries:", data.error);
                    }
                } catch (error) {
                    console.error("Error parsing recent verified entries response:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to fetch recently verified entries.");
        });
    }

    // Function to fetch and display audit logs
    function loadAuditLogs() {
        $.post(
            "/cssc/server/staff/staff_dashboard_server.php",
            { action: "fetch_audit_logs" },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.success) {
                        const auditLogs = data.data;
                        const auditLogTableBody = $("#auditLogTable tbody");
                        auditLogTableBody.empty();

                        if (auditLogs.length === 0) {
                            auditLogTableBody.append('<tr><td colspan="4" class="text-center">No audit logs available.</td></tr>');
                        } else {
                            auditLogs.forEach((log) => {
                                auditLogTableBody.append(`
                                    <tr>
                                        <td>${log.timestamp}</td>
                                        <td>${log.name}</td>
                                        <td>${log.action_type}</td>
                                        <td>${log.action_details}</td>
                                    </tr>
                                `);
                            });
                        }
                    } else {
                        console.error("Failed to fetch audit logs:", data.error);
                    }
                } catch (error) {
                    console.error("Error parsing audit logs response:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to fetch audit logs.");
        });
    }

  // Function to update the current date
  function updateCurrentDate() {
      const now = new Date();
      const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
      const formattedDate = now.toLocaleDateString(undefined, options);
      $("#currentDate").text(formattedDate);
  }

  // Update the current date on page load
  updateCurrentDate();
});
