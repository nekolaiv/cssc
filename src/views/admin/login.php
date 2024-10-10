<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login Form</title>
    <link rel="stylesheet" href="/cssc/public/assets/css/global.css">
    <link rel="stylesheet" href="/cssc/public/assets/css/auth.css">
</head>
<body>
    <form action="" method="POST">
        <h3>Admin Account</h3>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <div class="password">
            <label for="password">Password</label>
            <a href="./forgot-password.php" class="forgot-password">forgot password?</a>
        </div>
        <input type="password" name="password" id="password" required>
        <button type="submit" class="primary-button">log in</button>
        <a href="./register.php"><button type="button" class="secondary-button">or register</button></a>
    </form>
</body>
</html>
