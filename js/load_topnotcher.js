$(document).ready(function () {
	leadboardLoad();
    $(".topnotcher-year").on("click", function (e) {
		$(".topnotcher-year").removeClass("active");
		$(this).addClass("active");
		const yearLevelValue = $(this).val();
		leadboardLoad(yearLevelValue);
	});

    function leadboardLoad(year=1){
		$.ajax({
			type: "POST",
			url: "/cssc/server/leaderboard_server.php",
			data: { year_level: year},
			dataType: "json",
			success: function(data) {
				let csTopnotcher = data.cs_topnotcher;
				let itTopnotcher = data.it_topnotcher;
				let actTopnotcher = data.act_topnotcher;
				console.log(data);
				$('#csFullname').text(csTopnotcher.fullname);
				$('#csTotalRating').text(csTopnotcher.total_rating);

				$('#itFullname').text(itTopnotcher.fullname);
				$('#itTotalRating').text(itTopnotcher.total_rating);

				if(year <= 2){
					$('#actFullname').text(actTopnotcher.fullname);
					$('#actTotalRating').text(actTopnotcher.total_rating);
				} else {
					$('#actFullname').text(actTopnotcher);
					$('#actTotalRating').text(actTopnotcher);
				}
			},
			error: function(xhr, status, error) {
				console.error("Error loading leaderboard: ", error);
			}
		});
	}

});