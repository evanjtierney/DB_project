<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'connect-db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: user-auth.php');
    exit;
}

$order = isset($_GET['sort']) ? $_GET['sort'] : 'accountName';
$dir = isset($_GET['dir']) && $_GET['dir'] === 'desc' ? 'DESC' : 'ASC';

// Validate sorting options
$validSorts = ['accountName', 'review_count'];
$order = in_array($order, $validSorts) ? $order : 'accountName';
$dir = in_array($dir, ['ASC', 'DESC']) ? $dir : 'ASC';

$query = $db->prepare("SELECT u.accountName, IFNULL(COUNT(r.accountName), 0) AS review_count 
                       FROM project_users u
                       LEFT JOIN project_reviews r ON u.accountName = r.accountName
                       GROUP BY u.accountName
                       ORDER BY $order $dir");
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users and Reviews</title>
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
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="users2.php">Users</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
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
        <h2>Users and their Review Counts</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="?sort=accountName&dir=<?php echo $dir === 'ASC' ? 'desc' : 'asc'; ?>">Account Name</a>
                    </th>
                    <th>
                        <a href="?sort=review_count&dir=<?php echo $dir === 'ASC' ? 'desc' : 'asc'; ?>">Number of Reviews</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['accountName']); ?></td>
                        <td><?php echo htmlspecialchars($user['review_count']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
