<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

// Initialize variables
$filterExercise = "";
$username = ""; // Initialize username variable

// Check if a filter is applied
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['exercise'])) {
    $filterExercise = $_POST['exercise'];
    $username = $_POST['username'];
}

// Check if a delete request is made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {
    $date = $_POST['date'];
    $username = $_POST['username'];
    
    $query = "DELETE FROM Max_History WHERE user_id = :user_id AND date = :date";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $username);
    $statement->bindValue(':date', $date);
    $statement->execute();
}

// Fetch username from GET or POST data
$username = isset($_POST['username']) ? $_POST['username'] : $_GET['username'];

// Fetch max history from the database
$query = "SELECT * FROM Max_History WHERE user_id = :user_id ORDER BY date DESC";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $username);
$statement->execute();
$maxHistory = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

// Process form submissions
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
  <title>Max History</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Max History</h1>
    <!-- Display max history table -->
    <?php if (count($maxHistory) > 0): ?>
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Squat Max</th>
            <th>Bench Max</th>
            <th>Deadlift Max</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($maxHistory as $max): ?>
            <tr>
              <td><?php echo $max['date']; ?></td>
              <td><?php echo $max['squat_max']; ?></td>
              <td><?php echo $max['bench_max']; ?></td>
              <td><?php echo $max['dl_max']; ?></td>
              <td>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">
                  <input type="hidden" name="date" value="<?php echo $max['date']; ?>">
                  <input type="hidden" name="username" value="<?php echo $username; ?>" />
                  <button type="submit" class="btn btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No max history found for this user.</p>
    <?php endif; ?>
    
    <!-- Form to navigate to the home page -->
    <form id="home" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Use the username from PHP variable -->
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="hidden" name="Home" value="true">
        <input type="submit" value="Home" class="btn" />
    </form>
  </div>

  <!-- Bootstrap JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg"></script>
</body>
</html>
