document.addEventListener('DOMContentLoaded', function() {
  const menuToggle = document.getElementById('menu-toggle');
  const navMenu = document.getElementById('nav-menu');
  const navItems = document.querySelectorAll('.nav-items');

  menuToggle.addEventListener('click', function() {
    navMenu.classList.toggle('active');
  });

  if (window.innerWidth < 768) {
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });
        });
    }
});