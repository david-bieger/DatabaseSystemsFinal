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

}