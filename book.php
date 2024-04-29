<?php
require("connect-db.php");
require("books-db.php");
session_start();
$_SESSION['last_visited'] = basename($_SERVER['PHP_SELF']);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $isbn = $_POST['isbn'];
}
$book_info = getBookInfo($isbn);
$authors = getAuthors($isbn);
$genres = getGenres($isbn);
$links = getPurchaseLinks($isbn);
$ratingInfo = getRatingInfo($isbn);
// var_dump($book_info);
// var_dump($authors);
// var_dump($genres);
// var_dump($links);
// var_dump($ratingInfo);
?>

<!DOCTYPE html>
<html>
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
                            <a class="nav-link active" aria-current="page" href="books.php">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reading-list.php">Reading Lists</a>
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
            if($ratingInfo['numRatings'] == 1)
            { 
                echo round($ratingInfo['avgRating'], 2) . " stars | " . $ratingInfo['numRatings'] . " rating"; 
            }
            else if($ratingInfo['numRatings'] > 1)
            { 
                echo round($ratingInfo['avgRating'], 2) . " stars | " . $ratingInfo['numRatings'] . " ratings"; 
            }
            else
            {
                echo "No reviews";
            }
            ?>
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


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>