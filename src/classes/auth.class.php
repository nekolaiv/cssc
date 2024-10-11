<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Auth{
    protected $database;

    public function __construct(){
        $this->database = new Database();
    }

    function __destruct(){
        $this->db->close();
        ob_end_flush();
    }

    function _loginUser() {
    extract($_POST);

    $user = $this->db->query("SELECT * FROM users WHERE username='$username'")->fetch_assoc();

    if (!$user) {
      header("location:" . $_SERVER["HTTP_REFERER"]."?status=false");
      return;
    }

    if (password_verify($_POST["password"], $user["password"])) {
      var_dump($user);
      $_SESSION["user_id"] = $user["user_id"];
      $_SESSION["is_loggedin"] = TRUE;
      $_SESSION["email"] = $user["email"];
      $_SESSION["fullname"] = $user["fullname"];
      $_SESSION["profile"] = $user["profile"];
      header("location: " . $_SERVER["HTTP_REFERER"]."/index.php?status=true");
    }  
    
  }


}

?>


