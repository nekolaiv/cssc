<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/classes/auth.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/clean.function.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cssc/tools/session.function.php');

generateCSRF();


$required = '*';
$email = $password = '';
$email_err = $password_err = '';
$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clean input data
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);

    // Validate email
    if (empty($email)) {
        $email_err = "Email is required";
    } else if (!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')) {
        $email_err = "Invalid email - use @wmsu.edu.ph";
    }

    // Validate password
    if (empty($password)) {
        $password_err = "Password is required";
    } else if (strlen($password) < 8) {
        $password_err = "Minimum 8 characters required";
    }

    // Process login if no validation errors
    if ($email_err === ' ' && $password_err === ' ') { // Spacing is used for show password to be pushed to the left
        $login_status = $auth->login($email, $password);
        if ($login_status === 'first login'){
            header("Location: set-password.php");
            exit;
        }
        if ($login_status === true) {
            // Redirect based on user role
            if ($_SESSION['profile']['user-role'] === 'user') {

                echo '<script type="text/javascript">window.location.href = "../views/student/home";</script>';
                exit;
            } else if ($_SESSION['user-role'] === 'staff') {
                header('Location: /cssc/views/staff/index.php');
                exit;
            } else if ($_SESSION['user-role'] === 'admin') {
                header("Location: ../views/admin/index.php");
                exit;
            }
        } else {
            // Handle login errors
            $email_err = $login_status[0];
            $password_err = $login_status[1];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="/cssc/css/global.css">
    <link rel="stylesheet" href="/cssc/css/auth.css">
</head>
<body>
    <form id="myForm" action="" method="POST">
        <h3>Login Form</h3>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <?php if (!empty($_SESSION['feedback'])): ?>
            <span class="success feedback"><?= $_SESSION['feedback'] ?></span><br>
            <?php unset($_SESSION['feedback']); ?>
        <?php endif; ?>

        <!-- Email Field -->
        <label for="email">Email <span class="error"><?= $required ?></span></label>
        <input type="email" name="email" placeholder="email" id="email" value="<?php echo htmlspecialchars($email); ?>" tabindex="1">
        <?php if (!empty($email_err)): ?><span class="error auth-err"><?= $email_err ?></span><br><?php endif; ?>

        <!-- Password Field -->
        <div class="password-util">
            <label for="password">Password <span class="error"><?= $required ?></span></label>
            <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
        </div>
        <input type="password" name="password" placeholder="password" id="password" class="password" value="<?php echo htmlspecialchars($password); ?>" tabindex="2">

        <!-- Show Password -->
        <div class="below-input">
            <?php if (!empty($password_err)): ?>
                <span class="error auth-err"><?= $password_err ?></span><br>
            <?php endif; ?>
            <div class="show-password">
                <input class="showpassword-checkbox togglePassword" type="checkbox" onclick="myFunction()" tabindex="-1">
                <p>Show password</p>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="primary-button" name="form-action" value="attempt-login" tabindex="3">Login</button>
    </form>
    <script src="/cssc/js/auth_show-password.js"></script>
</body>
</html>
