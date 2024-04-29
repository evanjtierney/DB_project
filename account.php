<?php
session_start();
require 'connect-db.php';
require 'user-db.php';

// Ensure that the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: user-auth.php');
    exit;
}

// Fetch user details from the database
$accountName = $_SESSION['accountName'];
$user_info = getUserByAccountName($accountName);

if (!$user_info) {
    exit('No user found with that account name.'); // In case no user is found
}

// Query to count the number of reviews
$review_query = "SELECT COUNT(*) FROM project_reviews WHERE accountName = :accountName";
$review_stmt = $db->prepare($review_query);
$review_stmt->bindValue(':accountName', $accountName);
$review_stmt->execute();
$review_count = $review_stmt->fetchColumn();

// Query to get the list of favorite genres
$genre_query = "SELECT genre FROM project_favoriteGenres WHERE accountName = :accountName";
$genre_stmt = $db->prepare($genre_query);
$genre_stmt->bindValue(':accountName', $accountName);
$genre_stmt->execute();
$favorite_genres = $genre_stmt->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Library</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="account.php">Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    <h1>Account Details</h1>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($accountName); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></p>
        <p><strong>Number of Reviews:</strong> <?php echo $review_count; ?></p>
        <p><strong>Favorite Genres:</strong></p>
        <ul>
            <?php foreach ($favorite_genres as $genre): ?>
                <li><?php echo htmlspecialchars($genre); ?></li>
            <?php endforeach; ?>
        </ul>    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
