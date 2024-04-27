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
?>