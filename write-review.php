<?php
session_start();
require("connect-db.php");
require("books-db.php");
require("reviews-db.php");
?>

<?php
// Ensure that the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: user-auth.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $isbn = $_POST['isbn'];
    $book_info = getBookInfo($isbn);
    if (!empty($_POST['submitBtn']))
    {
        addReview($_SESSION['accountName'], $isbn, $_POST['content'], $_POST['rating'], date("Y-m-d"));
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Write Review</title>
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
                            <a class="nav-link active" aria-current="page" href="books.php">Browse</a>
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

        <h3>Write a review for <?php echo $book_info['title']; ?></h3>

        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
            <table style="width:98%">
                <tr>
                    <td colspan=2>
                    <div class='mb-3'>
                        Rating:
                        <select class='form-select' id='rating' name='rating'>
                        <option selected></option>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                        </select>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <div class='mb-3'>
                            Content (max 255 characters): 
                            <input type='text' class='form-control' id='content' name='content'
                                placeholder='Content' />
                        </div>
                    </td>
                </tr>
            </table>

            <input type="submit" value="Submit" id="submitBtn" name="submitBtn" class="btn btn-primary"
                title="Submit a review" />
            <input type="hidden" value="<?= $isbn ?>" name="isbn" />
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>