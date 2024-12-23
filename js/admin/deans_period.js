function formatToDateTimeLocal(datetime) {
    const date = new Date(datetime);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function formatToDateTime(datetimeLocal) {
    const date = new Date(datetimeLocal);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

$(document).ready(function () {
    let periods = [];
    const rowsPerPage = 5;
    let currentPage = 1;

    // Initial Load
    loadPeriods();
    setupFilters();
    setupSearch();

    // Fetch Periods
    function loadPeriods(filters = {}) {
        $.post(
            "/cssc/server/admin/deans_period_server.php",
            { action: "read", ...filters },
            function (response) {
                try {
                    periods = JSON.parse(response);
                    displayTable(currentPage);
                    setupPagination();
                } catch (error) {
                    console.error("Error parsing periods data:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to load periods.");
        });
    }

    // Display Table
    function displayTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const visible = periods.slice(start, end);

        const tableBody = $("#periodsTable tbody");
        tableBody.empty();

        if (visible.length === 0) {
            tableBody.append('<tr><td colspan="6" class="text-center">No data found.</td></tr>');
            return;
        }

        visible.forEach((period) => {
            tableBody.append(`
                <tr>
                    <td>${period.year}</td>
                    <td>${period.semester}</td>
                    <td>${new Date(period.start_date).toLocaleString()}</td>
                    <td>${new Date(period.end_date).toLocaleString()}</td>
                    <td>${period.status === "open" ? "Open" : "Closed"}</td>
                    <td>
                        <button class="btn btn-info btn-sm edit-period-btn" data-id="${period.id}">Edit</button>
                        <button class="btn btn-warning btn-sm toggle-status-btn" data-id="${period.id}">
                            ${period.status === "open" ? "Close" : "Open"}
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Setup Pagination
    function setupPagination() {
        const totalPages = Math.ceil(periods.length / rowsPerPage);
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
        $("#filterStatus").on("change", function () {
            const filters = {
                status: $("#filterStatus").val(),
            };
            loadPeriods(filters);
        });
    }

    // Search
    function setupSearch() {
        $("#searchPeriod").on("keyup", function () {
            const query = $(this).val().toLowerCase();

            const filtered = periods.filter((p) =>
                p.year.toLowerCase().includes(query)
            );

            displayTable(1);
        });
    }

    // Open Create Period Modal
    $("#createPeriodBtn").on("click", function () {
        $("#periodForm")[0].reset();
        $("#periodId").val("");
        $("#formError").addClass("d-none").text("");
        $("#periodModalLabel").text("Create Application Period");
        $("#periodModal").modal("show");
    });

    // Submit Create/Update Period Form
    $("#periodForm").on("submit", function (e) {
        e.preventDefault();

        const formData = {
            id: $("#periodId").val(),
            year: $("#year").val(),
            semester: $("#semester").val(),
            start_date: formatToDateTime($("#startDate").val()),
            end_date: formatToDateTime($("#endDate").val()),
            status: $("#status").val(),
        };

        $.post(
            "/cssc/server/admin/deans_period_server.php",
            { action: "save", ...formData },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.error) {
                        $("#formError").removeClass("d-none").text(data.error);
                    } else {
                        $("#periodModal").modal("hide");
                        loadPeriods();
                    }
                } catch (error) {
                    console.error("Error parsing save response:", error);
                }
            }
        ).fail(function () {
            $("#formError").removeClass("d-none").text("Failed to save the period.");
        });
    });

    // Edit Period
    $(document).on("click", ".edit-period-btn", function () {
        const periodId = $(this).data("id");

        const period = periods.find((p) => p.id == periodId);
console.log(period);
        if (period) {
            $("#periodId").val(period.id);
            $("#year").val(period.year);
            $("#semester").val(period.semester);
            $("#startDate").val(formatToDateTimeLocal(period.start_date));
            $("#endDate").val(formatToDateTimeLocal(period.end_date));
            $("#status").val(period.status);
            $("#formError").addClass("d-none").text("");
            $("#periodModalLabel").text("Update Application Period");
            $("#periodModal").modal("show");
        }
    });

    // Toggle Period Status
    $(document).on("click", ".toggle-status-btn", function () {
        const periodId = $(this).data("id");

        $.post(
            "/cssc/server/admin/deans_period_server.php",
            { action: "toggle_status", id: periodId },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.error) {
                        alert(data.error);
                    } else {
                        loadPeriods();
                    }
                } catch (error) {
                    console.error("Error parsing toggle status response:", error);
                }
            }
        ).fail(function () {
            alert("Failed to toggle period status.");
        });
    });
});
