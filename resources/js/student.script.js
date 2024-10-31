document.addEventListener('DOMContentLoaded', function() {
    const profileIcon = document.getElementById('profile-icon');
    const profileDropdown = document.getElementById('profile-dropdown');
    const navItems = document.querySelectorAll('.nav-items');
    const navMenu = document.getElementById('nav-menu');
    profileIcon.addEventListener('click', function() {
        profileDropdown.classList.toggle('active');
    });

    window.addEventListener('click', function(event) {
        if (!profileIcon.contains(event.target)) {
            profileDropdown.classList.toggle('active');
        }
    });

    // navItems.forEach(item => {
    //     item.addEventListener('click', function() {
    //         profileDropdown.classList.toggle('active');
    //         navMenu.classList.toggle('active');
    //     });
    // });
});