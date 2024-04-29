<?php
function getAllReviews($isbn)
{
    global $db;
   $query = "SELECT * FROM project_reviews WHERE isbn=:isbn";    
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}
?>