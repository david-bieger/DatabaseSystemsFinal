<?php
require("connect-db.php");
require("database-functions.php");

$username = $_GET['username'];

// Retrieve user's information from the database
$query = 'SELECT * FROM Users WHERE user_id = :user_id';
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $username);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

// Retrieve user's goals from the database
$query_goals = 'SELECT * FROM User_goals WHERE user_id = :user_id AND exercise IN ("Bench Press", "Squat", "Deadlift")';
$statement_goals = $db->prepare($query_goals);
$statement_goals->bindValue(':user_id', $username);
$statement_goals->execute();
$user_goals = $statement_goals->fetchAll(PDO::FETCH_ASSOC);
$statement_goals->closeCursor();

// Handle form submission to update 1 rep maxes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['submit'])) {
        $squatMax = $_POST["squat_max"];
        $benchMax = $_POST["bench_max"];
        $dlMax = $_POST["dl_max"];

        // Update user's 1 rep maxes in the database
        $query = 'UPDATE Users SET squat_max = :squat_max, bench_max = :bench_max, dl_max = :dl_max WHERE user_id = :user_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':squat_max', $squatMax);
        $statement->bindValue(':bench_max', $benchMax);
        $statement->bindValue(':dl_max', $dlMax);
        $statement->bindValue(':user_id', $username);
        $statement->execute();
        $statement->closeCursor();

        // Redirect back to the profile page after updating
        header("Location: myprofile.php?username=$username");
        exit;
    }

    // Handle goal submission
    if (!empty($_POST['submit_goal'])) {
        $exercise = $_POST['exercise'];
        $goal_value = $_POST['goal_value'];
        $target_date = $_POST['target_date'];

        // Insert the new goal into the database
        $query_insert_goal = 'INSERT INTO User_goals (user_id, exercise, goal_value, target_date) VALUES (:user_id, :exercise, :goal_value, :target_date)';
        $statement_insert_goal = $db->prepare($query_insert_goal);
        $statement_insert_goal->bindValue(':user_id', $username);
        $statement_insert_goal->bindValue(':exercise', $exercise);
        $statement_insert_goal->bindValue(':goal_value', $goal_value);
        $statement_insert_goal->bindValue(':target_date', $target_date);
        $statement_insert_goal->execute();
        $statement_insert_goal->closeCursor();

        // Redirect back to the profile page after updating
        header("Location: myprofile.php?username=$username");
        exit;
    }
}
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
  <title>My Profile - <?php echo $username; ?></title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
  <style>
    /* Hide the "Add Goal" form by default */
    #addGoalForm {
      display: none;
    }
  </style>
</head>
<body>  
  <div>  
    <h1>My Profile - <?php echo $username; ?></h1>
    <h2>1 Rep Maxes:</h2>
    <!-- Form to update 1 rep maxes -->
    <form id="editMaxesForm" action="myprofile.php?username=<?php echo $username; ?>" method="post">     
      Squat Max: <input type="number" name="squat_max" value="<?php echo $user['squat_max']; ?>" required /> <br/>
      Bench Max: <input type="number" name="bench_max" value="<?php echo $user['bench_max']; ?>" required /> <br/> 
      Deadlift Max: <input type="number" name="dl_max" value="<?php echo $user['dl_max']; ?>" required /> <br/> 
      <input type="submit" name="submit" value="Save Changes" class="btn" />
    </form>
    <h2>Goals:</h2>
    <!-- Goals table -->
    <table class="table">
      <thead>
        <tr>
          <th>Exercise</th>
          <th>Goal Value</th>
          <th>Target Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array("Bench Press", "Squat", "Deadlift") as $exercise) : ?>
          <?php $goal_exists = false; ?>
          <?php foreach ($user_goals as $goal) : ?>
            <?php if ($goal['exercise'] === $exercise) : ?>
              <!-- Display existing goal -->
              <tr>
                <td><?php echo $goal['exercise']; ?></td>
                <td><?php echo $goal['goal_value']; ?></td>
                <td><?php echo $goal['target_date']; ?></td>
                <td>
                  <!-- "Update" button to toggle "Add Goal" form -->
                  <button type="button" class="btn btn-primary btn-sm" onclick="toggleAddGoalForm('<?php echo $exercise; ?>')">Update</button>
                </td>
              </tr>
              <?php $goal_exists = true; ?>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php if (!$goal_exists) : ?>
            <!-- Display "Add Goal" form -->
            <tr>
              <td><?php echo $exercise; ?></td>
              <td colspan="2">
                <form id="addGoalForm_<?php echo $exercise; ?>" action="myprofile.php?username=<?php echo $username; ?>" method="post">
                  <input type="hidden" name="exercise" value="<?php echo $exercise; ?>" />
                  Goal Value: <input type="number" name="goal_value" required /> 
                  Target Date: <input type="date" name="target_date" required />
                  <input type="submit" name="submit_goal" value="Set Goal" class="btn" />
                </form>
              </td>
              <td></td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
    <!-- Form to navigate to the home page -->
    <form id="home" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="hidden" name="Home" value="true">
        <input type="submit" value="Home" class="btn" />
    </form>
  </div>

  <script>
    // JavaScript function to toggle visibility of "Add Goal" form
    function toggleAddGoalForm(exercise) {
        var formId = "addGoalForm_" + exercise; // Generate form ID
        var addGoalForm = document.getElementById(formId); // Get form element
        if (addGoalForm.style.display === "none" || addGoalForm.style.display === "") {
            addGoalForm.style.display = "table-row"; // Show the form
        } else {
            addGoalForm.style.display = "none"; // Hide the form
        }
    }
  </script>


  <!-- Bootstrap JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg"></script>
</body>
</html>
