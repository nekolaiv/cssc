<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Login Form</title>
    <link rel="stylesheet" href="/cssc/public/assets/css/global.css">
    <link rel="stylesheet" href="/cssc/public/assets/css/auth.css">
</head>
<body>
    <form action="" method="POST">
        <h3>Student Account</h3>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">New Password</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm-password">Confirm Password</label>
        <input type="password" name="confirm-password" id="confirm-password" required>

        <button type="submit" class="primary-button">reset password</button>
        <a href="./login.php"><button type="button" class="secondary-button">cancel</button></a>
    </form>
</body>
</html>
