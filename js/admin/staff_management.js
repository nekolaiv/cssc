$(document).ready(function () {
  let staff = [];
  const rowsPerPage = 5; // Rows per page
  let currentPage = 1;

  // Load staff data on page load
  loadStaff();

  // Event delegation for password reveal to avoid multiple bindings
  $(document)
    .off("click.reveal", ".reveal-password")
    .on("click.reveal", ".reveal-password", function () {
      const password = $(this).data("password");
      console.log("Reveal Password clicked:", password); // Debugging
      alert(`Password: ${password}`);
    });

  function loadStaff() {
    $.ajax({
      url: "/cssc/server/admin/staff_server.php",
      type: "POST",
      data: { action: "read" },
      success: function (response) {
        staff = JSON.parse(response);
        displayTable(currentPage);
        setupPagination();
      },
    });
  }

  function displayTable(page) {
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const visibleStaff = staff.slice(startIndex, endIndex);

    const tableBody = $("#staffTable tbody");
    tableBody.empty(); // Clear the table before appending new rows

    if (visibleStaff.length === 0) {
      tableBody.append(`
                <tr>
                    <td colspan="5" class="text-center">No data found.</td>
                </tr>
            `);
    } else {
      visibleStaff.forEach((staffMember) => {
        const fullName = `${staffMember.first_name} ${
          staffMember.middle_name ? staffMember.middle_name + " " : ""
        }${staffMember.last_name}`;
        tableBody.append(`
                    <tr>
                        <td>${staffMember.staff_id}</td>
                        <td>${fullName}</td>
                        <td>${staffMember.email}</td>
                        <td>
                            <span class="masked-password">••••••••</span>
                            <button class="btn btn-sm btn-secondary reveal-password" data-password="${staffMember.password}">Reveal</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="${staffMember.staff_id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${staffMember.staff_id}">Delete</button>
                        </td>
                    </tr>
                `);
      });
    }

    // Attach event listeners for dynamically loaded elements
    attachEventListeners();
  }

  function setupPagination() {
    const pageCount = Math.ceil(staff.length / rowsPerPage);
    const pagination = $("#pagination ul");
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

  $("#addStaffBtn").click(function () {
    $("#staffForm")[0].reset();
    $(".form-control").removeClass("is-invalid");
    $(".invalid-feedback").text("");
    $("#staff_id").val("");
    $("#staffModalLabel").text("Add Staff");
    $("#staffModal").modal("show");
  });

  $("#staffForm").submit(function (e) {
    e.preventDefault();

    const action = $("#staff_id").val() ? "update" : "create";
    const formData = $(this).serialize() + `&action=${action}`;

    $.ajax({
      url: "/cssc/server/admin/staff_server.php",
      type: "POST",
      data: formData,
      success: function (response) {
        const result = JSON.parse(response);

        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").text("");
        $("#staffModal .modal-content").removeClass("border-danger");

        if (result.success) {
          alert("Staff saved successfully!");
          $("#staffModal").modal("hide");
          loadStaff();
        } else if (result.errors) {
          $("#staffModal .modal-content").addClass("border-danger");
          Object.keys(result.errors).forEach((field) => {
            const fieldElement = $(`[name="${field}"]`);
            fieldElement.addClass("is-invalid");
            fieldElement.next(".invalid-feedback").text(result.errors[field]);
          });
        } else {
          alert("An unexpected error occurred.");
        }
      },
    });
  });

  function attachEventListeners() {
    // Edit button event listener
    $(".edit-btn").click(function () {
      const staff_id = $(this).data("id");

      $.ajax({
        url: "/cssc/server/admin/staff_server.php",
        type: "POST",
        data: { action: "get", staff_id },
        success: function (response) {
          const staffMember = JSON.parse(response);

          if (staffMember.error) {
            alert("Error: " + staffMember.error);
            return;
          }

          $("#staff_id").val(staffMember.staff_id);
          $("#email").val(staffMember.email);
          $("#first_name").val(staffMember.first_name);
          $("#last_name").val(staffMember.last_name);
          $("#middle_name").val(staffMember.middle_name);
          $("#password").val("");

          $(".form-control").removeClass("is-invalid");
          $(".invalid-feedback").text("");
          $("#staffModal .modal-content").removeClass("border-danger");

          $("#staffModalLabel").text("Edit Staff");
          $("#staffModal").modal("show");
        },
      });
    });

    // Delete button event listener
    $(".delete-btn").click(function () {
      const staff_id = $(this).data("id");

      if (confirm("Are you sure you want to delete this staff?")) {
        $.ajax({
          url: "/cssc/server/admin/staff_server.php",
          type: "POST",
          data: { action: "delete", staff_id },
          success: function (response) {
            const result = JSON.parse(response);
            alert(
              result.success ? "Deleted successfully!" : "Failed to delete."
            );
            loadStaff();
          },
        });
      }
    });
  }
});