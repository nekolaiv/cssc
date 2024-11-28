const sidebar = document.getElementById('sidebar');
const toggleButton = document.getElementById('toggle-btn');

// Toggle sidebar
toggleButton.addEventListener('click', function () {
  sidebar.classList.toggle('close');
  closeAllSubMenus();
});

// Dynamic content loading with AJAX
$(document).on('click', '.menu-link', function (e) {
  e.preventDefault();
  const url = $(this).data('url');
  $.ajax({
    url: url,
    method: 'GET',
    success: function (data) {
      $('#content').html(data);
    },
    error: function () {
      $('#content').html('<p>Error loading content.</p>');
    }
  });

  // Highlight active menu link
  $('.menu-link').parent().removeClass('active');
  $(this).parent().addClass('active');
});

// Handle submenu toggle
function toggleSubMenu(button) {
  if (!button.nextElementSibling.classList.contains('show')) {
    closeAllSubMenus();
  }

  button.nextElementSibling.classList.toggle('show');
  button.classList.toggle('rotate');

  if (sidebar.classList.contains('close')) {
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');
  }
}

function closeAllSubMenus() {
  Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
    ul.classList.remove('show');
    ul.previousElementSibling.classList.remove('rotate');
  });
}