function loadCourseForm() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../resources/views/student/includes/course-inputs.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const courseContainer = document.getElementById('course-inputs');
            courseContainer.insertAdjacentHTML('beforeend', xhr.responseText);
            populateInputs(); // Populate inputs with session storage values
            addInputListeners(); // Add listeners to save inputs to session storage
            saveCourseContainer(); // Save course container to session storage
        } else {
            console.error('Error loading form.');
        }
    };
    xhr.send();
}neo

function addInputListeners() {
    const courseContainer = document.getElementById('course-inputs');
    const inputs = courseContainer.querySelectorAll('input');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            const storageKey = `${input.name}${index}`; // Create a unique key for session storage
            sessionStorage.setItem(storageKey, input.value); // Save to session storage
            saveCourseContainer(); // Save course container state
        });
    });
}

function populateInputs() {
    const courseContainer = document.getElementById('course-inputs');
    const inputs = courseContainer.querySelectorAll('input');

    inputs.forEach((input, index) => {
        const savedValue = sessionStorage.getItem(`${input.name}${index}`); // Retrieve from session storage
        if (savedValue) {
            input.value = savedValue; // Populate with saved value
        }
    });
}

function removeCourse(button) {
    const courseDiv = button.parentElement; // Get the parent div
    const inputs = courseDiv.querySelectorAll('input');
    
    // Remove the course entry
    courseDiv.remove(); 

    // Clear session storage for each input in this course
    inputs.forEach((input, index) => {
        const storageKey = `${input.name}${index}`; // Create the corresponding storage key
        sessionStorage.removeItem(storageKey); // Remove from session storage
    });

    // Re-index remaining courses in session storage
    reIndexCourseInputs();
    saveCourseContainer(); // Save updated course container state
}

function reIndexCourseInputs() {
    const courseContainer = document.getElementById('course-inputs');
    const courses = courseContainer.children;

    // Loop through each course to re-index inputs
    for (let i = 0; i < courses.length; i++) {
        const inputs = courses[i].querySelectorAll('input');

        inputs.forEach((input, j) => {
            const key = input.name; // Get the input name
            const savedValue = sessionStorage.getItem(`${key}${i}`); // Adjust index for re-indexing
            if (savedValue) {
                input.value = savedValue; // Restore saved value
            }
 
            input.addEventListener('input', () => {
                const storageKey = `${key}${i}`; // New unique key based on the new index
                sessionStorage.setItem(storageKey, input.value); // Save to session storage
                saveCourseContainer(); // Save course container state
            });
        });
    }
}

function saveCourseContainer() {
    const courseContainer = document.getElementById('course-inputs');
    sessionStorage.setItem('courseContainer', courseContainer.innerHTML); // Save inner HTML to session storage
}

function restoreCourseContainer() {
    const courseContainer = document.getElementById('course-inputs');
    const savedHTML = sessionStorage.getItem('courseContainer');
    alert(savedHTML);
    if (savedHTML) {
        courseContainer.insertAdjacentHTML('beforeend', savedHTML);
        // courseContainer.innerHTML = savedHTML; // Restore HTML from session storage
        populateInputs(); // Populate the restored inputs with saved values
        addInputListeners(); // Reattach input listeners to the restored inputs
    }
}

// Load the form when needed (you can call this function based on your application's logic)
document.addEventListener('DOMContentLoaded', () => {
    restoreCourseContainer(); // Restore the course container state on load
    loadCourseForm(); // Load the form
});

        function saveCourseToSession1(courseDiv) {
            // Serialize the entire HTML of the courseDiv
            alert('hello');
            const courseHTML = courseDiv;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../src/controllers/student-controller.class.php?action=saveCourse', true);
            xhr.open('POST', './index.php?action=saveCourse', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`courseHTML=${encodeURIComponent(courseHTML)}`);
        }

        function calculateGPA() {
            const formData = new FormData();
            const courses = document.querySelectorAll('.course');

            courses.forEach(course => {
                const subjectCode = course.querySelector('input[name="subjectCode[]"]').value;
                const units = course.querySelector('input[name="units[]"]').value;
                const grades = course.querySelector('input[name="grades[]"]').value;

                formData.append('subjectCode[]', subjectCode);
                formData.append('units[]', units);
                formData.append('grades[]', grades);
            });

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/public/index.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('results').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('results').innerHTML = "<p>Error calculating GPA.</p>";
                }
            };

            xhr.send(formData);
        }