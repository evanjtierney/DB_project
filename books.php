<?php
require("connect-db.php");
require("books-db.php");
?>

<?php
$list_of_books = getAllBooks();
?>

<!DOCTYPE html>
<html>
    <body>
        <h2>
            Books
        </h2>
        <h3>List of books</h3>
        <div class="row justify-content-center">  
            <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
                <thead>
                    <tr style="background-color:#B0B0B0">
                        <th width="30%"><b>ISBN</b></th>
                        <th width="30%"><b>Title</b></th>        
                        <th width="30%"><b>SeriesTitle</b></th> 
                        <th width="30%"><b>PageCount</b></th>
                        <th width="30%"><b>PubDate</b></th>        
                        <th><b>Update?</b></th>
                        <th><b>Delete?</b></th>
                    </tr>
                </thead>
                <?php foreach ($list_of_books as $book_info): ?>
                    <tr>
                        <td><?php echo $book_info['isbn']; ?></td>
                        <td><?php echo $book_info['title']; ?></td>        
                        <td><?php echo $book_info['seriesTitle']; ?></td>          
                        <td><?php echo $book_info['pageCount']; ?></td>
                        <td><?php echo $book_info['pubDate']; ?></td>        
                        <td>Update</td>
                        <td>Delete</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </body>
</html>