<?php
function getAllUsers()
{
   global $db;
   $query = "select * from project_users";    
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function getUserByAccountName($accountName) {
   global $db;
   $query = "SELECT accountName, email FROM project_users WHERE accountName = :accountName";
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->execute();
   $result = $statement->fetch(); // fetch() for single result
   $statement->closeCursor();
   return $result;
}

function getUsernamePassword($identifier) { 
   global $db;
   $query = "SELECT accountName, password FROM project_users WHERE (accountName = :identifier OR email = :identifier)";
   $statement = $db->prepare($query);
   $statement->bindValue(':identifier', $identifier);
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function newUser($name, $password, $email) {
   global $db;
   $date = date('Y-m-d');
   $query = "INSERT INTO project_users (accountName, password, email, creationDate) VALUES (:name, :password, :email, :date)";    
   $statement = $db->prepare($query);
   $statement->bindValue(':name', $name);
   $statement->bindValue(':password', $password);
   $statement->bindValue(':email', $email);
   $statement->bindValue(':date', $date);
   $statement->execute();
   $statement->closeCursor();
}

function getUserReadingLists($accountName) {
   global $db;
   $query = "SELECT listName FROM project_readingLists WHERE accountName = :accountName";
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->execute();
   $result = $statement->fetchAll(); 
   $statement->closeCursor();

   return $result;
}

function createReadingList($accountName, $listName) {
   global $db;
   $query = "INSERT INTO ReadingLists (accountName, listName) VALUES (:accountName, :listName)";
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->bindValue(':listName', $listName);
   try {
       $statement->execute();
       return true;
   } catch (PDOException $e) {
       error_log('Error in createReadingList: ' . $e->getMessage());
       return false;
   } finally {
       $statement->closeCursor();
   }
}

function addBookToReadingList($accountName, $listName, $isbn)
{
   global $db;
   $query = "INSERT INTO project_listContains (accountName, listName, isbn) VALUES (:accountName, :listName, :isbn)";    
   try {
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->bindValue(':listName', $listName);
   $statement->bindValue(':isbn', $isbn);
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

function getReadingStatus($accountName, $isbn)
{
   global $db;
   $query = "SELECT * FROM project_bookmarks WHERE accountName=:accountName AND isbn=:isbn";    
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->bindValue(':isbn', $isbn);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();

   if ($result === false) {
      return null;
  }
   
   return $result;
}

function updateReadingStatus($accountName, $isbn, $bookmark)
{
   global $db;
   $query = "UPDATE project_bookmarks SET bookmark=:bookmark WHERE accountName=:accountName AND isbn=:isbn";    
   try {
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->bindValue(':isbn', $isbn);
   $statement->bindValue(':bookmark', $bookmark);
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

function createBookmark($accountName, $isbn, $bookmark, $status)
{
   global $db;
   $query = "INSERT INTO project_bookmarks (accountName, isbn, bookmark, `status`) VALUES (:accountName, :isbn, :bookmark, :status)";    
   try {
   $statement = $db->prepare($query);
   $statement->bindValue(':accountName', $accountName);
   $statement->bindValue(':isbn', $isbn);
   $statement->bindValue(':bookmark', $bookmark);
   $statement->bindValue(':status', $status);
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