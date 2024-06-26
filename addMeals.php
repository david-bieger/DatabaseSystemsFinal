<?php
require("connect-db.php");
require("database-functions.php");
// echo "add Meals";
// Initialize username variable
$username = isset($_GET['username']) ? $_GET['username'] : 'David';

// Check if a form is submitted, and if so, update $username
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   if (!empty($_POST['username'])) {
//       $username = $_POST['username'];
//   }
// } else {
//   // Check if the username is passed in the URL parameters
//   if(isset($_GET['username'])) {
//     $username = $_GET['username'];
//   }
// }

function addMeal($username, $date, $calories, $carbs, $protein, $fat) {
    global $db;
    $query = 'INSERT INTO Meal_History (user_id, date, calories, carbs, protein, fat) 
    VALUES 
    (:username, :meal_date, :calories, :carbs, :protein, :fat)';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':meal_date', $date);
    $statement->bindValue(':calories', $calories);
    $statement->bindValue(':carbs', $carbs);
    $statement->bindValue(':protein', $protein);
    $statement->bindValue(':fat', $fat);
    $statement->execute();
    //$result = $statement->fetch(); // Fetch the result row
    $statement->closeCursor();

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Submit'])) {
        $username = $_POST['username'];
        $date = $_POST['date'];
        $calories = $_POST["calories"];
        $protein = $_POST["protein"];
        $fat = $_POST["fat"];
        $carbs = $_POST["carbohydrates"];
        //$username = $_GET['username'];

        // if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //     if (!empty($_POST['username'])) {
        //         $username = $_POST['username'];
        //     }
        // } else {
        //     // Check if the username is passed in the URL parameters
        //     if(isset($_GET['username'])) {
        //       $username = $_GET['username'];
        //     }
        // }

        addMeal($username, $date, $calories, $carbs, $protein, $fat);
        
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
  <title>Add Meals</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Add Meals</h1>
    <form id="addMealForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">
      Date: <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" /> <br/>
      Calories: <input type="number" name="calories" required /> <br/>
      Carbohydrates (g): <input type="number" name="carbohydrates" required /> <br/>
      Protein (g): <input type="number" name="protein" required /> <br/>
      Fat (g): <input type="number" name="fat" required /> <br/>
      <input type="hidden" name="username" value="<?php echo $username; ?>" />
      <input type="submit" name="Submit" value="Submit" class="btn" />
    </form>

    <form id="home" action="http://localhost/cs4750/DatabaseSystemsFinal/home.php" method="get">
        <!-- Pass the username as a query parameter -->
        <input type="hidden" name="username" value="<?php echo $username; ?>" /> 
        <input type="submit" value="Home" class="btn" />
    </form>

  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>