<?php
include 'connect-db.php';  // Connect to your database
include 'user-db.php';     // Include user database functions

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: user-auth.php'); // Redirect to login page if not logged in
    exit();
}

$accountName = $_SESSION['username'];
$creationResult = null; // To store result of creation attempt

// Handle form submission for creating a new list
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newListName'])) {
    $listName = $_POST['newListName'];
    if (createReadingList($accountName, $listName)) {
        $creationResult = "List created successfully!";
    } else {
        $creationResult = "Failed to create list.";
    }
}

// Fetch existing lists
$lists = getUserReadingLists($accountName);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reading Lists</title>
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
                        <a class="nav-link" href="reading-list.php">Reading Lists</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>Your Reading Lists</h1>
    <?php if ($creationResult): ?>
        <p><?= htmlspecialchars($creationResult) ?></p>
    <?php endif; ?>
    <ul>
        <?php foreach ($lists as $list): ?>
            <li><?= htmlspecialchars($list['listName']) ?></li>
            <!-- Add more functionality like edit, delete here -->
        <?php endforeach; ?>
    </ul>

    <h2>Add a new reading list</h2>
    <form method="post">
        <input type="text" name="newListName" required>
        <button type="submit">Create List</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
