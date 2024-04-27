<?php

// This page is to allow new users to create their accounts given the relevant fields:
// account name, email, and password

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("connect-db.php");

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
        $query = "INSERT INTO project_users (username, password, email, creation_time) VALUES (:username, :hashed_password, :email, NOW())";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':hashed_password', $hash);
        $statement->execute();
        $statement->closeCursor();
        
        // Redirect to login page or somewhere appropriate
        header("Location: user-auth.php");
    } else {
        $error_message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <?php if (!empty($error_message)) { echo '<p class="error">' . $error_message . '</p>'; } ?>
    <form action="register.php" method="post">
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
