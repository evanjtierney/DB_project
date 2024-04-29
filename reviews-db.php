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

function addReview($accountName, $isbn, $content, $rating, $reviewDate)
{
    global $db;
   $query = "INSERT INTO project_reviews (accountName, isbn, content, rating, reviewDate) VALUES (:accountName, :isbn, :content, :rating, :reviewDate)";    
   try {
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->bindValue(':isbn', $isbn);
   $statement->bindValue(':content', $content);
   $statement->bindValue(':rating', $rating);
   $statement->bindValue(':reviewDate', $reviewDate);
   $statement->execute();
   $statement->closeCursor();
   } catch (PDOException $e)
   {
      $e->getMessage();
   } catch (Exception $e) 
   {
      $e->getMessage();
   }
}
?>