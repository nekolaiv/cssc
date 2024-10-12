<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$email = $new_password = $confirm_password = '';
$email_required = $new_password_required = $confirm_password_required = '*';
$email_err = $new_password_err = $confirm_password_err = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    if($_POST['new-password'] == $_POST['confirm-password']){
        
    } else {
        $confirm_passwordErr = "Passwords do not match!";
    }
}

?>


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
        <label for="email">Email
            <?php if (!empty($emailRequired)): ?>
                <span class="error"><?= $emailRequired ?></span><br>
            <?php endif; ?>
        </label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="new_password">New Password
            <?php if (!empty($new_passwordRequired)): ?>
                <span class="error"><?= $new_passwordRequired ?></span><br>
            <?php endif; ?>
        </label>
        <input type="password" name="new-password" id="new-password" value="<?php echo htmlspecialchars($new_password); ?>" required>

        <label for="confirm-password">Confirm Password
            <?php if (!empty($confirm_passwordRequired)): ?>
                <span class="error"><?= $confirm_passwordRequired ?></span><br>
            <?php endif; ?>
        </label>
        <input type="password" name="confirm-password" id="confirm-password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
        <?php if (!empty($confirm_passwordErr)): ?>
            <span class="error" id="confirm-passwordErr"><?= $confirm_passwordErr ?></span><br>
        <?php endif; ?>

        <button type="submit" class="primary-button">reset password</button>
        <a href="./login.php"><button type="button" class="secondary-button">cancel</button></a>
    </form>
</body>
</html>
