$(document).ready(function () {
    const apiUrl = "/cssc/server/staff/profile_server.php";

    // Fetch and display staff profile details
    function fetchProfile() {
        $.ajax({
            url: apiUrl,
            type: "POST",
            data: { action: "fetchProfile" },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    $("#staff-identifier").text(data.identifier);
                    $("#staff-firstname").text(data.firstname);
                    $("#staff-middlename").text(data.middlename || "N/A");
                    $("#staff-lastname").text(data.lastname);
                    $("#staff-email").text(data.email);
                    $("#staff-username").text(data.username);
                    $("#staff-department").text(data.department_name);

                    // Pre-fill modal form fields
                    $("#editIdentifier").val(data.identifier);
                    $("#editFirstname").val(data.firstname);
                    $("#editMiddlename").val(data.middlename);
                    $("#editLastname").val(data.lastname);
                    $("#editEmail").val(data.email);
                    $("#editUsername").val(data.username);

                    // Fetch and populate departments in the dropdown
                    fetchDepartments(data.department_name);
                } else {
                    alert(response.message || "Failed to fetch profile details.");
                }
            },
            error: function () {
                alert("An error occurred while fetching profile details.");
            },
        });
    }

    // Fetch departments and populate the dropdown
    function fetchDepartments(currentDepartment) {
        $.ajax({
            url: apiUrl,
            type: "POST",
            data: { action: "fetchDepartments" },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const departments = response.data;
                    const departmentSelect = $("#editDepartment");
                    departmentSelect.empty();

                    // Populate departments in the dropdown
                    departments.forEach((department) => {
                        const isSelected = department.department_name === currentDepartment ? "selected" : "";
                        departmentSelect.append(
                            `<option value="${department.id}" ${isSelected}>${department.department_name}</option>`
                        );
                    });
                } else {
                    alert(response.message || "Failed to fetch departments.");
                }
            },
            error: function () {
                alert("An error occurred while fetching departments.");
            },
        });
    }

    // Save profile changes
    $("#saveProfileChanges").on("click", function () {
        const data = {
            action: "updateProfile",
            identifier: $("#editIdentifier").val(),
            firstname: $("#editFirstname").val(),
            middlename: $("#editMiddlename").val(),
            lastname: $("#editLastname").val(),
            email: $("#editEmail").val(),
            username: $("#editUsername").val(),
            department: $("#editDepartment").val(),
        };

        $.ajax({
            url: apiUrl,
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Profile updated successfully!");
                    fetchProfile(); // Refresh profile data
                    $("#editProfileModal").modal("hide"); // Hide the modal
                } else {
                    alert(response.message || "Failed to update profile.");
                }
            },
            error: function () {
                alert("An error occurred while updating the profile.");
            },
            complete: function () {
                $(".modal-backdrop").remove(); // Ensure the backdrop is removed
            },
        });
    });

    // Change password
    $("#savePasswordChanges").on("click", function () {
        const currentPassword = $("#currentPassword").val();
        const newPassword = $("#newPassword").val();
        const confirmPassword = $("#confirmPassword").val();

        if (!currentPassword || !newPassword || !confirmPassword) {
            alert("All password fields are required.");
            return;
        }

        if (newPassword !== confirmPassword) {
            alert("New password and confirmation do not match.");
            return;
        }

        $.ajax({
            url: apiUrl,
            type: "POST",
            data: {
                action: "changePassword",
                currentPassword: currentPassword,
                newPassword: newPassword,
                confirmPassword: confirmPassword,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Password changed successfully!");
                    $("#changePasswordForm")[0].reset(); // Reset the form
                    $("#changePasswordModal").modal("hide"); // Hide the modal
                } else {
                    alert(response.message || "Failed to change password.");
                }
            },
            error: function () {
                alert("An error occurred while changing the password.");
            },
            complete: function () {
                $(".modal-backdrop").remove(); // Ensure the backdrop is removed
            },
        });
    });

    // Initial fetch of profile data
    fetchProfile();
});
