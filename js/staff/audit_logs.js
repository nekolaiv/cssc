$(document).ready(function () {
    let auditLogs = [];
    const rowsPerPage = 5;
    let currentPage = 1;

    // Initial Load
    loadAuditLogs();
    loadRoles();
    setupFilters();

    // Fetch Audit Logs
    function loadAuditLogs(filters = {}) {
        $.post(
            "/cssc/server/staff/audit_server.php",
            { action: "read", ...filters },
            function (response) {
                try {
                    const data = JSON.parse(response);
                    console.log(data);
                    if (data.success) {
                        auditLogs = data.data;
                        displayTable(currentPage);
                        setupPagination();
                    } else {
                        console.error(data.error);
                        $("#auditLogsTable tbody").html(
                            `<tr><td colspan="6" class="text-center">${data.error || "No logs found."}</td></tr>`
                        );
                    }
                } catch (error) {
                    console.error("Error parsing response:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to load audit logs.");
        });
    }

    // Fetch Roles for Dropdown
    function loadRoles() {
        $.post(
            "/cssc/server/staff/audit_server.php",
            { action: "fetch_roles" },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.success) {
                        const roles = data.data;
                        const dropdown = $("#filterRole");
                        dropdown.empty().append('<option value="">All Roles</option>');

                        roles.forEach((role) => {
                            dropdown.append(`<option value="${role.id}">${role.role_name}</option>`);
                        });
                    } else {
                        console.error(data.error);
                    }
                } catch (error) {
                    console.error("Error parsing roles data:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to fetch roles.");
        });
    }

    // Display Table
    function displayTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const visible = auditLogs.slice(start, end);

        const tableBody = $("#auditLogsTable tbody");
        tableBody.empty();

        if (visible.length === 0) {
            tableBody.append('<tr><td colspan="6" class="text-center">No data found.</td></tr>');
            return;
        }

        visible.forEach((log) => {
            tableBody.append(`
                <tr>
                    <td>${log.user_id}</td>
                    <td>${log.name || "N/A"}</td>
                    <td>${log.action_type}</td>
                    <td>${log.action_details}</td>
                    <td>${log.timestamp}</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-log-btn" data-id="${log.id}">Delete</button>
                    </td>
                </tr>
            `);
        });
    }

    // Setup Pagination
    function setupPagination() {
        const totalPages = Math.ceil(auditLogs.length / rowsPerPage);
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

    // Filters
    function setupFilters() {
        $("#filterRole").on("change", function () {
            const filters = {
                role_id: $("#filterRole").val(),
            };
            loadAuditLogs(filters);
        });
    }

    // Delete Audit Log
    $(document).on("click", ".delete-log-btn", function () {
        const logId = $(this).data("id");

        if (!confirm("Are you sure you want to delete this audit log entry?")) {
            return;
        }

        $.post(
            "/cssc/server/staff/audit_server.php",
            { action: "delete", id: logId },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.success) {
                        alert("Audit log entry deleted successfully.");
                        loadAuditLogs(); // Reload the logs
                    } else {
                        alert(data.error || "Failed to delete the audit log entry.");
                    }
                } catch (error) {
                    console.error("Error parsing delete response:", error);
                }
            }
        ).fail(function () {
            alert("Failed to delete audit log entry.");
        });
    });
});
