function loadPage(page) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', `index.php?page=${page}`, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                document.getElementById('content').innerHTML = xhr.responseText;
            } else {
                console.error('Error:', xhr.statusText);
            }
        }
    };

    xhr.send();
}
