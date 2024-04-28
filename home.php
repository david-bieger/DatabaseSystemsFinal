<?php
require("connect-db.php");
require("database-functions.php");

$username = $_GET['username'];


// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['myProfile'])) {
        $username = $_POST['username'];
        // Redirect to myProfile.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/myProfile.php?username=$username");
        exit;
    } elseif (isset($_POST['addExercise'])) {
        $username = $_POST['username'];
        // Redirect to addExercise.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/addExercise.php?username=$username");
        exit;
    } elseif (isset($_POST['seeExercises'])) {
      $username = $_POST['username'];
        // Redirect to seeExercises.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/exerciseHistory.php?username=$username");
        exit;
    } elseif (isset($_POST['mealHistory'])) {
      $username = $_POST['username'];
        // Redirect to mealHistory.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/mealHistory.php?username=$username");
        exit;
    } elseif (isset($_POST['addMeals'])) {
      $username = $_POST['username'];
        // Redirect to addMeals.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/addMeals.php?username=$username");
        exit;
    } elseif (isset($_POST['addWeight'])) {
      $username = $_POST['username'];
        // Redirect to friends.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/addBodyWeight.php?username=$username");
        exit;
    } elseif (isset($_POST['seeWeight'])) {
      $username = $_POST['username'];
        // Redirect to friends.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/bodyWeightHistory.php?username=$username");
        exit;
    } elseif (isset($_POST['friends'])) {
      $username = $_POST['username'];
        // Redirect to friends.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/friends.php?username=$username");
        exit;
    } elseif (isset($_POST['logout'])) {
      $username = $_POST['username'];
        // Redirect to friends.php
        header("Location: http://localhost/cs4750/DatabaseSystemsFinal/login.php");
        exit;
    } elseif (isset($_POST['seeMaxes'])) {
      $username = $_POST['username'];
        // Redirect to friends.php
      header("Location: http://localhost/cs4750/DatabaseSystemsFinal/maxHistory.php?username=$username");
      exit;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>Welcome <?php echo $username; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Welcome <?php echo $username; ?> !</h1>
    <form id="myProfile" action="home.php" method="post">
      <input type="hidden" name="username" value="<?php echo $username; ?>" />     
      <input type="submit" name="myProfile" value="My Profile" class="btn" />
    </form>
    <form id="addExercise" action="home.php" method="post">     
      <input type="hidden" name="username" value="<?php echo $username; ?>" />
      <input type="submit" name="addExercise" value="Add Exercise" class="btn" />
    </form>
    <form id="seeExercises" action="home.php" method="post">
      <input type="hidden" name="username" value="<?php echo $username; ?>" />     
      <input type="submit" name="seeExercises" value="Exercise History" class="btn" />
    </form>
    <form id="seeMaxes" action="home.php" method="post">
      <input type="hidden" name="username" value="<?php echo $username; ?>" />     
      <input type="submit" name="seeMaxes" value="1 Rep Max History" class="btn" />
    </form>
    <form id="addMeals" action="home.php" method="post">   
      <input type="hidden" name="username" value="<?php echo $username; ?>" />  
      <input type="submit" name="addMeals" value="Add Meals" class="btn" />
    </form>
    <form id="mealHistory" action="home.php" method="post"> 
      <input type="hidden" name="username" value="<?php echo $username; ?>" />    
      <input type="submit" name="mealHistory" value="Meal History" class="btn" />
    </form>
    <form id="addBodyWeight" action="home.php" method="post">  
      <input type="hidden" name="username" value="<?php echo $username; ?>" />   
      <input type="submit" name="addWeight" value="Add Body Weight" class="btn" />
    </form>
    <form id="seeBodyWeight" action="home.php" method="post">  
      <input type="hidden" name="username" value="<?php echo $username; ?>" />   
      <input type="submit" name="seeWeight" value="Body Weight History" class="btn" />
    </form>
    <form id="friends" action="home.php" method="post">  
      <input type="hidden" name="username" value="<?php echo $username; ?>" />   
      <input type="submit" name="friends" value="Friends" class="btn" />
    </form>
    <form id="logout" action="home.php" method="post">  
      <input type="hidden" name="username" value="<?php echo $username; ?>" />   
      <input type="submit" name="logout" value="Log Out" class="btn" />
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>