// Store DOM elements in constants
// const addFieldButton = document.getElementById('addField');
// const submitDataButton = document.getElementById('submitData');
// const inputContainer = document.getElementById('inputContainer');

// addFieldButton.addEventListener('click', function() {
//     alert('clicked');
//     const newFieldDiv = document.createElement('div');

//     const subjectCodeInput = document.createElement('input');
//     subjectCodeInput.type = 'text';
//     subjectCodeInput.placeholder = 'Subject Code';

//     const unitInput = document.createElement('input');
//     unitInput.type = 'number';
//     unitInput.placeholder = 'Units';

//     const gradeInput = document.createElement('input');
//     gradeInput.type = 'text';
//     gradeInput.placeholder = 'Grade';

//     newFieldDiv.appendChild(subjectCodeInput);
//     newFieldDiv.appendChild(unitInput);
//     newFieldDiv.appendChild(gradeInput);

//     inputContainer.appendChild(newFieldDiv);
// });

// submitDataButton.addEventListener('click', function() {
//     const subjects = [];

//     const fieldDivs = inputContainer.children;
//     for (let div of fieldDivs) {
//         const inputs = div.getElementsByTagName('input');
//         if (inputs.length === 3) {
//             subjects.push({
//                 subjectCode: inputs[0].value,
//                 units: inputs[1].value,
//                 grade: inputs[2].value
//             });
//         }
//     }

//     const xhr = new XMLHttpRequest();
//     xhr.open('POST', './index.php', true); // Replace with your server endpoint
//     xhr.setRequestHeader('Content-Type', 'application/json');

//     xhr.onreadystatechange = function() {
//         if (xhr.readyState === 4) { // Check if the request is complete
//             alert('Response received: ' + xhr.responseText); // Debug log
//             if (xhr.status === 200) {
//                 alert('Success: ' + xhr.responseText); // Success message
//             } else {
//                 alert('Error: ' + xhr.status + ' ' + xhr.statusText); // Error details
//             }
//         }
//     };

//     xhr.send(JSON.stringify({ subjects: subjects })); // Send the subjects data as JSON
// });




function attachAddSubjectListener() {
    const addSubjectButton = document.getElementById('addSubject');
    if (addSubjectButton) { // Check if the element exists
        addSubjectButton.addEventListener('click', function() {
            alert('add');
            const xhr = new XMLHttpRequest();
            xhr.open('POST', './index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var subjects = JSON.parse(xhr.responseText);
                    updateSubjects(subjects);
                }
            };
            xhr.send('action=addSubject');
        });
    }
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('removeSubject')) {
        alert('remove');
        var index = event.target.getAttribute('data-index');
        const xhr = new XMLHttpRequest();
        xhr.open('POST', './index.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var subjects = JSON.parse(xhr.responseText);
                updateSubjects(subjects);
            }
        };
        xhr.send('action=removeSubject&index=' + index);
    }
});

function updateSubjects(subjects) {
    alert('update');
    var container = document.getElementById('subjectFieldsContainer');
    container.innerHTML = ''; // Clear existing fields
    subjects.forEach(function(subject, index) {
        var div = document.createElement('div');
        div.className = 'subjectField';
        div.innerHTML = `
            <input type="text" name="subjectCode[]" placeholder="Subject Code" value="${subject.subjectCode}" />
            <input type="text" name="unit[]" placeholder="Unit" value="${subject.unit}" />
            <input type="text" name="grade[]" placeholder="Grade" value="${subject.grade}" />
            <button class="removeSubject" data-index="${index}">Remove</button>
        `;
        container.appendChild(div);
    });
}