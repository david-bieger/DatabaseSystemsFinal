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
</head>
<body>  
  <div>  
    <h1>My Profile - <?php echo $username; ?></h1>
    <form id="editMaxesForm" action="myprofile.php?username=<?php echo $username; ?>" method="post">     
      Squat Max: <input type="number" name="squat_max" value="<?php echo $user['squat_max']; ?>" required /> <br/>
      Bench Max: <input type="number" name="bench_max" value="<?php echo $user['bench_max']; ?>" required /> <br/> 
      Deadlift Max: <input type="number" name="dl_max" value="<?php echo $user['dl_max']; ?>" required /> <br/> 
      <input type="submit" name="submit" value="Save Changes" class="btn" />
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
