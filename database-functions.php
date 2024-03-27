<?php
function addRequests($reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{

    //leaving this example here so we know what to do later
    global $db;

    $reqDate = date('Y-m-d'); //ensures proper data type
    //$query = "INSERT INTO requests (reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES ('" . $reqDate . "', '" . $roomNumber . "', '" . $reqBy . "', '" . $repairDesc . "', '" . $reqPriority . "')";

    $query = "INSERT INTO requests (reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES (:reqDate, :roomNumber, :reqBy, :repairDesc, :reqPriority)";


    try {

        $statement = $db->prepare($query); //precompile

        //fill in value
        $statement->bindValue(':reqDate', $reqDate);
        $statement->bindValue(':roomNumber', $roomNumber);
        $statement->bindValue(':reqBy', $reqBy);
        $statement->bindValue(':repairDesc', $repairDesc);
        $statement->bindValue(':reqPriority', $reqPriority);

        $statement->execute(); //execute
        $statement->closeCursor(); //close when done

    } catch (PDOException $e) {
        $e->getMessage();
    } catch (Exception $e) {
        $e->getMessage();
    }

    function validateUser($username, $password) {
        global $db;
        $query = 'SELECT password FROM users WHERE user_id = :user_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $username);
        $statement->execute();
        $result = $statement->fetch(); // Fetch the result row
        $statement->closeCursor();
    
        // Check if a row was returned
        if ($result) {
            // Compare the password from the database with the input password
            $passwordFromDB = $result['password'];
            if ($password === $passwordFromDB) {
                return true; // Passwords match
            }
        }
        return false; // User not found or password doesn't match
    }
    

}