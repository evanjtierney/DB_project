<?php
require("connect-db.php");
require("user-db.php")
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); 
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier']); 
    $password = trim($_POST['password']); 

    $user_info = getUsernamePassword($identifier);

    // Check if any user was found
    if (!empty($user_info)) {
        $accountName = $user_info[0]['accountName'];
        $user_password = $user_info[0]['password'];

        // Verify the password with the hashed password
        if (password_verify($password, $user_password)) {
            // Password is correct, so start a new session
            $_SESSION["loggedin"] = true;
            $_SESSION["accountName"] = $accountName; // Update session with user's account name
            header("location: books.php"); // Redirect user to welcome page
            exit;
        } else {
            // password not valid
            $login_err = "Invalid username or password. NONEMPTY RESULT";
        }
    } else {
        // no username
        $login_err = "Invalid username or password.";
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

    <div>
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username / Email</label>
                <input type="text" name="identifier" required>
            </div>    
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
        <p>Don't have an account? <a href="new-user.php">Sign up here</a></p>
    </div>    
</body>
</html>
