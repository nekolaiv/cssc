$(document).ready(function () {
    loadVerifiedEntries();

    // Load verified entries into the table
    function loadVerifiedEntries() {
        $.ajax({
            url: "/cssc/server/verifiedEntriesServer.php",
            type: "POST",
            data: { action: "read" },
            success: function (response) {
                const entries = JSON.parse(response);
                const tableBody = $("#verifiedEntriesTable tbody");
                tableBody.empty();

                if (entries.length === 0) {
                    tableBody.append(`
                        <tr>
                            <td colspan="7" class="text-center">No verified entries found.</td>
                        </tr>
                    `);
                } else {
                    entries.forEach((entry) => {
                        tableBody.append(`
                            <tr>
                                <td>${entry.student_id}</td>
                                <td>${entry.fullname}</td>
                                <td>${entry.course_details || `${entry.course}-${entry.year_level}-${entry.section}`}</td>
                                <td>${entry.adviser_name}</td>
                                <td>${entry.gwa}</td>
                                <td>${entry.created_at}</td>
                                <td>
                                    <button class="btn btn-sm btn-info view-details" data-id="${entry.id}">View</button>
                                    <button class="btn btn-sm btn-danger remove-entry" data-id="${entry.id}">Remove</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: function () {
                alert("Failed to load verified entries. Please try again.");
            },
        });
    }

    // Event delegation for View Details button
    $(document).on("click", ".view-details", function () {
        const id = $(this).data("id");

        // Fetch entry details
        $.ajax({
            url: "/cssc/server/verifiedEntriesServer.php",
            type: "POST",
            data: { action: "get", id },
            success: function (response) {
                const result = JSON.parse(response);

                if (result.error) {
                    alert(result.error);
                    return;
                }

                if (result.success && result.entry) {
                    const entry = result.entry;

                    // Populate the modal with details
                    $("#modalVerifiedStudentId").text(entry.student_id || "N/A");
                    $("#modalVerifiedFullName").text(entry.fullname || "N/A");
                    $("#modalVerifiedEmail").text(entry.email || "N/A");
                    $("#modalVerifiedCourse").text(entry.course || "N/A");
                    $("#modalVerifiedYearLevel").text(entry.year_level || "N/A");
                    $("#modalVerifiedSection").text(entry.section || "N/A");
                    $("#modalVerifiedAdviserName").text(entry.adviser_name || "N/A");
                    $("#modalVerifiedGWA").text(entry.gwa || "N/A");

                    if (entry.image_proof) {
                        $("#modalVerifiedImageProof")
                            .attr("src", `data:image/png;base64,${entry.image_proof}`)
                            .show();
                    } else {
                        $("#modalVerifiedImageProof").attr("src", "").hide();
                    }

                    // Attach ID to the removeVerifiedBtn
                    $("#removeVerifiedBtn").data("id", id);

                    // Show the modal
                    $("#verifiedDetailsModal").modal("show");
                } else {
                    alert("Invalid data received from the server.");
                }
            },
            error: function () {
                alert("Failed to fetch entry details. Please try again.");
            },
        });
    });

    // Event delegation for Remove button in the table
    $(document).on("click", ".remove-entry", function () {
        const id = $(this).data("id");

        if (confirm("Are you sure you want to remove this entry from verified entries?")) {
            // Remove entry from verified entries
            removeVerifiedEntry(id);
        }
    });

    // Event handler for Remove Verified Entry button in the modal
    $("#removeVerifiedBtn").click(function () {
        const id = $(this).data("id");

        if (confirm("Are you sure you want to remove this entry?")) {
            // Remove entry from verified entries
            removeVerifiedEntry(id);
        }
    });

    // Function to remove a verified entry
    function removeVerifiedEntry(id) {
        $.ajax({
            url: "/cssc/server/verifiedEntriesServer.php",
            type: "POST",
            data: { action: "remove", id },
            success: function (response) {
                const result = JSON.parse(response);

                if (result.success) {
                    alert(result.message);
                    $("#verifiedDetailsModal").modal("hide"); // Hide the modal if open
                    loadVerifiedEntries(); // Reload the table
                } else {
                    alert(result.error || "Failed to remove the entry.");
                }
            },
            error: function () {
                alert("Failed to remove entry. Please try again.");
            },
        });
    }
});
