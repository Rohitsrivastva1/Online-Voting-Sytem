
<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['username']))
{
    header("location: welcome.php");
    exit;
}
require_once "config.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username + password";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }


if(empty($err))
{
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    
    
    // Try to execute this statement
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // this means the password is corrct. Allow user to login
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;

                            //Redirect user to welcome page
                            header("location: welcome.php");
                            
                        }
                    }

                }

    }
}    


}


?>









<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="admin-style.css">

    <title>Bu Online Voting System</title>
  </head>
  <body>
  
  <div class="background-wrap">
  <div class="background"></div>
</div>


<form id="accesspanel" action="login" method="post">
  <h1 id="litheader">Bundelkhand University </h1>
  <h3 id="litheader">Online Voting System</h3>
  <div class="inset">
    <p>
      <input type="text" name="username" id="email" placeholder="Email address">
    </p>
    <p>
      <input type="password" name="password" id="password" placeholder="Access code">
    </p>
    <div style="text-align: center;">
      <div class="checkboxouter">
        <input type="checkbox" name="rememberme" id="remember" value="Remember">
        <label class="checkbox"></label>
      </div>
      <label for="remember">Remember me for 14 days</label>
    </div>
    <input class="loginLoginValue" type="hidden" name="service" value="login" />
  </div>
  <p class="p-container">
    <input type="submit" name="Login" id="go" value="Authorize">
  </p>
</form>


   <script>

$(document).ready(function() {

var state = false;

//$("input:text:visible:first").focus();

$('#accesspanel').on('submit', function(e) {

    e.preventDefault();

    state = !state;

    if (state) {
        document.getElementById("litheader").className = "poweron";
        document.getElementById("go").className = "";
        document.getElementById("go").value = "Initializing...";
    }else{
        document.getElementById("litheader").className = "";
        document.getElementById("go").className = "denied";
        document.getElementById("go").value = "Access Denied";
    }

});

});
   </script>


  </body>
</html>