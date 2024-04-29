<?php
session_start(); 
require("connect-db.php");
require("books-db.php");
$list_of_books = getAllBooks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
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

    <h3>List of books</h3>
    <div class="row justify-content-center">  
        <table class="w3-table w3-bordered w3-card-4 center" style="width:98%">
            <thead>
                <tr style="background-color:#B0B0B0">
                    <th width="10%"><b>ISBN</b></th>
                    <th width="40%"><b>Title</b></th>        
                    <th width="30%"><b>Series Title</b></th> 
                    <th width="20%"><b>Publication Date (Y-M-D)</b></th>
                    <th width="5%"></th>      
                </tr>
            </thead>
            <?php foreach ($list_of_books as $book_info): ?>
                <tr>
                    <td><?php echo $book_info['isbn']; ?></td>
                    <td><?php echo $book_info['title']; ?></td>        
                    <td><?php echo $book_info['seriesTitle']; ?></td>          
                    <td><?php echo $book_info['pubDate']; ?></td>
                    <td>
                        <form action="book.php" method="post"> 
                            <input type="submit" value="More" name="moreBtn" 
                                    class="btn btn-primary" /> 
                            <input type="hidden" name="isbn" 
                                    value="<?php echo $book_info['isbn']; ?>" /> 
                        </form>
                    </td>      
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
