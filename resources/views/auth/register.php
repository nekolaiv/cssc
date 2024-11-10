<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// require_once("../../../src/classes/auth.class.php");
// require_once("../../../src/utils/clean.function.php");
// require_once("../../../src/utils/session.function.php");

// use Src\Classes\Auth;

// $required = '*';
// $email = $password = $confirm_password = '';
// $email_err = $password_err = $confirm_password_err = ' ';
// $auth = new Auth();


// if($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $email = cleanInput($_POST['email']);
//     $password = cleanInput($_POST['password']);
//     $confirm_password = cleanInput($_POST['confirm-password']);

//     if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -12) === '@wmsu.edu.ph')){
//         $email_err = "invalid email - use @wmsu.edu.ph";
//     } else if($auth->studentEmailExists($email)){
//         $email_err = "email already exists";
//     }

//     if(strlen($_POST['password']) < 8){
//         $password_err = "minimum 8 characters";
//     }

//     if(!($_POST['password'] === $_POST['confirm-password'])){
//         $confirm_password_err = "passwords do not match";
//     }

//     if($email_err == ' ' && $password_err == ' ' && $confirm_password_err == ' '){
//         if($auth->studentRegister($email, $password)){
//             $_SESSION['feedback'] = 'account registered successfully';
//             header("Location: ./login.php");
//             exit;
//         } else {
//             echo "
//                 <script type='text/javascript'>
//                     alert('Something went wrong. Please try again.');
//                 </script>";
//             exit;
//         }   
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="/cssc/resources/css/global.css">
    <link rel="stylesheet" href="/cssc/resources/css/auth.css">
</head>
<body>
    <form id="myForm" action="" method="POST">
        <h3>Register Student Account</h3>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="email">Email <span class="error"><?= $required ?></span></label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
        <?php if (!empty($email_err)): ?><span class="error auth-err"><?= $email_err ?></span><br><?php endif; ?>

        <label for="password">Password <span class="error"><?= $required ?></span></label>
        <input type="password" name="password" id="password" class="password" value="<?php echo htmlspecialchars($password); ?>">
        <div class="below-input">
            <?php if (!empty($password_err)): ?>
                <span class="error auth-err"><?= $password_err ?></span><br>
            <?php endif; ?>
            <div class="show-password">
                <input class="showpassword-checkbox togglePassword" type="checkbox" onclick="myFunction()" tabindex="-1">
                <p>show password</p>
            </div>
        </div>

        <label for="confirm-password">Confirm Password <span class="error"><?= $required ?></span></label>
        <input type="password" name="confirm-password" id="confirm-password" class="password" value="<?php echo htmlspecialchars($confirm_password); ?>">
        <div class="below-input">
            <?php if (!empty($confirm_password_err)): ?>
                <span class="error auth-err"><?= $confirm_password_err ?></span><br>
            <?php endif; ?>
            <div class="show-password">
                <input class="showpassword-checkbox togglePassword" type="checkbox" onclick="myFunction()" tabindex="-1">
                <p>show password</p>
            </div>
        </div>

        <button type="submit" class="primary-button" name="form-action" value="attempt-register">register</button>
        <button type="submit" class="secondary-button" name="form-action" value="switch-to-login">login instead</button>
    </form>
    <script src="/cssc/resources/js/show-password.js"></script>
    <script>
    document.getElementById('myForm').onsubmit = function() {
        history.pushState(null, '', location.href); // Prevent back navigation
    };
    </script>
</body>
</html>
