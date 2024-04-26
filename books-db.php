<?php
function getAllBooks()
{
   global $db;
   $query = "select * from project_books";    
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function getBookInfo($isbn)
{
   global $db;
   $query = "SELECT * FROM project_books WHERE isbn=:isbn";

   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();

   return $result;
}

function getAuthors($isbn)
{
   global $db;
   $query = "SELECT author FROM project_bookAuthors NATURAL JOIN (SELECT * FROM project_books WHERE isbn=:isbn) AS B";

   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getGenres($isbn)
{
   global $db;
   $query = "SELECT genre FROM project_bookGenres NATURAL JOIN (SELECT * FROM project_books WHERE isbn=:isbn) AS B";

   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getPurchaseLinks($isbn)
{
   global $db;
   $query = "SELECT link FROM project_purchaseLinks NATURAL JOIN (SELECT * FROM project_books WHERE isbn=:isbn) AS B";

   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getRatingInfo($isbn)
{
   global $db;
   $query = "SELECT AVG(rating) AS avgRating, COUNT(rating) AS numRatings FROM project_reviews NATURAL JOIN (SELECT * FROM project_books WHERE isbn=:isbn) AS B";

   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();

   return $result;
}
?>