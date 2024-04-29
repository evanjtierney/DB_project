<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

// Function to check if the account we want to follow exists
function doesAccountExist($accountName) {
    global $db;
    $query = "SELECT COUNT(*) FROM project_users WHERE accountName = :accountName";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':accountName', $accountName);
    $stmt->execute();
    $exists = $stmt->fetchColumn() > 0;
    $stmt->closeCursor();
    return $exists;
}

// Function to follow a user
function followUser($followingAccount, $followedAccount) {
    global $db;
    $query = "INSERT INTO project_accountsFollowed (followingAccount, followedAccount) VALUES (:followingAccount, :followedAccount)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':followingAccount', $followingAccount);
    $stmt->bindValue(':followedAccount', $followedAccount);
    $stmt->execute();
    $stmt->closeCursor();
}

// Function to unfollow a user
function unfollowUser($followingAccount, $followedAccount) {
    global $db;
    $query = "DELETE FROM project_accountsFollowed WHERE followingAccount = :followingAccount AND followedAccount = :followedAccount";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':followingAccount', $followingAccount);
    $stmt->bindValue(':followedAccount', $followedAccount);
    $stmt->execute();
    $stmt->closeCursor();
}

// Function to get list of followers
function getFollowers($accountName) {
    global $db;
    $query = "SELECT followingAccount FROM project_accountsFollowed WHERE followedAccount = :accountName";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':accountName', $accountName);
    $stmt->execute();
    $followers = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $followers;
}

function getFollowing($accountName) {
    global $db;
    $query = "SELECT followedAccount FROM project_accountsFollowed WHERE followingAccount = :accountName";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':accountName', $accountName);
    $stmt->execute();
    $following = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $following;
}


// Handle follow/unfollow form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['follow']) && !empty($_POST['username'])) {
        $usernameToFollow = trim($_POST['username']);
        if (doesAccountExist($usernameToFollow)) {
            followUser($accountName, $usernameToFollow);
        } else {
            echo "User does not exist.";
        }
    } elseif (isset($_POST['unfollow']) && !empty($_POST['username'])) {
        $usernameToUnfollow = trim($_POST['username']);
        unfollowUser($accountName, $usernameToUnfollow);
    }
}

$followers = getFollowers($accountName);
$following = getFollowing($accountName);

function getFavoriteGenres($accountName) {
    global $db;
    $query = "SELECT genre FROM project_favoriteGenres WHERE accountName = :accountName";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':accountName', $accountName);
    $stmt->execute();
    $genres = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $genres;
}

// Function to add a favorite genre for a user
function addFavoriteGenre($accountName, $genre) {
    global $db;
    $query = "INSERT INTO project_favoriteGenres (accountName, genre) VALUES (:accountName, :genre)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':accountName', $accountName);
    $stmt->bindValue(':genre', $genre);
    $stmt->execute();
    $stmt->closeCursor();
}

// Function to remove a favorite genre for a user
function removeFavoriteGenre($accountName, $genre) {
    global $db;
    $query = "DELETE FROM project_favoriteGenres WHERE accountName = :accountName AND genre = :genre";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':accountName', $accountName);
    $stmt->bindValue(':genre', $genre);
    $stmt->execute();
    $stmt->closeCursor();
}

// Handle POST request for adding or removing genres
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_genre']) && !empty($_POST['new_genre'])) {
        // Add the new genre
        addFavoriteGenre($accountName, $_POST['new_genre']);
    } elseif (isset($_POST['remove_genre']) && !empty($_POST['existing_genre'])) {
        // Remove the specified genre
        removeFavoriteGenre($accountName, $_POST['existing_genre']);
    }

    // Reload the favorite genres after modification
    $favorite_genres = getFavoriteGenres($accountName);
}


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
            <a class="nav-link" href="books.php">Books</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Find Friends</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account.php">Account</a>
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
        
        <!-- Section to display current favorite genres -->
        <h2>Current Favorite Genres:</h2>
        <ul>
            <?php foreach ($favorite_genres as $genre): ?>
                <li><?php echo htmlspecialchars($genre); ?></li>
            <?php endforeach; ?>
        </ul>

        <!-- Form to add a new favorite genre -->
        <form method="post" action="account.php" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="new_genre" name="new_genre" placeholder="Enter new favorite genre" required>
                <button type="submit" class="btn btn-success" name="add_genre">Add Genre</button>
            </div>
        </form>

        <!-- Form to remove an existing favorite genre -->
        <form method="post" action="account.php" class="mb-3">
            <div class="input-group">
                <select class="form-control" id="existing_genre" name="existing_genre" required>
                    <?php foreach ($favorite_genres as $genre): ?>
                        <option value="<?php echo htmlspecialchars($genre); ?>">
                            <?php echo htmlspecialchars($genre); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-danger" name="remove_genre">Remove Genre</button>
            </div>
        </form>
        </ul>

        <!-- List of users the current user is following -->
        <h2>Who You Follow:</h2>
        <ul>
            <?php foreach ($following as $followedAccount): ?>
                <li><?php echo htmlspecialchars($followedAccount); ?></li>
            <?php endforeach; ?>
        </ul>

        <!-- Form to follow a user -->
        <form method="post" action="account.php" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="username_to_follow" name="username" placeholder="Enter username to follow" required>
                <button type="submit" class="btn btn-success" name="follow">Follow</button>
            </div>
        </form>

        <!-- Form to unfollow a user -->
        <form method="post" action="account.php" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="username_to_unfollow" name="username" placeholder="Enter username to unfollow" required>
                <button type="submit" class="btn btn-danger" name="unfollow">Unfollow</button>
            </div>
        </form>

        <!-- List of followers -->
        <h2>Who Follows You:</h2>
        <ul>
            <?php foreach ($followers as $follower): ?>
                <li><?php echo htmlspecialchars($follower); ?></li>
            <?php endforeach; ?>
        </ul>
    

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
