<?php
function getAllReviews($isbn)
{
    global $db;
   $query = "SELECT * FROM project_reviews WHERE isbn=:isbn";    
   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function sortByReviewDate($isbn)
{
    global $db;
   $query = "SELECT * FROM project_reviews WHERE isbn=:isbn ORDER BY reviewDate ASC";    
   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function sortAscRating($isbn)
{
    global $db;
   $query = "SELECT * FROM project_reviews WHERE isbn=:isbn ORDER BY rating ASC";    
   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function sortDescRating($isbn)
{
    global $db;
   $query = "SELECT * FROM project_reviews WHERE isbn=:isbn ORDER BY rating DESC";    
   $statement = $db->prepare($query);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}
?>