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

function addBodyWeight($username, $date, $weight) {
    global $db;
    $query = 'INSERT INTO Body_Weight_History (user_id, date, weight) 
    VALUES 
    (:username, :weight_date, :weight)';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':weight_date', $date);
    $statement->bindValue(':weight', $weight);
    $statement->execute();
    //$result = $statement->fetch(); // Fetch the result row
    $statement->closeCursor();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Submit'])) {
        $username = $_POST['username'];
        $date = $_POST['date'];
        $weight = $_POST["weight"];
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

        addBodyWeight($username, $date, $weight);
        
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
    <h1>Add Body Weight</h1>
    <form id="addWeightForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?username=<?php echo $username; ?>" method="post">
      Date: <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" /> <br/>
      Weight: <input type="number" name="weight" required /> <br/>
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