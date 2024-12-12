$(document).ready(function () {
    loadDashboardData();

    // Function to fetch and display dashboard data
    function loadDashboardData() {
        $.ajax({
            url: '/cssc/server/admin/admin_index_server.php',
            type: 'POST',
            data: { action: 'get_dashboard_data' },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#academic-year').text(`Academic Year: ${result.data.academic_year}`);
                    $('#semester').text(`Semester: ${result.data.semester}`);
                } else {
                    alert(`Error: ${result.message}`);
                }
            },
            error: function () {
                alert('Failed to load dashboard data.');
            }
        });
    }

    // Function to update time and date
    function updateDateTime() {
        const now = new Date();
        const time = now.toLocaleTimeString();
        const date = now.toLocaleDateString();

        $('#current-time').text(time);
        $('#current-date').text(date);
    }

    // Update time and date every second
    setInterval(updateDateTime, 1000);
    updateDateTime();
});
