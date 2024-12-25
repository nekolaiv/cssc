$(document).ready(function () {
    let curricula = [];
    const rowsPerPage = 5;
    let currentPage = 1;

    // Initial Load
    loadCurricula();
    loadCourses();

    // Fetch Curricula
    function loadCurricula(filters = {}) {
        $.post("/cssc/server/admin/curriculum_server.php", { action: "read", ...filters }, function (response) {
            try {
                curricula = JSON.parse(response);
                displayTable();
                setupPagination();
            } catch (error) {
                console.error("Error parsing curricula data:", error);
            }
        }).fail(function () {
            console.error("Failed to load curricula.");
        });
    }

    // Fetch Courses
    function loadCourses(callback) {
        $.post("/cssc/server/admin/curriculum_server.php", { action: "fetch_courses" }, function (response) {
            try {
                const courses = JSON.parse(response);
                const dropdown = $("#course");
                const filterDropdown = $("#filterCourse");
                dropdown.empty().append('<option value="">Select Course</option>');
                filterDropdown.empty().append('<option value="">All Courses</option>');

                courses.forEach(course => {
                    dropdown.append(`<option value="${course.id}">${course.course_name}</option>`);
                    filterDropdown.append(`<option value="${course.id}">${course.course_name}</option>`);
                });

                if (callback) callback(); // Trigger any callback if provided
            } catch (error) {
                console.error("Error parsing courses data:", error);
            }
        }).fail(function () {
            console.error("Failed to fetch courses.");
        });
    }

    // Display Table
    function displayTable() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const visible = curricula.slice(start, end);

        const tableBody = $("#curriculumTable tbody");
        tableBody.empty();

        if (visible.length === 0) {
            tableBody.append('<tr><td colspan="5" class="text-center">No data found.</td></tr>');
            return;
        }

        visible.forEach(curr => {
            tableBody.append(`
                <tr>
                    <td>${curr.effective_year}</td>
                    <td>${curr.version}</td>
                    <td>${curr.remarks || 'N/A'}</td>
                    <td>${curr.course_name}</td>
                    <td>
                        <button class="btn btn-info btn-sm edit-btn" data-id="${curr.id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${curr.id}">Delete</button>
                    </td>
                </tr>
            `);
        });
    }

    // Setup Pagination
    function setupPagination() {
        const totalPages = Math.ceil(curricula.length / rowsPerPage);
        const pagination = $("#pagination ul").empty();

        for (let i = 1; i <= totalPages; i++) {
            pagination.append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a href="#" class="page-link" data-page="${i}">${i}</a>
                </li>
            `);
        }

        $(".page-link").on("click", function (e) {
            e.preventDefault();
            currentPage = parseInt($(this).data("page"));
            displayTable();
        });
    }

    // Form Submission
    $("#curriculumForm").on("submit", function (e) {
        e.preventDefault();
    
        // Serialize the form data correctly
        const formData = $(this).serializeArray();
        const formObject = {};
        formData.forEach(field => {
            formObject[field.name] = field.value;
        });
    
        formObject.action = "save";
    
        console.log("Form Data Sent:", formObject);
    
        // Send AJAX request
        $.post(
            "/cssc/server/admin/curriculum_server.php",
            formObject,
            function (response) {
                console.log("Server Response:", response);
                try {
                    const res = JSON.parse(response);
                    if (res.errors) {
                        $("#formError").removeClass("d-none").html(
                            Object.values(res.errors).map(err => `<div>${err}</div>`).join("")
                        );
                    } else if (res.success) {
                        $("#curriculumModal").modal("hide");
                        loadCurricula();
                    } else {
                        $("#formError").removeClass("d-none").text(res.error || "Failed to save curriculum.");
                    }
                } catch (error) {
                    console.error("Error parsing response:", error);
                }
            }
        ).fail(function () {
            $("#formError").removeClass("d-none").text("Failed to save curriculum.");
        });
    });
    
    

    // Edit Curriculum
    $(document).on("click", ".edit-btn", function () {
        const id = $(this).data("id");

        $.post("/cssc/server/admin/curriculum_server.php", { action: "get", id }, function (response) {
            const res = JSON.parse(response);
            if (res.success) {
                const curr = res.data;
                $("#curriculumId").val(curr.id);
                $("#effectiveYear").val(curr.effective_year);
                $("#version").val(curr.version);
                $("#remarks").val(curr.remarks);
                $("#course").val(curr.course_id);
                $("#formError").addClass("d-none");
                $("#curriculumModal").modal("show");
            } else {
                alert(res.error || "Failed to fetch curriculum details.");
            }
        }).fail(function () {
            alert("Failed to fetch curriculum details.");
        });
    });

    // Delete Curriculum
    $(document).on("click", ".delete-btn", function () {
        const id = $(this).data("id");

        if (!confirm("Are you sure you want to delete this curriculum?")) {
            return;
        }

        $.post("/cssc/server/admin/curriculum_server.php", { action: "delete", id }, function (response) {
            const res = JSON.parse(response);
            if (res.success) {
                loadCurricula();
            } else {
                alert(res.error || "Failed to delete curriculum.");
            }
        }).fail(function () {
            alert("Failed to delete curriculum.");
        });
    });

    // Filters
    $("#filterCourse, #searchYear, #searchRemarks, #searchVersion").on("input change", function () {
        const filters = {
            course_id: $("#filterCourse").val(),
            year: $("#searchYear").val(),
            remarks: $("#searchRemarks").val(),
            version: $("#searchVersion").val()
        };
        loadCurricula(filters);
    });

    // Create New Curriculum
    $("#createCurriculumBtn").on("click", function () {
        loadCourses(() => {
            $("#curriculumForm")[0].reset();
            $("#curriculumId").val("");
            $("#formError").addClass("d-none");
            $("#curriculumModal").modal("show");
        });
    });
});
