$('#grading').on('submit', function(e) {
    e.preventDefault();
    const data = $(e.currentTarget).serializeArray();
    let form = {
        "subject-name[]": [],
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
        url: "/cssc/server/student_save-subject.php",
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