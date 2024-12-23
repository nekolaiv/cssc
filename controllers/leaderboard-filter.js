$('#filter-leaderboard').on('submit', function(e) {
    e.preventDefault();
    window.location.reload();
    const selectedYear = $('#leaderboard-year-level-filter').val();
    const selectedPeriod = $('#leaderboard-submission-period-filter').val();

    $.ajax({
        type: "POST",
        url: "/cssc/server/leaderboard_load.php",
        data: { year_level: selectedYear, submission_period: selectedPeriod },
        dataType: "json",
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error("Error loading leaderboard: ", error);
        }
    }); 
});