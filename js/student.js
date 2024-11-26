document.addEventListener("DOMContentLoaded", function () {
    loadStudents();

    // Function to load all students
    function loadStudents() {
        $.ajax({
            url: "/cssc/server/studentServer.php",
            type: "POST",
            data: { action: "read" },
            success: function (response) {
                let students = JSON.parse(response);
                let tableBody = document.querySelector("#studentsTable tbody");
                tableBody.innerHTML = "";

                students.forEach((student) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${student.student_id}</td>
                            <td>${student.first_name} ${student.last_name}</td>
                            <td>${student.email}</td>
                            <td>${student.course}</td>
                            <td>${student.year_level}</td>
                            <td>
                                <button class="edit-btn" data-id="${student.user_id}">Edit</button>
                                <button class="delete-btn" data-id="${student.user_id}">Delete</button>
                            </td>
                        </tr>`;
                });
            }
        });
    }

    // Add other AJAX calls for create, update, and delete
});
