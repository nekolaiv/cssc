<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../classes/auth.class.php');
require_once('../tools/session.function.php');
require_once('../tools/clean.function.php');

generateCSRF();
regenerateSession();

$required = '*';
$email = $password = '';
$email_err = $password_err = ' ';
$auth = new Auth();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);

    if(empty($email)){
        $email_err = "email is required";
    } else if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')){
        $email_err = "invalid email - use @wmsu.edu.ph";
    }

    if(empty($password)){
        $password_err = "password is required";
    } else if(strlen($password) < 8){
        $password_err = "minimum 8 characters";
    }

    if($email_err == ' ' && $password_err == ' '){
        $login_status = $auth->login($email, $password);
        if($login_status === true){
            if($_SESSION['user-type'] === 'student'){
                echo '<script type="text/javascript">window.location.href = "../views/student/home";</script>';
            } else if($_SESSION['user-type'] === 'staff'){
                
                // echo the path or use header location depending on the structure of the code
            } else if($_SESSION['user-type'] === 'admin'){
                header("Location: ../views/admin/index.php");
                exit;
                // echo the path or use header location depending on the structure of the code
            }
        } else {
            $email_err = $login_status[0];
            $password_err = $login_status[1];
        }
    }
}

// if (isset($_SESSION["is_loggedIn"])) {
//   header("location: ./home.php");
//   exit;
// }

// extract($_SESSION);
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
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
        <?php if (!empty($_SESSION['feedback'])): ?><span class="success feedback"><?= $_SESSION['feedback'] ?></span><br><?php unset($_SESSION['feedback']); endif; ?>
        <label for="email">Email <span class="error"><?= $required ?></span></label>
        <input type="email" name="email" placeholder="email" id="email" value="<?php echo htmlspecialchars($email); ?>" tabindex="1">
        <?php if (!empty($email_err)): ?><span class="error auth-err"><?= $email_err ?></span><br><?php endif; ?>
        
        <div class="password-util">
            <label for="password">Password <span class="error"><?= $required ?></span></label>
            <a href="forgot-password.php" class="forgot-password">forgot password?</a>
            <!-- <button type="submit" class="forgot-password" name="form-action" value="forgot-password" tabindex="5">forgot password?</button> -->
        </div>
        
        <input type="password" name="password" placeholder="password" id="password" class="password" value="<?php echo htmlspecialchars($password); ?>"  tabindex="2" >

        <div class="below-input">
            <?php if (!empty($password_err)): ?>
                <span class="error auth-err"><?= $password_err ?></span><br>
            <?php endif; ?>
            <div class="show-password">
                <input class="showpassword-checkbox togglePassword" type="checkbox" onclick="myFunction()" tabindex="-1">
                <p>show password</p>
            </div>
        </div>

        <button type="submit" class="primary-button" name="form-action" value="attempt-login" tabindex="3">login</button>
        <a href="register.php"><button type="button" class="secondary-button" name="form-action" value="switch-to-register" tabindex="4">or register</button></a>
    </form>
    <script src="/cssc/js/auth_show-password.js"></script>
</body>
</html>
