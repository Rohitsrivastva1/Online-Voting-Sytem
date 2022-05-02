<?php
require_once "config.php";

$rollNo = $password = $confirm_password = "";
$rollNo_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if rollNo is empty
    if(empty(trim($_POST["rollNo"]))){
        $rollNo_err = "rollNo cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE rollNo = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_rollNo);

            // Set the value of param rollNo
            $param_rollNo = trim($_POST['rollNo']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $rollNo_err = "This rollNo is already taken"; 
                }
                else{
                    $rollNo = trim($_POST['rollNo']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
// if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
//     $password_err = "Passwords should match";
// }


// If there were no errors, go ahead and insert into the database
if(empty($rollNo_err) && empty($password_err))
{
    $sql = "INSERT INTO users (rollNo, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_rollNo, $param_password);

        // Set these parameters
        $param_rollNo = $rollNo;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>







<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="style.css">

    <title>Bu Online Voting System</title>
  </head>
  <body>
  
  <h2>Bu </h2>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action=" " method="post">
			<h1>Create Account</h1>
			<!-- <div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div> -->
			<span>or use your email for registration</span>
			<input type="text" placeholder="Name" name ="name"  />
			<input type="text" placeholder="Rollno" name ="rollNo" />
			<input type="email" placeholder="Email" name ="email"  />
			<input type="password" placeholder="Password"  name ="password" />
			<button>Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="#">
			<h1>Sign in</h1>
			<!-- <div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div> -->
			<!-- <span>or use your account</span> -->
			<input type="text" placeholder="Rollno" name ="rollNo" />
			<input type="password" placeholder="Password" name ="password" />
			<a href="#">Forgot your password?</a>
			<button>Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>Please login with your personal info For Voting</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1>
				<p>Enter your personal details and start Voting</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
    
   <script src="main.js"></script>

  </body>
</html>