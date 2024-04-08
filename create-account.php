<?php
//haven't done much with this yet at all
require("connect-db.php");

function userAlreadyExists($username) {
    global $db;
    $query = 'SELECT user_id FROM Users WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $username);
    $statement->execute();
    $result = $statement->fetch(); // Fetch the result row
    $statement->closeCursor();

    // Check if a row was returned
    if ($result) {
        // user already exists
        return true;
    }
    return false; // User not found or password doesn't match
}

function addUserToDatabase($username, $password, $name, $dob, $squatMax, $benchMax, $dlMax) {
    global $db;
    $query = 'INSERT INTO Users (user_id, password, name, DOB, squat_max, bench_max, dl_max) 
    VALUES 
    (:username, :password, :name, :dob, :squat_max, :bench_max, :dl_max)';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':name', $name); // Bind name
    $statement->bindValue(':dob', $dob); // Bind DOB
    $statement->bindValue(':squat_max', $squatMax); // Bind squat max
    $statement->bindValue(':bench_max', $benchMax); // Bind bench max
    $statement->bindValue(':dl_max', $dlMax); // Bind deadlift max
    $statement->execute();
    $statement->closeCursor();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Submit'])) {
        $username = $_POST["username"];
        $password1 = $_POST["password"];
        $password2 = $_POST["confirm-password"];
        $name = $_POST["name"];
        $dob = $_POST["dob"];
        $squatMax = $_POST["squat_max"];
        $benchMax = $_POST["bench_max"];
        $dlMax = $_POST["dl_max"];
        if ($password1 != $password2)  {
            echo "Passwords must match, please try again.";
        } else if (strlen($password1) < 10) {
            echo "Password must be at least 10 characters long. Try again."; // used for cyber 
        }
        else {
            $userExists = userAlreadyExists($username);
            if ($userExists){
                echo "User already exists. Please enter a new username or log in with your existing account credentials.";
            }
            else {
                addUserToDatabase($username, $password1, $name, $dob, $squatMax, $benchMax, $dlMax);
                echo "Account successfully created.";
                header("Location: http://localhost/cs4750/DatabaseSystemsFinal/login.php");
                exit;
            }
            
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>Create Account</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css" /> 
</head>
<body>  
  <div>  
    <h1>Gym Tracker</h1>
    <form id="createAccountForm" action="create-account.php" method="post">     
      Username: <input type="text" name="username" required /> <br/>
      Password: <input type="password" name="password" required /> <br/>
      Confirm Password: <input type="password" name="confirm-password" required /> <br/>
      Name: <input type="text" name="name" required /> <br/>
      Date of Birth: <input type="date" name="dob" required /> <br/> 
      Squat Max: <input type="number" name="squat_max" required /> <br/>
      Bench Max: <input type="number" name="bench_max" required /> <br/> 
      Deadlift Max: <input type="number" name="dl_max" required /> <br/> 
      <input type="submit" name="Submit" value="Create Account" class="btn" />
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
