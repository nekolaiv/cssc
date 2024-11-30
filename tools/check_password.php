<?php
require_once '../classes/_admin.class.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Verification Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Password Verification Tool</h1>
    <form id="passwordForm" method="POST" action="">
        <div class="mb-3">
            <label for="plainPassword" class="form-label">Enter Plain Password:</label>
            <input type="text" class="form-control" id="plainPassword" name="plainPassword" required>
        </div>
        <div class="mb-3">
            <label for="hashedPassword" class="form-label">Enter Hashed Password:</label>
            <input type="text" class="form-control" id="hashedPassword" name="hashedPassword" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify Password</button>
    </form>

    <div class="mt-4">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $admin = new Admin(); // Assuming the Admin class is properly included and initialized

            $plainPassword = $_POST['plainPassword'];
            $hashedPassword = $_POST['hashedPassword'];

            if ($admin->verifyPassword($plainPassword, $hashedPassword)) {
                echo "<div class='alert alert-success'>Password matches!</div>";
            } else {
                echo "<div class='alert alert-danger'>Password does not match.</div>";
            }
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
