<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="/cssc/css/sidebar.css">
    <link rel="stylesheet" href="/cssc/css/admin_index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <?php require "../../includes/admin_sidebar.php"; ?>
        <div class="main">
            <!-- Green strip on top -->
            <div id="dashboard-header" class="bg-success text-white p-2 d-flex justify-content-between align-items-center">
                <div id="academic-details" class="px-3">
                    <span id="academic-year">Academic Year: Loading...</span> |
                    <span id="semester">Semester: Loading...</span>
                </div>
                <div id="datetime-details" class="px-3 text-end">
                    <span id="current-time"></span> |
                    <span id="current-date"></span>
                </div>
            </div>
            <div class="p-3">
                <div id="content">
                    <h1>Welcome to the Admin Dashboard</h1>
                    <p>Select an option from the sidebar to get started.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="/cssc/js/admin/admin_index.js"></script>
    <script src="/cssc/js/admin/admin_sidebar.js"></script>
</body>

</html>
