<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

function get_exercise_names() {
    global $db;

    $query = "SELECT exercise_id, exercise_name FROM Exercises";
    $statement = $db->prepare($query);
    $statement->execute();
    $exercises = $statement->fetchAll();
    $statement->closeCursor();
    }


// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle adding set to the database
    // You'll need to write code here to insert set data into the database
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>Add Exercise</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Add Exercise</h1>
    <form id="addExerciseForm" action="addExercise.php" method="post">     
      <label for="exercise">Select an exercise:</label>
      <select name="exercise" id="exercise">
        <?php foreach ($exercises as $exercise) : ?>
          <option value="<?php echo $exercise['exercise_id']; ?>"><?php echo $exercise['exercise_name']; ?></option>
        <?php endforeach; ?>
      </select>
      <br>
      <label for="description">Exercise Description:</label>
      <textarea id="description" name="description" readonly></textarea>
      <br>
      <!-- Add input fields for adding sets -->
      <!-- You can use JavaScript to dynamically add more input fields for sets -->
      <input type="submit" name="addSet" value="Add Set" class="btn" />
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
