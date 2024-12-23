
// Load page function
function loadPage(page='home.php') {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', './index.php?page=' + page, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('content').innerHTML = xhr.responseText;
            sessionStorage.setItem('last-page', page);
            history.replaceState({ page: page }, '', '');
            if(page === 'leaderboard.php'){
                
            } else if(page === 'calculate.php'){
                subjectFieldsSubmission();
            } else if(page === 'results.php'){
                validateEntry();
            }
        } else {
            console.error("Error loading page:", xhr.statusText);
            document.getElementById('content').innerHTML = "<p>Error loading page.</p>";
        }
    };
    xhr.send();
}


// Load the last visited page or home page by default
document.addEventListener("DOMContentLoaded", function() {
    const last_page = sessionStorage.getItem('last-page') || 'home.php';
    loadPage(last_page);

    // Logout functionality
    document.getElementById('logout-button').addEventListener('click', function() {
        sessionStorage.removeItem('last-page');
    });
});

function validateEntry(){
    $('#submit-calculation-entry').on('click', (e) => {
        let action = {'submit-entry': 'submit-calculation'};
        $.post("../src/controllers/student-controller.class.php", action);
    });
}

// Grading form submission
function subjectFieldsSubmission(){
    $('#grading').on('submit', (e) => {
        e.preventDefault();
        const data = $(e.currentTarget).serializeArray();
        let form = {
            "subject-code[]": [],
            "unit[]": [],
            "grade[]": [],
        };
        data.forEach((data, index) => {
            form[data.name].push(data.value);
        });

        $.post("../src/utils/save-subject.session.php", form).done((data) => {
            console.log(JSON.parse(data));
        });
    });

    $('input').on('input', (e) => {
        $('#grading').submit();
    })
}

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
    $('input').on('input', (e) => {
        $('#grading').submit();
    })
    $('#grading').submit();
}

function removeSubjectRow(i) {
    $(`#row-${i}`).remove();
    $('#grading').submit();
}

function displayFormData(formData) {
    let formDataEntries = [];
    
    // Loop through all FormData entries
    formData.forEach(function(value, key) {
        if (value instanceof File) {
            // If the value is a file object, show file details
            formDataEntries.push(key + ": " + value.name + " (size: " + value.size + " bytes, type: " + value.type + ")");
        } else {
            // Otherwise, just display the key-value pair
            formDataEntries.push(key + ": " + value);
        }
    });
    
    // Show the form data as a string in an alert box
    // alert(formDataEntries.join("\n"));
}




// function subjectFieldsSubmission() {
//     $('#grading').on('submit', (e) => {
//         e.preventDefault();
        
//         // Create a FormData object to handle file uploads
//         let formData = new FormData();
        
//         // Iterate over each form field and append it to the FormData object
//         $('#grading').find('input').each(function() {
//             // For file input fields (image proofs), we need to handle them differently
//             if ($(this).attr('type') === 'file') {
//                 // alert('file detected');
//                 $.each(this.files, function(i, file) {
//                     formData.append($(this).attr('name'), file);
//                 });
//             } else {
//                 formData.append($(this).attr('name'), $(this).val());
//             }
//         });

//         displayFormData(formData);
        
//         // Send the form data using AJAX
//         $.ajax({
//             url: "../src/utils/save-subject.session.php",  // The PHP script that will handle the request
//             type: "POST",
//             data: formData,  // Send the FormData object
//             contentType: false,  // Let jQuery set content type
//             processData: false,  // Don't process the data (important for file uploads)
//             success: function(data) {
//                 console.log(JSON.parse(data));  // Handle the response from the server
//             },
//             error: function(xhr, status, error) {
//                 console.error("There was an error with the request:", status, error);
//             }
//         });
//     });

    // Trigger form submission on input change
//     $('input').on('input', (e) => {
//         $('#grading').submit();
//     });
// }

    // Initialize subject management functionality
// function initializeSubjectManagement() {
//     const subjectContainer = document.getElementById('subjectContainer');
//     const addSubjectButton = document.getElementById('addSubject');
//     const saveSubjectsButton = document.getElementById('saveSubjects');

//     if (!addSubjectButton) return; // Early exit if button doesn't exist

//     // Load subjects from localStorage
//     function loadSubjects() {
//         const subjects = JSON.parse(localStorage.getItem('subjects')) || [];
//         subjectContainer.innerHTML = '';
//         subjects.forEach((subject, index) => {
//             renderSubjectField(subject, index);
//         });
//     }

//     // Render a subject field
//     function renderSubjectField(subject, index) {
//     const subjectField = document.createElement('div');
//     subjectField.classList.add('subjectField');
//     subjectField.innerHTML = `
//         <input type="text" name="subjects[${index}][subject-code]" placeholder="Subject Code" value="${subject['subject-code'] || ''}" />
//         <input type="text" name="subjects[${index}][unit]" placeholder="Unit" value="${subject['unit'] || ''}" />
//         <input type="text" name="subjects[${index}][grade]" placeholder="Grade" value="${subject['grade'] || ''}" />
//         <button type="button" class="remove-subject" data-index="${index}">Remove</button>
//     `;

//     // Add event listeners to the input fields to save values
//     const inputs = subjectField.querySelectorAll('input');
//     inputs.forEach(input => {
//         input.addEventListener('input', function() {
//             const subjects = JSON.parse(localStorage.getItem('subjects')) || [];
//             // alert(localStorage.getItem('subjects'));
//             subjects[index] = subjects[index];
//             // Extract the key (subject-code, unit, grade) using regex to get the appropriate key
//             const key = input.name.match(/(?:\[(.*?)\])/)[1]; // Extract the key from the input name
//             subjects[index][key] = input.value; //
//             localStorage.setItem('subjects', JSON.stringify(subjects));
//             alert(JSON.stringify(subjects));
//         });
//     });

//     subjectContainer.appendChild(subjectField);
// }


//     // Add a new subject
//     addSubjectButton.addEventListener('click', function() {
//         const subjects = JSON.parse(localStorage.getItem('subjects')) || [];
//         subjects.push({ 'subject-code': '', 'unit': '', 'grade': '' });
//         localStorage.setItem('subjects', JSON.stringify(subjects));
//         loadSubjects();
//     });

//     // Handle remove subject
//     subjectContainer.addEventListener('click', function(event) {
//         if (event.target.classList.contains('remove-subject')) {
//             const index = event.target.dataset.index;
//             let subjects = JSON.parse(localStorage.getItem('subjects')) || [];
//             subjects.splice(index, 1);
//             localStorage.setItem('subjects', JSON.stringify(subjects));
//             loadSubjects();
//         }
//     });

//     // Save to PHP session
//     saveSubjectsButton.addEventListener('click', function() {
//         saveToSession();
//     });

//     function saveToSession() {
//         const subjects = localStorage.getItem('subjects');
//         const xhr = new XMLHttpRequest();
//         xhr.open('POST', '../resources/views/student/save-subject.session.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/json');
//         xhr.onload = function() {
//             if (xhr.status === 200) {
//                 console.log('Subjects saved to session');
//             } else {
//                 console.error('Error saving subjects:', xhr.statusText);
//             }
//         };
//         xhr.send(subjects);
//     }

//     // Load subjects on page load
//     loadSubjects();
// }



// function addInputField() {
//     alert('add');
//     const xhr = new XMLHttpRequest();
//     xhr.open('POST', '../resources/views/student/calculate.php', true);
//     // xhr.open('POST', './', true);
//     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

//     xhr.onreadystatechange = function() {
//         if (xhr.readyState === XMLHttpRequest.DONE) {
//             if (xhr.status === 200) {
//                 alert(xhr.responseText); // Logs the response from the PHP script
//             } else {
//                 console.error('Error:', xhr.statusText);
//             }
//         }
//     };

//     xhr.send('action=addSubject'); // Trigger the PHP code
// }


// document.addEventListener('DOMContentLoaded', function() {
//     const form = document.getElementById('subjectFieldsContainer');

//     form.addEventListener('submit', function(event) {
//         event.preventDefault(); // Prevent default form submission

//         const formData = new FormData(form);
        
//         // Send AJAX request
//         fetch('', {
//             method: 'POST',
//             body: formData
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 document.getElementById('calculate-div2').innerHTML = getSubjectFieldsHTML(data.subjects);
//             } else {
//                 alert('Error saving subjects.');
//             }
//         })
//         .catch(error => console.error('Error:', error));
//     });

//     // Delegate event for removing subjects
//     document.getElementById('calculate-div2').addEventListener('click', function(event) {
//         if (event.target.classList.contains('remove-subject')) {
//             event.preventDefault();
//             const index = event.target.getAttribute('data-index');

//             const formData = new FormData();
//             formData.append('action', 'remove-subject');
//             formData.append('remove-subject', index);

//             fetch('', {
//                 method: 'POST',
//                 body: formData
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     document.getElementById('calculate-div2').innerHTML = getSubjectFieldsHTML(data.subjects);
//                 } else {
//                     alert('Error removing subject.');
//                 }
//             })
//             .catch(error => console.error('Error:', error));
//         }
//     });
// });

// Function to generate HTML for subject fields
// function getSubjectFieldsHTML(subjects) {
//     alert('get')
//     return subjects.map((subject, index) => `
//         <div class="subjectField">
//             <input type="text" name="subjects[${index}][subject-code]" placeholder="Subject Code" value="${subject['subject-code']}" />
//             <input type="text" name="subjects[${index}][unit]" placeholder="Unit" value="${subject['unit']}" />
//             <input type="text" name="subjects[${index}][grade]" placeholder="Grade" value="${subject['grade']}" />
//             <button type="button" class="remove-subject" data-index="${index}">Remove</button>
//         </div>
//     `).join('');
// }