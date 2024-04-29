<?php
require("connect-db.php");
require("books-db.php");
require("reviews-db.php");
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $isbn = $_POST['isbn'];
    $list_of_reviews = getAllReviews($isbn);
    $book_info = getBookInfo($isbn);
    if (!empty($_POST['sortRecentBtn']))
    {
        $list_of_reviews = sortByReviewDate($isbn);
    }
    else if (!empty($_POST['sortAscBtn']))
    {
        $list_of_reviews = sortAscRating($isbn);
    }
    else if (!empty($_POST['sortDescBtn']))
    {
        $list_of_reviews = sortDescRating($isbn);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reviews</title>
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
            <span class="navbar-brand mb-0 h1">Library</span>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://www.cs.virginia.edu/~ejt7yqz/DB_project/books.php">Browse</a> <!-- note this link will be different for different devs -->
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

        <h3>Reviews for <?php echo $book_info['title']; ?></h3>

        <!-- "Write a review" button -->
        <form action="write-review.php" method="post"> 
            <input type="submit" value="Write a review" name="writeReviewBtn" 
                    class="btn btn-primary" /> 
            <input type="hidden" name="isbn" 
                    value="<?php echo $book_info['isbn']; ?>" /> 
        </form>
        <br>

        <!-- Sort reviews -->
        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
            <h4>Sort by: </h4>
            <div class="row g-3 mx-auto">    
                <div class="col-4 d-grid ">
                <input type="submit" value="Most Recent" id="sortRecentBtn" name="sortRecentBtn" 
                    class="btn btn-dark" title="Sort by review date" />
                <input type="hidden" value="<?= $_POST['reviewDate'] ?>" name="recent_reviewDate" />
                <input type="hidden" value="<?= $_POST['isbn'] ?>" name="isbn" />
                </div>

                <div class="col-4 d-grid ">
                <input type="submit" value="Rating (Ascending)" id="sortAscBtn" name="sortAscBtn"
                    class="btn btn-dark" title="Sort by ascending rating" />    
                <input type="hidden" value="<?= $_POST['rating'] ?>" name="ratingAsc" /> 
                <input type="hidden" value="<?= $_POST['isbn'] ?>" name="isbn" />             
                </div>	
                    
                <div class="col-4 d-grid">
                    <input type="submit" value="Rating (Descending)" name="sortDescBtn"
                    id="sortDescgBtn" class="btn btn-dark"  title="Sort by descending rating" />
                    <input type="hidden" value="<?= $_POST['rating'] ?>" name="ratingDesc" />
                <input type="hidden" value="<?= $_POST['isbn'] ?>" name="isbn" />
                </div>      
            </div> 
        </form>
        <br>


        <div class="row justify-content-center">
            <table class="w3-table w3-bordered w3-card-4 center" style="width:98%">
                <thead>
                    <tr style="background-color:#B0B0B0">
                        <th width="20%"><b>Reviewer</b></th>
                        <th width="10%"><b>Review Date</b></th>        
                        <th width="5%"><b>Rating</b></th> 
                        <th width="65%"><b>Content</b></th>
                        <th width="5%"></th>      
                    </tr>
                </thead>
                <?php foreach ($list_of_reviews as $review_info): ?>
                    <tr>
                        <td><?php echo $review_info['accountName']; ?></td>
                        <td><?php echo $review_info['reviewDate']; ?></td>        
                        <td><?php echo $review_info['rating']; ?></td>          
                        <td><?php echo $review_info['content']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>