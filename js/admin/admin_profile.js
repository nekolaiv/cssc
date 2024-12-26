$(document).ready(function () {
    const apiUrl = "/cssc/server/admin/profile_server.php";

    // Fetch and display admin profile details
    function fetchProfile() {
        $.ajax({
            url: apiUrl,
            type: "POST",
            data: { action: "fetchProfile" },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    $("#admin-identifier").text(data.identifier);
                    $("#admin-firstname").text(data.firstname);
                    $("#admin-middlename").text(data.middlename || "N/A");
                    $("#admin-lastname").text(data.lastname);
                    $("#admin-email").text(data.email);
                    $("#admin-username").text(data.username);

                    // Pre-fill modal form fields
                    $("#editIdentifier").val(data.identifier);
                    $("#editFirstname").val(data.firstname);
                    $("#editMiddlename").val(data.middlename);
                    $("#editLastname").val(data.lastname);
                    $("#editEmail").val(data.email);
                    $("#editUsername").val(data.username);
                } else {
                    alert(response.message || "Failed to fetch profile details.");
                }
            },
            error: function () {
                alert("An error occurred while fetching profile details.");
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
