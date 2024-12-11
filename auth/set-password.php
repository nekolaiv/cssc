<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/student.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/auth.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');

$required = '*';
$email = $new_password = $confirm_password = '';
$email_err = $new_password_err = $confirm_password_err = ' ';
$auth = new Auth();

extract($_SESSION);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = cleanInput($_POST['email']);
    $new_password = cleanInput($_POST['new-password']);
    $confirm_password = cleanInput($_POST['confirm-password']);

    if(empty($email)){
        $email_err = "email is required";
    } else if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')){
        $email_err = "invalid email - use @wmsu.edu.ph";
    }

    if(empty($new_password)){
        $new_password_err = "password is required";
    } else if(strlen($_POST['new-password']) < 8){
        $new_password_err = "minimum 8 characters";
    }

    if(empty($confirm_password)){
        $confirm_password_err = "password is required";
    } else if(!($new_password === $confirm_password)){
        $new_password_err = "passwords do not match";
        $confirm_password_err = "passwords do not match";
    }

    if($email_err == ' ' && $new_password_err == ' ' && $confirm_password_err == ' '){
        $reset_status = $auth->resetPassword($email, $new_password);
        if($reset_status === true){
            $_SESSION['feedback'] = 'success! login with your new password';
            header("Location: login.php");
            exit;
        } else {
            $email_err = $reset_status[0];
            $new_password_err = $reset_status[1];
        }
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password Form</title>
    <link rel="stylesheet" href="/cssc/css/global.css">
    <link rel="stylesheet" href="/cssc/css/auth.css">
</head>
<body>
    <form id="myForm" action="" method="POST">
        <h3>Set New Password</h3><br>
        <p>Kindly set a new secured password</p>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="email">Email <span class="error"><?= $required ?></span></label>
        <input type="email" name="email" placeholder="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
        <?php if (!empty($email_err)): ?><span class="error auth-err"><?= $email_err ?></span><br><?php endif; ?>
        
        <label for="new-password">New Password <span class="error"><?= $required ?></span></label>
        <input type="password" name="new-password" placeholder="new password" id="new-password" class="password" value="<?php echo htmlspecialchars($new_password); ?>">
        <div class="below-input">
            <?php if (!empty($new_password_err)): ?>
                <span class="error auth-err"><?= $new_password_err ?></span><br>
            <?php endif; ?>
            <div class="show-password">
                <input class="showpassword-checkbox togglePassword" type="checkbox" onclick="myFunction()" tabindex="-1">
                <p>show password</p>
            </div>
        </div>


        <label for="confirm-password">Confirm Password <span class="error"><?= $required ?></span></label>
        <input type="password" name="confirm-password" placeholder="confirm password" id="confirm-password" class="password" value="<?php echo htmlspecialchars($confirm_password); ?>">
        <div class="below-input">
            <?php if (!empty($confirm_password_err)): ?>
                <span class="error auth-err"><?= $confirm_password_err ?></span><br>
            <?php endif; ?>
            <div class="show-password">
                <input class="showpassword-checkbox togglePassword" type="checkbox" onclick="myFunction()" tabindex="-1">
                <p>show password</p>
            </div>
        </div>


        <button type="submit" class="primary-button" name="form-action" value="attempt-reset-password">reset password</button>
        <!-- <a href="login.php"><button type="button" class="secondary-button" name="form-action" value="switch-to-login">cancel</button></a> -->
    </form>
    <script src="/cssc/js/auth_show-password.js"></script>
</body>
</html>
