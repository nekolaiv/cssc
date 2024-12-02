$(document).ready(function () {
  let admins = [];
  let filteredAdmins = [];
  const rowsPerPage = 5; // Rows per page
  let currentPage = 1;

  loadAdmins();

  // Event delegation for Reveal Password
  $(document)
    .off("click.reveal", ".reveal-password")
    .on("click.reveal", ".reveal-password", function () {
      const actualPassword = $(this).data("password");
      alert(`Password: ${actualPassword || "Password could not be retrieved."}`);
    });

  // Search functionality
  $("#searchAdmin").on("input", function () {
    const query = $(this).val().toLowerCase();
    filteredAdmins = admins.filter(
      (admin) =>
        admin.admin_id.toString().toLowerCase().includes(query) || // Convert to string
        `${admin.first_name} ${admin.middle_name || ""} ${admin.last_name}`
          .toLowerCase()
          .includes(query)
    );
    currentPage = 1; // Reset to the first page when searching
    displayTable(currentPage);
    setupPagination(filteredAdmins); // Update pagination for filtered data
  });

  // Load admins into the table
  function loadAdmins() {
    $.ajax({
      url: "/cssc/server/admin/admin_management_server.php",
      type: "POST",
      data: { action: "read" },
      success: function (response) {
        admins = JSON.parse(response);
        filteredAdmins = admins; // Initially show all admins
        displayTable(currentPage);
        setupPagination(filteredAdmins); // Pass all data initially
      },
    });
  }

  function displayTable(page) {
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const visibleAdmins = filteredAdmins.slice(startIndex, endIndex);

    const tableBody = $("#adminsTable tbody");
    tableBody.empty(); // Clear the table before appending new rows

    if (visibleAdmins.length === 0) {
      tableBody.append(`
        <tr>
          <td colspan="5" class="text-center">No data found.</td>
        </tr>
      `);
    } else {
      visibleAdmins.forEach((admin) => {
        const fullName = `${admin.first_name} ${
          admin.middle_name ? admin.middle_name + " " : ""
        }${admin.last_name}`;
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
  }

  function setupPagination(data) {
    const pageCount = Math.ceil(data.length / rowsPerPage);
    const pagination = $("#pagination");
    pagination.empty();

    for (let i = 1; i <= pageCount; i++) {
      const activeClass = i === currentPage ? "active" : "";
      pagination.append(`
        <li class="page-item ${activeClass}">
          <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>
      `);
    }

    $(".page-link").on("click", function (e) {
      e.preventDefault();
      currentPage = parseInt($(this).data("page"));
      displayTable(currentPage);

      $(".page-item").removeClass("active");
      $(this).parent().addClass("active");
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
        url: "/cssc/server/admin/admin_management_server.php",
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
      });
    });

    $(".delete-btn").click(function () {
      const admin_id = $(this).data("id");
      if (confirm("Are you sure you want to delete this admin?")) {
        $.ajax({
          url: "/cssc/server/admin/admin_management_server.php",
          type: "POST",
          data: { action: "delete", admin_id },
          success: function () {
            loadAdmins();
          },
        });
      }
    });
  }

  $(document).on("click", "#togglePassword", function () {
    const passwordField = $("#password");
    const type =
      passwordField.attr("type") === "password" ? "text" : "password";
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
      url: "/cssc/server/admin/admin_management_server.php",
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
    });
  });
});