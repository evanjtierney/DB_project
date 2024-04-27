<?php
session_start(); // Start a new session

require("connect-db.php"); // Database connection file

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier']); // Username or Email
    $password = trim($_POST['password']); // Password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare a select statement (uses password and password hash in case)
    $sql = "SELECT username, password FROM users WHERE (username = :identifier OR email = :identifier) AND (password = :password OR password = :password_hash)";
    
    if ($stmt = $db->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":identifier", $identifier, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()) {
            if($stmt->rowCount() == 1) {
                // Password is correct, so start a new session and
                // Save the username to the session
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $identifier; // Update session with user's identifier
                header("location: welcome.php"); // Redirect user to welcome page
            } else {
                // Display an error message if username doesn't exist
                $login_err = "Invalid username or password.";
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->closeCursor();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Assuming you have a CSS file for styling -->
</head>
<body>
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
