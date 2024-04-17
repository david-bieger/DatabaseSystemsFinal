<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

// Assuming you get the user ID from the URL parameter
//$userId = $_GET['userId'];
$username = "David";

$query = "SELECT * FROM Exercise_History WHERE user_id = :user_id ORDER BY date DESC";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $username);
$statement->execute();
$exercises = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Home'])) {
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
    <label for="exercise">Filter Exercises:</label>
      <select name="exercise" id="exercise">
        <?php foreach ($exercises as $exercise) : ?>
          <option value="<?php echo $exercise['exercise']; ?>" <?php if(isset($selectedExercise) && $exercise['exercise'] == $selectedExercise) echo "selected"; ?>><?php echo $exercise['exercise']; ?></option>
        <?php endforeach; ?>
      </select>
    <?php if (count($exercises) > 0): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Exercise</th>
          <th>Set Number</th>
          <th>Weight</th>
          <th>Reps</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($exercises as $exercise): ?>
        <tr>
          <td><?php echo $exercise['date']; ?></td>
          <td><?php echo $exercise['exercise']; ?></td>
          <td><?php echo $exercise['set_number']; ?></td>
          <td><?php echo $exercise['weight']; ?></td>
          <td><?php echo $exercise['reps']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p>No exercise history found for this user.</p>
    <?php endif; ?>
    <form id="home" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="Home" value="true">
        <input type="submit" value="Home" class="btn" />
    </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>

