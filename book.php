<?php
session_start();
require("connect-db.php");
require("books-db.php");
require("reviews-db.php");
require("user-db.php");
?>

<?php
// Ensure that the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: user-auth.php');
    exit;
}
$_SESSION['last_visited'] = basename($_SERVER['PHP_SELF']);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $isbn = $_POST['isbn'];
    $accountName = $_SESSION['accountName'];
    $readingLists = getUserReadingLists($accountName);
    if (!empty($_POST['submitReviewBtn']))
    {
        addReview($accountName, $isbn, $_POST['content'], $_POST['rating'], date("Y-m-d"));
    }
    else if (!empty($_POST['addToList']))
    {
        addBookToReadingList($accountName, $_POST['readingList'], $isbn);
    }
}
$book_info = getBookInfo($isbn);
$authors = getAuthors($isbn);
$genres = getGenres($isbn);
$links = getPurchaseLinks($isbn);
$ratingInfo = getRatingInfo($isbn);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Book Info</title>
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
                        <a class="nav-link active" aria-current="page" href="books.php">Browse</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reading Lists</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="books.php">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reading-list.php">Reading Lists</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="account.php">Account</a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user-auth.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="new-user.php">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

        <h3><em><?php echo $book_info['seriesTitle']; ?></em></h3>
        <h1><b><?php echo $book_info['title']; ?></b></h1>
        <br>
        <h3>By <?php for ($i=0; $i<count($authors); $i++)
        {
            echo $authors[$i]['author'];
            if ($i < count($authors) - 1) {
                echo ", ";
            }
        } 
        ?></h3>
        <h4>
           <?php 
            if($ratingInfo['numRatings'] > 0):
                echo round($ratingInfo['avgRating'], 2) . " stars"; ?>
                <form method="post" action="reviews.php" class="in-line">
                    <input type="submit" value="<?php echo $ratingInfo['numRatings'] . " reviews"; ?>" name="viewRatings" 
                                        class="link-button" />
                    <input type="hidden" name="isbn" value="<?php echo $isbn; ?>" />
                </form>
            <?php else: ?>
                No reviews
                <form method="post" action="write-review.php" class="in-line">
                    <input type="submit" value="Write a review" name="writeReview" 
                        class="link-button" />
                    <input type="hidden" name="isbn" value="<?php echo $isbn; ?>" />
                </form>
            <?php endif; ?>
        </h4>
        <br>
        <p>Genres: <?php for ($i=0; $i<count($genres); $i++)
        {
            echo $genres[$i]['genre'];
            if ($i < count($genres) - 1) {
                echo ", ";
            }
        } 
        ?></p>
        <p>Page count: <?php echo $book_info['pageCount']; ?></p>
        <p>First published: <?php echo $book_info['pubDate']; ?></p>
        <p>ISBN: <?php echo $book_info['isbn']; ?></p>
        <br>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="purchaseDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Purchase
            </button>
            <ul class="dropdown-menu" aria-labelledby="purchaseDropdown">
                <?php foreach ($links as $link): ?>
                    <li><a class="dropdown-item" href="<?php echo $link['link']; ?>"><?php echo $link['link']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <br>

        <!-- Add to reading list -->
        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
        <div class="input-group mb-3 w-50">
            <select class='form-select' id='readingList' name='readingList'>
              <option selected></option>
              <?php if (!is_null($readingLists)):
                    foreach ($readingLists as $readingList): ?>
                    <option value="<?php echo $readingList['listName']; ?>"><?php echo $readingList['listName']; ?></option>
                    <?php endforeach; 
                endif; ?>
            </select>
            <input type="submit" value="Add to reading list" id="addToList" name="addToList" class="btn btn-dark"
                title="Add to reading list" />
            <input type="hidden" name="isbn" value="<?php echo $isbn; ?>" />
        </div>
        </form>
        <!-- <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
            <input list="readingLists">
            <datalist id="readingLists">
                <?php if (!is_null($readingLists)):
                    foreach ($readingLists as $readingList): ?>
                    <option value="<?= $readingList ?>">
                    <?php endforeach; 
                endif; ?>
            </datalist>
        </form> -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>