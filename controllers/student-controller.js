$(document).ready(function () {
	$(".nav-items").on("click", function (e) {
		e.preventDefault();
		$(".nav-items").removeClass("nav-active");
		$(this).addClass("nav-active");
		let url = $(this).attr("href");
		console.log(url);
		window.history.pushState({ path: url }, "", url);
	});


	$("#home-link").on("click", function (e) {
		e.preventDefault();
		loadPage('home-content.php');
	});

	$("#result-home-link").on("click", function (e) {
		e.preventDefault();
		loadPage('home-content.php');
	});

	$("#home-logo-link").on("click", function (e) {
		e.preventDefault();
		loadPage('home-content.php');
	});

	$("#leaderboard-link").on("click", function (e) {
		e.preventDefault();
		viewLeaderboard();
	});

	$("#home-leaderboard-link").on("click", function (e) {
		e.preventDefault();
		loadPage('leaderboard-content.php');
	});

	$("#calculate-link").on("click", function (e) {
		e.preventDefault();
		loadPage('calculate-content.php');
	});

	$("#about-link").on("click", function (e) {
		e.preventDefault();
		loadPage('about-content.php');
	});

	$("#results-link").on("click", function (e) {
		e.preventDefault();
		calculateGWA()
		loadPage('results-content.php');
	});

	$("#previous-link").on("click", function (e) {
		e.preventDefault();
		loadPage('previous-results-content.php');
	});


	$("#profile-link").on("click", function (e) {
		e.preventDefault();
		loadPage('profile-content.php');
	});

	$("#filter-year").on("click", function (e) {
		e.preventDefault();
		alert("filter year clicked");
	});
	

	let url = window.location.href;

	if (url.endsWith("home")) {
		$("#home-link").trigger("click");
		$("#result-home-link").trigger("click");
		$("#home-logo-link").trigger("click");
	} else if (url.endsWith("leaderboard")) {
		$("#leaderboard-link").trigger("click");
		$("#home-leaderboard-link").trigger("click");
	} else if (url.endsWith("results")) {
		$("#results-link").trigger("click");
	} else if (url.endsWith("previous")) {
		$("#previous-link").trigger("click");
	} else if (url.endsWith("profile")) {
		$("#profile-link").trigger("click");
	} else if (url.endsWith("calculate")) {
		$("#calculate-link").trigger("click");
		// $("#result-calculate-link").trigger("click");
	} else {
		$("#home-link").trigger("click");
	}
	
	$(window).on('popstate', function(event) {
		let url = window.location.href;
		if (url.endsWith("home")) {
			// $("#home-link").trigger("click");
			// $("#result-home-link").trigger("click");
			// $("#home-logo-link").trigger("click");
			loadPage('home-content.php');
		} else if (url.endsWith("results")) {
			// $("#results-link").trigger("click");
			loadPage('results-content.php');
		} else if (url.endsWith("previous")) {
			// $("#results-link").trigger("click");
			loadPage('previous-results-content.php');
		} else if (url.endsWith("profile")) {
			// $("#profile-link").trigger("click");
			loadPage('profile-content.php');
		} else if (url.endsWith("calculate")) {
			// $("#calculate-link").trigger("click");
			// $("#result-calculate-link").trigger("click");
			loadPage('calculate-content.php');
		} else if (url.endsWith("leaderboard")) {
			// $("#calculate-link").trigger("click");
			// $("#result-calculate-link").trigger("click");
			viewLeaderboard();
		} else {
			// $("#home-link").trigger("click");
			loadPage('home-content.php');
		}
	});

	function viewLeaderboard() {
		$.ajax({
			type: "GET", // Use GET request
			url: "contents/leaderboard-content.php", // URL for products view
			dataType: "html", // Expect HTML response
			success: function (response) {
				$(".content").html(response); // Load the response into the content area

				// Initialize DataTable for product table
				var table = $("#table-products").DataTable({
					dom: "rtp", // Set DataTable options
					pageLength: 10, // Default page length
					ordering: false, // Disable ordering
				});

				// Bind custom input to DataTable search
				$("#custom-search").on("keyup", function () {
					table.search(this.value).draw(); // Search products based on input
				});

				// Bind change event for category filter
				$("#course-filter").on("change", function () {
				if (this.value !== "choose") {
					table.column(4).search(this.value).draw(); // Filter products by selected category
				}
				});

				$("#year-filter").on("change", function () {
					table.column(5).search(this.value).draw();
				});

				$("#period-filter").on("change", function () {
				if (this.value !== "choose") {
					table.column(6).search(this.value).draw(); // Filter products by selected category
				}
				});

				// Event listener for adding a product
				$("#add-product").on("click", function (e) {
					e.preventDefault(); // Prevent default behavior
					addProduct(); // Call function to add product
				});

				// Event listener for adding a product
				$(".edit-product").on("click", function (e) {
					e.preventDefault(); // Prevent default behavior
					editProduct(this.dataset.id); // Call function to add product
				});
			},
		});
	}


	function loadPage(page) {
		$.ajax({
			type: "GET",
			url: `contents/${page}`,
			dataType: "html",
			success: function (response) {
				$(".content").html(response);
			},
			error: function (xhr, status, error) {
				console.error("Error loading the page: ", error);
			}
		});
	}
	

	// Function to fetch product categories
	function calculateGWA() {
		$.ajax({
			url: "/cssc/server/student_calculate.php", // URL for fetching categories
			type: "POST", // Use GET request
			dataType: "json",
			success: function (data) {
				console.log("Success: ", data);
			},
			error: function (xhr, status, error) {
				console.error("Error calculating: ", error);
				return false;
			}
		});
	}

	// function leadboardLoad(course=null, year=null, period=null){
	// 	const selectedYear = year;
	// 	const selectedCourse = course;
	// 	const selectedPeriod = period;
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "/cssc/server/leaderboard_load.php",
	// 		data: { year_level: selectedYear, course: selectedCourse, submission_period: selectedPeriod },
	// 		dataType: "json",
	// 		success: function(response) {
	// 			console.log(response);
	// 		},
	// 		error: function(xhr, status, error) {
	// 			console.error("Error loading leaderboard: ", error);
	// 		}
	// 	});
	// }

	

	// ========== DUMPS ==========

	// $("#result-calculate-link").on("click", function (e) {
	// 	e.preventDefault();
	// 	loadPage('calculate-content.php');
	// });

	// $("#calculate-gwa").on("click", function (e) {
	// 	e.preventDefault();
	// 	alert("Clicked-calculate-gwa");
		
	// });

	// function loadCalculatePage(page) {
	// 	$.ajax({
	// 		type: "GET",
	// 		url: `${page}`,
	// 		dataType: "html",
	// 		success: function (response) {
	// 			$(".content").html(response);
	// 		},
	// 		error: function (xhr, status, error) {
	// 			console.error("Error loading the page: ", error);
	// 		}
	// 	});
	// }

	// function editProduct(productId) {
	// 	$.ajax({
	// 		type: "GET",
	// 		url: "../products/edit-product.html",
	// 		dataType: "html",
	// 		success: function (view) {
	// 			alert(productId);
	// 			fetchCategories(); // Load categories for the select input
	// 			fetchRecord(productId);
	// 			// Assuming 'view' contains the new content you want to display
	// 			$(".modal-container").empty().html(view); // Load the modal view
	// 			$("#staticBackdropedit").modal("show"); // Show the modal
	// 			$("#staticBackdropedit").attr("data-id", productId);

	// 			// Event listener for the add product form submission
	// 			$("#form-edit-product ").on("submit", function (e) {
	// 			e.preventDefault(); // Prevent default form submission
	// 			updateProduct(productId); // Call function to save product
	// 			});
	// 		},
	// 	});
	// }

	

	// function fetchRecord(productId) {
	// 	$.ajax({
	// 	url: `../products/fetch-product.php?id=${productId}`, // URL for fetching categories
	// 	type: "POST", // Use GET request
	// 	dataType: "json", // Expect JSON response
	// 	success: function (product) {
	// 		alert('success edit fetch');
	// 		$("#code").val(product.code);
	// 		$("#name").val(product.name);
	// 		$("#category").val(product.category_id).trigger("change"); // Set the selected category
	// 		$("#price").val(product.price);
	// 	},
	// 	});
	// }

	// // Function to show the add product modal
	// function addProduct() {
	// 	$.ajax({
	// 		type: "GET", // Use GET request
	// 		url: "../products/add-product.html", // URL for add product view
	// 		dataType: "html", // Expect HTML response
	// 		success: function (view) {
	// 			$(".modal-container").html(view); // Load the modal view
	// 			$("#staticBackdrop").modal("show"); // Show the modal

	// 			fetchCategories(); // Load categories for the select input

	// 			// Event listener for the add product form submission
	// 			$("#form-add-product").on("submit", function (e) {
	// 				e.preventDefault(); // Prevent default form submission
	// 				saveProduct(); // Call function to save product
	// 			});
	// 		},
	// 	});
	// }

	// // Function to save a new product
	// function saveProduct() {
	// 	$.ajax({
	// 		type: "POST", // Use POST request
	// 		url: "../products/add-product.php", // URL for saving product
	// 		data: $("form").serialize(), // Serialize the form data for submission
	// 		dataType: "json", // Expect JSON response
	// 		success: function (response) {
	// 			if (response.status === "error") {
	// 			// Handle validation errors
	// 			if (response.codeErr) {
	// 				$("#code").addClass("is-invalid"); // Mark field as invalid
	// 				$("#code").next(".invalid-feedback").text(response.codeErr).show(); // Show error message
	// 			} else {
	// 				$("#code").removeClass("is-invalid"); // Remove invalid class if no error
	// 			}
	// 			if (response.nameErr) {
	// 				$("#name").addClass("is-invalid");
	// 				$("#name").next(".invalid-feedback").text(response.nameErr).show();
	// 			} else {
	// 				$("#name").removeClass("is-invalid");
	// 			}
	// 			if (response.categoryErr) {
	// 				$("#category").addClass("is-invalid");
	// 				$("#category")
	// 				.next(".invalid-feedback")
	// 				.text(response.categoryErr)
	// 				.show();
	// 			} else {
	// 				$("#category").removeClass("is-invalid");
	// 			}
	// 			if (response.priceErr) {
	// 				$("#price").addClass("is-invalid");
	// 				$("#price")
	// 				.next(".invalid-feedback")
	// 				.text(response.priceErr)
	// 				.show();
	// 			} else {
	// 				$("#price").removeClass("is-invalid");
	// 			}
	// 			} else if (response.status === "success") {
	// 				// On success, hide modal and reset form
	// 				$("#staticBackdrop").modal("hide");
	// 				$("form")[0].reset(); // Reset the form
	// 				// Optionally, reload products to show new entry
	// 				viewProducts();
	// 			}
	// 		},
	// 	});
	// }

	// // Function to save a new product
	// function updateProduct(productId) {
	// 	$.ajax({
	// 		type: "POST", // Use POST request
	// 		url: `../products/update-product.php?id=${productId}`, // URL for saving product
	// 		data: $("form").serialize(), // Serialize the form data for submission
	// 		dataType: "json", // Expect JSON response
	// 		success: function (response) {
	// 			if (response.status === "error") {
	// 			// Handle validation errors
	// 			if (response.codeErr) {
	// 				$("#code").addClass("is-invalid"); // Mark field as invalid
	// 				$("#code").next(".invalid-feedback").text(response.codeErr).show(); // Show error message
	// 			} else {
	// 				$("#code").removeClass("is-invalid"); // Remove invalid class if no error
	// 			}
	// 			if (response.nameErr) {
	// 				$("#name").addClass("is-invalid");
	// 				$("#name").next(".invalid-feedback").text(response.nameErr).show();
	// 			} else {
	// 				$("#name").removeClass("is-invalid");
	// 			}
	// 			if (response.categoryErr) {
	// 				$("#category").addClass("is-invalid");
	// 				$("#category")
	// 				.next(".invalid-feedback")
	// 				.text(response.categoryErr)
	// 				.show();
	// 			} else {
	// 				$("#category").removeClass("is-invalid");
	// 			}
	// 			if (response.priceErr) {
	// 				$("#price").addClass("is-invalid");
	// 				$("#price")
	// 				.next(".invalid-feedback")
	// 				.text(response.priceErr)
	// 				.show();
	// 			} else {
	// 				$("#price").removeClass("is-invalid");
	// 			}
	// 			} else if (response.status === "success") {
	// 				// On success, hide modal and reset form
	// 				$("#staticBackdropedit").modal("hide");
	// 				$("form")[0].reset(); // Reset the form
	// 				// Optionally, reload products to show new entry
	// 				viewProducts();
	// 			}
	// 		},
	// 	});
	// }

	// $(window).on('popstate', function(event) {
	// 	if (event.originalEvent.state) {
	// 		var page = event.originalEvent.state.page;
	// 		console.log('Navigated to page: ' + page);
	// 		loadPage(page);
	// 	}
	// });

	// function loadHomePage() {
	// 	$.ajax({
	// 		type: "GET", // Use GET request
	// 		url: `home-main.php`, // URL for products view
	// 		dataType: "html", // Expect HTML response
	// 		success: function (response) {
	// 			$(".content").html(response);
	// 		},
	// 	});
	// }

	// function loadLeaderboardPage() {
	// 	$.ajax({
	// 		type: "GET", // Use GET request
	// 		url: `leaderboard-main.php`, // URL for products view
	// 		dataType: "html", // Expect HTML response
	// 		success: function (response) {
	// 			$(".content").html(response);
	// 		},
	// 	});
	// }

	// function loadAboutPage() {
	// 	$.ajax({
	// 		type: "GET", // Use GET request
	// 		url: `about-main.php`, // URL for products view
	// 		dataType: "html", // Expect HTML response
	// 		success: function (response) {
	// 			$(".content").html(response);
	// 		},
	// 	});
	// }
});

// document.addEventListener("DOMContentLoaded", function() {
//     const last_page = sessionStorage.getItem('last-page') || 'home.php';
//     loadPage(last_page);

//     // Logout functionality
//     document.getElementById('logout-button').addEventListener('click', function() {
//         sessionStorage.removeItem('last-page');
//     });
// });