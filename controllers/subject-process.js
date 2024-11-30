

$('#grading').on('submit', function(e) {
    e.preventDefault();
    const data = $(e.currentTarget).serializeArray();
    let form = {
        "subject-code[]": [],
        "unit[]": [],
        "grade[]": [],
    };

    // Populate form object
    data.forEach((data, index) => {
        form[data.name].push(data.value);
    });

    $.ajax({
        type: "POST",
        url: "../../server/student_save-subject.php",
        data: form, 
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