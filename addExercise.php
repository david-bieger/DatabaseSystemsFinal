<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

function get_exercise_names() {
    global $db;

    $query = "SELECT exercise_name FROM Exercises";
    $statement = $db->prepare($query);
    $statement->execute();
    $exercises = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $exercises;
}

function get_exercise_description($name) {
    global $db;

    $query = "SELECT description FROM Exercises WHERE exercise_name = :exercise_name";
    $statement = $db->prepare($query);
    $statement->bindValue(':exercise_name', $name);
    $statement->execute();
    $description = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $description;
}

function get_exercise_muscles($name) {
    global $db;

    $query = "SELECT muscle FROM Exercises WHERE exercise_name = :exercise_name";
    $statement = $db->prepare($query);
    $statement->bindValue(':exercise_name', $name);
    $statement->execute();
    $muscle = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $muscle;
}

function add_set($name, $exercise, $date, $weight, $reps) {
    global $db;
    $query = 'INSERT INTO Exercise_History (user_id, exercise, date, weight, reps) 
    VALUES 
    (:user_id, :exercise, :date, :weight, :reps)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $name);
    $statement->bindValue(':date', $date);
    $statement->bindValue(':exercise', $exercise);
    $statement->bindValue(':weight', $weight);
    $statement->bindValue(':reps', $reps);
    $statement->execute();
    $statement->closeCursor();
}

function add_favorite($name, $exercise) {
  global $db;
  $query = 'INSERT INTO Favorite_Exercises (user_id, exercise_name) 
  VALUES 
  (:user_id, :exercise)';
  $statement = $db->prepare($query);
  $statement->bindValue(':user_id', $name);
  $statement->bindValue(':exercise', $exercise);
  $statement->execute();
  $statement->closeCursor();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = isset($_POST['username']) ? $_POST['username'] : $_GET['username'];

  $selectedExercise = 0;
  if (isset($_POST["seeInfo"])) {
      if (!empty($_POST["exercise"])) {
          $selectedExercise = $_POST["exercise"];
          $description = get_exercise_description($selectedExercise);
          $muscles = get_exercise_muscles($selectedExercise);
      }
  } elseif (isset($_POST["favorite"])) {
      $exerciseName = isset($_POST["exercise"]) ? $_POST["exercise"] : 0;
      $username = $_POST['username'];
      add_favorite($username, $exerciseName);
  } elseif (isset($_POST["addSet"])) {
      $date = date("Y-m-d");
      $exerciseName = isset($_POST["exercise"]) ? $_POST["exercise"] : 0;
      $weight = isset($_POST["weight"]) ? $_POST["weight"] : 0;
      $reps = isset($_POST["num_reps"]) ? $_POST["num_reps"] : 0;
      // You might want to add validation for $weight and $reps here
      add_set($username, $exerciseName, $date, $weight, $reps); // For simplicity, assuming set_number as 1
  }
  if (!empty($_POST['Home'])) {
      // Use the username from the form input value
      $username = $_POST['username'];
      header("Location: http://localhost/cs4750/DatabaseSystemsFinal/home.php?username=$username");
      exit();
  }
} 
else {
  // If it's not a POST request, use the username from the GET data
  $username = $_GET['username'];
  }

$exercises = get_exercise_names();
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
    <form id="addExerciseForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">     
      <label for="exercise">Select an exercise:</label>
      <select name="exercise" id="exercise">
          <?php foreach ($exercises as $exercise) : ?>
              <option value="<?php echo $exercise['exercise_name']; ?>" <?php if(isset($selectedExercise) && $exercise['exercise_name'] == $selectedExercise) echo "selected"; ?>><?php echo $exercise['exercise_name']; ?></option>
          <?php endforeach; ?>
      </select>
      <input type="submit" name="favorite" value="Add To Favorites" class="btn" />
      <br>
      <input type="submit" name="seeInfo" value="See Exercise Information" class="btn" />
      <br>
      <label for="exercise">Exercise Name:</label>
      <textarea id="exerciseName" name="exerciseName" readonly><?php if (isset($selectedExercise)) echo $selectedExercise; ?></textarea>
      <br>
      <label for="description">Exercise Description:</label>
      <textarea id="description" name="description" readonly><?php if (isset($description)) echo $description['description']; ?></textarea>
      <br>
      <label for="muscle">Muscle(s) Targeted:</label>
      <textarea id="muscle" name="muscle" readonly><?php if (isset($muscles)) echo $muscles['muscle']; ?></textarea>
      <br>
      Date: <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" /> <br/>
      Weight: <input type="number" name="weight"  /> <br/>
      Number of Reps: <input type="number" name="num_reps" /> <br/>
      <input type="hidden" name="username" value="<?php echo $username; ?>" />
      <input type="submit" name="addSet" value="Add Set" class="btn" />
    </form>


    <form id="home" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="hidden" name="Home" value="true">
        <input type="submit" value="Home" class="btn" />
    </form>
    
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
