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
}

// Check if a delete request is made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['set_number'])) {
    $set_number = $_POST['set_number'];
    
    $query = "DELETE FROM Exercise_History WHERE set_number = :set_number";
    $statement = $db->prepare($query);
    $statement->bindValue(':set_number', $set_number);
    $statement->execute();
}

// Fetch username from GET or POST data
$username = isset($_POST['username']) ? $_POST['username'] : $_GET['username'];

$query = "SELECT * FROM Exercise_History WHERE user_id = :user_id";
// Add WHERE clause if a filter is applied
if (!empty($filterExercise)) {
    $query .= " AND exercise = :exercise";
}

$query .= " ORDER BY date DESC";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $username);
// Bind filter parameter if a filter is applied
if (!empty($filterExercise)) {
    $statement->bindValue(':exercise', $filterExercise);
}
$statement->execute();
$exercises = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    <h1>Exercise History</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <label for="exercise">Filter Exercises:</label>
      <select name="exercise" id="exercise">
        <option value="">All Exercises</option>
        <?php
        // Fetch distinct exercise names from the database
        $query = "SELECT DISTINCT exercise FROM Exercise_History";
        $statement = $db->prepare($query);
        $statement->execute();
        $exerciseNames = $statement->fetchAll(PDO::FETCH_COLUMN);
        $statement->closeCursor();

        // Display dropdown options
        foreach ($exerciseNames as $exerciseName) {
            $selected = ($filterExercise == $exerciseName) ? 'selected' : '';
            echo "<option value='$exerciseName' $selected>$exerciseName</option>";
        }
        ?>
      </select>
      <input type="submit" value="Apply Filter" class="btn" />
    </form>
    <?php if (count($exercises) > 0): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Exercise</th>
          <th>Weight</th>
          <th>Reps</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($exercises as $exercise): ?>
        <tr>
          <td><?php echo $exercise['date']; ?></td>
          <td><?php echo $exercise['exercise']; ?></td>
          <td><?php echo $exercise['weight']; ?></td>
          <td><?php echo $exercise['reps']; ?></td>
          <td>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="set_number" value="<?php echo $exercise['set_number']; ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p>No exercise history found for this user.</p>
    <?php endif; ?>
    <form id="home" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Use the username from PHP variable -->
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="hidden" name="Home" value="true">
        <input type="submit" value="Home" class="btn" />
    </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</
