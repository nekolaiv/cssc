$(document).ready(function () {

	const last_page = sessionStorage.getItem('last-page') || 'home.php';
    loadPage(last_page);

  	// Event listener for navigation links
	$(".nav-items").on("click", function (e) {
		e.preventDefault(); // Prevent default anchor click behavior
		$(".nav-items").removeClass("nav-active"); // Remove active class from all links
		$(this).addClass("nav-active"); // Add active class to the clicked link

		// let url = $(this).attr("href"); // Get the URL from the href attribute
		// console.log(url);
		// window.history.pushState({ path: url }, "", url); // Update the browser's URL without reloading
	});

	// Event listener for the dashboard link
	$("#home-link").on("click", function (e) {
		e.preventDefault(); // Prevent default behavior
		loadPage('home.php'); // Call the function to load analytics
	})

	// Event listener for the products link
	$("#leaderboard-link").on("click", function (e) {
		e.preventDefault(); // Prevent default behavior
		loadPage('leaderboard.php'); // Call the function to load products
	});

	// Event listener for the products link
	$("#about-link").on("click", function (e) {
		e.preventDefault(); // Prevent default behavior
		loadPage('about.php'); // Call the function to load products
	});

	// Determine which page to load based on the current URL
	let url = window.location.href;
	// if (url.endsWith("views/student/index.php")) {
	// 	$("#home-link").trigger("click");
	// }

	$(window).on('popstate', function(event) {
		if (event.originalEvent.state) {
			var page = event.originalEvent.state.page;
			console.log('Navigated to page: ' + page);
			// Optionally, update the page content or UI based on the page value
			loadPage(page);
		}
	});


	// Function to load products view
	function loadPage(page) {
		$.ajax({
			type: "GET", // Use GET request
			url: `../student/${page}`, // URL for products view
			dataType: "html", // Expect HTML response
			success: function (response) {
				$("#content").html(response); // Load the response into the content area
				sessionStorage.setItem('last-page', page);
            	history.pushState({ page: page }, '', page);
				// // Initialize DataTable for product table
				// var table = $("#table-products").DataTable({
				// 	dom: "rtp", // Set DataTable options
				// 	pageLength: 10, // Default page length
				// 	ordering: false, // Disable ordering
				// });

				// // Bind custom input to DataTable search
				// $("#custom-search").on("keyup", function () {
				// 	table.search(this.value).draw(); // Search products based on input
				// });

				// // Bind change event for category filter
				// $("#category-filter").on("change", function () {
				// if (this.value !== "choose") {
				// 	table.column(3).search(this.value).draw(); // Filter products by selected category
				// }
				// });

				// // Event listener for adding a product
				// $("#add-product").on("click", function (e) {
				// 	e.preventDefault(); // Prevent default behavior
				// 	addProduct(); // Call function to add product
				// });

				// // Event listener for adding a product
				// $(".edit-product").on("click", function (e) {
				// 	e.preventDefault(); // Prevent default behavior
				// 	editProduct(this.dataset.id); // Call function to add product
				// });
			},
		});
	}



	// Function to show the add product modal
	function editProduct(productId) {
		$.ajax({
			type: "GET", // Use GET request
			url: "../products/edit-product.html", // URL to get product data
			dataType: "html", // Expect JSON response
			success: function (view) {
				alert(productId);
				fetchCategories(); // Load categories for the select input
				fetchRecord(productId);
				// Assuming 'view' contains the new content you want to display
				$(".modal-container").empty().html(view); // Load the modal view
				$("#staticBackdropedit").modal("show"); // Show the modal
				$("#staticBackdropedit").attr("data-id", productId);

				// Event listener for the add product form submission
				$("#form-edit-product ").on("submit", function (e) {
				e.preventDefault(); // Prevent default form submission
				updateProduct(productId); // Call function to save product
				});
			},
		});
	}

	// Function to fetch product categories
	function fetchCategories() {
		$.ajax({
			url: "../products/fetch-categories.php", // URL for fetching categories
			type: "GET", // Use GET request
			dataType: "json", // Expect JSON response
			success: function (data) {
				// Clear existing options and add a default "Select" option
				$("#category").empty().append('<option value="">--Select--</option>');

				// Append each category to the select dropdown
				$.each(data, function (index, category) {
				$("#category").append(
					$("<option>", {
					value: category.id, // Value attribute
					text: category.name, // Displayed text
					})
				);
				});
			},
		});
	}

	function fetchRecord(productId) {
		$.ajax({
		url: `../products/fetch-product.php?id=${productId}`, // URL for fetching categories
		type: "POST", // Use GET request
		dataType: "json", // Expect JSON response
		success: function (product) {
			alert('success edit fetch');
			$("#code").val(product.code);
			$("#name").val(product.name);
			$("#category").val(product.category_id).trigger("change"); // Set the selected category
			$("#price").val(product.price);
		},
		});
	}

	// Function to show the add product modal
	function addProduct() {
		$.ajax({
			type: "GET", // Use GET request
			url: "../products/add-product.html", // URL for add product view
			dataType: "html", // Expect HTML response
			success: function (view) {
				$(".modal-container").html(view); // Load the modal view
				$("#staticBackdrop").modal("show"); // Show the modal

				fetchCategories(); // Load categories for the select input

				// Event listener for the add product form submission
				$("#form-add-product").on("submit", function (e) {
					e.preventDefault(); // Prevent default form submission
					saveProduct(); // Call function to save product
				});
			},
		});
	}

	// Function to save a new product
	function saveProduct() {
		$.ajax({
			type: "POST", // Use POST request
			url: "../products/add-product.php", // URL for saving product
			data: $("form").serialize(), // Serialize the form data for submission
			dataType: "json", // Expect JSON response
			success: function (response) {
				if (response.status === "error") {
				// Handle validation errors
				if (response.codeErr) {
					$("#code").addClass("is-invalid"); // Mark field as invalid
					$("#code").next(".invalid-feedback").text(response.codeErr).show(); // Show error message
				} else {
					$("#code").removeClass("is-invalid"); // Remove invalid class if no error
				}
				if (response.nameErr) {
					$("#name").addClass("is-invalid");
					$("#name").next(".invalid-feedback").text(response.nameErr).show();
				} else {
					$("#name").removeClass("is-invalid");
				}
				if (response.categoryErr) {
					$("#category").addClass("is-invalid");
					$("#category")
					.next(".invalid-feedback")
					.text(response.categoryErr)
					.show();
				} else {
					$("#category").removeClass("is-invalid");
				}
				if (response.priceErr) {
					$("#price").addClass("is-invalid");
					$("#price")
					.next(".invalid-feedback")
					.text(response.priceErr)
					.show();
				} else {
					$("#price").removeClass("is-invalid");
				}
				} else if (response.status === "success") {
					// On success, hide modal and reset form
					$("#staticBackdrop").modal("hide");
					$("form")[0].reset(); // Reset the form
					// Optionally, reload products to show new entry
					viewProducts();
				}
			},
		});
	}

	// Function to save a new product
	function updateProduct(productId) {
		$.ajax({
			type: "POST", // Use POST request
			url: `../products/update-product.php?id=${productId}`, // URL for saving product
			data: $("form").serialize(), // Serialize the form data for submission
			dataType: "json", // Expect JSON response
			success: function (response) {
				if (response.status === "error") {
				// Handle validation errors
				if (response.codeErr) {
					$("#code").addClass("is-invalid"); // Mark field as invalid
					$("#code").next(".invalid-feedback").text(response.codeErr).show(); // Show error message
				} else {
					$("#code").removeClass("is-invalid"); // Remove invalid class if no error
				}
				if (response.nameErr) {
					$("#name").addClass("is-invalid");
					$("#name").next(".invalid-feedback").text(response.nameErr).show();
				} else {
					$("#name").removeClass("is-invalid");
				}
				if (response.categoryErr) {
					$("#category").addClass("is-invalid");
					$("#category")
					.next(".invalid-feedback")
					.text(response.categoryErr)
					.show();
				} else {
					$("#category").removeClass("is-invalid");
				}
				if (response.priceErr) {
					$("#price").addClass("is-invalid");
					$("#price")
					.next(".invalid-feedback")
					.text(response.priceErr)
					.show();
				} else {
					$("#price").removeClass("is-invalid");
				}
				} else if (response.status === "success") {
					// On success, hide modal and reset form
					$("#staticBackdropedit").modal("hide");
					$("form")[0].reset(); // Reset the form
					// Optionally, reload products to show new entry
					viewProducts();
				}
			},
		});
	}
});
