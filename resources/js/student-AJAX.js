function loadPage(page) {
    fetch(`index.php?page=${page}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}
