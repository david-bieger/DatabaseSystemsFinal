<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

$username = $_GET['username'];
//$username = "David";

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

function add_set($name, $exercise, $date, $set_number, $weight, $reps) {
    global $db;
    $query = 'INSERT INTO Exercise_History (user_id, exercise, date, set_number, weight, reps) 
    VALUES 
    (:user_id, :exercise, :date, :set_number, :weight, :reps)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $name);
    $statement->bindValue(':date', $date);
    $statement->bindValue(':exercise', $exercise);
    $statement->bindValue(':set_number', $set_number);
    $statement->bindValue(':weight', $weight);
    $statement->bindValue(':reps', $reps);
    $statement->execute();
    //$result = $statement->fetch(); // Fetch the result row
    $statement->closeCursor();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedExercise = 0;
    if (isset($_POST["seeInfo"])) {
        if (!empty($_POST["exercise"])) {
            $selectedExercise = $_POST["exercise"];
            $description = get_exercise_description($selectedExercise);
            $muscles = get_exercise_muscles($selectedExercise);
        }
    } if (isset($_POST["addSet"])) {
        $date = date("Y-m-d");
        $exerciseName = 0;
        if ($selectedExercise != 0) {
            $exerciseName = $selectedExercise;
        }
        $exerciseName = $_POST["exercise"];
        $weight = $_POST["weight"];
        $reps = $_POST["num_reps"];
        $date = $_POST["date"];
        $set_number = $_POST["set_number"];
        // You might want to add validation for $weight and $reps here
        add_set($username, $exerciseName, $date, $set_number, $weight, $reps); // For simplicity, assuming set_number as 1
    }
    if (!empty($_POST['Home'])) {
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/home.php?username=$username");
        exit();
    }
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
    <form id="addExerciseForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">     
      <label for="exercise">Select an exercise:</label>
      <select name="exercise" id="exercise">
        <?php foreach ($exercises as $exercise) : ?>
          <option value="<?php echo $exercise['exercise_name']; ?>" <?php if(isset($selectedExercise) && $exercise['exercise_name'] == $selectedExercise) echo "selected"; ?>><?php echo $exercise['exercise_name']; ?></option>
        <?php endforeach; ?>
      </select>
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
      <!-- Add input fields for adding sets -->
      <!-- You can use JavaScript to dynamically add more input fields for sets -->
      Date: <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" /> <br/>
      Set Number: <input type="number" name="set_number"  /> <br/>
      Weight: <input type="number" name="weight"  /> <br/>
      Number of Reps: <input type="number" name="num_reps" /> <br/>
      <input type="submit" name="addSet" value="Add Set" class="btn" />
    </form>

    <form id="home" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="Home" value="true">
        <input type="submit" value="Home" class="btn" />
    </form>
    
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
