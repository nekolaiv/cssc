$(document).ready(function () {
    // Load data on page load
    loadAcademicTerms();
    loadGwaSchedules();
    loadTermsDropdown();

    // Add Academic Term form submission
    $("#addAcademicTermForm").submit(function (e) {
        e.preventDefault();

        const formData = $(this).serialize() + "&action=add_academic_term";
        $.ajax({
            url: "/cssc/server/admin/academic_server.php",
            type: "POST",
            data: formData,
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert(result.message);
                    $("#addAcademicTermModal").modal("hide");
                    loadAcademicTerms();
                    loadTermsDropdown();
                } else {
                    alert("Error: " + result.message);
                }
            },
            error: function () {
                alert("Failed to add academic term.");
            },
        });
    });

    // Add GWA Submission Schedule form submission
    $("#addGwaSubmissionForm").submit(function (e) {
        e.preventDefault();

        const formData = $(this).serialize() + "&action=add_gwa_schedule";
        $.ajax({
            url: "/cssc/server/admin/academic_server.php",
            type: "POST",
            data: formData,
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert(result.message);
                    $("#addGwaSubmissionModal").modal("hide");
                    loadGwaSchedules();
                } else {
                    alert("Error: " + result.message);
                }
            },
            error: function () {
                alert("Failed to add GWA schedule.");
            },
        });
    });

    // Clear modal on close and remove backdrop issues
    $("#addAcademicTermModal, #addGwaSubmissionModal").on("hidden.bs.modal", function () {
        $(this).find("form")[0].reset(); // Clear form
        $("body").removeClass("modal-open"); // Remove modal-open class
        $(".modal-backdrop").remove(); // Remove lingering backdrop
        $("html, body").css("overflow", "auto"); // Re-enable scrolling
    });

    // Load academic terms for dropdown
    function loadTermsDropdown() {
        $.ajax({
            url: "/cssc/server/admin/academic_server.php",
            type: "POST",
            data: { action: "get_all_terms" },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    const terms = result.data;
                    const termDropdown = $("#gwaTermId"); // Dropdown ID
                    termDropdown.empty();
                    termDropdown.append('<option value="">Select a term</option>');

                    terms.forEach((term) => {
                        termDropdown.append(`
                            <option value="${term.term_id}">
                                ${term.academic_year} - ${term.semester}
                            </option>
                        `);
                    });
                } else {
                    alert("Error loading terms: " + result.message);
                }
            },
            error: function () {
                alert("Failed to load terms.");
            },
        });
    }

    // Toggle active academic term
    $(document).on("click", ".toggle-active-term", function () {
        const termId = $(this).data("id");

        if (confirm("Are you sure you want to activate this academic term?")) {
            $.ajax({
                url: "/cssc/server/admin/academic_server.php",
                type: "POST",
                data: { action: "toggle_active_term", term_id: termId },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert(result.message);
                        loadAcademicTerms();
                    } else {
                        alert("Error: " + result.message);
                    }
                },
                error: function () {
                    alert("Failed to toggle active term.");
                },
            });
        }
    });

    // Toggle active GWA schedule
    $(document).on("click", ".toggle-active-gwa", function () {
        const submissionId = $(this).data("id");

        if (confirm("Are you sure you want to activate this GWA schedule?")) {
            $.ajax({
                url: "/cssc/server/admin/academic_server.php",
                type: "POST",
                data: { action: "toggle_active_gwa_schedule", submission_id: submissionId },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert(result.message);
                        loadGwaSchedules();
                    } else {
                        alert("Error: " + result.message);
                    }
                },
                error: function () {
                    alert("Failed to toggle active GWA schedule.");
                },
            });
        }
    });

    // Load academic terms
    function loadAcademicTerms() {
        $.ajax({
            url: "/cssc/server/admin/academic_server.php",
            type: "POST",
            data: { action: "get_all_terms" },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    populateAcademicTermsTable(result.data);
                } else {
                    alert("Error: " + result.message);
                }
            },
            error: function () {
                alert("Failed to load academic terms.");
            },
        });
    }

    // Load GWA submission schedules
    function loadGwaSchedules() {
        $.ajax({
            url: "/cssc/server/admin/academic_server.php",
            type: "POST",
            data: { action: "get_all_gwa_schedules" },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    populateGwaSchedulesTable(result.data);
                } else {
                    alert("Error: " + result.message);
                }
            },
            error: function () {
                alert("Failed to load GWA schedules.");
            },
        });
    }

    // Populate academic terms table
    function populateAcademicTermsTable(data) {
        const tableBody = $("#academicTermTable tbody");
        tableBody.empty();

        if (data.length === 0) {
            tableBody.append('<tr><td colspan="7" class="text-center">No academic terms found.</td></tr>');
            return;
        }

        data.forEach((term) => {
            const isActive = parseInt(term.active) === 1;
            const statusLabel = isActive
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
            const toggleButtonClass = isActive ? "btn-outline-danger" : "btn-outline-success";
            const toggleButtonText = isActive ? "Deactivate" : "Activate";

            tableBody.append(`
                <tr>
                    <td>${term.term_id}</td>
                    <td>${term.academic_year}</td>
                    <td>${term.semester}</td>
                    <td>${term.start_date}</td>
                    <td>${term.end_date}</td>
                    <td>${statusLabel}</td>
                    <td>
                        <button class="btn ${toggleButtonClass} btn-sm toggle-active-term" data-id="${term.term_id}">${toggleButtonText}</button>
                    </td>
                </tr>
            `);
        });
    }

    // Populate GWA schedules table
    function populateGwaSchedulesTable(data) {
        const tableBody = $("#gwaSubmissionTable tbody");
        tableBody.empty();

        if (data.length === 0) {
            tableBody.append('<tr><td colspan="6" class="text-center">No GWA schedules found.</td></tr>');
            return;
        }

        data.forEach((schedule) => {
            const isActive = parseInt(schedule.active) === 1;
            const statusLabel = isActive
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
            const toggleButtonClass = isActive ? "btn-outline-danger" : "btn-outline-success";
            const toggleButtonText = isActive ? "Deactivate" : "Activate";

            tableBody.append(`
                <tr>
                    <td>${schedule.submission_id}</td>
                    <td>${schedule.term_id}</td>
                    <td>${schedule.gwa_submission_start}</td>
                    <td>${schedule.gwa_submission_end}</td>
                    <td>${statusLabel}</td>
                    <td>
                        <button class="btn ${toggleButtonClass} btn-sm toggle-active-gwa" data-id="${schedule.submission_id}">${toggleButtonText}</button>
                    </td>
                </tr>
            `);
        });
    }
});
