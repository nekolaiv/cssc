function loadPage(page) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', './index.php?page=' + page, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('content').innerHTML = xhr.responseText;
            // Save the current page in sessionStorage
            sessionStorage.setItem('last-page', page);
            history.replaceState({ page: page }, '', '');
        } else {
            document.getElementById('content').innerHTML = "<p>Error loading page.</p>";
        }
    };
    xhr.send();
}

function clearLastPage() {
    sessionStorage.removeItem('last-page');
}

// Load the last visited page or home page by default
document.addEventListener("DOMContentLoaded", function() {
    const last_page = sessionStorage.getItem('last-page') || 'home.php';
    loadPage(last_page);
});


document.getElementById('logout-button').addEventListener('click', function() {
    clearLastPage();
    // Add your logout logic here (e.g., redirect to the login page)
});