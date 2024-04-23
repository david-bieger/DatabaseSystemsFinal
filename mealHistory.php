<?php
require("connect-db.php");
require("database-functions.php");

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

// Check if a delete request is made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['meal_number'])) {
    $meal_number = $_POST['meal_number'];
    $username = $_POST['username'];
    
    $query = "DELETE FROM Meal_History WHERE meal_number = :meal_number";
    $statement = $db->prepare($query);
    $statement->bindValue(':meal_number', $meal_number);
    $statement->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Home'])) {
        $username = $_POST['username'];
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/home.php?username=$username");
        exit();
    }
}

$query = "SELECT * FROM Meal_History WHERE user_id = :user_id ORDER BY date DESC";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $username);
$statement->execute();
$meals = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>Meal History</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Meal History</h1>
    <?php if (count($meals) > 0): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Calories</th>
          <th>Carbs</th>
          <th>Protein</th>
          <th>Fat</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($meals as $meal): ?>
        <tr>
          <td><?php echo $meal['date']; ?></td>
          <td><?php echo $meal['calories']; ?></td>
          <td><?php echo $meal['carbs']; ?></td>
          <td><?php echo $meal['protein']; ?></td>
          <td><?php echo $meal['fat']; ?></td>
          <td>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">
                <input type="hidden" name="meal_number" value="<?php echo $meal["meal_number"]; ?>">
                <input type="hidden" name="username" value="<?php echo $username; ?>" />
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p>No meal history found for this user.</p>
    <?php endif; ?>
    <form id="home" action="http://localhost/cs4750/DatabaseSystemsFinal/home.php" method="get">
        <!-- Pass the username as a query parameter -->
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="submit" value="Home" class="btn" />
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
