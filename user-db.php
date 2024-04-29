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
?>