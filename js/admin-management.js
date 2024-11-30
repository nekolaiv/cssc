$(document).ready(function () {
    loadAdmins();

    // Load admins into the table
    function loadAdmins() {
        $.ajax({
            url: "/cssc/server/adminServer.php",
            type: "POST",
            data: { action: "read" },
            success: function (response) {
                const admins = JSON.parse(response);
                const tableBody = $("#adminsTable tbody");
                tableBody.empty();

                if (admins.length === 0) {
                    tableBody.append(`
                        <tr>
                            <td colspan="6" class="text-center">No data found.</td>
                        </tr>
                    `);
                } else {
                    admins.forEach((admin) => {
                        const fullName = `${admin.first_name} ${admin.middle_name ? admin.middle_name + ' ' : ''}${admin.last_name}`;
                        tableBody.append(`
                            <tr>
                                <td>${admin.admin_id}</td>
                                <td>${fullName}</td>
                                <td>${admin.email}</td>
                                <td>
                                    <span class="masked-password">••••••••</span>
                                    <button class="btn btn-sm btn-secondary reveal-password" data-password="${admin.password}">Reveal</button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${admin.admin_id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${admin.admin_id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                }

                attachEventListeners();
            },
        });
    }

    $("#addAdminBtn").click(function () {
        $("#adminForm")[0].reset();
        $("#admin_id").val(""); // Clear the hidden admin_id field
        $("#adminModalLabel").text("Add Admin"); // Update the modal title
    
        // Reset form validation and errors
        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").text("");
        $("#adminModal .modal-content").removeClass("border-danger");
    
        // Show the modal
        $("#adminModal").modal("show");
    });

    // Add event listeners for Edit and Delete buttons
    function attachEventListeners() {
        $(".edit-btn").click(function () {
            const admin_id = $(this).data("id");

            $.ajax({
                url: "/cssc/server/adminServer.php",
                type: "POST",
                data: { action: "get", admin_id },
                success: function (response) {
                    const admin = JSON.parse(response);

                    if (admin.error) {
                        alert("Error fetching admin: " + admin.error);
                        return;
                    }

                    // Populate the form fields
                    $("#admin_id").val(admin.admin_id);
                    $("#email").val(admin.email);
                    $("#first_name").val(admin.first_name);
                    $("#last_name").val(admin.last_name);
                    $("#middle_name").val(admin.middle_name);
                    $("#password").val(""); // Do not display hashed password

                    // Update the modal title
                    $("#adminModalLabel").text("Edit Admin");

                    // Reset form validation and errors
                    $(".form-control").removeClass("is-invalid");
                    $(".invalid-feedback").text("");
                    $("#adminModal .modal-content").removeClass("border-danger");

                    // Show the modal
                    $("#adminModal").modal("show");
                },
                error: function () {
                    alert("Failed to fetch admin data.");
                },
            });
        });

        $(".delete-btn").click(function () {
            const admin_id = $(this).data("id");
            if (confirm("Are you sure you want to delete this admin?")) {
                $.ajax({
                    url: "/cssc/server/adminServer.php",
                    type: "POST",
                    data: { action: "delete", admin_id },
                    success: function () {
                        loadAdmins();
                    },
                });
            }
        });
    }

    $(document).on("click", ".reveal-password", function () {
        const button = $(this);
        const actualPassword = button.data("password");

        if (actualPassword) {
            alert(`Password: ${actualPassword}`);
        } else {
            alert("Password could not be retrieved.");
        }
    });

    $(document).on("click", "#togglePassword", function () {
        const passwordField = $("#password");
        const type = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);

        // Update button text based on visibility
        $(this).text(type === "password" ? "Show" : "Hide");
    });

    // Handle form submission for Add/Edit
    $("#adminForm").submit(function (e) {
        e.preventDefault();

        const action = $("#admin_id").val() ? "update" : "create";
        const formData = $(this).serialize() + `&action=${action}`;

        $.ajax({
            url: "/cssc/server/adminServer.php",
            type: "POST",
            data: formData,
            success: function (response) {
                const result = JSON.parse(response);

                // Clear previous error messages
                $(".form-control").removeClass("is-invalid");
                $(".invalid-feedback").text("");
                $("#adminModal .modal-content").removeClass("border-danger");

                if (result.success) {
                    alert("Admin saved successfully!");
                    $("#adminModal").modal("hide");
                    loadAdmins();
                } else if (result.errors) {
                    // Display error messages
                    $("#adminModal .modal-content").addClass("border-danger");
                    Object.keys(result.errors).forEach(function (field) {
                        const fieldElement = $(`[name="${field}"]`);
                        fieldElement.addClass("is-invalid");
                        fieldElement
                            .next(".invalid-feedback")
                            .text(result.errors[field])
                            .show();
                    });
                } else {
                    alert("An unexpected error occurred.");
                }
            },
            error: function () {
                alert("Failed to save admin.");
            },
        });
    });
});
