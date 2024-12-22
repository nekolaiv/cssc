

$('#filter-leaderboard').on('submit', function(e) {
     e.preventDefault(); // Prevent default form submission

    // Get the selected value
    const selectedYear = $('#leaderboard-year-level-filter').val();

    // Validate the selection
    if (!selectedYear) {
        alert("Please select a year level to filter.");
        return;
    }
    // let form = {
    //     "subject-name": [],
    //     "subject-code": [],
    //     "unit": [],
    //     "grade": [],
    // };

    // // Populate form object
    // data.forEach((item) => {
    //     if (item.name === "subject-name[]") {
    //         form["subject-name"].push(item.value);
    //     } else if (item.name === "subject-code[]") {
    //         form["subject-code"].push(item.value);
    //     } else if (item.name === "unit[]") {
    //         form["unit"].push(item.value);
    //     } else if (item.name === "grade[]") {
    //         form["grade"].push(item.value);
    //     }
    // });

    $.ajax({
        type: "POST",
        url: "/cssc/server/student_save-subject.php",
        data: { year_level: selectedYear },
        dataType: "json",
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error("Error submitting subject fields: ", error);
        }
    });
    
    });
$('input').on('input', function() {
    $('#grading').submit();
});

function addSubjectRow() {
    $('#grading').append(`
        <div class="subject-fields" id='row-${session_length}'>
            <input type="text" name="subject-code[]">
            <input type="number" name="unit[]">
            <input type="number" name="grade[]">
            <button type="button" class="subject-remove-buttons" onclick="removeSubjectRow(${session_length})">remove</button>
        </div>
    `);

    session_length++;

    $('input').on('input', function() {
        $('#grading').submit();
    });
    $('#grading').submit();
}

function removeSubjectRow(i) {
    $(`#row-${i}`).remove();
    $('#grading').submit();
}