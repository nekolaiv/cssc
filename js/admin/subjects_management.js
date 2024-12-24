$(document).ready(function () {
    let subjects = [];
    const rowsPerPage = 5;
    let currentPage = 1;

    // Initial Load
    loadSubjects();
    loadCourses();
    loadCurriculums();
    setupFilters();

    // Fetch Subjects
    function loadSubjects(filters = {}) {
        $.post(
            "/cssc/server/admin/subjects_server.php",
            { action: "read", ...filters },
            function (response) {
                console.log(response);
                try {
                    subjects = JSON.parse(response);
                    if (subjects.success === false) {
                        displayError(subjects.error);
                    } else {
                        displayTable(currentPage);
                        setupPagination();
                    }
                } catch (error) {
                    console.error("Error parsing subjects data:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to load subjects.");
        });
    }

    // Fetch Courses for Filter and Dropdown
    function loadCourses() {
        $.post(
            "/cssc/server/admin/subjects_server.php",
            { action: "fetch_courses" },
            function (response) {
                try {
                    const courses = JSON.parse(response);
                    const courseDropdown = $("#filterCourse");
                    const courseModalDropdown = $("#course");
                    courseDropdown.empty().append('<option value="">All Courses</option>');
                    courseModalDropdown.empty().append('<option value="">Select Course</option>');

                    courses.forEach((course) => {
                        courseDropdown.append(`<option value="${course.id}">${course.course_name}</option>`);
                        courseModalDropdown.append(`<option value="${course.id}">${course.course_name}</option>`);
                    });
                } catch (error) {
                    console.error("Error parsing courses data:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to fetch courses.");
        });
    }

    // Fetch Curriculums for Filter and Dropdown
    function loadCurriculums() {
        $.post(
            "/cssc/server/admin/subjects_server.php",
            { action: "fetch_curriculums" },
            function (response) {
                try {
                    const curriculums = JSON.parse(response);
                    const curriculumDropdown = $("#filterCurriculum");
                    const curriculumModalDropdown = $("#curriculumModal");
                    curriculumDropdown.empty().append('<option value="">All Curriculums</option>');
                    curriculumModalDropdown.empty().append('<option value="">Select Curriculum</option>');

                    curriculums.forEach((curriculum) => {
                        curriculumDropdown.append(`<option value="${curriculum.id}">${curriculum.remarks}</option>`);
                        curriculumModalDropdown.append(`<option value="${curriculum.id}">${curriculum.remarks}</option>`);
                    });
                } catch (error) {
                    console.error("Error parsing curriculums data:", error);
                }
            }
        ).fail(function () {
            console.error("Failed to fetch curriculums.");
        });
    }

    // Display Subjects Table
    function displayTable(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const visibleSubjects = subjects.slice(start, end);

    const tableBody = $("#subjectsTable tbody");
    tableBody.empty();

    if (visibleSubjects.length === 0) {
        tableBody.append('<tr><td colspan="10" class="text-center">No data found.</td></tr>');
        return;
    }

    visibleSubjects.forEach((subject) => {
        tableBody.append(`
            <tr>
                <td>${subject.subject_code}</td>
                <td>${subject.descriptive_title}</td>
                <td>${subject.prerequisite || "None"}</td>
                <td>${subject.lec_units}</td>
                <td>${subject.lab_units}</td>
                <td>${subject.total_units}</td>
                <td>${subject.year_level}</td>
                <td>${subject.semester}</td>
                <td>${subject.curriculum_remarks || "N/A"}</td> <!-- Display curriculum remarks -->
                <td>
                    <button class="btn btn-info btn-sm edit-subject-btn" data-id="${subject.id}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-subject-btn" data-id="${subject.id}">Delete</button>
                </td>
            </tr>
        `);
    });
}


    // Setup Pagination
    function setupPagination() {
        const totalPages = Math.ceil(subjects.length / rowsPerPage);
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
        $("#filterCourse, #filterCurriculum, #filterYearLevel, #filterSemester").on("change", function () {
            const filters = {
                course_id: $("#filterCourse").val(),
                curriculum_id: $("#filterCurriculum").val(),
                year_level: $("#filterYearLevel").val(),
                semester: $("#filterSemester").val(),
            };
            console.log(filters);
            loadSubjects(filters);
        });
    }

    // Open Create Subject Modal
    $("#createSubjectBtn").on("click", function () {
        $("#subjectForm")[0].reset();
        $("#subjectId").val("");
        $("#formError").addClass("d-none").text("");
        $("#subjectModalLabel").text("Create Subject");
        $("#subjectModal").modal("show");
    });

    // Submit Create/Update Subject Form
    $("#subjectForm").on("submit", function (e) {
        e.preventDefault();
    
        // Use FormData for proper serialization
        const formData = new FormData(this);
    
        formData.append("action", "save"); // Add the action to the form data
    
        // Debugging
        for (const [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
    
        // Send AJAX request
        $.ajax({
            url: "/cssc/server/admin/subjects_server.php",
            method: "POST",
            data: formData,
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Let the browser set the Content-Type
            success: function (response) {
                console.log("Server Response:", response); // Debugging
                try {
                    const data = JSON.parse(response);
    
                    if (data.errors) {
                        $("#formError").removeClass("d-none").html(
                            Object.values(data.errors).map((err) => `<div>${err}</div>`).join("")
                        );
                    } else if (data.success) {
                        $("#subjectModal").modal("hide");
                        loadSubjects();
                    } else {
                        $("#formError").removeClass("d-none").text(data.error || "Failed to save subject.");
                    }
                } catch (error) {
                    console.error("Error parsing response:", error);
                }
            },
            error: function () {
                $("#formError").removeClass("d-none").text("Failed to save subject.");
            }
        });
    });
    

    // Edit Subject
    $(document).on("click", ".edit-subject-btn", function () {
        const subjectId = $(this).data("id");

        $.post(
            "/cssc/server/admin/subjects_server.php",
            { action: "get", id: subjectId },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.success) {
                        const subject = data.data;
                        $("#subjectId").val(subject.id);
                        $("#subjectCode").val(subject.subject_code);
                        $("#descriptiveTitle").val(subject.descriptive_title);
                        $("#prerequisite").val(subject.prerequisite);
                        $("#lecUnits").val(subject.lec_units);
                        $("#labUnits").val(subject.lab_units);
                        $("#totalUnits").val(subject.total_units);
                        $("#yearLevel").val(subject.year_level);
                        $("#semester").val(subject.semester);
                        $("#curriculum").val(subject.curriculum_id);
                        $("#formError").addClass("d-none").text("");
                        $("#subjectModalLabel").text("Update Subject");
                        $("#subjectModal").modal("show");
                    } else {
                        alert(data.error || "Failed to fetch subject details.");
                    }
                } catch (error) {
                    console.error("Error parsing subject data:", error);
                }
            }
        ).fail(function () {
            alert("Failed to fetch subject details.");
        });
    });

    // Delete Subject
    $(document).on("click", ".delete-subject-btn", function () {
        const subjectId = $(this).data("id");

        if (!confirm("Are you sure you want to delete this subject?")) {
            return;
        }

        $.post(
            "/cssc/server/admin/subjects_server.php",
            { action: "delete", id: subjectId },
            function (response) {
                try {
                    const data = JSON.parse(response);

                    if (data.success) {
                        loadSubjects();
                    } else {
                        alert(data.error || "Failed to delete subject.");
                    }
                } catch (error) {
                    console.error("Error parsing delete response:", error);
                }
            }
        ).fail(function () {
            alert("Failed to delete subject.");
        });
    });
});
