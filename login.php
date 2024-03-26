<?php
require("connect-db.php");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" name="login" value="Login">
    </form>
    <br>
    <form action="create_account.php" method="get">
        <input type="submit" value="Create Account">
    </form>
</body>
</html>



<?php

// Stub function for validating username and password
function validateUser($username, $password) {
    // Your validation logic here
    // For demonstration purposes, return true if username and password are valid
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (validateUser($username, $password)) {
            // Redirect to success page or perform other actions
            echo "Login successful!";
        } else {
            // Redirect back to login page with error message
            echo "Invalid username or password. Please try again.";
        }
    }
}
?>