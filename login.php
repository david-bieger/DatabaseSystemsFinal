<?php
require("connect-db.php");
require("database-functions.php");

// STUB: should be able to have validate user function only in the database-functions file, but that is currently not working.
function validateUser($username, $password) {
    global $db;
    $query = 'SELECT password FROM Users WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $username);
    $statement->execute();
    $result = $statement->fetch(); // Fetch the result row
    $statement->closeCursor();

    // Check if a row was returned
    if ($result) {
        // Compare the password from the database with the input password
        $passwordFromDB = $result['password'];
        if ($password === $passwordFromDB) {
            return true; // Passwords match
        }
    }
    
    return false; // User not found or password doesn't match
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Submit'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (validateUser($username, $password)) {
            // Redirect to success page or perform other actions
            //echo "Login successful!";
            header("Location: http://localhost/cs4750/DatabaseSystemsFinal/home.php?username=$username");
        } else {
            // Redirect back to login page with error message
            echo "Invalid username or password. Please try again.";
        }
    }
}

// Check if "Create Account" form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create-account-submit'])) {
    // Redirect user to create-account.php
    header("Location: http://localhost/cs4750/DatabaseSystemsFinal/create-account.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>Gym Tracker</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Gym Tracker</h1>
    <form id="loginForm" action="login.php" method="post">     
      Username: <input type="text" name="username" required /> <br/>
      Password: <input type="password" name="password" required /> <br/>
      <input type="submit" name="Submit" value="Submit" class="btn" />
    </form>
    
    <!-- Create Account form -->
    <form id="createAccountForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="create-account-submit" value="true">
        <input type="submit" value="Create Account" class="btn" />
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
