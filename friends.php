<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

// Assuming you get the user ID from the URL parameter
// Initialize username variable
$username = "";

// Check if a form is submitted, and if so, update $username
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST['username'])) {
      $username = $_POST['username'];
  }
} else {
  // Check if the username is passed in the URL parameters
  if(isset($_GET['username'])) {
    $username = $_GET['username'];
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

function addRequest($user_id1, $user_id2) {
    global $db;
    $query = 'INSERT INTO friend_requests (user_id1, user_id2) 
    VALUES 
    (:user_id1, :user_id2)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id1', $username);
    $statement->bindValue(':user_id2', $user_id2);
    $statement->execute();
    $statement->closeCursor();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!empty($_POST['Submit'])) {
        $username = $_POST['username'];
        $user_id2 = $_POST["request_id"];
        addRequest($username, $user_id2);
    }

    if (!empty($_POST['Home'])) {
        $username = $_POST['username'];
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/home.php?username=$username");
        exit();
    }
}
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
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $request): ?>
        <tr>
          <td><?php echo $request['user_id1']; ?></td>
          <td>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p>No requests found for this user.</p>
    <?php endif; ?>
    <h1>Send Friend Request</h1>
    <form id="sendRequestForm" action="friends.php" method="post">
      Friend's Username: <input type="text" name="request_id" required /> <br/>
      <input type="submit" name="Submit" value="Submit" class="btn" />
    </form>
    <form id="home" action="http://localhost/cs4750/DatabaseSystemsFinal/home.php" method="get">
        <!-- Pass the username as a query parameter -->
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="submit" value="Home" class="btn" />
    </form>
    
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    