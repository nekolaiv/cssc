<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../../classes/auth.class.php");
require_once("../../helpers/clean.function.php");
require_once("../../helpers/session.function.php");

$required = '*';
$email = $new_password = $confirm_password = '';
$email_err = $new_password_err = $confirm_password_err = '';
$auth = new Auth();

extract($_SESSION);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = cleanInput($_POST['email']);
    $new_password = cleanInput($_POST['new-password']);
    $confirm_password = cleanInput($_POST['confirm-password']);

    if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')){
        $email_err = "invalid email - use @wmsu.edu.ph";
    } else if(!($auth->staffEmailExists($email))){
        $email_err = "email does not exist";
    }

    if(strlen($_POST['new-password']) < 8){
        $new_password_err = "password must be at least 8 characters long.";
    }

    if(!($_POST['new-password'] === $_POST['confirm-password'])){
        $confirm_password_err = "passwords do not match";
    }

    if($email_err == '' && $new_password_err == '' && $confirm_password_err == ''){
        if($auth->staffResetPassword($email, $new_password)){
            $_SESSION['feedback'] = 'reset password successful';
            header("Location: ./login.php");
            exit;
        } else {
            $new_password_err = 'new password is similar to old password';
        }
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Form</title>
    <link rel="stylesheet" href="/cssc/src/css/global.css">
    <link rel="stylesheet" href="/cssc/src/css/auth.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <form action="" method="POST">
        <h3>Reset Password</h3>
        <label for="email">Email <span class="error"><?= $required ?></span></label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <?php if (!empty($email_err)): ?><span class="error auth-err"><?= $email_err ?></span><br><?php endif; ?>
        
        <label for="new_password">New Password <span class="error"><?= $required ?></span></label>
        <div class="password-container">
            <input type="password" name="new-password" id="new-password" class="password" value="<?php echo htmlspecialchars($new_password); ?>" required>
            <span class="togglePassword toggle-eye"><i class="uil-eye-slash"></i></span>
        </div>
        
        <?php if (!empty($new_password_err)): ?>
            <span class="error auth-err"><?= $new_password_err ?></span><br>
        <?php endif; ?>

        <label for="confirm-password">Confirm Password <span class="error"><?= $required ?></span></label>
        <div class="password-container">
            <input type="password" name="confirm-password" id="confirm-password" class="password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
            <span class="togglePassword toggle-eye"><i class="uil-eye-slash"></i></span>
        </div>
        <?php if (!empty($confirm_password_err)): ?>
            <span class="error auth-err"><?= $confirm_password_err ?></span><br>
        <?php endif; ?>

        <button type="submit" class="primary-button">reset password</button>
        <a href="./login.php"><button type="button" class="secondary-button">cancel</button></a>
    </form>
    <script src="/cssc/src/js/script.js"></script>
</body>
</html>
