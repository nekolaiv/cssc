<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../../classes/auth.class.php");
require_once("../../helpers/clean.function.php");
require_once("../../helpers/session.function.php");

$required = '*';
$email = $password = $confirm_password = '';
$email_err = $password_err = $confirm_password_err = '';
$auth = new Auth();


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);
    $confirm_password = cleanInput($_POST['confirm-password']);

    if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')){
        $email_err = "invalid email - use @wmsu.edu.ph";
    } else if($auth->emailExists($email)){
        $email_err = "email already exists";
    }

    if(strlen($_POST['password']) < 8){
        $password_err = "password must be at least 8 characters long.";
    }

    if(!($_POST['password'] === $_POST['confirm-password'])){
        $confirm_password_err = "passwords do not match";
    }

    if($email_err == '' && $password_err == '' && $confirm_password_err == ''){
        if($auth->studentRegister($email, $password)){
            $_SESSION['feedback'] = 'account registered successfully';
            header("Location: ./login.php");
            exit;
        } else {
            echo "
                <script type='text/javascript'>
                    alert('Something went wrong. Please try again.');
                </script>";
            exit;
        }   
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login Form</title>
    <link rel="stylesheet" href="/cssc/public/assets/css/global.css">
    <link rel="stylesheet" href="/cssc/public/assets/css/auth.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <form action="" method="POST">
        <h3>Register Student Account</h3>
        <label for="email">Email <span class="error"><?= $required ?></span></label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <?php if (!empty($email_err)): ?><span class="error auth-err"><?= $email_err ?></span><br><?php endif; ?>

        <label for="password">Password <span class="error"><?= $required ?></span></label>
        <div class="password-container">
            <input type="password" name="password" id="password" class="password" value="<?php echo htmlspecialchars($password); ?>" required>
            <span id="togglePassword" class="toggle-eye"><i class="uil-eye-slash"></i></span>
        </div>
        <?php if (!empty($password_err)): ?>
            <span class="error auth-err"><?= $password_err ?></span><br>
        <?php endif; ?>

        <label for="confirm-password">Confirm Password <span class="error"><?= $required ?></span></label>
        <div class="password-container">
            <input type="password" name="confirm-password" id="confirm-password" class="password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
            <span class="togglePassword toggle-eye"><i class="uil-eye-slash"></i></span>
        </div>
        <?php if (!empty($confirm_password_err)): ?>
            <span class="error auth-err"><?= $confirm_password_err ?></span><br>
        <?php endif; ?>

        <button type="submit" class="primary-button">register</button>
        <a href="./login.php"><button type="button" class="secondary-button">login instead</button></a>
    </form>
    <script src="/cssc/src/js/script.js"></script>
</body>
</html>
