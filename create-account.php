<?php
//haven't done much with this yet at all
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Submit'])) {
        $username = $_POST["username"];
        $password1 = $_POST["password"];
        $password2 = $_POST["confirm password"];
        if ($password1 != $password2)  {
            echo "Passwords must match, please try again.";
        } else if (strlen($password1) < 10) {
            echo "Password must be at least 10 characters long. Try again."; // used for cyber 
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
      Confirm Password: <input type="password" name="confirm password" required /> <br/>
      <input type="submit" name="Submit" value="Create Account" class="btn" />
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
