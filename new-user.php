<?php
    require("connect-db.php");
    require("user-db.php");
    
ini_set('display_errors', 1);
error_reporting(E_ALL);

// This page is to allow new users to create their accounts given the relevant fields:
// account name, email, and password

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Check if passwords match
    if ($password == $confirm_password) {
        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // SQL to insert data
        newUser($username, $hash, $email);
        
        // Redirect to login page or somewhere appropriate
        header("Location: user-auth.php");
    } else {
        $error_message = "Passwords do not match, please try again";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <title>Login</title>
        <meta charset="utf-8">    
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Library</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Reading Lists</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="user-auth.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="new-user.php">Sign Up</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    <h2>Register</h2>
    <?php if (!empty($error_message)) { echo '<p class="error">' . $error_message . '</p>'; } ?>
    <form action="new-user.php" method="post">
        <p>
            <label for="username">Account Name:</label>
            <input type="text" name="username" id="username" required>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </p>
        <p>
            <input type="submit" value="Register">
        </p>
    </form>
</body>
</html>
