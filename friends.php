<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

// Assuming you get the user ID from the URL parameter
// Initialize username variable
$username = isset($_GET['username']) ? $_GET['username'] : 'David';

function addRequest($user_id1, $user_id2) {
    global $db;
    $query = 'INSERT INTO friend_requests (user_id1, user_id2) 
    VALUES 
    (:user_id1, :user_id2)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id1', $user_id1);
    $statement->bindValue(':user_id2', $user_id2);
    $statement->execute();
    $statement->closeCursor();
}

function validateRequest($user_id1, $user_id2) {
    //Reject when you are already friends (1)
    //or when a request is active (2)
    //or when the user isn't a user (3)
    global $db;
    $query1 = 'SELECT * FROM friends 
    WHERE ((user_id1 = :user_id1 AND user_id2 = :user_id2)
    OR (user_id2 = :user_id1 AND user_id1 = :user_id2))';
    $statement1 = $db->prepare($query1);
    $statement1->bindValue(':user_id1', $user_id1);
    $statement1->bindValue(':user_id2', $user_id2);
    $statement1->execute();
    $result1 = $statement1->fetch(); // Fetch the result row
    $statement1->closeCursor();
    // Check if a row was returned
    if ($result1) {
        return 1;
    }
    $query2 = 'SELECT * FROM friend_requests 
    WHERE ((user_id1 = :user_id1 AND user_id2 = :user_id2)
    OR (user_id2 = :user_id1 AND user_id1 = :user_id2))';
    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':user_id1', $user_id1);
    $statement2->bindValue(':user_id2', $user_id2);
    $statement2->execute();
    $result2 = $statement2->fetch(); // Fetch the result row
    $statement2->closeCursor();    
    if ($result2) {
        return 2;
    }
    $query3 = 'SELECT * FROM Users WHERE user_id = :user_id2';
    $statement3 = $db->prepare($query3);
    $statement3->bindValue(':user_id2', $user_id2);
    $statement3->execute();
    $result3 = $statement3->fetch(); // Fetch the result row
    $statement3->closeCursor();    
    if (!$result3) {
        return 3;
    }
    return 0; // User not found or password doesn't match
}

function acceptRequest($user_id1, $user_id2) {
    global $db;
    $query = 'INSERT INTO friends (user_id1, user_id2) 
    VALUES 
    (:user_id1, :user_id2)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id1', $user_id1);
    $statement->bindValue(':user_id2', $user_id2);
    $statement->execute();
    $statement->closeCursor();
    // Once accepted, delete the request
    $query_delete = 'DELETE FROM friend_requests 
    WHERE (user_id1 = :user_id1 AND user_id2 = :user_id2)
    OR (user_id1 = :user_id2 AND user_id2 = :user_id1)';
    $statement_delete = $db->prepare($query_delete);
    $statement_delete->bindValue(':user_id1', $user_id1);
    $statement_delete->bindValue(':user_id2', $user_id2);
    $statement_delete->execute();
    $statement_delete->closeCursor();
}

function declineRequest($user_id1, $user_id2) {
    global $db;
    $query = 'DELETE FROM friend_requests 
    WHERE (user_id1 = :user_id1 AND user_id2 = :user_id2)
    OR (user_id1 = :user_id2 AND user_id2 = :user_id1)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id1', $user_id1);
    $statement->bindValue(':user_id2', $user_id2);
    $statement->execute();
    $statement->closeCursor();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Submit'])) {
        $username = $_POST['username'];
        $user_id2 = $_POST['user_id2'];
        $flag = validateRequest($username, $user_id2);
        if($flag == 0){
            echo "Request sent!";
            addRequest($username, $user_id2);
        }
        if($flag == 1){
            echo "You are already friends with this user";
        }
        if($flag == 2){
            echo "You already have an active request with this user";
        }
        if($flag == 3){
            echo "The user could not be found";
        }
    }
    if (!empty($_POST['accept'])) {
        $user_id1 = $_POST['accept'];
        acceptRequest($user_id1, $username);
        echo "Friend request accepted!";
    }
    if (!empty($_POST['decline'])) {
        $user_id1 = $_POST['decline'];
        declineRequest($user_id1, $username);
        echo "Friend request declined!";
    }
    if (!empty($_POST['Home'])) {
      $username = $_POST['username'];
      header("Location: http://localhost/cs4750/DatabaseSystemsFinal/home.php?username=$username");
      exit();
    }
}

$query = "SELECT * FROM (friends JOIN Users ON friends.user_id2 = Users.user_id) WHERE user_id1 = :user_id1";

$statement = $db->prepare($query);
$statement->bindValue(':user_id1', $username);
$statement->execute();
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

$query2 = "SELECT * FROM (friends JOIN Users ON friends.user_id1 = Users.user_id) WHERE user_id2 = :user_id2";

$statement2 = $db->prepare($query2);
$statement2->bindValue(':user_id2', $username);
$statement2->execute();
$friends2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
$statement2->closeCursor();

$query_requests = "SELECT * FROM friend_requests WHERE user_id2 = :user_id2";

$statement3 = $db->prepare($query_requests);
$statement3->bindValue(':user_id2', $username);
$statement3->execute();
$requests = $statement3->fetchAll(PDO::FETCH_ASSOC);
$statement3->closeCursor();

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>Exercise History</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Friends</h1>
    <?php if ((count($friends) + count($friends2)) > 0): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Friend</th>
          <th>Squat Max</th>
          <th>Bench Max</th>
          <th>Deadlift Max</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($friends as $friend): ?>
        <tr>
          <td><?php echo $friend['user_id2']; ?></td>
          <td><?php echo $friend['squat_max']; ?></td>
          <td><?php echo $friend['bench_max']; ?></td>
          <td><?php echo $friend['dl_max']; ?></td>
          <td>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php foreach ($friends2 as $friend2): ?>
        <tr>
          <td><?php echo $friend2['user_id1']; ?></td>
          <td><?php echo $friend2['squat_max']; ?></td>
          <td><?php echo $friend2['bench_max']; ?></td>
          <td><?php echo $friend2['dl_max']; ?></td>
          <td>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p>No friends found for this user.</p>
    <?php endif; ?>
    <?php if (count($requests) > 0): ?>
    <h1>Incoming Friend Requests</h1>
    <table class="table">
      <thead>
        <tr>
          <th>Friend</th>
          <th>Accept</th>
          <th>Decline</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $request): ?>
        <tr>
          <td><?php echo $request['user_id1']; ?></td>
          <td>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">
              <input type="hidden" name="accept" value="<?php echo $request['user_id1']; ?>">
              <button type="submit" class="btn btn-success btn-block">Accept</button>
            </form>
          </td>
          <td> <!-- New column for Decline button -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">
              <input type="hidden" name="decline" value="<?php echo $request['user_id1']; ?>">
              <button type="submit" class="btn btn-danger btn-block">Decline</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php else: ?>
    <p>No requests found for this user.</p>
    <?php endif; ?>
    <h1>Send Friend Request</h1>
    <form id="addRequestForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">
      Friend's Username: <input type="text" name="user_id2" required /> <br/>
      <input type="hidden" name="username" value="<?php echo $username; ?>" />
      <input type="submit" name="Submit" value="Submit" class="btn" />
    </form>
    <form id="home" action="http://localhost/cs4750/DatabaseSystemsFinal/home.php" method="get">
        <!-- Pass the username as a query parameter -->
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="submit" value="Home" class="btn" />
    </form>    
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
