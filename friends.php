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

// //Check if a delete request is made
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $user_id2 = $_POST['user_id2'];
//     $query = "DELETE FROM friends WHERE user_id1 = $username AND user_id2 = :user_id2";
//     $statement = $db->prepare($query);
//     $statement->bindValue(':set_number', $set_number);
//     $statement->execute();
// }

$query = "SELECT * FROM (friends JOIN Users ON friends.user_id2 = Users.user_id) WHERE user_id1 = :user_id1";

$statement = $db->prepare($query);
$statement->bindValue(':user_id1', $username);
$statement->execute();
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    <?php if (count($friends) > 0): ?>
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
      </tbody>
    </table>
    <?php else: ?>
    <p>No friends found for this user.</p>
    <?php endif; ?>
    <form id="home" action="http://localhost/cs4750/DatabaseSystemsFinal/home.php" method="get">
        <!-- Pass the username as a query parameter -->
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="submit" value="Home" class="btn" />
    </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    